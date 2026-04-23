<?php

namespace App\Filament\Pages\Mahasiswa;

use App\Models\Notifikasi;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class NotifikasiPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBell;

    protected static string | \UnitEnum | null $navigationGroup = 'Magang';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Notifikasi';

    protected static ?string $title = 'Notifikasi';

    protected string $view = 'filament.pages.mahasiswa.notifikasi';

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    public static function getNavigationBadge(): ?string
    {
        $count = Notifikasi::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public function getNotifikasiProperty()
    {
        return Notifikasi::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function markAsRead(int $id): void
    {
        Notifikasi::where('id', $id)
            ->where('user_id', auth()->id())
            ->update(['is_read' => true]);
    }

    public function markAllAsRead(): void
    {
        Notifikasi::markAllReadForUser(auth()->id());
    }
}
