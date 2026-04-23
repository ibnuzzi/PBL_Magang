<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongan_magang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mitra_id')->constrained('mitra_perusahaan')->cascadeOnDelete();
            $table->foreignId('pembuat_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul', 200);
            $table->text('deskripsi')->nullable();
            $table->enum('jenis_magang', ['pilihan', 'wajib']);
            $table->integer('kuota')->default(1);
            $table->integer('kuota_terisi')->default(0);
            $table->decimal('syarat_ipk', 3, 2)->default(0.00);
            $table->tinyInteger('syarat_semester')->default(1);
            $table->json('syarat_prodi')->nullable();
            $table->json('dokumen_required')->nullable();
            $table->date('tanggal_buka');
            $table->date('tanggal_tutup');
            $table->date('tanggal_mulai_magang')->nullable();
            $table->date('tanggal_selesai_magang')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_full')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lowongan_magang');
    }
};
