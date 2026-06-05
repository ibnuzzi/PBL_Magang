<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nim',
        'nip',
        'email',
        'password',
        'role',
        'angkatan',
        'ipk',
        'semester',
        'program_studi_id',
        'no_hp',
        'foto',
        'skills',
        'pengalaman',
        'cv_path',
        'portfolio_path',
        'cv_text',
        'portfolio_text',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'ipk' => 'decimal:2',
        ];
    }

    /**
     * Determine if the user can access the Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active;
    }

    // ─── Relationships ───────────────────────────────────────────────

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function pendaftaranMagangAsMahasiswa(): HasMany
    {
        return $this->hasMany(PendaftaranMagang::class, 'mahasiswa_id');
    }

    public function pendaftaranMagangAsDosen(): HasMany
    {
        return $this->hasMany(PendaftaranMagang::class, 'dosen_pembimbing_id');
    }

    public function approvalPendaftaran(): HasMany
    {
        return $this->hasMany(ApprovalPendaftaran::class, 'approver_id');
    }

    public function penilaianAsPenguji(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'penguji_id');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function pengumuman(): HasMany
    {
        return $this->hasMany(Pengumuman::class, 'pembuat_id');
    }

    public function lowonganDibuat(): HasMany
    {
        return $this->hasMany(LowonganMagang::class, 'pembuat_id');
    }

    public function mitraDiajukan(): HasMany
    {
        return $this->hasMany(MitraPerusahaan::class, 'diajukan_oleh');
    }

    // ─── Role Helpers ────────────────────────────────────────────────

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    public function isDosen(): bool
    {
        return in_array($this->role, ['dosen', 'koordinator', 'kps', 'kajur', 'wadir1']);
    }

    public function isKoordinator(): bool
    {
        return $this->role === 'koordinator';
    }

    public function isKPS(): bool
    {
        return $this->role === 'kps';
    }

    public function isKajur(): bool
    {
        return $this->role === 'kajur';
    }

    public function isWadir1(): bool
    {
        return $this->role === 'wadir1';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user bisa approve di level tertentu.
     */
    public function canApproveLevel(string $level): bool
    {
        return $this->role === $level;
    }

    /**
     * Get unread notifikasi count.
     */
    public function getUnreadNotifikasiCountAttribute(): int
    {
        return $this->notifikasi()->where('is_read', false)->count();
    }
}
