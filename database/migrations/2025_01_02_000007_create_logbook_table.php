<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbook', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelaksanaan_id')->constrained('pelaksanaan_magang')->cascadeOnDelete();
            $table->date('tanggal');
            $table->tinyInteger('minggu_ke');
            $table->tinyInteger('hari_ke');
            $table->text('kegiatan');
            $table->text('hasil');
            $table->enum('status_supervisor', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->enum('status_dosen', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->string('bukti_ttd_path', 255)->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['pelaksanaan_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbook');
    }
};
