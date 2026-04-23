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
            'krs' => 'KRS',
            'transkip_nilai' => 'Transkip Nilai',
            'cv' => 'CV',
            'surat_lamaran' => 'Surat Lamaran',
            'proposal_magang' => 'Proposal Magang',
            'sertifikat_kompetensi' => 'Sertifikat Kompetensi',
            'loa_perusahaan' => 'LOA Perusahaan',
            'surat_rekomendasi' => 'Surat Rekomendasi',
        ];
    }

    /**
     * Dokumen default required untuk Flow A (Pilihan).
     */
    public static function dokumenPilihan(): array
    {
        return ['krs', 'transkip_nilai', 'cv', 'surat_lamaran', 'proposal_magang'];
    }

    /**
     * Dokumen default required untuk Flow B (Mandiri) — termasuk LOA.
     */
    public static function dokumenMandiri(): array
    {
        return ['krs', 'transkip_nilai', 'cv', 'surat_lamaran', 'proposal_magang', 'loa_perusahaan'];
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
