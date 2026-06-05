<x-filament-panels::page>
    @php
        $totalWeight = $weightCv + $weightPortfolio + $weightIpk;
        $isWeightValid = $totalWeight === 100;
        $selectedDetail = $this->selectedDetail;
    @endphp

    <div style="display: grid; grid-template-columns: 1.2fr 1.8fr; gap: 1.5rem; align-items: start;" class="rekom-grid">
        
        {{-- ==================== LEFT COLUMN: PROFILE & CONFIG ==================== --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            {{-- Academic Profile Card (Read Only) --}}
            <x-filament::section>
                <x-slot name="heading">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <x-filament::icon icon="heroicon-o-academic-cap" style="width: 20px; height: 20px; color: #003B7A;" />
                        <span>Data Akademik (Resmi JTI)</span>
                    </div>
                </x-slot>
                <x-slot name="description">Data ini dimasukkan oleh Admin dan bersifat tidak dapat diubah oleh mahasiswa.</x-slot>

                <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 0.5rem;">
                    <div>
                        <label style="font-size: 0.75rem; font-weight: 600; color: #6b7280;">PROGRAM STUDI</label>
                        <p style="font-size: 0.875rem; font-weight: 700; color: #111827; margin: 0.15rem 0 0 0;">{{ $programStudiName }}</p>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="font-size: 0.75rem; font-weight: 600; color: #6b7280;">IPK TERAKHIR</label>
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.15rem;">
                                <span style="font-size: 1.125rem; font-weight: 800; color: #003B7A;">{{ $ipk !== null ? number_format($ipk, 2) : 'Belum diisi' }}</span>
                                <span style="font-size: 0.65rem; font-weight: 600; padding: 0.2rem 0.5rem; border-radius: 9999px; background: #e5e7eb; color: #4b5563;">Skala 4.0</span>
                            </div>
                        </div>
                        <div>
                            <label style="font-size: 0.75rem; font-weight: 600; color: #6b7280;">SEMESTER</label>
                            <p style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0.15rem 0 0 0;">{{ $semester ?? 'Belum diisi' }}</p>
                        </div>
                    </div>
                </div>
            </x-filament::section>

            {{-- Skills & Experience Card (Editable) --}}
            <x-filament::section>
                <x-slot name="heading">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <x-filament::icon icon="heroicon-o-pencil-square" style="width: 20px; height: 20px; color: #003B7A;" />
                        <span>Keterampilan & Pengalaman</span>
                    </div>
                </x-slot>
                <x-slot name="description">Lengkapi keterampilan teknis dan proyek Anda untuk analisis kecocokan AI.</x-slot>

                <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 0.5rem;">
                    <div>
                        <label for="skills" style="font-size: 0.75rem; font-weight: 600; color: #475569;">Keterampilan Teknis (Pisahkan dengan koma)</label>
                        <textarea id="skills" wire:model="skills" rows="3" placeholder="Contoh: Laravel, PHP, MySQL, React, JavaScript, Git, Figma, UI/UX"
                            style="width: 100%; font-size: 0.8125rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; padding: 0.5rem 0.75rem; margin-top: 0.25rem; outline: none; background: #f8fafc; resize: vertical;"
                            onfocus="this.style.borderColor='#003B7A'; this.style.background='#fff';"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';"></textarea>
                    </div>

                    <div>
                        <label for="pengalaman" style="font-size: 0.75rem; font-weight: 600; color: #475569;">Riwayat Pengalaman / Proyek</label>
                        <textarea id="pengalaman" wire:model="pengalaman" rows="3" placeholder="Contoh: Mengembangkan website e-commerce dengan Laravel, Mendesain wireframe aplikasi mobile di Figma, Anggota divisi IT Himpunan Mahasiswa."
                            style="width: 100%; font-size: 0.8125rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; padding: 0.5rem 0.75rem; margin-top: 0.25rem; outline: none; background: #f8fafc; resize: vertical;"
                            onfocus="this.style.borderColor='#003B7A'; this.style.background='#fff';"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';"></textarea>
                    </div>
                </div>
            </x-filament::section>

            {{-- Document Uploads Card (PDF only) --}}
            <x-filament::section>
                <x-slot name="heading">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <x-filament::icon icon="heroicon-o-document-arrow-up" style="width: 20px; height: 20px; color: #003B7A;" />
                        <span>Unggah CV & Portofolio (PDF)</span>
                    </div>
                </x-slot>
                <x-slot name="description">Unggah berkas PDF untuk diekstrak dan dicocokkan otomatis oleh AI.</x-slot>

                <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 0.5rem;">
                    {{-- CV Upload --}}
                    <div style="border: 1px solid #f1f5f9; padding: 0.75rem; border-radius: 0.5rem; background: #fafafa;">
                        <span style="font-size: 0.75rem; font-weight: 700; color: #334155; display: block; margin-bottom: 0.25rem;">CURRICULUM VITAE (CV)</span>
                        @if($cvPath)
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <x-filament::icon icon="heroicon-s-document-check" style="width: 16px; height: 16px; color: #16a34a;" />
                                <a href="{{ Storage::url($cvPath) }}" target="_blank" style="font-size: 0.75rem; color: #003B7A; font-weight: 600; text-decoration: underline;">Lihat CV Saat Ini</a>
                            </div>
                        @endif
                        <input type="file" wire:model="cvFile" accept=".pdf" style="font-size: 0.75rem; width: 100%;">
                        <div wire:loading wire:target="cvFile" style="font-size: 0.7rem; color: #d97706; margin-top: 0.25rem;">Mengunggah CV...</div>
                    </div>

                    {{-- Portfolio Upload --}}
                    <div style="border: 1px solid #f1f5f9; padding: 0.75rem; border-radius: 0.5rem; background: #fafafa;">
                        <span style="font-size: 0.75rem; font-weight: 700; color: #334155; display: block; margin-bottom: 0.25rem;">PORTOFOLIO KARYA</span>
                        @if($portfolioPath)
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <x-filament::icon icon="heroicon-s-document-check" style="width: 16px; height: 16px; color: #16a34a;" />
                                <a href="{{ Storage::url($portfolioPath) }}" target="_blank" style="font-size: 0.75rem; color: #003B7A; font-weight: 600; text-decoration: underline;">Lihat Portofolio Saat Ini</a>
                            </div>
                        @endif
                        <input type="file" wire:model="portfolioFile" accept=".pdf" style="font-size: 0.75rem; width: 100%;">
                        <div wire:loading wire:target="portfolioFile" style="font-size: 0.7rem; color: #d97706; margin-top: 0.25rem;">Mengunggah Portofolio...</div>
                    </div>
                </div>
            </x-filament::section>

            {{-- SAW Weight Config Card --}}
            <x-filament::section>
                <x-slot name="heading">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <x-filament::icon icon="heroicon-o-adjustments-horizontal" style="width: 20px; height: 20px; color: #003B7A;" />
                        <span>Bobot Kriteria (Metode SAW)</span>
                    </div>
                </x-slot>
                <x-slot name="description">Atur signifikansi kepentingan tiap kriteria. Total bobot harus tepat 100%.</x-slot>

                <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 0.5rem;" x-data="{
                    weightCv: @entangle('weightCv'),
                    weightPortfolio: @entangle('weightPortfolio'),
                    weightIpk: @entangle('weightIpk'),
                    get total() { return (parseInt(this.weightCv)||0) + (parseInt(this.weightPortfolio)||0) + (parseInt(this.weightIpk)||0); }
                }">
                    
                    {{-- Weight CV --}}
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; font-weight: 600; color: #475569;">
                            <span>Kriteria C1: Kecocokan CV</span>
                            <span x-text="weightCv + '%'" style="color: #003B7A; font-weight: 700;"></span>
                        </div>
                        <input type="range" min="0" max="100" step="5" x-model="weightCv" style="width: 100%; height: 6px; border-radius: 9999px; background: #e2e8f0; outline: none; margin-top: 0.25rem;">
                    </div>

                    {{-- Weight Portfolio --}}
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; font-weight: 600; color: #475569;">
                            <span>Kriteria C2: Kecocokan Portofolio</span>
                            <span x-text="weightPortfolio + '%'" style="color: #003B7A; font-weight: 700;"></span>
                        </div>
                        <input type="range" min="0" max="100" step="5" x-model="weightPortfolio" style="width: 100%; height: 6px; border-radius: 9999px; background: #e2e8f0; outline: none; margin-top: 0.25rem;">
                    </div>

                    {{-- Weight IPK --}}
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; font-weight: 600; color: #475569;">
                            <span>Kriteria C3: Nilai Akademik (IPK)</span>
                            <span x-text="weightIpk + '%'" style="color: #003B7A; font-weight: 700;"></span>
                        </div>
                        <input type="range" min="0" max="100" step="5" x-model="weightIpk" style="width: 100%; height: 6px; border-radius: 9999px; background: #e2e8f0; outline: none; margin-top: 0.25rem;">
                    </div>

                    {{-- Sum and submit block --}}
                    <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 0.5rem; border-top: 1px solid #f1f5f9; margin-top: 0.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.35rem;">
                            <span style="font-size: 0.75rem; font-weight: 600; color: #475569;">Total Bobot:</span>
                            <span x-text="total + '%'" :style="total === 100 ? 'font-weight: 800; color:#16a34a; font-size:0.875rem;' : 'font-weight: 800; color:#dc2626; font-size:0.875rem;'"></span>
                            <span x-show="total === 100" style="color: #16a34a; font-weight: bold; font-size: 0.875rem;">✓</span>
                            <span x-show="total !== 100" style="color: #dc2626; font-weight: bold; font-size: 0.875rem;">⚠</span>
                        </div>
                    </div>

                    <button type="button" wire:click="simpanProfilDanRekomendasi" :disabled="total !== 100"
                        style="width: 100%; padding: 0.65rem; border: none; border-radius: 0.6rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.8125rem; font-weight: 700; background: linear-gradient(135deg,#003B7A 0%,#002856 100%); color: #fff; cursor: pointer; transition: opacity 0.2s;"
                        onmouseover="this.style.opacity='0.92';" onmouseout="this.style.opacity='1';">
                        <span wire:loading.remove wire:target="simpanProfilDanRekomendasi">Simpan Profil & Cari Rekomendasi</span>
                        <span wire:loading wire:target="simpanProfilDanRekomendasi">Memproses Analisis SAW...</span>
                    </button>
                </div>
            </x-filament-panels::section>

        </div>

        {{-- ==================== RIGHT COLUMN: RECOMMENDATIONS RENDER ==================== --}}
        <div>
            @if(empty($recommendations))
                <x-filament::section style="height: 100%; display: flex; align-items: center; justify-content: center; min-height: 480px;">
                    <div style="text-align: center; padding: 4rem 2rem;">
                        <div style="display: flex; justify-content: center; margin-bottom: 1.5rem;">
                            <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(0,59,122,0.06); display: flex; align-items: center; justify-content: center; color: #003B7A;">
                                <x-filament::icon icon="heroicon-o-sparkles" style="width: 42px; height: 42px;" />
                            </div>
                        </div>
                        <h3 style="font-size: 1.125rem; font-weight: 700; color: #1e293b; margin: 0;">Rekomendasi Magang AI Belum Diproses</h3>
                        <p style="font-size: 0.8125rem; color: #64748b; margin-top: 0.5rem; max-width: 20rem; margin-left: auto; margin-right: auto; line-height: 1.5;">
                            Silakan isi keterampilan Anda dan klik tombol <strong>Simpan & Cari Rekomendasi</strong> untuk memicu perhitungan kecocokan SAW terhadap seluruh lowongan magang aktif.
                        </p>
                    </div>
                </x-filament::section>
            @else
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    
                    {{-- Header of recommendations list --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.25rem 0.5rem;">
                        <span style="font-size: 0.8125rem; font-weight: 700; color: #475569;">Hasil Rekomendasi ({{ count($recommendations) }} ditemukan)</span>
                        <span style="font-size: 0.7rem; font-weight: 600; padding: 0.25rem 0.5rem; border-radius: 9999px; background: #ecfdf5; color: #047857; display: flex; align-items: center; gap: 0.25rem;">
                            <span style="width: 5px; height: 5px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                            Terurut Berdasarkan Kelayakan SAW
                        </span>
                    </div>

                    @foreach($recommendations as $index => $item)
                        @php
                            $v = $item['vacancy'];
                            $rankNum = $index + 1;
                            $score = $item['saw_score'];
                            
                            // Determine score color theme
                            $scoreColor = '#059669'; // Emerald
                            $scoreBg = '#ecfdf5';
                            if ($score < 60) {
                                $scoreColor = '#dc2626'; // Red
                                $scoreBg = '#fef2f2';
                            } elseif ($score < 80) {
                                $scoreColor = '#d97706'; // Amber
                                $scoreBg = '#fffbeb';
                            }
                        @endphp
                        
                        {{-- Recommendation Card --}}
                        <div style="background: white; border-radius: 0.875rem; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.2s;"
                             onmouseover="this.style.borderColor='#003B7A'; this.style.boxShadow='0 4px 14px rgba(0,59,122,0.08)';"
                             onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.05)';">
                             
                             {{-- Card Header --}}
                             <div style="padding: 1.1rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem;">
                                 <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                                     {{-- Rank Badge --}}
                                     <div style="width: 2rem; height: 2rem; border-radius: 0.5rem; background: #003B7A; color: white; display: flex; align-items: center; justify-content: center; font-size: 0.875rem; font-weight: 800; font-family: 'Plus Jakarta Sans', sans-serif;">
                                         #{{ $rankNum }}
                                     </div>
                                     <div>
                                         <h4 style="font-size: 0.9375rem; font-weight: 700; color: #0f172a; margin: 0; line-height: 1.25;">{{ $v->judul }}</h4>
                                         <p style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin: 0.2rem 0 0 0;">{{ $v->mitra->nama }}</p>
                                     </div>
                                 </div>
                                 
                                 {{-- SAW Match Score Badge --}}
                                 <div style="text-align: right; flex-shrink: 0;">
                                     <div style="padding: 0.3rem 0.75rem; border-radius: 9999px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.8125rem; font-weight: 800; background: {{ $scoreBg }}; color: {{ $scoreColor }}; border: 1px solid {{ $scoreColor }}30;">
                                         {{ number_format($score, 1) }}% Match
                                     </div>
                                 </div>
                             </div>

                             {{-- Card Body --}}
                             <div style="padding: 1.1rem;">
                                 @if($v->deskripsi)
                                     <p style="font-size: 0.75rem; color: #475569; margin: 0 0 1rem 0; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                         {{ $v->deskripsi }}
                                     </p>
                                 @endif

                                 {{-- Criteria Score Breakdown (Progress Bars) --}}
                                 <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; background: #f8fafc; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 0.25rem;">
                                     {{-- CV Score --}}
                                     <div>
                                         <div style="display: flex; justify-content: space-between; font-size: 0.65rem; font-weight: 600; color: #64748b; margin-bottom: 0.2rem;">
                                             <span>CV Match</span>
                                             <span style="font-weight: 700; color: #0f172a;">{{ number_format($item['score_cv']) }}/100</span>
                                         </div>
                                         <div style="height: 4px; border-radius: 9999px; background: #e2e8f0; overflow: hidden;">
                                             <div style="height: 100%; border-radius: 9999px; background: #3b82f6; width: {{ $item['score_cv'] }}%;"></div>
                                         </div>
                                     </div>

                                     {{-- Portfolio Score --}}
                                     <div>
                                         <div style="display: flex; justify-content: space-between; font-size: 0.65rem; font-weight: 600; color: #64748b; margin-bottom: 0.2rem;">
                                             <span>Portofolio</span>
                                             <span style="font-weight: 700; color: #0f172a;">{{ number_format($item['score_portfolio']) }}/100</span>
                                         </div>
                                         <div style="height: 4px; border-radius: 9999px; background: #e2e8f0; overflow: hidden;">
                                             <div style="height: 100%; border-radius: 9999px; background: #a855f7; width: {{ $item['score_portfolio'] }}%;"></div>
                                         </div>
                                     </div>

                                     {{-- IPK Score --}}
                                     <div>
                                         <div style="display: flex; justify-content: space-between; font-size: 0.65rem; font-weight: 600; color: #64748b; margin-bottom: 0.2rem;">
                                             <span>IPK Akademik</span>
                                             <span style="font-weight: 700; color: #0f172a;">{{ number_format($item['score_ipk']) }}/100</span>
                                         </div>
                                         <div style="height: 4px; border-radius: 9999px; background: #e2e8f0; overflow: hidden;">
                                             <div style="height: 100%; border-radius: 9999px; background: #10b981; width: {{ $item['score_ipk'] }}%;"></div>
                                         </div>
                                     </div>
                                 </div>
                             </div>

                             {{-- Card Footer Actions --}}
                             <div style="padding: 0.75rem 1.1rem; background: #fafafa; border-top: 1px solid #f1f5f9; display: flex; gap: 0.5rem; justify-content: flex-end;">
                                 <x-filament::button size="sm" color="gray" wire:click="openDetail({{ $v->id }})" outlined>
                                     Detail Info
                                 </x-filament::button>
                                 
                                 <x-filament::button size="sm" wire:click="daftarLowongan({{ $v->id }})" wire:loading.attr="disabled" wire:target="daftarLowongan({{ $v->id }})">
                                     <span wire:loading.remove wire:target="daftarLowongan({{ $v->id }})">Daftar Sekarang</span>
                                     <span wire:loading wire:target="daftarLowongan({{ $v->id }})">Memproses...</span>
                                 </x-filament::button>
                             </div>
                        </div>
                    @endforeach

                </div>
            @endif
        </div>

    </div>

    {{-- Detail Vacancy Modal popup (same as BrowseLowongan) --}}
    @if($selectedDetail)
        <div style="position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.5); padding: 1rem;">
            <div style="background: white; border-radius: 0.75rem; width: 100%; max-width: 600px; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);">
                
                {{-- Modal Header --}}
                <div style="padding: 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h2 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin: 0;">{{ $selectedDetail->judul }}</h2>
                        <p style="font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0 0 0;">{{ $selectedDetail->mitra->nama }}</p>
                    </div>
                    <button wire:click="closeDetail" style="color: #9ca3af; background: none; border: none; cursor: pointer; padding: 0.25rem;">
                        <x-filament::icon icon="heroicon-o-x-mark" style="width: 24px; height: 24px;" />
                    </button>
                </div>

                {{-- Modal Body --}}
                <div style="padding: 1.25rem; overflow-y: auto; flex: 1;">
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin: 0 0 0.5rem 0;">Deskripsi Lowongan</h4>
                        <p style="font-size: 0.875rem; color: #4b5563; line-height: 1.5; margin: 0; white-space: pre-wrap;">{{ $selectedDetail->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; background: #f9fafb; padding: 1rem; border-radius: 0.5rem;">
                        <div>
                            <span style="display: block; font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">Jenis Magang</span>
                            <span style="font-size: 0.875rem; font-weight: 500; color: #111827;">{{ ucfirst($selectedDetail->jenis_magang) }}</span>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">Batas Pendaftaran</span>
                            <span style="font-size: 0.875rem; font-weight: 500; color: #111827;">{{ $selectedDetail->tanggal_tutup->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">Syarat IPK Minimal</span>
                            <span style="font-size: 0.875rem; font-weight: 500; color: #111827;">{{ $selectedDetail->syarat_ipk > 0 ? number_format($selectedDetail->syarat_ipk, 2) : 'Tidak ada syarat' }}</span>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">Syarat Semester Minimal</span>
                            <span style="font-size: 0.875rem; font-weight: 500; color: #111827;">{{ $selectedDetail->syarat_semester > 1 ? $selectedDetail->syarat_semester : 'Tidak ada syarat' }}</span>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">Mulai Magang</span>
                            <span style="font-size: 0.875rem; font-weight: 500; color: #111827;">{{ $selectedDetail->tanggal_mulai_magang ? $selectedDetail->tanggal_mulai_magang->format('d M Y') : '-' }}</span>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">Selesai Magang</span>
                            <span style="font-size: 0.875rem; font-weight: 500; color: #111827;">{{ $selectedDetail->tanggal_selesai_magang ? $selectedDetail->tanggal_selesai_magang->format('d M Y') : '-' }}</span>
                        </div>
                    </div>

                    @if(!empty($selectedDetail->dokumen_required))
                        <div>
                            <h4 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin: 0 0 0.5rem 0;">Dokumen Wajib Upload</h4>
                            <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.875rem; color: #4b5563;">
                                @foreach($selectedDetail->dokumen_required as $dok)
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
                    @if(!$selectedDetail->isFull())
                        <x-filament::button wire:click="daftarLowongan({{ $selectedDetail->id }})">
                            Daftar Sekarang
                        </x-filament::button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <style>
        @media (max-width: 1024px) {
            .rekom-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</x-filament-panels::page>
