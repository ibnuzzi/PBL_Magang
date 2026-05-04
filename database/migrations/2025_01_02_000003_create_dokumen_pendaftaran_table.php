<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran_magang')->cascadeOnDelete();
            $table->enum('jenis_dokumen', [
                'khs',
                'proposal_magang',
                'cv',
                'surat_izin_ortu',
                'surat_pengantar',
                'surat_integritas',
                'surat_lamaran',
                'surat_rekomendasi',
                'sertifikasi_kompetensi',
            ]);
            $table->string('file_path', 255);
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('keterangan_reject')->nullable();
            $table->timestamps();

            $table->unique(['pendaftaran_id', 'jenis_dokumen']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_pendaftaran');
    }
};
