<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (config('database.default') === 'mysql') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE dokumen_pendaftaran MODIFY COLUMN jenis_dokumen ENUM(
                'khs',
                'proposal_magang',
                'cv',
                'surat_izin_ortu',
                'surat_pengantar',
                'surat_integritas',
                'surat_lamaran',
                'surat_rekomendasi',
                'sertifikasi_kompetensi',
                'portfolio'
            ) NOT NULL");
        }
    }

    public function down(): void
    {
        if (config('database.default') === 'mysql') {
            \Illuminate\Support\Facades\DB::table('dokumen_pendaftaran')->where('jenis_dokumen', 'portfolio')->delete();
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE dokumen_pendaftaran MODIFY COLUMN jenis_dokumen ENUM(
                'khs',
                'proposal_magang',
                'cv',
                'surat_izin_ortu',
                'surat_pengantar',
                'surat_integritas',
                'surat_lamaran',
                'surat_rekomendasi',
                'sertifikasi_kompetensi'
            ) NOT NULL");
        }
    }
};
