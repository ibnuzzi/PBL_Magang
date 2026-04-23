<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembuat_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul', 200);
            $table->text('konten');
            $table->enum('target_role', ['all', 'mahasiswa', 'dosen', 'koordinator', 'kps', 'kajur', 'wadir1', 'admin'])->default('all');
            $table->enum('jenis_magang', ['all', 'reguler', 'mandiri', 'cti'])->default('all');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};
