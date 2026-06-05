<x-filament-panels::page>
    @php
        $pendaftaranList = $this->pendaftaran;
        $statusSteps = $this->getStatusSteps();
    @endphp

    @if($pendaftaranList->isEmpty())
        <x-filament::section>
            <div style="text-align: center; padding: 3rem 1rem;">
                <div style="display: flex; justify-content: center; margin-bottom: 0.75rem; color: #9ca3af;">
                    <x-filament::icon icon="heroicon-o-clipboard-document-list" style="width: 48px; height: 48px;" />
                </div>
                <h3 style="font-size: 0.9375rem; font-weight: 600; color: #111827; margin: 0;">Belum ada pendaftaran</h3>
                <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Anda belum mendaftar magang. Silakan browse lowongan.</p>
                <div style="display: flex; justify-content: center; gap: 0.75rem; margin-top: 1.5rem;">
                    <x-filament::button tag="a" :href="\App\Filament\Pages\Mahasiswa\BrowseLowongan::getUrl()">
                        Browse Lowongan
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>
    @else
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            @foreach($pendaftaranList as $pendaftaran)
                <div style="background: white; border-radius: 0.75rem; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">

                    {{-- Header --}}
                    <div style="padding: 1.25rem; border-bottom: 1px solid #f3f4f6;">
                        <div style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
                            <div>
                                <h3 style="font-size: 1.0625rem; font-weight: 600; color: #111827; margin: 0;">
                                    @if($pendaftaran->lowongan)
                                        {{ $pendaftaran->lowongan->judul }}
                                    @else
                                        Magang Mandiri — {{ $pendaftaran->mitra->nama }}
                                    @endif
                                </h3>
                                <p style="font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0 0 0;">
                                    {{ $pendaftaran->mitra->nama }} · Didaftarkan {{ $pendaftaran->tanggal_daftar->format('d M Y') }}
                                </p>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <x-filament::badge :color="match($pendaftaran->jenis_magang) { 'pilihan' => 'info', 'mandiri' => 'warning', 'wajib' => 'danger', default => 'gray' }">
                                    {{ ucfirst($pendaftaran->jenis_magang) }}
                                </x-filament::badge>
                                <x-filament::badge :color="$pendaftaran->status_color">
                                    {{ $pendaftaran->status_label }}
                                </x-filament::badge>
                            </div>
                        </div>
                    </div>

                    {{-- Stepper --}}
                    @if(!in_array($pendaftaran->status, [\App\Models\PendaftaranMagang::STATUS_DITOLAK, \App\Models\PendaftaranMagang::STATUS_DIBATALKAN]))
                        <div style="padding: 1rem 1.25rem; overflow-x: auto;">
                            <div style="display: flex; align-items: center; min-width: max-content;">
                                @php
                                    $currentIndex = $pendaftaran->getCurrentStepIndex();
                                    $stepKeys = array_keys($statusSteps);
                                @endphp
                                @foreach($statusSteps as $stepKey => $stepLabel)
                                    @php
                                        $stepIndex = array_search($stepKey, $stepKeys);
                                        $isCompleted = $stepIndex < $currentIndex;
                                        $isCurrent = $stepIndex === $currentIndex;
                                    @endphp
                                    <div style="display: flex; align-items: center; {{ !$loop->last ? 'flex: 1;' : '' }}">
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <div style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; transition: all 0.3s;
                                                {{ $isCompleted ? 'background: #22c55e; color: white;' : ($isCurrent ? 'background: #f59e0b; color: white; box-shadow: 0 0 0 4px rgba(245,158,11,0.2);' : 'background: #e5e7eb; color: #6b7280;') }}">
                                                @if($isCompleted)
                                                    <x-filament::icon icon="heroicon-s-check" style="width: 16px; height: 16px;" />
                                                @else
                                                    {{ $stepIndex + 1 }}
                                                @endif
                                            </div>
                                            <span style="margin-top: 0.25rem; font-size: 0.625rem; text-align: center; line-height: 1.2; max-width: 70px;
                                                {{ $isCurrent ? 'font-weight: 600; color: #f59e0b;' : 'color: #6b7280;' }}">
                                                {{ $stepLabel }}
                                            </span>
                                        </div>
                                        @if(!$loop->last)
                                            <div style="flex: 1; height: 2px; margin: 0 0.25rem; {{ $isCompleted ? 'background: #22c55e;' : 'background: #e5e7eb;' }}"></div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div style="padding: 1rem 1.25rem;">
                            <div style="padding: 0.75rem; border-radius: 0.5rem; {{ $pendaftaran->status === \App\Models\PendaftaranMagang::STATUS_DITOLAK ? 'background: #fef2f2;' : 'background: #f9fafb;' }}">
                                <p style="font-size: 0.875rem; margin: 0; {{ $pendaftaran->status === \App\Models\PendaftaranMagang::STATUS_DITOLAK ? 'color: #b91c1c;' : 'color: #4b5563;' }}">
                                    @if($pendaftaran->status === \App\Models\PendaftaranMagang::STATUS_DITOLAK)
                                        <strong>Ditolak:</strong> {{ $pendaftaran->alasan_ditolak ?? 'Tidak ada alasan yang diberikan.' }}
                                    @else
                                        Pendaftaran ini telah dibatalkan.
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Dokumen --}}
                    @if($pendaftaran->dokumen->isNotEmpty())
                        <div style="padding: 0.75rem 1.25rem; border-top: 1px solid #f3f4f6;">
                            <h4 style="font-size: 0.875rem; font-weight: 500; color: #374151; margin: 0 0 0.5rem 0;">Dokumen</h4>
                            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                @foreach($pendaftaran->dokumen as $doc)
                                    <x-filament::badge :color="match($doc->status) { 'disetujui' => 'success', 'ditolak' => 'danger', default => 'gray' }">
                                        {{ \App\Models\DokumenPendaftaran::jenisOptions()[$doc->jenis_dokumen] ?? $doc->jenis_dokumen }}
                                        ({{ ucfirst($doc->status) }})
                                    </x-filament::badge>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Dosen --}}
                    @if($pendaftaran->dosenPembimbing)
                        <div style="padding: 0.5rem 1.25rem; border-top: 1px solid #f3f4f6;">
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">
                                <span style="font-weight: 500;">Dosen Pembimbing:</span> {{ $pendaftaran->dosenPembimbing->name }}
                            </p>
                        </div>
                    @endif

                    {{-- Surat Magang --}}
                    @if($pendaftaran->surat->where('status', 'diterbitkan')->isNotEmpty())
                        <div style="padding: 0.75rem 1.25rem; border-top: 1px solid #f3f4f6;">
                            <h4 style="font-size: 0.875rem; font-weight: 500; color: #374151; margin: 0 0 0.5rem 0;">Surat yang Diterbitkan</h4>
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                @foreach($pendaftaran->surat->where('status', 'diterbitkan') as $surat)
                                    <div style="display: flex; align-items: center; justify-content: space-between; background: #f9fafb; padding: 0.5rem 0.75rem; border-radius: 0.375rem; border: 1px solid #e5e7eb;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <x-filament::icon icon="heroicon-o-document-text" style="width: 20px; height: 20px; color: #f59e0b;" />
                                            <div>
                                                <p style="font-size: 0.875rem; font-weight: 600; color: #111827; margin: 0;">
                                                    {{ $surat->jenis_surat === 'pengantar' ? 'Surat Pengantar Magang' : 'Letter of Acceptance (LOA)' }}
                                                </p>
                                                <p style="font-size: 0.75rem; color: #6b7280; margin: 0;">
                                                    No: {{ $surat->nomor_surat }} · Terbit: {{ $surat->diterbitkan_at?->format('d M Y') ?? $surat->created_at->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <x-filament::button tag="a" :href="route('surat.download', $surat->id)" target="_blank" size="xs" color="gray" icon="heroicon-m-arrow-down-tray">
                                            Unduh
                                        </x-filament::button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Actions --}}
                    @if($pendaftaran->canBeSubmitted() || $pendaftaran->canUploadDokumen())
                        <div style="padding: 1rem 1.25rem; background: #f9fafb; display: flex; flex-wrap: wrap; gap: 0.75rem; border-top: 1px solid #f3f4f6;">
                            @if($pendaftaran->canUploadDokumen())
                                <x-filament::button tag="a" :href="\App\Filament\Pages\Mahasiswa\UploadDokumen::getUrl()" color="gray">
                                    Upload Dokumen
                                </x-filament::button>
                            @endif
                            @if($pendaftaran->canBeSubmitted())
                                <x-filament::button wire:click="submitPendaftaran({{ $pendaftaran->id }})" wire:loading.attr="disabled">
                                    Submit Pendaftaran
                                </x-filament::button>
                                <x-filament::button color="danger" wire:click="batalkanPendaftaran({{ $pendaftaran->id }})" wire:confirm="Yakin ingin membatalkan pendaftaran ini?">
                                    Batalkan
                                </x-filament::button>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</x-filament-panels::page>
