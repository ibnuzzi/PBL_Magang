<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitra_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 200);
            $table->text('alamat');
            $table->string('bidang_usaha', 100);
            $table->string('nama_pic', 150);
            $table->string('jabatan_pic', 100);
            $table->string('no_hp_pic', 20);
            $table->string('email_pic', 150);
            $table->boolean('is_resmi_polinema')->default(false);
            $table->boolean('is_cti')->default(false);
            $table->integer('kuota_mahasiswa')->default(0);
            $table->enum('status_verifikasi', ['terverifikasi', 'menunggu', 'ditolak'])->default('terverifikasi');
            $table->foreignId('diajukan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitra_perusahaan');
    }
};
