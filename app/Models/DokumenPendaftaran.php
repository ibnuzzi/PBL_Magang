<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenPendaftaran extends Model
{
    protected $table = 'dokumen_pendaftaran';

    protected $fillable = [
        'pendaftaran_id',
        'jenis_dokumen',
        'file_path',
        'status',
        'keterangan_reject',
    ];

    // ─── Jenis Dokumen Labels ────────────────────────────────────────

    public static function jenisOptions(): array
    {
        return [
            'khs' => 'KHS',
            'proposal_magang' => 'Proposal Magang',
            'cv' => 'CV',
            'surat_izin_ortu' => 'Surat Izin Ortu',
            'surat_pengantar' => 'Surat Pengantar',
            'surat_integritas' => 'Surat Integritas',
            'surat_lamaran' => 'Surat Lamaran',
            'surat_rekomendasi' => 'Surat Rekomendasi',
            'sertifikasi_kompetensi' => 'Sertifikasi Kompetensi',
        ];
    }

    /**
     * Dokumen default required untuk Flow A (Pilihan).
     */
    public static function dokumenPilihan(): array
    {
        return ['khs', 'proposal_magang', 'cv', 'surat_izin_ortu', 'surat_pengantar', 'surat_integritas', 'surat_lamaran', 'surat_rekomendasi', 'sertifikasi_kompetensi'];
    }

    /**
     * Dokumen default required untuk Flow B (Mandiri).
     */
    public static function dokumenMandiri(): array
    {
        return ['khs', 'proposal_magang', 'cv', 'surat_izin_ortu', 'surat_pengantar', 'surat_integritas', 'surat_lamaran', 'surat_rekomendasi', 'sertifikasi_kompetensi'];
    }

    // ─── Relationships ───────────────────────────────────────────────

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(PendaftaranMagang::class, 'pendaftaran_id');
    }

    // ─── Helpers ─────────────────────────────────────────────────────

    public function isApproved(): bool
    {
        return $this->status === 'disetujui';
    }

    public function isRejected(): bool
    {
        return $this->status === 'ditolak';
    }

    public function isPending(): bool
    {
        return $this->status === 'menunggu';
    }

    public function getJenisLabelAttribute(): string
    {
        return self::jenisOptions()[$this->jenis_dokumen] ?? strtoupper(str_replace('_', ' ', $this->jenis_dokumen));
    }
}
