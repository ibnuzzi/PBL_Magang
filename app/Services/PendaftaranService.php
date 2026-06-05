<?php

namespace App\Services;

use App\Models\ApprovalPendaftaran;
use App\Models\DokumenPendaftaran;
use App\Models\LowonganMagang;
use App\Models\MitraPerusahaan;
use App\Models\PendaftaranMagang;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PendaftaranService
{
    protected NotifikasiService $notifikasiService;

    public function __construct(NotifikasiService $notifikasiService)
    {
        $this->notifikasiService = $notifikasiService;
    }

    /**
     * Buat draft pendaftaran Flow A (Pilihan/Wajib) — dari lowongan.
     */
    public function createDraftPilihan(User $mahasiswa, LowonganMagang $lowongan): PendaftaranMagang
    {
        $jenisMagang = $lowongan->jenis_magang; // pilihan or wajib
        $this->validatePendaftaran($mahasiswa, $jenisMagang, $lowongan->mitra_id);

        return PendaftaranMagang::create([
            'mahasiswa_id' => $mahasiswa->id,
            'lowongan_id' => $lowongan->id,
            'mitra_id' => $lowongan->mitra_id,
            'jenis_magang' => $jenisMagang,
            'status' => PendaftaranMagang::STATUS_DRAFT,
            'tanggal_daftar' => now(),
        ]);
    }

    /**
     * Buat draft pendaftaran Flow B (Mandiri) — dari mitra existing atau baru.
     */
    public function createDraftMandiri(User $mahasiswa, int $mitraId): PendaftaranMagang
    {
        $this->validatePendaftaran($mahasiswa, 'mandiri', $mitraId);

        return PendaftaranMagang::create([
            'mahasiswa_id' => $mahasiswa->id,
            'lowongan_id' => null,
            'mitra_id' => $mitraId,
            'jenis_magang' => 'mandiri',
            'status' => PendaftaranMagang::STATUS_DRAFT,
            'tanggal_daftar' => now(),
        ]);
    }

    /**
     * Validasi kelayakan pendaftaran magang mahasiswa.
     */
    protected function validatePendaftaran(User $mahasiswa, string $jenisMagang, int $mitraId): void
    {
        // 1. Validasi Semester
        $semester = (int) $mahasiswa->semester;
        if ($semester !== 6 && $semester !== 7) {
            throw new \Exception('Pendaftaran magang hanya diperbolehkan untuk mahasiswa semester 6 atau 7.');
        }

        if ($jenisMagang === 'pilihan' && $semester !== 6) {
            throw new \Exception('Magang Pilihan hanya boleh diambil oleh mahasiswa semester 6.');
        }

        if ($jenisMagang === 'wajib' && $semester !== 7) {
            throw new \Exception('Magang Wajib hanya boleh diambil oleh mahasiswa semester 7.');
        }

        // 2. Validasi Pendaftaran Aktif Tunggal
        $activePendaftaran = PendaftaranMagang::where('mahasiswa_id', $mahasiswa->id)
            ->whereNotIn('status', [
                PendaftaranMagang::STATUS_DITOLAK,
                PendaftaranMagang::STATUS_DIBATALKAN,
                PendaftaranMagang::STATUS_SELESAI
            ])
            ->exists();

        if ($activePendaftaran) {
            throw new \Exception('Anda masih memiliki pendaftaran magang yang aktif. Harap selesaikan seleksi atau batalkan terlebih dahulu.');
        }

        // 3. Validasi Mitra Resmi untuk Magang SKS (Pilihan/Wajib)
        if (in_array($jenisMagang, ['pilihan', 'wajib'])) {
            $mitra = MitraPerusahaan::find($mitraId);
            if (!$mitra || (!$mitra->is_resmi_polinema && !$mitra->is_cti)) {
                throw new \Exception('Magang SKS (Pilihan & Wajib) hanya diperbolehkan pada mitra resmi Polinema atau CTI.');
            }
        }
    }

    /**
     * Submit pendaftaran — mahasiswa selesai upload dokumen.
     * Status: draft/dokumen_kurang → menunggu_verifikasi_dokumen
     */
    public function submitPendaftaran(PendaftaranMagang $pendaftaran): PendaftaranMagang
    {
        if (!$pendaftaran->canBeSubmitted()) {
            throw new \Exception('Pendaftaran tidak dapat disubmit pada status saat ini.');
        }

        $pendaftaran->update([
            'status' => PendaftaranMagang::STATUS_MENUNGGU_VERIFIKASI,
        ]);

        // Kirim notifikasi ke koordinator
        $this->notifikasiService->notifyPendaftaranSubmitted($pendaftaran);

        return $pendaftaran->fresh();
    }

    /**
     * Koordinator verifikasi dokumen — set status dokumen_lengkap/dokumen_kurang.
     */
    public function verifikasiDokumen(PendaftaranMagang $pendaftaran, bool $lengkap): PendaftaranMagang
    {
        $newStatus = $lengkap
            ? PendaftaranMagang::STATUS_MENUNGGU_KOORDINATOR
            : PendaftaranMagang::STATUS_DOKUMEN_KURANG;

        $pendaftaran->update(['status' => $newStatus]);

        $this->notifikasiService->notifyStatusChange($pendaftaran);

        return $pendaftaran->fresh();
    }

    /**
     * Process approval berjenjang.
     *
     * Flow: koordinator → kps → kajur → wadir1 → disetujui_penuh
     */
    public function processApproval(
        PendaftaranMagang $pendaftaran,
        User $approver,
        string $action,
        ?string $catatan = null
    ): PendaftaranMagang {
        return DB::transaction(function () use ($pendaftaran, $approver, $action, $catatan) {
            $levelMap = [
                PendaftaranMagang::STATUS_MENUNGGU_KOORDINATOR => ['level' => 'koordinator', 'urutan' => 1, 'next' => PendaftaranMagang::STATUS_MENUNGGU_KPS],
                PendaftaranMagang::STATUS_MENUNGGU_KPS => ['level' => 'kps', 'urutan' => 2, 'next' => PendaftaranMagang::STATUS_MENUNGGU_KAJUR],
                PendaftaranMagang::STATUS_MENUNGGU_KAJUR => ['level' => 'kajur', 'urutan' => 3, 'next' => PendaftaranMagang::STATUS_MENUNGGU_WADIR1],
                PendaftaranMagang::STATUS_MENUNGGU_WADIR1 => ['level' => 'wadir1', 'urutan' => 4, 'next' => PendaftaranMagang::STATUS_DISETUJUI_PENUH],
            ];

            $currentLevel = $levelMap[$pendaftaran->status] ?? null;

            if (!$currentLevel) {
                throw new \Exception('Pendaftaran tidak dalam status yang bisa di-approve.');
            }

            if (!$approver->canApproveLevel($currentLevel['level'])) {
                throw new \Exception('Anda tidak memiliki hak untuk approve di level ini.');
            }

            // Buat record approval
            ApprovalPendaftaran::create([
                'pendaftaran_id' => $pendaftaran->id,
                'approver_id' => $approver->id,
                'level' => $currentLevel['level'],
                'status' => $action === 'approve' ? 'disetujui' : 'ditolak',
                'urutan_level' => $currentLevel['urutan'],
                'catatan' => $catatan,
                'diproses_at' => now(),
            ]);

            if ($action === 'approve') {
                $pendaftaran->update(['status' => $currentLevel['next']]);

                // Notifikasi ke approver berikutnya
                $nextLevelKey = $currentLevel['next'];
                $nextLevel = $levelMap[$nextLevelKey] ?? null;
                if ($nextLevel) {
                    $this->notifikasiService->notifyNextApprover($pendaftaran, $nextLevel['level']);
                }
            } else {
                $pendaftaran->update([
                    'status' => PendaftaranMagang::STATUS_DITOLAK,
                    'alasan_ditolak' => $catatan,
                ]);
                $this->notifikasiService->notifyPendaftaranDitolak($pendaftaran, $catatan ?? 'Tidak ada alasan');
            }

            $this->notifikasiService->notifyStatusChange($pendaftaran);

            return $pendaftaran->fresh();
        });
    }

    /**
     * Terbitkan surat pengantar.
     */
    public function terbitkanSurat(PendaftaranMagang $pendaftaran): PendaftaranMagang
    {
        $pendaftaran->update(['status' => PendaftaranMagang::STATUS_SURAT_TERBIT]);
        $this->notifikasiService->notifySuratTerbit($pendaftaran);
        return $pendaftaran->fresh();
    }

    /**
     * Update status LOA diterima.
     */
    public function loaDiterima(PendaftaranMagang $pendaftaran): PendaftaranMagang
    {
        $pendaftaran->update(['status' => PendaftaranMagang::STATUS_LOA]);
        $this->notifikasiService->notifyStatusChange($pendaftaran);
        return $pendaftaran->fresh();
    }

    /**
     * Mulai pelaksanaan magang.
     */
    public function mulaiPelaksanaan(PendaftaranMagang $pendaftaran): PendaftaranMagang
    {
        $pendaftaran->update(['status' => PendaftaranMagang::STATUS_BERJALAN]);
        $this->notifikasiService->notifyStatusChange($pendaftaran);

        // Increment kuota terisi di lowongan
        if ($pendaftaran->lowongan) {
            $pendaftaran->lowongan->incrementKuota();
        }

        return $pendaftaran->fresh();
    }

    /**
     * Batalkan pendaftaran.
     */
    public function batalkanPendaftaran(PendaftaranMagang $pendaftaran, ?string $alasan = null): PendaftaranMagang
    {
        $pendaftaran->update([
            'status' => PendaftaranMagang::STATUS_DIBATALKAN,
            'catatan' => $alasan,
        ]);
        $this->notifikasiService->notifyStatusChange($pendaftaran);
        return $pendaftaran->fresh();
    }
}
