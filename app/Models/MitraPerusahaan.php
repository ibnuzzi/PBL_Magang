<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MitraPerusahaan extends Model
{
    protected $table = 'mitra_perusahaan';

    protected $fillable = [
        'nama',
        'alamat',
        'bidang_usaha',
        'nama_pic',
        'jabatan_pic',
        'no_hp_pic',
        'email_pic',
        'is_resmi_polinema',
        'is_cti',
        'kuota_mahasiswa',
        'status_verifikasi',
        'diajukan_oleh',
    ];

    protected function casts(): array
    {
        return [
            'is_resmi_polinema' => 'boolean',
            'is_cti' => 'boolean',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────

    public function pendaftaranMagang(): HasMany
    {
        return $this->hasMany(PendaftaranMagang::class, 'mitra_id');
    }

    public function lowonganMagang(): HasMany
    {
        return $this->hasMany(LowonganMagang::class, 'mitra_id');
    }

    public function pengaju(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    // ─── Helpers ─────────────────────────────────────────────────────

    public function isVerified(): bool
    {
        return $this->status_verifikasi === 'terverifikasi';
    }

    public function isPending(): bool
    {
        return $this->status_verifikasi === 'menunggu';
    }

    // ─── Scopes ──────────────────────────────────────────────────────

    public function scopeVerified($query)
    {
        return $query->where('status_verifikasi', 'terverifikasi');
    }

    public function scopePending($query)
    {
        return $query->where('status_verifikasi', 'menunggu');
    }
}
