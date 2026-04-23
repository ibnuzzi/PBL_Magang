<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LowonganMagang extends Model
{
    protected $table = 'lowongan_magang';

    protected $fillable = [
        'mitra_id',
        'pembuat_id',
        'judul',
        'deskripsi',
        'jenis_magang',
        'kuota',
        'kuota_terisi',
        'syarat_ipk',
        'syarat_semester',
        'syarat_prodi',
        'dokumen_required',
        'tanggal_buka',
        'tanggal_tutup',
        'tanggal_mulai_magang',
        'tanggal_selesai_magang',
        'is_published',
        'is_full',
    ];

    protected function casts(): array
    {
        return [
            'syarat_prodi' => 'array',
            'dokumen_required' => 'array',
            'tanggal_buka' => 'date',
            'tanggal_tutup' => 'date',
            'tanggal_mulai_magang' => 'date',
            'tanggal_selesai_magang' => 'date',
            'is_published' => 'boolean',
            'is_full' => 'boolean',
            'syarat_ipk' => 'decimal:2',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(MitraPerusahaan::class, 'mitra_id');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembuat_id');
    }

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(PendaftaranMagang::class, 'lowongan_id');
    }

    // ─── Helpers ─────────────────────────────────────────────────────

    /**
     * Cek apakah lowongan masih bisa didaftarkan.
     */
    public function isOpen(): bool
    {
        return $this->is_published
            && !$this->is_full
            && $this->tanggal_buka <= now()
            && $this->tanggal_tutup >= now();
    }

    /**
     * Cek apakah kuota sudah penuh.
     */
    public function isFull(): bool
    {
        return $this->kuota_terisi >= $this->kuota;
    }

    /**
     * Cek apakah mahasiswa memenuhi syarat.
     */
    public function checkSyarat(User $mahasiswa): array
    {
        $errors = [];

        if ($this->syarat_ipk > 0 && ($mahasiswa->ipk ?? 0) < $this->syarat_ipk) {
            $errors[] = "IPK minimal {$this->syarat_ipk}, IPK Anda: " . ($mahasiswa->ipk ?? 'belum diisi');
        }

        if ($this->syarat_semester > 1 && ($mahasiswa->semester ?? 0) < $this->syarat_semester) {
            $errors[] = "Semester minimal {$this->syarat_semester}, semester Anda: " . ($mahasiswa->semester ?? 'belum diisi');
        }

        if (!empty($this->syarat_prodi) && !in_array($mahasiswa->program_studi_id, $this->syarat_prodi)) {
            $errors[] = 'Program studi Anda tidak memenuhi syarat lowongan ini';
        }

        return $errors;
    }

    /**
     * Increment kuota terisi dan update is_full.
     */
    public function incrementKuota(): void
    {
        $this->increment('kuota_terisi');
        if ($this->fresh()->isFull()) {
            $this->update(['is_full' => true]);
        }
    }

    /**
     * Scope: hanya lowongan yang published dan masih buka.
     */
    public function scopeOpen($query)
    {
        return $query->where('is_published', true)
            ->where('is_full', false)
            ->where('tanggal_buka', '<=', now())
            ->where('tanggal_tutup', '>=', now());
    }

    /**
     * Label jenis magang untuk tampilan.
     */
    public function getJenisMagangLabelAttribute(): string
    {
        return match ($this->jenis_magang) {
            'pilihan' => 'Pilihan',
            'wajib' => 'Wajib',
            default => ucfirst($this->jenis_magang),
        };
    }
}
