<x-filament-panels::page>
    @php
        $notifikasiPaginator = $this->notifikasi;
        $notifikasiList = $notifikasiPaginator->items();
    @endphp

    <div style="max-width: 56rem; margin: 0 auto;">
        @if(count($notifikasiList) > 0)
            <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
                <x-filament::button color="gray" size="sm" wire:click="markAllAsRead">
                    Tandai semua telah dibaca
                </x-filament::button>
            </div>
        @endif

        @if(count($notifikasiList) === 0)
            <x-filament::section>
                <div style="text-align: center; padding: 3rem 1rem;">
                    <div style="display: flex; justify-content: center; margin-bottom: 0.75rem; color: #9ca3af;">
                        <x-filament::icon icon="heroicon-o-bell-slash" style="width: 48px; height: 48px;" />
                    </div>
                    <h3 style="font-size: 0.9375rem; font-weight: 600; color: #111827; margin: 0;">Belum ada notifikasi</h3>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Notifikasi akan muncul saat ada pembaruan pada pendaftaran magang Anda.</p>
                </div>
            </x-filament::section>
        @else
            <div style="background: white; border-radius: 0.75rem; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                @foreach($notifikasiList as $item)
                    <div style="padding: 1rem; display: flex; align-items: flex-start; gap: 0.75rem; {{ !$item->is_read ? 'background: #fffbeb;' : '' }} {{ !$loop->first ? 'border-top: 1px solid #f3f4f6;' : '' }}"
                         onmouseover="this.style.background='#f9fafb';"
                         onmouseout="this.style.background='{{ !$item->is_read ? '#fffbeb' : 'transparent' }}';">

                        {{-- Icon --}}
                        <div style="flex-shrink: 0; margin-top: 2px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
                                {{ match($item->color) {
                                    'success' => 'background: #dcfce7; color: #16a34a;',
                                    'warning' => 'background: #fef3c7; color: #d97706;',
                                    'danger' => 'background: #fee2e2; color: #dc2626;',
                                    'info' => 'background: #cffafe; color: #0891b2;',
                                    default => 'background: #dbeafe; color: #2563eb;',
                                } }}">
                                <x-filament::icon :icon="$item->icon" style="width: 20px; height: 20px;" />
                            </div>
                        </div>

                        {{-- Content --}}
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.5rem;">
                                <div>
                                    <p style="font-size: 0.875rem; margin: 0; {{ !$item->is_read ? 'font-weight: 600; color: #111827;' : 'font-weight: 500; color: #374151;' }}">
                                        {{ $item->judul }}
                                    </p>
                                    <p style="font-size: 0.875rem; color: #4b5563; margin: 0.25rem 0 0 0;">{{ $item->pesan }}</p>
                                    <p style="font-size: 0.75rem; color: #9ca3af; margin: 0.375rem 0 0 0;">{{ $item->created_at->diffForHumans() }} · {{ $item->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0;">
                                    @if(!$item->is_read)
                                        <x-filament::button color="gray" size="xs" wire:click="markAsRead({{ $item->id }})">
                                            Tandai dibaca
                                        </x-filament::button>
                                    @endif
                                    @if($item->link)
                                        <a href="{{ $item->link }}" style="color: #9ca3af; display: flex;">
                                            <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" style="width: 16px; height: 16px;" />
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 1rem;">
                {{ $notifikasiPaginator->links() }}
            </div>
        @endif
    </div>
</x-filament-panels::page>
