<?php

namespace App\Services;

use App\Models\LowonganMagang;
use App\Models\PendaftaranMagang;
use App\Models\User;
use Illuminate\Support\Collection;

class SyaratCheckService
{
    /**
     * Cek apakah mahasiswa memenuhi syarat lowongan.
     * Return array of error messages (kosong = lolos).
     */
    public function checkSyarat(User $mahasiswa, LowonganMagang $lowongan): array
    {
        $errors = [];

        // Cek role
        if ($mahasiswa->role !== 'mahasiswa') {
            $errors[] = 'User bukan mahasiswa';
            return $errors;
        }

        // Cek IPK
        if ($lowongan->syarat_ipk > 0) {
            $ipk = $mahasiswa->ipk ?? 0;
            if ($ipk < $lowongan->syarat_ipk) {
                $errors[] = "IPK minimal {$lowongan->syarat_ipk}, IPK Anda: " . ($mahasiswa->ipk ?? 'belum diisi');
            }
        }

        // Cek semester
        if ($lowongan->syarat_semester > 1) {
            $semester = $mahasiswa->semester ?? 0;
            if ($semester < $lowongan->syarat_semester) {
                $errors[] = "Semester minimal {$lowongan->syarat_semester}, semester Anda: " . ($mahasiswa->semester ?? 'belum diisi');
            }
        }

        // Cek prodi
        if (!empty($lowongan->syarat_prodi)) {
            if (!in_array($mahasiswa->program_studi_id, $lowongan->syarat_prodi)) {
                $prodiName = $mahasiswa->programStudi?->nama ?? 'tidak diketahui';
                $errors[] = "Program studi {$prodiName} tidak termasuk dalam syarat lowongan";
            }
        }

        // Cek is_active
        if (!$mahasiswa->is_active) {
            $errors[] = 'Akun mahasiswa tidak aktif';
        }

        return $errors;
    }

    /**
     * Cek apakah mahasiswa sudah terdaftar di lowongan ini.
     */
    public function hasExistingPendaftaran(User $mahasiswa, LowonganMagang $lowongan): bool
    {
        return PendaftaranMagang::where('mahasiswa_id', $mahasiswa->id)
            ->where('lowongan_id', $lowongan->id)
            ->whereNotIn('status', [
                PendaftaranMagang::STATUS_DITOLAK,
                PendaftaranMagang::STATUS_DIBATALKAN,
            ])
            ->exists();
    }

    /**
     * Cek apakah mahasiswa sudah punya pendaftaran magang aktif (any type).
     */
    public function hasActivePendaftaran(User $mahasiswa): bool
    {
        return PendaftaranMagang::where('mahasiswa_id', $mahasiswa->id)
            ->whereNotIn('status', [
                PendaftaranMagang::STATUS_DITOLAK,
                PendaftaranMagang::STATUS_DIBATALKAN,
                PendaftaranMagang::STATUS_SELESAI,
            ])
            ->exists();
    }

    /**
     * Filter mahasiswa yang eligible untuk lowongan tertentu.
     * Return: ['eligible' => [...], 'ineligible' => [...]]
     */
    public function filterEligibleMahasiswa(Collection $mahasiswaList, LowonganMagang $lowongan): array
    {
        $eligible = [];
        $ineligible = [];

        foreach ($mahasiswaList as $mhs) {
            $errors = $this->checkSyarat($mhs, $lowongan);
            $alreadyRegistered = $this->hasExistingPendaftaran($mhs, $lowongan);

            if ($alreadyRegistered) {
                $errors[] = 'Sudah terdaftar di lowongan ini';
            }

            if (empty($errors)) {
                $eligible[] = [
                    'mahasiswa' => $mhs,
                    'errors' => [],
                ];
            } else {
                $ineligible[] = [
                    'mahasiswa' => $mhs,
                    'errors' => $errors,
                ];
            }
        }

        return [
            'eligible' => $eligible,
            'ineligible' => $ineligible,
        ];
    }
}
