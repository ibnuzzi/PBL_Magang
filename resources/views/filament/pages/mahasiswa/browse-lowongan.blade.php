<x-filament-panels::page>
    {{-- Search & Filter --}}
    <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
        <div style="flex: 1; min-width: 200px;">
            <x-filament::input.wrapper>
                <x-filament::input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari lowongan atau perusahaan..."
                />
            </x-filament::input.wrapper>
        </div>
        <div style="min-width: 150px;">
            <select
                wire:model.live="filterJenis"
                style="width: 100%; height: 42px; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; color: #374151; background: white; cursor: pointer;"
            >
                <option value="">Semua Jenis</option>
                <option value="pilihan">Pilihan</option>
                <option value="wajib">Wajib</option>
            </select>
        </div>
    </div>

    {{-- Lowongan Cards --}}
    @if($this->lowongan->isEmpty())
        <x-filament::section>
            <div style="text-align: center; padding: 3rem 1rem;">
                <div style="display: flex; justify-content: center; margin-bottom: 0.75rem; color: #9ca3af;">
                    <x-filament::icon icon="heroicon-o-inbox" style="width: 48px; height: 48px;" />
                </div>
                <h3 style="font-size: 0.9375rem; font-weight: 600; color: #111827; margin: 0;">Belum ada lowongan</h3>
                <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Belum ada lowongan yang tersedia saat ini.</p>
            </div>
        </x-filament::section>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
            @foreach($this->lowongan as $item)
                <div style="background: white; border-radius: 0.75rem; border: 1px solid #e5e7eb; overflow: hidden; transition: box-shadow 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05);"
                     onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)';"
                     onmouseout="this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)';">

                    {{-- Header --}}
                    <div style="padding: 1.25rem; border-bottom: 1px solid #f3f4f6;">
                        <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.5rem;">
                            <div style="flex: 1; min-width: 0;">
                                <h3 style="font-size: 1.0625rem; font-weight: 600; color: #111827; margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $item->judul }}
                                </h3>
                                <p style="font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0 0 0;">
                                    {{ $item->mitra->nama }}
                                </p>
                            </div>
                            <x-filament::badge :color="$item->jenis_magang === 'pilihan' ? 'info' : 'danger'">
                                {{ ucfirst($item->jenis_magang) }}
                            </x-filament::badge>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div style="padding: 1.25rem;">
                        @if($item->deskripsi)
                            <p style="font-size: 0.875rem; color: #4b5563; margin: 0 0 1rem 0; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $item->deskripsi }}
                            </p>
                        @endif

                        <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.875rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #6b7280;">Kuota</span>
                                <span style="font-weight: 600; color: {{ $item->isFull() ? '#dc2626' : '#16a34a' }};">
                                    {{ $item->kuota_terisi }}/{{ $item->kuota }}
                                </span>
                            </div>
                            @if($item->syarat_ipk > 0)
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="color: #6b7280;">IPK Min</span>
                                    <span style="font-weight: 500; color: #111827;">{{ number_format($item->syarat_ipk, 2) }}</span>
                                </div>
                            @endif
                            @if($item->syarat_semester > 1)
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="color: #6b7280;">Semester Min</span>
                                    <span style="font-weight: 500; color: #111827;">{{ $item->syarat_semester }}</span>
                                </div>
                            @endif
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #6b7280;">Batas Pendaftaran</span>
                                <span style="font-weight: 500; color: #111827;">{{ $item->tanggal_tutup->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div style="padding: 1rem 1.25rem; background: #f9fafb; border-top: 1px solid #f3f4f6;">
                        @if($item->isFull())
                            <x-filament::button color="gray" disabled style="width: 100%;">
                                Kuota Penuh
                            </x-filament::button>
                        @else
                            <x-filament::button
                                wire:click="daftarLowongan({{ $item->id }})"
                                wire:loading.attr="disabled"
                                wire:target="daftarLowongan({{ $item->id }})"
                                style="width: 100%;"
                            >
                                <span wire:loading.remove wire:target="daftarLowongan({{ $item->id }})">Daftar Sekarang</span>
                                <span wire:loading wire:target="daftarLowongan({{ $item->id }})">Mendaftar...</span>
                            </x-filament::button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-filament-panels::page>
