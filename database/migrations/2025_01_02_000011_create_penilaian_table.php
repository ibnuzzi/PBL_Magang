<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelaksanaan_id')->constrained('pelaksanaan_magang')->cascadeOnDelete();
            $table->foreignId('parameter_id')->constrained('parameter_penilaian')->cascadeOnDelete();
            $table->foreignId('penguji_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('nilai_industri', 5, 2)->default(0);
            $table->decimal('nilai_dosen', 5, 2)->default(0);
            $table->decimal('nilai_penguji', 5, 2)->default(0);
            $table->decimal('nilai_akhir', 5, 2)->default(0);
            $table->string('grade', 2)->nullable();
            $table->string('predikat', 50)->nullable();
            $table->boolean('sudah_dikonversi')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
