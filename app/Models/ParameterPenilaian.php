<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParameterPenilaian extends Model
{
    protected $table = 'parameter_penilaian';

    protected $fillable = [
        'tahun_akademik',
        'bobot_industri',
        'bobot_dosen',
        'bobot_penguji',
        'konversi_grade',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'bobot_industri' => 'decimal:2',
            'bobot_dosen' => 'decimal:2',
            'bobot_penguji' => 'decimal:2',
            'konversi_grade' => 'array',
            'is_active' => 'boolean',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────

    public function aspek(): HasMany
    {
        return $this->hasMany(AspekPenilaian::class, 'parameter_id');
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'parameter_id');
    }
}
