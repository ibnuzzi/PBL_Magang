<?php

namespace App\Filament\Widgets;

use App\Models\Notifikasi;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class NotifikasiWidget extends Widget
{
    protected string $view = 'filament.widgets.notifikasi-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -1;

    public function getNotifikasiProperty()
    {
        return Notifikasi::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function getUnreadCountProperty(): int
    {
        return Notifikasi::where('user_id', Auth::user()->id)
            ->where('is_read', false)
            ->count();
    }

    public function markAsRead(int $id): void
    {
        $notifikasi = Notifikasi::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();

        if ($notifikasi) {
            $notifikasi->markAsRead();
        }
    }

    public function markAllAsRead(): void
    {
        Notifikasi::markAllReadForUser(Auth::user()->id);
    }
}
