<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('skills')->nullable()->after('foto');
            $table->text('pengalaman')->nullable()->after('skills');
            $table->string('cv_path', 255)->nullable()->after('pengalaman');
            $table->string('portfolio_path', 255)->nullable()->after('cv_path');
            $table->text('cv_text')->nullable()->after('portfolio_path');
            $table->text('portfolio_text')->nullable()->after('cv_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'skills',
                'pengalaman',
                'cv_path',
                'portfolio_path',
                'cv_text',
                'portfolio_text',
            ]);
        });
    }
};
