<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements FilamentUser, HasAvatar
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
        'nidn',
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
        'cv_link',
        'khs_link',
        'kompetensi',
        'is_active',
        'status_magang',
        'kuota_bimbingan',
    ];

    // ─── Status Magang Constants ─────────────────────────────────────

    public const STATUS_MAGANG_TIDAK_AKTIF = 'tidak_aktif';
    public const STATUS_MAGANG_PROSES = 'proses';
    public const STATUS_MAGANG_DITERIMA = 'diterima';
    public const STATUS_MAGANG_DITOLAK = 'ditolak';

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

    public function canAccessPanel(Panel $panel): bool
    {
        if (!$this->is_active) {
            return false;
        }

        return match ($panel->getId()) {
            'admin' => $this->role === 'admin',
            'mahasiswa' => $this->role === 'mahasiswa' || $this->role === 'admin',
            'koordinator' => $this->role === 'koordinator' || $this->role === 'admin',
            'dosen' => in_array($this->role, ['dosen', 'kps', 'kajur']) || $this->role === 'admin',
            'wadir' => $this->role === 'wadir1' || $this->role === 'admin',
            default => false,
        };
    }

    /**
     * Get the avatar URL for the Filament navbar.
     */
    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->foto) {
            return '/storage/' . $this->foto;
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random';
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
        return $this->role === $level || $this->role === 'admin';
    }

    /**
     * Get unread notifikasi count.
     */
    public function getUnreadNotifikasiCountAttribute(): int
    {
        return $this->notifikasi()->where('is_read', false)->count();
    }

    // ─── Status Magang Helpers ───────────────────────────────────────

    /**
     * Label untuk status_magang (human-readable).
     */
    public static function statusMagangOptions(): array
    {
        return [
            self::STATUS_MAGANG_TIDAK_AKTIF => 'Tidak Aktif',
            self::STATUS_MAGANG_PROSES => 'Proses Pendaftaran',
            self::STATUS_MAGANG_DITERIMA => 'Diterima / MBKM',
            self::STATUS_MAGANG_DITOLAK => 'Ditolak',
        ];
    }

    public function getStatusMagangLabelAttribute(): string
    {
        return self::statusMagangOptions()[$this->status_magang] ?? 'Tidak Aktif';
    }

    public function getStatusMagangColorAttribute(): string
    {
        return match ($this->status_magang) {
            self::STATUS_MAGANG_TIDAK_AKTIF => 'gray',
            self::STATUS_MAGANG_PROSES => 'warning',
            self::STATUS_MAGANG_DITERIMA => 'success',
            self::STATUS_MAGANG_DITOLAK => 'danger',
            default => 'gray',
        };
    }

    /**
     * Keterangan lengkap per status magang.
     */
    public function getStatusMagangKeteranganAttribute(): string
    {
        return match ($this->status_magang) {
            self::STATUS_MAGANG_TIDAK_AKTIF => 'Anda belum mendaftar magang. Silakan browse lowongan atau daftar mandiri.',
            self::STATUS_MAGANG_PROSES => 'Pendaftaran Anda sedang diproses. Anda tidak dapat mendaftar ke tempat lain selama proses ini berlangsung.',
            self::STATUS_MAGANG_DITERIMA => 'Selamat! Anda telah diterima magang. LOA sudah divalidasi oleh Koordinator Magang.',
            self::STATUS_MAGANG_DITOLAK => 'Pendaftaran Anda ditolak. Anda dapat mendaftar ulang ke tempat lain.',
            default => '',
        };
    }

    /**
     * Cek apakah mahasiswa boleh apply magang baru.
     */
    public function canApplyMagang(): bool
    {
        return in_array($this->status_magang, [
            self::STATUS_MAGANG_TIDAK_AKTIF,
            self::STATUS_MAGANG_DITOLAK,
        ]);
    }

    /**
     * Cek apakah mahasiswa sedang dalam proses pendaftaran.
     */
    public function isMagangProses(): bool
    {
        return $this->status_magang === self::STATUS_MAGANG_PROSES;
    }

    /**
     * Cek apakah mahasiswa sudah diterima magang.
     */
    public function isMagangDiterima(): bool
    {
        return $this->status_magang === self::STATUS_MAGANG_DITERIMA;
    }
}
