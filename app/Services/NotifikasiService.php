<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\PendaftaranMagang;
use App\Models\User;

class NotifikasiService
{
    /**
     * Kirim notifikasi ke user.
     */
    public function kirim(User $user, string $jenis, string $judul, string $pesan, ?string $link = null): Notifikasi
    {
        return Notifikasi::create([
            'user_id' => $user->id,
            'jenis' => $jenis,
            'judul' => $judul,
            'pesan' => $pesan,
            'link' => $link,
            'is_read' => false,
        ]);
    }

    /**
     * Notifikasi saat mahasiswa submit pendaftaran → ke koordinator.
     */
    public function notifyPendaftaranSubmitted(PendaftaranMagang $pendaftaran): void
    {
        $koordinators = User::where('role', 'koordinator')->where('is_active', true)->get();

        foreach ($koordinators as $koordinator) {
            $this->kirim(
                $koordinator,
                'pendaftaran_baru',
                'Pendaftaran Magang Baru',
                "Mahasiswa {$pendaftaran->mahasiswa->name} telah mengajukan pendaftaran magang ({$pendaftaran->jenis_magang_label}).",
                "/admin/pendaftaran-magangs/{$pendaftaran->id}/edit"
            );
        }
    }

    /**
     * Notifikasi saat status berubah → ke mahasiswa.
     */
    public function notifyStatusChange(PendaftaranMagang $pendaftaran): void
    {
        $this->kirim(
            $pendaftaran->mahasiswa,
            'status_berubah',
            'Status Pendaftaran Diperbarui',
            "Status pendaftaran magang Anda telah berubah menjadi: {$pendaftaran->status_label}.",
            '/admin/status-pendaftaran'
        );
    }

    /**
     * Notifikasi saat approval → ke approver level berikutnya.
     */
    public function notifyNextApprover(PendaftaranMagang $pendaftaran, string $level): void
    {
        $approvers = User::where('role', $level)->where('is_active', true)->get();

        foreach ($approvers as $approver) {
            $this->kirim(
                $approver,
                'approval_pending',
                'Pendaftaran Menunggu Approval Anda',
                "Pendaftaran magang oleh {$pendaftaran->mahasiswa->name} menunggu approval Anda.",
                "/admin/approval-pendaftarans/{$pendaftaran->id}/edit"
            );
        }
    }

    /**
     * Notifikasi saat dokumen ditolak → ke mahasiswa.
     */
    public function notifyDokumenDitolak(PendaftaranMagang $pendaftaran, string $jenisDokumen): void
    {
        $this->kirim(
            $pendaftaran->mahasiswa,
            'dokumen_ditolak',
            'Dokumen Ditolak',
            "Dokumen {$jenisDokumen} Anda pada pendaftaran magang telah ditolak. Silakan upload ulang.",
            '/admin/status-pendaftaran'
        );
    }

    /**
     * Notifikasi saat pendaftaran ditolak → ke mahasiswa.
     */
    public function notifyPendaftaranDitolak(PendaftaranMagang $pendaftaran, string $alasan): void
    {
        $this->kirim(
            $pendaftaran->mahasiswa,
            'pendaftaran_ditolak',
            'Pendaftaran Magang Ditolak',
            "Pendaftaran magang Anda telah ditolak. Alasan: {$alasan}",
            '/admin/status-pendaftaran'
        );
    }

    /**
     * Notifikasi saat surat pengantar terbit → ke mahasiswa.
     */
    public function notifySuratTerbit(PendaftaranMagang $pendaftaran): void
    {
        $this->kirim(
            $pendaftaran->mahasiswa,
            'surat_terbit',
            'Surat Pengantar Magang Terbit',
            'Surat pengantar magang Anda telah diterbitkan. Silakan download dari sistem.',
            '/admin/status-pendaftaran'
        );
    }
}
