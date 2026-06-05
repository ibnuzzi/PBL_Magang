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
        'ttd_dosen',
        'foto_kegiatan',
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

    public function generateSupervisorToken(string $noHpSupervisor): LogbookSupervisorToken
    {
        $this->supervisorTokens()->where('is_used', false)->update(['expired_at' => now()]);

        return $this->supervisorTokens()->create([
            'token' => \Illuminate\Support\Str::random(40),
            'no_hp_supervisor' => $noHpSupervisor,
            'is_used' => false,
            'expired_at' => now()->addDays(7),
            'wa_sent' => false,
        ]);
    }

    protected static function booted()
    {
        static::saving(function ($logbook) {
            if ($logbook->bukti_ttd_path && $logbook->status_supervisor === 'menunggu') {
                $logbook->status_supervisor = 'disetujui';
                if (!$logbook->submitted_at) {
                    $logbook->submitted_at = now();
                }
            }

            if ($logbook->ttd_dosen && $logbook->status_dosen === 'menunggu') {
                $logbook->status_dosen = 'disetujui';
            }
        });
    }
}
