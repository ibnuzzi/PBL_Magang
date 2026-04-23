<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran_magang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lowongan_id')->nullable()->constrained('lowongan_magang')->nullOnDelete();
            $table->foreignId('mitra_id')->constrained('mitra_perusahaan')->cascadeOnDelete();
            $table->foreignId('dosen_pembimbing_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('jenis_magang', ['pilihan', 'mandiri', 'wajib']);
            $table->enum('status', [
                'draft',
                'menunggu_verifikasi_dokumen',
                'dokumen_lengkap',
                'dokumen_kurang',
                'menunggu_approval_koordinator',
                'menunggu_approval_kps',
                'menunggu_approval_kajur',
                'menunggu_approval_wadir1',
                'disetujui_penuh',
                'surat_pengantar_terbit',
                'loa_diterima',
                'berjalan',
                'selesai',
                'ditolak',
                'dibatalkan',
            ])->default('draft');
            $table->text('catatan')->nullable();
            $table->text('alasan_ditolak')->nullable();
            $table->timestamp('tanggal_daftar')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_magang');
    }
};
