<div>
    @php
        $notifikasi = $this->notifikasi;
        $unreadCount = $this->unreadCount;
    @endphp

    <x-filament::section>
        <x-slot name="heading">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span>Notifikasi</span>
                @if($unreadCount > 0)
                    <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 20px; height: 20px; border-radius: 50%; background: #ef4444; color: white; font-size: 0.75rem; font-weight: 700; padding: 0 4px;">{{ $unreadCount }}</span>
                @endif
            </div>
        </x-slot>

        @if($unreadCount > 0)
            <x-slot name="headerEnd">
                <x-filament::button color="gray" size="xs" wire:click="markAllAsRead">
                    Tandai semua dibaca
                </x-filament::button>
            </x-slot>
        @endif

        @if($notifikasi->isEmpty())
            <div style="text-align: center; padding: 1.5rem 1rem;">
                <div style="display: flex; justify-content: center; margin-bottom: 0.5rem; color: #9ca3af;">
                    <x-filament::icon icon="heroicon-o-bell-slash" style="width: 32px; height: 32px;" />
                </div>
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Belum ada notifikasi</p>
            </div>
        @else
            <div style="display: flex; flex-direction: column;">
                @foreach($notifikasi as $item)
                    <div style="padding: 0.75rem 0; display: flex; align-items: flex-start; gap: 0.75rem; {{ !$loop->first ? 'border-top: 1px solid #f3f4f6;' : '' }} {{ !$item->is_read ? 'background: #fffbeb; margin: 0 -1rem; padding-left: 1rem; padding-right: 1rem; border-radius: 0.375rem;' : '' }}">
                        <div style="flex-shrink: 0; margin-top: 2px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
                                {{ match($item->color) {
                                    'success' => 'background: #dcfce7; color: #16a34a;',
                                    'warning' => 'background: #fef3c7; color: #d97706;',
                                    'danger' => 'background: #fee2e2; color: #dc2626;',
                                    'info' => 'background: #cffafe; color: #0891b2;',
                                    default => 'background: #dbeafe; color: #2563eb;',
                                } }}">
                                <x-filament::icon :icon="$item->icon" style="width: 16px; height: 16px;" />
                            </div>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.5rem;">
                                <p style="font-size: 0.875rem; margin: 0; {{ !$item->is_read ? 'font-weight: 600; color: #111827;' : 'font-weight: 500; color: #374151;' }}">{{ $item->judul }}</p>
                                @if(!$item->is_read)
                                    <button wire:click="markAsRead({{ $item->id }})" style="flex-shrink: 0; width: 8px; height: 8px; border-radius: 50%; background: #f59e0b; border: none; cursor: pointer; margin-top: 6px;" title="Tandai dibaca"></button>
                                @endif
                            </div>
                            <p style="font-size: 0.8125rem; color: #4b5563; margin: 0.125rem 0 0 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $item->pesan }}</p>
                            <p style="font-size: 0.75rem; color: #9ca3af; margin: 0.25rem 0 0 0;">{{ $item->created_at->diffForHumans() }}</p>
                        </div>
                        @if($item->link)
                            <a href="{{ $item->link }}" style="flex-shrink: 0; color: #9ca3af; display: flex; align-items: center;">
                                <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" style="width: 16px; height: 16px;" />
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

            <div style="padding-top: 0.75rem; border-top: 1px solid #f3f4f6; text-align: center; margin-top: 0.5rem;">
                <a href="{{ \App\Filament\Pages\Mahasiswa\NotifikasiPage::getUrl() }}" style="font-size: 0.875rem; color: #f59e0b; font-weight: 500; text-decoration: none;">
                    Lihat semua notifikasi →
                </a>
            </div>
        @endif
    </x-filament::section>
</div>
