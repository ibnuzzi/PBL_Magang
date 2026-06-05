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

    protected static function booted()
    {
        static::saving(function (SuratMagang $surat) {
            if ($surat->status === 'diterbitkan' && is_null($surat->diterbitkan_at)) {
                $surat->diterbitkan_at = now();
            }
        });

        static::saved(function (SuratMagang $surat) {
            if ($surat->status === 'diterbitkan') {
                $pendaftaran = $surat->pendaftaran;
                if ($pendaftaran) {
                    if ($surat->jenis_surat === 'pengantar' && $pendaftaran->status !== \App\Models\PendaftaranMagang::STATUS_SURAT_TERBIT) {
                        app(\App\Services\PendaftaranService::class)->terbitkanSurat($pendaftaran);
                    } elseif ($surat->jenis_surat === 'loa' && $pendaftaran->status !== \App\Models\PendaftaranMagang::STATUS_LOA) {
                        app(\App\Services\PendaftaranService::class)->loaDiterima($pendaftaran);
                    }
                }
            }
        });
    }

    // ─── Relationships ───────────────────────────────────────────────

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(PendaftaranMagang::class, 'pendaftaran_id');
    }
}
