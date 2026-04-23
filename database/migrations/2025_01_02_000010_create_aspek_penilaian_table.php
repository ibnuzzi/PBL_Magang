<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aspek_penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parameter_id')->constrained('parameter_penilaian')->cascadeOnDelete();
            $table->enum('penilai', ['industri', 'dosen', 'penguji']);
            $table->string('nama_aspek', 150);
            $table->decimal('bobot_aspek', 5, 2);
            $table->tinyInteger('urutan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aspek_penilaian');
    }
};
