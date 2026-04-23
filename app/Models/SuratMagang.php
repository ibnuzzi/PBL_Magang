<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratMagang extends Model
{
    protected $table = 'surat_magang';

    protected $fillable = [
        'pendaftaran_id',
        'jenis_surat',
        'nomor_surat',
        'file_path',
        'status',
        'diterbitkan_at',
    ];

    protected function casts(): array
    {
        return [
            'diterbitkan_at' => 'datetime',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(PendaftaranMagang::class, 'pendaftaran_id');
    }
}
