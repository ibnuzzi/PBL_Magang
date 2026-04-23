<x-filament-panels::page>
    <div style="max-width: 48rem; margin: 0 auto;">
        <x-filament::section>
            <x-slot name="heading">Pendaftaran Magang Mandiri</x-slot>
            <x-slot name="description">Untuk magang mandiri, Anda perlu memilih mitra perusahaan yang sudah ada di sistem atau mendaftarkan mitra baru.</x-slot>

            <form wire:submit="submitMandiri">
                {{-- Pilih jenis mitra --}}
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Mitra Perusahaan</label>
                    <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="radio" wire:model.live="jenis_mitra" value="existing" style="accent-color: #f59e0b; width: 16px; height: 16px;">
                            <span style="margin-left: 0.5rem; font-size: 0.875rem; color: #374151;">Pilih dari daftar</span>
                        </label>
                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="radio" wire:model.live="jenis_mitra" value="baru" style="accent-color: #f59e0b; width: 16px; height: 16px;">
                            <span style="margin-left: 0.5rem; font-size: 0.875rem; color: #374151;">Daftarkan mitra baru</span>
                        </label>
                    </div>

                    @if($jenis_mitra === 'existing')
                        <select
                            wire:model="mitra_id"
                            style="width: 100%; height: 42px; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; color: #374151; background: white;"
                        >
                            <option value="">-- Pilih Mitra Perusahaan --</option>
                            @foreach(\App\Models\MitraPerusahaan::verified()->orderBy('nama')->get() as $mitra)
                                <option value="{{ $mitra->id }}">{{ $mitra->nama }} — {{ $mitra->bidang_usaha }}</option>
                            @endforeach
                        </select>
                        @error('mitra_id') <p style="margin-top: 0.25rem; font-size: 0.875rem; color: #dc2626;">{{ $message }}</p> @enderror
                    @else
                        {{-- Form mitra baru --}}
                        <div style="margin-top: 1rem; padding: 1rem; background: #f9fafb; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
                            <h3 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin: 0 0 1rem 0;">Data Perusahaan Baru</h3>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                                <div>
                                    <label style="display: block; font-size: 0.8125rem; font-weight: 500; color: #4b5563; margin-bottom: 0.25rem;">Nama Perusahaan *</label>
                                    <x-filament::input.wrapper><x-filament::input type="text" wire:model="nama_perusahaan" /></x-filament::input.wrapper>
                                    @error('nama_perusahaan') <p style="margin-top: 0.25rem; font-size: 0.8125rem; color: #dc2626;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display: block; font-size: 0.8125rem; font-weight: 500; color: #4b5563; margin-bottom: 0.25rem;">Bidang Usaha *</label>
                                    <x-filament::input.wrapper><x-filament::input type="text" wire:model="bidang_usaha" /></x-filament::input.wrapper>
                                    @error('bidang_usaha') <p style="margin-top: 0.25rem; font-size: 0.8125rem; color: #dc2626;">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div style="margin-top: 1rem;">
                                <label style="display: block; font-size: 0.8125rem; font-weight: 500; color: #4b5563; margin-bottom: 0.25rem;">Alamat *</label>
                                <textarea wire:model="alamat" rows="2" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; color: #374151; resize: vertical;"></textarea>
                                @error('alamat') <p style="margin-top: 0.25rem; font-size: 0.8125rem; color: #dc2626;">{{ $message }}</p> @enderror
                            </div>

                            <h3 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin: 1.25rem 0 1rem 0;">Data PIC</h3>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                                <div>
                                    <label style="display: block; font-size: 0.8125rem; font-weight: 500; color: #4b5563; margin-bottom: 0.25rem;">Nama PIC *</label>
                                    <x-filament::input.wrapper><x-filament::input type="text" wire:model="nama_pic" /></x-filament::input.wrapper>
                                    @error('nama_pic') <p style="margin-top: 0.25rem; font-size: 0.8125rem; color: #dc2626;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display: block; font-size: 0.8125rem; font-weight: 500; color: #4b5563; margin-bottom: 0.25rem;">Jabatan PIC *</label>
                                    <x-filament::input.wrapper><x-filament::input type="text" wire:model="jabatan_pic" /></x-filament::input.wrapper>
                                    @error('jabatan_pic') <p style="margin-top: 0.25rem; font-size: 0.8125rem; color: #dc2626;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display: block; font-size: 0.8125rem; font-weight: 500; color: #4b5563; margin-bottom: 0.25rem;">No. HP PIC *</label>
                                    <x-filament::input.wrapper><x-filament::input type="text" wire:model="no_hp_pic" /></x-filament::input.wrapper>
                                    @error('no_hp_pic') <p style="margin-top: 0.25rem; font-size: 0.8125rem; color: #dc2626;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display: block; font-size: 0.8125rem; font-weight: 500; color: #4b5563; margin-bottom: 0.25rem;">Email PIC *</label>
                                    <x-filament::input.wrapper><x-filament::input type="email" wire:model="email_pic" /></x-filament::input.wrapper>
                                    @error('email_pic') <p style="margin-top: 0.25rem; font-size: 0.8125rem; color: #dc2626;">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div style="display: flex; align-items: flex-start; gap: 0.5rem; margin-top: 1rem; padding: 0.75rem; background: #fffbeb; border-radius: 0.5rem; border: 1px solid #fde68a;">
                                <div style="flex-shrink: 0; color: #d97706; margin-top: 2px;">
                                    <x-filament::icon icon="heroicon-s-exclamation-triangle" style="width: 20px; height: 20px;" />
                                </div>
                                <p style="font-size: 0.875rem; color: #92400e; margin: 0;">
                                    Mitra baru akan diverifikasi oleh koordinator sebelum pendaftaran Anda diproses.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Submit --}}
                <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
                    <x-filament::button type="submit" wire:loading.attr="disabled">
                        <span wire:loading.remove>Buat Pendaftaran Mandiri</span>
                        <span wire:loading>Memproses...</span>
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>
    </div>
</x-filament-panels::page>
