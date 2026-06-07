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

        $pendaftaran = PendaftaranMagang::create([
            'mahasiswa_id' => $mahasiswa->id,
            'lowongan_id' => $lowongan->id,
            'mitra_id' => $lowongan->mitra_id,
            'jenis_magang' => $jenisMagang,
            'status' => PendaftaranMagang::STATUS_DRAFT,
            'tanggal_daftar' => now(),
        ]);

        // Update status magang mahasiswa → proses
        $mahasiswa->update(['status_magang' => User::STATUS_MAGANG_PROSES]);

        return $pendaftaran;
    }

    /**
     * Buat draft pendaftaran Flow B (Mandiri) — dari mitra existing atau baru.
     */
    public function createDraftMandiri(User $mahasiswa, int $mitraId): PendaftaranMagang
    {
        $this->validatePendaftaran($mahasiswa, 'mandiri', $mitraId);

        $pendaftaran = PendaftaranMagang::create([
            'mahasiswa_id' => $mahasiswa->id,
            'lowongan_id' => null,
            'mitra_id' => $mitraId,
            'jenis_magang' => 'mandiri',
            'status' => PendaftaranMagang::STATUS_DRAFT,
            'tanggal_daftar' => now(),
        ]);

        // Update status magang mahasiswa → proses
        $mahasiswa->update(['status_magang' => User::STATUS_MAGANG_PROSES]);

        return $pendaftaran;
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

        // Pastikan status magang tetap proses
        $pendaftaran->mahasiswa->update(['status_magang' => User::STATUS_MAGANG_PROSES]);

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

                // Update status magang mahasiswa → ditolak
                $pendaftaran->mahasiswa->update(['status_magang' => User::STATUS_MAGANG_DITOLAK]);

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
     * Ini adalah trigger utama untuk status_magang → diterima.
     */
    public function loaDiterima(PendaftaranMagang $pendaftaran): PendaftaranMagang
    {
        $pendaftaran->update(['status' => PendaftaranMagang::STATUS_LOA]);

        // Update status magang mahasiswa → diterima (LOA sudah divalidasi koordinator)
        $pendaftaran->mahasiswa->update(['status_magang' => User::STATUS_MAGANG_DITERIMA]);

        $this->notifikasiService->notifyStatusChange($pendaftaran);
        return $pendaftaran->fresh();
    }

    /**
     * Mulai pelaksanaan magang.
     */
    public function mulaiPelaksanaan(PendaftaranMagang $pendaftaran): PendaftaranMagang
    {
        // Secara otomatis pilih dosen pembimbing acak jika belum ditentukan
        if (!$pendaftaran->dosen_pembimbing_id) {
            $pendaftaran->dosen_pembimbing_id = $this->assignRandomDosenPembimbing($pendaftaran);
        }

        $pendaftaran->update([
            'status' => PendaftaranMagang::STATUS_BERJALAN,
            'dosen_pembimbing_id' => $pendaftaran->dosen_pembimbing_id,
        ]);
        $this->notifikasiService->notifyStatusChange($pendaftaran);

        // Increment kuota terisi di lowongan
        if ($pendaftaran->lowongan) {
            $pendaftaran->lowongan->incrementKuota();
        }

        return $pendaftaran->fresh();
    }

    /**
     * Pilih dosen pembimbing secara acak yang kuotanya belum penuh.
     */
    public function assignRandomDosenPembimbing(PendaftaranMagang $pendaftaran): ?int
    {
        // 1. Dapatkan semua dosen/staf yang eligible untuk bimbingan
        $eligibleSupervisors = User::whereIn('role', ['dosen', 'koordinator', 'kps', 'kajur'])
            ->where('is_active', true)
            ->get();

        if ($eligibleSupervisors->isEmpty()) {
            throw new \Exception('Tidak ada dosen pembimbing yang aktif di sistem.');
        }

        // 2. Cari dosen yang kuota mahasiswa bimbingannya belum penuh
        // Load dihitung dari pendaftaran magang dengan status 'berjalan' (internship ongoing)
        $availableSupervisors = $eligibleSupervisors->filter(function (User $supervisor) {
            $currentLoad = PendaftaranMagang::where('dosen_pembimbing_id', $supervisor->id)
                ->whereIn('status', PendaftaranMagang::activeStatuses())
                ->count();
            
            $quota = $supervisor->kuota_bimbingan ?? 5; // default 5 jika null/kosong
            return $currentLoad < $quota;
        });

        if ($availableSupervisors->isNotEmpty()) {
            // Pick randomly
            return $availableSupervisors->random()->id;
        }

        // 3. Fallback jika semua dosen sudah mencapai batas kuota:
        // Pilih dosen yang memiliki beban (load) bimbingan paling sedikit
        $supervisorWithLeastLoad = $eligibleSupervisors->sortBy(function (User $supervisor) {
            return PendaftaranMagang::where('dosen_pembimbing_id', $supervisor->id)
                ->whereIn('status', PendaftaranMagang::activeStatuses())
                ->count();
        })->first();

        return $supervisorWithLeastLoad ? $supervisorWithLeastLoad->id : null;
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

        // Kembalikan status magang mahasiswa → tidak_aktif (bisa apply lagi)
        $pendaftaran->mahasiswa->update(['status_magang' => User::STATUS_MAGANG_TIDAK_AKTIF]);

        $this->notifikasiService->notifyStatusChange($pendaftaran);
        return $pendaftaran->fresh();
    }

    /**
     * Plot dosen pembimbing secara otomatis (Bulk).
     * Mendistribusikan mahasiswa secara merata ke dosen-dosen yang tersedia.
     */
    public function plotDosenOtomatis(array $pendaftaranIds): array
    {
        $records = PendaftaranMagang::whereIn('id', $pendaftaranIds)
            ->whereNull('dosen_pembimbing_id') // Hanya plot yang belum memiliki pembimbing
            ->get();

        if ($records->isEmpty()) {
            return ['success' => false, 'message' => 'Tidak ada pendaftaran yang valid untuk diplot (semua yang dipilih mungkin sudah memiliki pembimbing).'];
        }

        $eligibleSupervisors = User::whereIn('role', ['dosen', 'koordinator', 'kps', 'kajur'])
            ->where('is_active', true)
            ->get();

        if ($eligibleSupervisors->isEmpty()) {
            return ['success' => false, 'message' => 'Tidak ada dosen pembimbing yang aktif di sistem.'];
        }

        $plottedCount = 0;

        foreach ($records as $pendaftaran) {
            // Cari dosen dengan load paling sedikit dan masih di bawah kuota
            $availableSupervisor = $eligibleSupervisors->sortBy(function (User $supervisor) {
                return PendaftaranMagang::where('dosen_pembimbing_id', $supervisor->id)
                    ->whereIn('status', PendaftaranMagang::activeStatuses())
                    ->count();
            })->filter(function (User $supervisor) {
                $currentLoad = PendaftaranMagang::where('dosen_pembimbing_id', $supervisor->id)
                    ->whereIn('status', PendaftaranMagang::activeStatuses())
                    ->count();
                $quota = $supervisor->kuota_bimbingan ?? 5;
                return $currentLoad < $quota;
            })->first();

            if ($availableSupervisor) {
                $pendaftaran->update(['dosen_pembimbing_id' => $availableSupervisor->id]);
                // Beritahu dosen ada bimbingan baru
                $this->notifikasiService->notifyStatusChange($pendaftaran);
                $plottedCount++;
            }
        }

        if ($plottedCount < $records->count()) {
            return ['success' => true, 'message' => "Berhasil memplot {$plottedCount} mahasiswa. Sebagian gagal karena sisa kuota seluruh dosen sudah penuh."];
        }

        return ['success' => true, 'message' => "Berhasil memplot {$plottedCount} mahasiswa secara merata."];
    }

    /**
     * Plot dosen pembimbing secara manual ke satu dosen pilihan.
     */
    public function plotDosenManual(array $pendaftaranIds, int $dosenId): array
    {
        $dosen = User::find($dosenId);
        if (!$dosen || !in_array($dosen->role, ['dosen', 'koordinator', 'kps', 'kajur'])) {
            return ['success' => false, 'message' => 'Dosen pembimbing tidak valid.'];
        }

        $records = PendaftaranMagang::whereIn('id', $pendaftaranIds)->get();
        if ($records->isEmpty()) {
            return ['success' => false, 'message' => 'Tidak ada pendaftaran yang dipilih.'];
        }

        $currentLoad = PendaftaranMagang::where('dosen_pembimbing_id', $dosen->id)
            ->whereIn('status', PendaftaranMagang::activeStatuses())
            ->count();
        $quota = $dosen->kuota_bimbingan ?? 5;
        $availableQuota = $quota - $currentLoad;

        if ($records->count() > $availableQuota) {
            return ['success' => false, 'message' => "Gagal: Anda memilih {$records->count()} mahasiswa, tetapi sisa kuota {$dosen->name} hanya {$availableQuota}."];
        }

        foreach ($records as $pendaftaran) {
            $pendaftaran->update(['dosen_pembimbing_id' => $dosen->id]);
            $this->notifikasiService->notifyStatusChange($pendaftaran);
        }

        return ['success' => true, 'message' => "Berhasil memplot {$records->count()} mahasiswa ke {$dosen->name}."];
    }
}
