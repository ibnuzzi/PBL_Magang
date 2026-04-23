<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalPendaftaran extends Model
{
    protected $table = 'approval_pendaftaran';

    protected $fillable = [
        'pendaftaran_id',
        'approver_id',
        'level',
        'status',
        'urutan_level',
        'catatan',
        'diproses_at',
    ];

    protected function casts(): array
    {
        return [
            'diproses_at' => 'datetime',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(PendaftaranMagang::class, 'pendaftaran_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
