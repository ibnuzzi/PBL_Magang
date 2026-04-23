<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $fillable = [
        'pembuat_id',
        'judul',
        'konten',
        'target_role',
        'jenis_magang',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'is_published' => 'boolean',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembuat_id');
    }
}
