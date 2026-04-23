<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AspekPenilaian extends Model
{
    protected $table = 'aspek_penilaian';

    protected $fillable = [
        'parameter_id',
        'penilai',
        'nama_aspek',
        'bobot_aspek',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'bobot_aspek' => 'decimal:2',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(ParameterPenilaian::class, 'parameter_id');
    }

    public function detailPenilaian(): HasMany
    {
        return $this->hasMany(DetailPenilaian::class, 'aspek_id');
    }
}
