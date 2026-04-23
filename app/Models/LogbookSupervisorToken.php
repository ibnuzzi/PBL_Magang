<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogbookSupervisorToken extends Model
{
    protected $table = 'logbook_supervisor_tokens';

    protected $fillable = [
        'logbook_id',
        'token',
        'no_hp_supervisor',
        'is_used',
        'expired_at',
        'wa_sent',
    ];

    protected function casts(): array
    {
        return [
            'is_used' => 'boolean',
            'expired_at' => 'datetime',
            'wa_sent' => 'boolean',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────

    public function logbook(): BelongsTo
    {
        return $this->belongsTo(Logbook::class, 'logbook_id');
    }
}
