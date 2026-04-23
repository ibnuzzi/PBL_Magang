<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbook_supervisor_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logbook_id')->constrained('logbook')->cascadeOnDelete();
            $table->string('token', 64)->unique();
            $table->string('no_hp_supervisor', 20);
            $table->boolean('is_used')->default(false);
            $table->timestamp('expired_at');
            $table->boolean('wa_sent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbook_supervisor_tokens');
    }
};
