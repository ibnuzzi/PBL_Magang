<x-filament-panels::page>
    @php
        $lowonganList = $this->lowonganList;
        $selectedLowongan = $this->selectedLowongan;
        $eligibilityData = $this->eligibilityData;
    @endphp

    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        {{-- Step 1: Pilih Lowongan Wajib --}}
        <x-filament::section>
            <x-slot name="heading">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: #f59e0b; color: white; font-size: 0.875rem; font-weight: 700;">1</span>
                    <span>Pilih Lowongan Wajib</span>
                </div>
            </x-slot>

            @if($lowonganList->isEmpty())
                <div style="padding: 1rem; background: #fffbeb; border-radius: 0.5rem; text-align: center;">
                    <p style="font-size: 0.875rem; color: #b45309;">Belum ada lowongan wajib yang aktif dan masih memiliki kuota.</p>
                </div>
            @else
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
                    @foreach($lowonganList as $lw)
                        @php
                            $isSelected = $selectedLowonganId == $lw->id;
                            $borderColor = $isSelected ? '#f59e0b' : '#e5e7eb';
                            $bgColor = $isSelected ? '#fffbeb' : '#ffffff';
                            $boxShadow = $isSelected ? '0 0 0 3px rgba(245,158,11,0.2)' : 'none';
                            $kuotaColor = $lw->isFull() ? '#dc2626' : '#16a34a';
                        @endphp
                        <button
                            wire:click="$set('selectedLowonganId', {{ $lw->id }})"
                            x-data="{ hovered: false }"
                            @mouseenter="hovered = true"
                            @mouseleave="hovered = false"
                            :style="hovered 
                                ? 'text-align: left; padding: 1rem; border-radius: 0.75rem; border: 2px solid #d1d5db; background: #f9fafb; cursor: pointer; transition: all 0.2s; box-shadow: {{ $boxShadow }};'
                                : 'text-align: left; padding: 1rem; border-radius: 0.75rem; border: 2px solid {{ $borderColor }}; background: {{ $bgColor }}; cursor: pointer; transition: all 0.2s; box-shadow: {{ $boxShadow }};'"
                        >
                            <h3 style="font-size: 0.9375rem; font-weight: 600; color: #111827; margin: 0;">{{ $lw->judul }}</h3>
                            <p style="font-size: 0.8125rem; color: #6b7280; margin-top: 0.25rem;">{{ $lw->mitra->nama }}</p>
                            <div style="display: flex; align-items: center; gap: 1rem; margin-top: 0.75rem; font-size: 0.8125rem;">
                                <span :style="'display: inline-flex; align-items: center; gap: 0.25rem; font-weight: 600; color: {{ $kuotaColor }};'">
                                    <x-filament::icon icon="heroicon-s-users" style="width: 16px; height: 16px;" />
                                    {{ $lw->kuota_terisi }}/{{ $lw->kuota }}
                                </span>
                                <span style="color: #9ca3af;">
                                    Tutup: {{ $lw->tanggal_tutup->format('d M Y') }}
                                </span>
                            </div>
                        </button>
                    @endforeach
                </div>
            @endif
        </x-filament::section>

        {{-- Step 2: Pilih Mahasiswa --}}
        @if($selectedLowongan)
            <x-filament::section>
                <x-slot name="heading">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; width: 100%;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: #f59e0b; color: white; font-size: 0.875rem; font-weight: 700;">2</span>
                            <span>Pilih Mahasiswa</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <x-filament::badge color="success">
                                {{ count($eligibilityData['eligible']) }} eligible
                            </x-filament::badge>
                            <x-filament::badge color="danger">
                                {{ count($eligibilityData['ineligible']) }} tidak eligible
                            </x-filament::badge>
                            <x-filament::badge color="warning">
                                {{ count($selectedMahasiswa) }} dipilih
                            </x-filament::badge>
                        </div>
                    </div>
                </x-slot>

                {{-- Info lowongan terpilih --}}
                <div style="padding: 0.75rem 1rem; background: #f3f4f6; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <span style="font-weight: 600; color: #111827;">{{ $selectedLowongan->judul }}</span>
                        <span style="color: #6b7280; margin-left: 0.5rem;">— {{ $selectedLowongan->mitra->nama }}</span>
                    </div>
                    <div style="color: #6b7280; font-size: 0.875rem;">
                        Sisa kuota: <strong style="color: #111827;">{{ $selectedLowongan->kuota - $selectedLowongan->kuota_terisi }}</strong>
                    </div>
                </div>

                {{-- Search --}}
                <div style="margin-bottom: 1rem;">
                    <x-filament::input.wrapper>
                        <x-filament::input
                            type="text"
                            wire:model.live.debounce.300ms="searchMahasiswa"
                            placeholder="Cari mahasiswa (nama/NIM)..."
                        />
                    </x-filament::input.wrapper>
                </div>

                {{-- Action buttons --}}
                <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                    <x-filament::button color="success" size="sm" wire:click="selectAllEligible">
                        Pilih Semua Eligible
                    </x-filament::button>
                    <x-filament::button color="gray" size="sm" wire:click="deselectAll">
                        Hapus Pilihan
                    </x-filament::button>
                </div>

                {{-- Eligible Table --}}
                @if(count($eligibilityData['eligible']) > 0)
                    <div style="margin-bottom: 1rem;">
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: #15803d; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.375rem;">
                            <x-filament::icon icon="heroicon-s-check-circle" style="width: 18px; height: 18px;" />
                            Eligible ({{ count($eligibilityData['eligible']) }})
                        </h3>
                        <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background: #f9fafb;">
                                        <th style="width: 40px; padding: 0.5rem 0.75rem;"></th>
                                        <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">NIM</th>
                                        <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Nama</th>
                                        <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Prodi</th>
                                        <th style="padding: 0.5rem 0.75rem; text-align: center; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">IPK</th>
                                        <th style="padding: 0.5rem 0.75rem; text-align: center; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Smt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($eligibilityData['eligible'] as $item)
                                        @php
                                            $mhs = $item['mahasiswa'];
                                            $isMhsSelected = in_array($mhs->id, $selectedMahasiswa);
                                            $rowBg = $isMhsSelected ? '#fffbeb' : 'transparent';
                                        @endphp
                                        <tr x-data="{ hovered: false }"
                                            @mouseenter="hovered = true"
                                            @mouseleave="hovered = false"
                                            :style="hovered 
                                                ? 'border-top: 1px solid #e5e7eb; background: #f9fafb;'
                                                : 'border-top: 1px solid #e5e7eb; background: {{ $rowBg }};'">
                                            <td style="padding: 0.5rem 0.75rem; text-align: center;">
                                                <input type="checkbox"
                                                    wire:click="toggleMahasiswa({{ $mhs->id }})"
                                                    @checked(in_array($mhs->id, $selectedMahasiswa))
                                                    style="border-radius: 4px; border: 1px solid #d1d5db; width: 16px; height: 16px; cursor: pointer; accent-color: #f59e0b;">
                                            </td>
                                            <td style="padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #111827; font-family: ui-monospace, monospace;">{{ $mhs->nim }}</td>
                                            <td style="padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #111827; font-weight: 500;">{{ $mhs->name }}</td>
                                            <td style="padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #6b7280;">{{ $mhs->programStudi?->nama ?? '-' }}</td>
                                            <td style="padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #111827; text-align: center;">{{ number_format($mhs->ipk ?? 0, 2) }}</td>
                                            <td style="padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #111827; text-align: center;">{{ $mhs->semester ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                {{-- Ineligible --}}
                @if(count($eligibilityData['ineligible']) > 0)
                    <details style="margin-top: 1rem;">
                        <summary style="font-size: 0.875rem; font-weight: 600; color: #b91c1c; cursor: pointer; display: flex; align-items: center; gap: 0.375rem; padding: 0.5rem 0;">
                            <x-filament::icon icon="heroicon-s-x-circle" style="width: 18px; height: 18px;" />
                            Tidak Eligible ({{ count($eligibilityData['ineligible']) }}) — klik untuk melihat
                        </summary>
                        <div style="margin-top: 0.5rem; border: 1px solid #fecaca; border-radius: 0.5rem; overflow: hidden;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background: #fef2f2;">
                                        <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #ef4444; text-transform: uppercase; letter-spacing: 0.05em;">NIM</th>
                                        <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #ef4444; text-transform: uppercase; letter-spacing: 0.05em;">Nama</th>
                                        <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #ef4444; text-transform: uppercase; letter-spacing: 0.05em;">Alasan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($eligibilityData['ineligible'] as $item)
                                        @php $mhs = $item['mahasiswa']; @endphp
                                        <tr style="border-top: 1px solid #fecaca;">
                                            <td style="padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #374151; font-family: ui-monospace, monospace;">{{ $mhs->nim }}</td>
                                            <td style="padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #374151;">{{ $mhs->name }}</td>
                                            <td style="padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #dc2626;">{{ implode(', ', $item['errors']) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </details>
                @endif
            </x-filament::section>

            {{-- Step 3: Konfirmasi --}}
            @if(count($selectedMahasiswa) > 0)
                <x-filament::section>
                    <x-slot name="heading">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: #f59e0b; color: white; font-size: 0.875rem; font-weight: 700;">3</span>
                            <span>Konfirmasi Penempatan</span>
                        </div>
                    </x-slot>

                    <div style="padding: 1rem; background: #eff6ff; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: flex-start; gap: 0.75rem;">
                        <div style="flex-shrink: 0; color: #2563eb; margin-top: 2px;">
                            <x-filament::icon icon="heroicon-s-information-circle" style="width: 20px; height: 20px;" />
                        </div>
                        <div style="font-size: 0.875rem; color: #1e40af;">
                            <p style="font-weight: 600; margin: 0;">Anda akan menempatkan {{ count($selectedMahasiswa) }} mahasiswa ke:</p>
                            <p style="margin: 0.25rem 0 0 0;">{{ $selectedLowongan->judul }} — {{ $selectedLowongan->mitra->nama }}</p>
                            <p style="margin: 0.25rem 0 0 0; font-size: 0.8125rem; color: #3b82f6;">Status pendaftaran akan diset ke "Menunggu Approval Koordinator".</p>
                        </div>
                    </div>

                    <x-filament::button
                        wire:click="processBulkPenempatan"
                        wire:loading.attr="disabled"
                        wire:confirm="Yakin ingin menempatkan {{ count($selectedMahasiswa) }} mahasiswa ke lowongan ini?"
                        style="width: 100%;"
                    >
                        <span wire:loading.remove wire:target="processBulkPenempatan">Proses Penempatan ({{ count($selectedMahasiswa) }} Mahasiswa)</span>
                        <span wire:loading wire:target="processBulkPenempatan">Memproses...</span>
                    </x-filament::button>
                </x-filament::section>
            @endif
        @endif
    </div>
</x-filament-panels::page>
