<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PendaftaranMagang extends Model
{
    protected $table = 'pendaftaran_magang';

    protected $fillable = [
        'mahasiswa_id',
        'lowongan_id',
        'mitra_id',
        'dosen_pembimbing_id',
        'jenis_magang',
        'status',
        'catatan',
        'alasan_ditolak',
        'tanggal_daftar',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_daftar' => 'datetime',
        ];
    }

    // ─── Status Constants ────────────────────────────────────────────

    public const STATUS_DRAFT = 'draft';
    public const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi_dokumen';
    public const STATUS_DOKUMEN_LENGKAP = 'dokumen_lengkap';
    public const STATUS_DOKUMEN_KURANG = 'dokumen_kurang';
    public const STATUS_MENUNGGU_KOORDINATOR = 'menunggu_approval_koordinator';
    public const STATUS_MENUNGGU_KPS = 'menunggu_approval_kps';
    public const STATUS_MENUNGGU_KAJUR = 'menunggu_approval_kajur';
    public const STATUS_MENUNGGU_WADIR1 = 'menunggu_approval_wadir1';
    public const STATUS_DISETUJUI_PENUH = 'disetujui_penuh';
    public const STATUS_SURAT_TERBIT = 'surat_pengantar_terbit';
    public const STATUS_LOA = 'loa_diterima';
    public const STATUS_BERJALAN = 'berjalan';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_DITOLAK = 'ditolak';
    public const STATUS_DIBATALKAN = 'dibatalkan';

    // ─── Status Labels ───────────────────────────────────────────────

    public static function statusOptions(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_MENUNGGU_VERIFIKASI => 'Menunggu Verifikasi Dokumen',
            self::STATUS_DOKUMEN_LENGKAP => 'Dokumen Lengkap',
            self::STATUS_DOKUMEN_KURANG => 'Dokumen Kurang',
            self::STATUS_MENUNGGU_KOORDINATOR => 'Menunggu Approval Koordinator',
            self::STATUS_MENUNGGU_KPS => 'Menunggu Approval KPS',
            self::STATUS_MENUNGGU_KAJUR => 'Menunggu Approval Kajur',
            self::STATUS_MENUNGGU_WADIR1 => 'Menunggu Approval Wadir 1',
            self::STATUS_DISETUJUI_PENUH => 'Disetujui Penuh',
            self::STATUS_SURAT_TERBIT => 'Surat Pengantar Terbit',
            self::STATUS_LOA => 'LOA Diterima',
            self::STATUS_BERJALAN => 'Berjalan',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DITOLAK => 'Ditolak',
            self::STATUS_DIBATALKAN => 'Dibatalkan',
        ];
    }

    /**
     * Status flow order untuk stepper visual.
     */
    public static function statusFlow(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_MENUNGGU_VERIFIKASI,
            self::STATUS_DOKUMEN_LENGKAP,
            self::STATUS_MENUNGGU_KOORDINATOR,
            self::STATUS_MENUNGGU_KPS,
            self::STATUS_MENUNGGU_KAJUR,
            self::STATUS_MENUNGGU_WADIR1,
            self::STATUS_DISETUJUI_PENUH,
            self::STATUS_SURAT_TERBIT,
            self::STATUS_LOA,
            self::STATUS_BERJALAN,
            self::STATUS_SELESAI,
        ];
    }

    /**
     * Get the current step index in the flow (for stepper visual).
     */
    public function getCurrentStepIndex(): int
    {
        $flow = self::statusFlow();
        $index = array_search($this->status, $flow);
        return $index !== false ? $index : -1;
    }

    // ─── Relationships ───────────────────────────────────────────────

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(LowonganMagang::class, 'lowongan_id');
    }

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(MitraPerusahaan::class, 'mitra_id');
    }

    public function dosenPembimbing(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing_id');
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(DokumenPendaftaran::class, 'pendaftaran_id');
    }

    public function approval(): HasMany
    {
        return $this->hasMany(ApprovalPendaftaran::class, 'pendaftaran_id');
    }

    public function surat(): HasMany
    {
        return $this->hasMany(SuratMagang::class, 'pendaftaran_id');
    }

    public function pelaksanaan(): HasOne
    {
        return $this->hasOne(PelaksanaanMagang::class, 'pendaftaran_id');
    }

    // ─── Helpers ─────────────────────────────────────────────────────

    public function isPilihan(): bool
    {
        return $this->jenis_magang === 'pilihan';
    }

    public function isMandiri(): bool
    {
        return $this->jenis_magang === 'mandiri';
    }

    public function isWajib(): bool
    {
        return $this->jenis_magang === 'wajib';
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isDitolak(): bool
    {
        return $this->status === self::STATUS_DITOLAK;
    }

    public function canBeSubmitted(): bool
    {
        return in_array($this->status, [
            self::STATUS_DRAFT,
            self::STATUS_DOKUMEN_KURANG,
        ]);
    }

    public function canUploadDokumen(): bool
    {
        return in_array($this->status, [
            self::STATUS_DRAFT,
            self::STATUS_DOKUMEN_KURANG,
            self::STATUS_MENUNGGU_VERIFIKASI,
        ]);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusOptions()[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'gray',
            self::STATUS_MENUNGGU_VERIFIKASI, self::STATUS_MENUNGGU_KOORDINATOR,
            self::STATUS_MENUNGGU_KPS, self::STATUS_MENUNGGU_KAJUR,
            self::STATUS_MENUNGGU_WADIR1 => 'warning',
            self::STATUS_DOKUMEN_LENGKAP, self::STATUS_DISETUJUI_PENUH => 'info',
            self::STATUS_DOKUMEN_KURANG => 'danger',
            self::STATUS_SURAT_TERBIT, self::STATUS_LOA => 'primary',
            self::STATUS_BERJALAN => 'success',
            self::STATUS_SELESAI => 'success',
            self::STATUS_DITOLAK, self::STATUS_DIBATALKAN => 'danger',
            default => 'gray',
        };
    }

    public function getJenisMagangLabelAttribute(): string
    {
        return match ($this->jenis_magang) {
            'pilihan' => 'Pilihan',
            'mandiri' => 'Mandiri',
            'wajib' => 'Wajib',
            default => ucfirst($this->jenis_magang),
        };
    }
}
