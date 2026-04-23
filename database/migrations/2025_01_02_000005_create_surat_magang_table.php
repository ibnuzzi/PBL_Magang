<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_magang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran_magang')->cascadeOnDelete();
            $table->enum('jenis_surat', ['pengantar', 'loa']);
            $table->string('nomor_surat', 100);
            $table->string('file_path', 255);
            $table->enum('status', ['draft', 'diterbitkan', 'dibatalkan'])->default('draft');
            $table->timestamp('diterbitkan_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_magang');
    }
};
