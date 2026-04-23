<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'jenis',
        'judul',
        'pesan',
        'link',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // ─── Helpers ─────────────────────────────────────────────────────

    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    public function markAsUnread(): void
    {
        $this->update(['is_read' => false]);
    }

    /**
     * Mark all notifications as read for a user.
     */
    public static function markAllReadForUser(int $userId): void
    {
        static::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Get icon berdasarkan jenis notifikasi.
     */
    public function getIconAttribute(): string
    {
        return match ($this->jenis) {
            'pendaftaran_baru' => 'heroicon-o-clipboard-document-list',
            'status_berubah' => 'heroicon-o-arrow-path',
            'approval_pending' => 'heroicon-o-clock',
            'dokumen_ditolak' => 'heroicon-o-x-circle',
            'pendaftaran_ditolak' => 'heroicon-o-hand-thumb-down',
            'surat_terbit' => 'heroicon-o-document-check',
            default => 'heroicon-o-bell',
        };
    }

    /**
     * Get warna berdasarkan jenis notifikasi.
     */
    public function getColorAttribute(): string
    {
        return match ($this->jenis) {
            'pendaftaran_baru' => 'primary',
            'status_berubah' => 'info',
            'approval_pending' => 'warning',
            'dokumen_ditolak', 'pendaftaran_ditolak' => 'danger',
            'surat_terbit' => 'success',
            default => 'gray',
        };
    }
}
