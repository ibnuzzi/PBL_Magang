<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parameter_penilaian', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_akademik', 20);
            $table->decimal('bobot_industri', 5, 2);
            $table->decimal('bobot_dosen', 5, 2);
            $table->decimal('bobot_penguji', 5, 2);
            $table->json('konversi_grade')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parameter_penilaian');
    }
};
