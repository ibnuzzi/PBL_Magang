<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penilaian extends Model
{
    protected $table = 'penilaian';

    protected $fillable = [
        'pelaksanaan_id',
        'parameter_id',
        'penguji_id',
        'nilai_industri',
        'nilai_dosen',
        'nilai_penguji',
        'nilai_akhir',
        'grade',
        'predikat',
        'sudah_dikonversi',
    ];

    protected function casts(): array
    {
        return [
            'nilai_industri' => 'decimal:2',
            'nilai_dosen' => 'decimal:2',
            'nilai_penguji' => 'decimal:2',
            'nilai_akhir' => 'decimal:2',
            'sudah_dikonversi' => 'boolean',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────

    public function pelaksanaan(): BelongsTo
    {
        return $this->belongsTo(PelaksanaanMagang::class, 'pelaksanaan_id');
    }

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(ParameterPenilaian::class, 'parameter_id');
    }

    public function penguji(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penguji_id');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPenilaian::class, 'penilaian_id');
    }
}
