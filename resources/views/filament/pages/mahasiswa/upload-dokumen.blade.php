<x-filament-panels::page>
    @php
        $pendaftaranList = $this->pendaftaranList;
        $selectedPendaftaran = $this->selectedPendaftaran;
        $requiredDokumen = $this->requiredDokumen;
        $existingDokumen = $this->existingDokumen;
    @endphp

    @if($pendaftaranList->isEmpty())
        <x-filament::section>
            <div style="text-align: center; padding: 3rem 1rem;">
                <div style="display: flex; justify-content: center; margin-bottom: 0.75rem; color: #9ca3af;">
                    <x-filament::icon icon="heroicon-o-document-arrow-up" style="width: 48px; height: 48px;" />
                </div>
                <h3 style="font-size: 0.9375rem; font-weight: 600; color: #111827; margin: 0;">Tidak ada pendaftaran yang perlu dilengkapi</h3>
                <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Semua dokumen sudah lengkap atau belum ada pendaftaran aktif.</p>
            </div>
        </x-filament::section>
    @else
        <div style="display: grid; grid-template-columns: 1fr 3fr; gap: 1.5rem;">
            {{-- Sidebar --}}
            <div>
                <x-filament::section>
                    <x-slot name="heading">Pilih Pendaftaran</x-slot>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        @foreach($pendaftaranList as $p)
                            <button
                                wire:click="$set('selectedPendaftaranId', {{ $p->id }})"
                                style="width: 100%; text-align: left; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid {{ $selectedPendaftaranId == $p->id ? '#f59e0b' : '#e5e7eb' }}; background: {{ $selectedPendaftaranId == $p->id ? '#fffbeb' : 'white' }}; cursor: pointer; transition: all 0.2s;"
                            >
                                <p style="font-size: 0.875rem; font-weight: 500; color: #111827; margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    @if($p->lowongan)
                                        {{ $p->lowongan->judul }}
                                    @else
                                        Mandiri — {{ $p->mitra->nama }}
                                    @endif
                                </p>
                                <p style="font-size: 0.75rem; color: #6b7280; margin: 0.25rem 0 0 0;">
                                    {{ ucfirst($p->jenis_magang) }} · {{ ucfirst(str_replace('_', ' ', $p->status)) }}
                                </p>
                            </button>
                        @endforeach
                    </div>
                </x-filament::section>
            </div>

            {{-- Main --}}
            <div>
                @if($selectedPendaftaran)
                    <x-filament::section>
                        <x-slot name="heading">Dokumen yang Diperlukan</x-slot>
                        <x-slot name="description">Upload dokumen berikut untuk melengkapi pendaftaran Anda.</x-slot>

                        <div style="display: flex; flex-direction: column;">
                            @foreach($requiredDokumen as $jenisDok)
                                @php
                                    $existing = $existingDokumen[$jenisDok] ?? null;
                                    $label = \App\Models\DokumenPendaftaran::jenisOptions()[$jenisDok] ?? strtoupper(str_replace('_', ' ', $jenisDok));
                                    $statusDoc = $existing['status'] ?? null;
                                @endphp
                                <div style="padding: 1rem 0; {{ !$loop->first ? 'border-top: 1px solid #f3f4f6;' : '' }}">
                                    <div style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
                                        <div style="flex: 1; min-width: 0;">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <h4 style="font-size: 0.875rem; font-weight: 500; color: #111827; margin: 0;">{{ $label }}</h4>
                                                @if($statusDoc)
                                                    <x-filament::badge :color="match($statusDoc) { 'disetujui' => 'success', 'ditolak' => 'danger', default => 'warning' }">
                                                        {{ ucfirst($statusDoc) }}
                                                    </x-filament::badge>
                                                @else
                                                    <x-filament::badge color="gray">Belum upload</x-filament::badge>
                                                @endif
                                            </div>
                                            @if($existing && $statusDoc === 'ditolak' && ($existing['keterangan_reject'] ?? null))
                                                <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; color: #dc2626;">Alasan: {{ $existing['keterangan_reject'] }}</p>
                                            @endif
                                        </div>

                                        @if($selectedPendaftaran->canUploadDokumen() && (!$statusDoc || $statusDoc !== 'disetujui'))
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <input type="file"
                                                    wire:model="uploadedFiles.{{ $jenisDok }}"
                                                    accept=".pdf,.jpg,.jpeg,.png"
                                                    style="font-size: 0.875rem; color: #6b7280;">
                                                <x-filament::button size="sm" wire:click="uploadDokumen('{{ $jenisDok }}')" wire:loading.attr="disabled">
                                                    Upload
                                                </x-filament::button>
                                            </div>
                                        @elseif($statusDoc === 'disetujui')
                                            <div style="color: #16a34a;">
                                                <x-filament::icon icon="heroicon-s-check-circle" style="width: 24px; height: 24px;" />
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-filament::section>
                @else
                    <x-filament::section>
                        <div style="text-align: center; padding: 3rem 1rem;">
                            <div style="display: flex; justify-content: center; margin-bottom: 0.5rem; color: #9ca3af;">
                                <x-filament::icon icon="heroicon-o-arrow-left" style="width: 32px; height: 32px;" />
                            </div>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Pilih pendaftaran di sebelah kiri untuk upload dokumen.</p>
                        </div>
                    </x-filament::section>
                @endif
            </div>
        </div>
    @endif
</x-filament-panels::page>
