<x-filament-panels::page>
    {{-- Status Magang Banner --}}
    @php $user = auth()->user(); @endphp
    @if(!$user->canApplyMagang())
        <div style="margin-bottom: 1.5rem; border-radius: 0.75rem; overflow: hidden;
            border: 1px solid {{ $user->isMagangDiterima() ? '#bbf7d0' : ($user->isMagangProses() ? '#fde68a' : '#fecaca') }};
            background: {{ $user->isMagangDiterima() ? '#f0fdf4' : ($user->isMagangProses() ? '#fffbeb' : '#fef2f2') }};">
            <div style="padding: 1rem 1.25rem; display: flex; align-items: flex-start; gap: 0.75rem;">
                <div style="flex-shrink: 0; margin-top: 2px;">
                    @if($user->isMagangDiterima())
                        <x-filament::icon icon="heroicon-s-check-circle" style="width: 24px; height: 24px; color: #16a34a;" />
                    @elseif($user->isMagangProses())
                        <x-filament::icon icon="heroicon-s-clock" style="width: 24px; height: 24px; color: #d97706;" />
                    @else
                        <x-filament::icon icon="heroicon-s-x-circle" style="width: 24px; height: 24px; color: #dc2626;" />
                    @endif
                </div>
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.375rem; flex-wrap: wrap;">
                        <h4 style="margin: 0; font-size: 0.9375rem; font-weight: 600;
                            color: {{ $user->isMagangDiterima() ? '#166534' : ($user->isMagangProses() ? '#92400e' : '#991b1b') }};">
                            @if($user->isMagangDiterima())
                                ✅ Diterima / MBKM
                            @elseif($user->isMagangProses())
                                ⏳ Proses Pendaftaran
                            @else
                                Status: {{ $user->status_magang_label }}
                            @endif
                        </h4>
                        <x-filament::badge :color="$user->status_magang_color">
                            {{ $user->status_magang_label }}
                        </x-filament::badge>
                    </div>
                    <p style="margin: 0 0 0.5rem 0; font-size: 0.875rem; line-height: 1.5;
                        color: {{ $user->isMagangDiterima() ? '#15803d' : ($user->isMagangProses() ? '#a16207' : '#b91c1c') }};">
                        {{ $user->status_magang_keterangan }}
                    </p>
                    <a href="{{ \App\Filament\Pages\Mahasiswa\StatusPendaftaran::getUrl() }}"
                       style="font-size: 0.875rem; font-weight: 600; color: #003B7A; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
                        Lihat Detail Pendaftaran →
                    </a>
                </div>
            </div>
            @if($user->isMagangProses())
                <div style="padding: 0.75rem 1.25rem; background: rgba(0,0,0,0.04); border-top: 1px solid rgba(0,0,0,0.06);">
                    <p style="margin: 0; font-size: 0.8125rem; color: #78716c;">
                        ℹ️ Anda tidak dapat mendaftar ke perusahaan lain selama pendaftaran masih dalam proses.
                    </p>
                </div>
            @endif
        </div>
    @endif

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

    {{-- Skeleton Loading --}}
    <div wire:loading wire:target="search, filterJenis" style="width: 100%;">
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
            @for($i=0; $i<6; $i++)
                <div style="background: white; border-radius: 0.75rem; border: 1px solid #e5e7eb; overflow: hidden; height: 100%;">
                    <div style="padding: 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between;">
                        <div style="width: 70%;">
                            <div style="height: 1.25rem; background: #e5e7eb; border-radius: 0.25rem; width: 100%; margin-bottom: 0.5rem; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;"></div>
                            <div style="height: 0.875rem; background: #e5e7eb; border-radius: 0.25rem; width: 60%; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;"></div>
                        </div>
                        <div style="height: 1.5rem; background: #e5e7eb; border-radius: 9999px; width: 20%; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;"></div>
                    </div>
                    <div style="padding: 1.25rem;">
                        <div style="height: 0.875rem; background: #e5e7eb; border-radius: 0.25rem; width: 100%; margin-bottom: 0.5rem; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;"></div>
                        <div style="height: 0.875rem; background: #e5e7eb; border-radius: 0.25rem; width: 80%; margin-bottom: 1.5rem; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;"></div>
                        
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                            <div style="height: 0.875rem; background: #e5e7eb; border-radius: 0.25rem; width: 30%; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;"></div>
                            <div style="height: 0.875rem; background: #e5e7eb; border-radius: 0.25rem; width: 20%; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;"></div>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                            <div style="height: 0.875rem; background: #e5e7eb; border-radius: 0.25rem; width: 40%; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;"></div>
                            <div style="height: 0.875rem; background: #e5e7eb; border-radius: 0.25rem; width: 30%; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;"></div>
                        </div>
                    </div>
                    <div style="padding: 1rem 1.25rem; background: #f9fafb; border-top: 1px solid #f3f4f6; display: flex; gap: 0.5rem;">
                        <div style="height: 2.25rem; background: #e5e7eb; border-radius: 0.5rem; width: 50%; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;"></div>
                        <div style="height: 2.25rem; background: #e5e7eb; border-radius: 0.5rem; width: 50%; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;"></div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
        }
    </style>

    {{-- Lowongan Cards --}}
    <div wire:loading.remove wire:target="search, filterJenis" style="width: 100%;">
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
                        @elseif(!$user->canApplyMagang())
                            <div style="display: flex; gap: 0.5rem;">
                                <x-filament::button
                                    color="gray"
                                    wire:click="openDetail({{ $item->id }})"
                                    style="flex: 1;"
                                    outlined
                                >
                                    Detail
                                </x-filament::button>
                                <x-filament::button color="gray" disabled style="flex: 1;" title="Anda masih memiliki pendaftaran aktif">
                                    Tidak Tersedia
                                </x-filament::button>
                            </div>
                        @else
                            <div style="display: flex; gap: 0.5rem;">
                                <x-filament::button
                                    color="gray"
                                    wire:click="openDetail({{ $item->id }})"
                                    style="flex: 1;"
                                    outlined
                                >
                                    Detail
                                </x-filament::button>
                                <x-filament::button
                                    wire:click="daftarLowongan({{ $item->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="daftarLowongan({{ $item->id }})"
                                    style="flex: 1;"
                                >
                                    <span wire:loading.remove wire:target="daftarLowongan({{ $item->id }})">Daftar</span>
                                    <span wire:loading wire:target="daftarLowongan({{ $item->id }})">Mendaftar...</span>
                                </x-filament::button>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Modal Detail Lowongan --}}
    @if($this->selectedDetail)
        <div style="position: fixed; inset: 0; z-index: 50; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.5); padding: 1rem;">
            <div style="background: white; border-radius: 0.75rem; width: 100%; max-width: 600px; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);">
                
                {{-- Modal Header --}}
                <div style="padding: 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h2 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin: 0;">{{ $this->selectedDetail->judul }}</h2>
                        <p style="font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0 0 0;">{{ $this->selectedDetail->mitra->nama }}</p>
                    </div>
                    <button wire:click="closeDetail" style="color: #9ca3af; background: none; border: none; cursor: pointer; padding: 0.25rem;">
                        <x-filament::icon icon="heroicon-o-x-mark" style="width: 24px; height: 24px;" />
                    </button>
                </div>

                {{-- Modal Body --}}
                <div style="padding: 1.25rem; overflow-y: auto; flex: 1;">
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin: 0 0 0.5rem 0;">Deskripsi Lowongan</h4>
                        <p style="font-size: 0.875rem; color: #4b5563; line-height: 1.5; margin: 0; white-space: pre-wrap;">{{ $this->selectedDetail->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; background: #f9fafb; padding: 1rem; border-radius: 0.5rem;">
                        <div>
                            <span style="display: block; font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">Jenis Magang</span>
                            <span style="font-size: 0.875rem; font-weight: 500; color: #111827;">{{ ucfirst($this->selectedDetail->jenis_magang) }}</span>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">Batas Pendaftaran</span>
                            <span style="font-size: 0.875rem; font-weight: 500; color: #111827;">{{ $this->selectedDetail->tanggal_tutup->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">Syarat IPK Minimal</span>
                            <span style="font-size: 0.875rem; font-weight: 500; color: #111827;">{{ $this->selectedDetail->syarat_ipk > 0 ? number_format($this->selectedDetail->syarat_ipk, 2) : 'Tidak ada syarat' }}</span>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">Syarat Semester Minimal</span>
                            <span style="font-size: 0.875rem; font-weight: 500; color: #111827;">{{ $this->selectedDetail->syarat_semester > 1 ? $this->selectedDetail->syarat_semester : 'Tidak ada syarat' }}</span>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">Mulai Magang</span>
                            <span style="font-size: 0.875rem; font-weight: 500; color: #111827;">{{ $this->selectedDetail->tanggal_mulai_magang ? $this->selectedDetail->tanggal_mulai_magang->format('d M Y') : '-' }}</span>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">Selesai Magang</span>
                            <span style="font-size: 0.875rem; font-weight: 500; color: #111827;">{{ $this->selectedDetail->tanggal_selesai_magang ? $this->selectedDetail->tanggal_selesai_magang->format('d M Y') : '-' }}</span>
                        </div>
                    </div>

                    @if(!empty($this->selectedDetail->dokumen_required))
                        <div>
                            <h4 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin: 0 0 0.5rem 0;">Dokumen Wajib Upload</h4>
                            <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.875rem; color: #4b5563;">
                                @foreach($this->selectedDetail->dokumen_required as $dok)
                                    <li>{{ \App\Models\DokumenPendaftaran::jenisOptions()[$dok] ?? strtoupper(str_replace('_', ' ', $dok)) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                {{-- Modal Footer --}}
                <div style="padding: 1.25rem; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end; gap: 0.75rem; background: #f9fafb;">
                    <x-filament::button color="gray" wire:click="closeDetail" outlined>
                        Tutup
                    </x-filament::button>
                    @if($this->selectedDetail->isFull())
                        <x-filament::button color="gray" disabled>
                            Kuota Penuh
                        </x-filament::button>
                    @elseif(!$user->canApplyMagang())
                        <x-filament::button color="gray" disabled title="Anda masih memiliki pendaftaran aktif">
                            Tidak Tersedia
                        </x-filament::button>
                    @else
                        <x-filament::button wire:click="daftarLowongan({{ $this->selectedDetail->id }})">
                            Daftar Sekarang
                        </x-filament::button>
                    @endif
                </div>
            </div>
        </div>
    @endif

</x-filament-panels::page>
