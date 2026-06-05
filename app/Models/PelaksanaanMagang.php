<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PelaksanaanMagang extends Model
{
    protected $table = 'pelaksanaan_magang';

    protected $fillable = [
        'pendaftaran_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'nama_supervisor',
        'jabatan_supervisor',
        'no_hp_supervisor',
        'status',
        'total_hari_kerja',
        'total_logbook_terisi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    protected static function booted()
    {
        static::saved(function (PelaksanaanMagang $pelaksanaan) {
            if ($pelaksanaan->status === 'berjalan') {
                $pendaftaran = $pelaksanaan->pendaftaran;
                if ($pendaftaran && $pendaftaran->status !== \App\Models\PendaftaranMagang::STATUS_BERJALAN) {
                    app(\App\Services\PendaftaranService::class)->mulaiPelaksanaan($pendaftaran);
                }
            }
        });
    }

    // ─── Relationships ───────────────────────────────────────────────

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(PendaftaranMagang::class, 'pendaftaran_id');
    }

    public function logbook(): HasMany
    {
        return $this->hasMany(Logbook::class, 'pelaksanaan_id');
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'pelaksanaan_id');
    }
}
