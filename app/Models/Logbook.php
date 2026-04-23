<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Logbook extends Model
{
    protected $table = 'logbook';

    protected $fillable = [
        'pelaksanaan_id',
        'tanggal',
        'minggu_ke',
        'hari_ke',
        'kegiatan',
        'hasil',
        'status_supervisor',
        'status_dosen',
        'bukti_ttd_path',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'submitted_at' => 'datetime',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────

    public function pelaksanaan(): BelongsTo
    {
        return $this->belongsTo(PelaksanaanMagang::class, 'pelaksanaan_id');
    }

    public function supervisorTokens(): HasMany
    {
        return $this->hasMany(LogbookSupervisorToken::class, 'logbook_id');
    }
}
