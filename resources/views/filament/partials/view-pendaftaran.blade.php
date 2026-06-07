<div style="font-family: inherit; padding: 0.5rem 0;">

    {{-- Status Banner --}}
    @php
        $statusColors = [
            'draft' => ['bg' => '#f3f4f6', 'text' => '#374151', 'border' => '#d1d5db'],
            'menunggu_verifikasi_dokumen' => ['bg' => '#fffbeb', 'text' => '#92400e', 'border' => '#fcd34d'],
            'dokumen_lengkap' => ['bg' => '#eff6ff', 'text' => '#1e40af', 'border' => '#93c5fd'],
            'dokumen_kurang' => ['bg' => '#fef2f2', 'text' => '#991b1b', 'border' => '#fca5a5'],
            'menunggu_approval_koordinator' => ['bg' => '#fffbeb', 'text' => '#92400e', 'border' => '#fcd34d'],
            'menunggu_approval_kps' => ['bg' => '#fffbeb', 'text' => '#92400e', 'border' => '#fcd34d'],
            'menunggu_approval_kajur' => ['bg' => '#fffbeb', 'text' => '#92400e', 'border' => '#fcd34d'],
            'menunggu_approval_wadir1' => ['bg' => '#fffbeb', 'text' => '#92400e', 'border' => '#fcd34d'],
            'disetujui_penuh' => ['bg' => '#ecfdf5', 'text' => '#065f46', 'border' => '#6ee7b7'],
            'surat_pengantar_terbit' => ['bg' => '#eff6ff', 'text' => '#1e40af', 'border' => '#93c5fd'],
            'loa_diterima' => ['bg' => '#eff6ff', 'text' => '#1e40af', 'border' => '#93c5fd'],
            'berjalan' => ['bg' => '#ecfdf5', 'text' => '#065f46', 'border' => '#6ee7b7'],
            'selesai' => ['bg' => '#ecfdf5', 'text' => '#065f46', 'border' => '#6ee7b7'],
            'ditolak' => ['bg' => '#fef2f2', 'text' => '#991b1b', 'border' => '#fca5a5'],
            'dibatalkan' => ['bg' => '#fef2f2', 'text' => '#991b1b', 'border' => '#fca5a5'],
        ];
        $sc = $statusColors[$record->status] ?? ['bg' => '#f3f4f6', 'text' => '#374151', 'border' => '#d1d5db'];
    @endphp

    <div style="background-color: {{ $sc['bg'] }}; border: 1px solid {{ $sc['border'] }}; border-radius: 0.75rem; padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <div style="font-size: 0.75rem; color: {{ $sc['text'] }}; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; margin-bottom: 0.2rem;">Status Pendaftaran</div>
            <div style="font-size: 1.1rem; font-weight: 700; color: {{ $sc['text'] }};">{{ $record->status_label }}</div>
        </div>
        <div style="background-color: {{ $sc['border'] }}; color: {{ $sc['text'] }}; padding: 0.4rem 1rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;">
            {{ ucfirst($record->jenis_magang) }}
        </div>
    </div>

    {{-- Main Card: Info Mahasiswa --}}
    <div style="background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden; margin-bottom: 1rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.06);">
        <div style="background: linear-gradient(135deg, #003B7A 0%, #002856 100%); padding: 0.85rem 1.25rem;">
            <h4 style="margin: 0; font-weight: 600; color: #ffffff; font-size: 0.9rem; letter-spacing: 0.02em;">👤 Informasi Mahasiswa</h4>
        </div>
        <div style="padding: 1rem 1.25rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600; margin-bottom: 0.25rem;">Nama Mahasiswa</div>
                    <div style="font-size: 0.95rem; font-weight: 600; color: #111827;">{{ $record->mahasiswa?->name ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600; margin-bottom: 0.25rem;">NIM</div>
                    <div style="font-size: 0.95rem; font-weight: 600; color: #111827;">{{ $record->mahasiswa?->nim ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600; margin-bottom: 0.25rem;">Program Studi</div>
                    <div style="font-size: 0.95rem; color: #374151;">{{ $record->mahasiswa?->programStudi?->nama ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600; margin-bottom: 0.25rem;">Tanggal Daftar</div>
                    <div style="font-size: 0.95rem; color: #374151;">{{ $record->tanggal_daftar?->format('d M Y') ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card: Info Perusahaan & Lowongan --}}
    <div style="background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden; margin-bottom: 1rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.06);">
        <div style="background: linear-gradient(135deg, #F5A623 0%, #e09210 100%); padding: 0.85rem 1.25rem;">
            <h4 style="margin: 0; font-weight: 600; color: #ffffff; font-size: 0.9rem; letter-spacing: 0.02em;">🏢 Informasi Perusahaan & Lowongan</h4>
        </div>
        <div style="padding: 1rem 1.25rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600; margin-bottom: 0.25rem;">Mitra Perusahaan</div>
                    <div style="font-size: 0.95rem; font-weight: 600; color: #111827;">{{ $record->mitra?->nama ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600; margin-bottom: 0.25rem;">Lowongan</div>
                    <div style="font-size: 0.95rem; color: #374151;">{{ $record->lowongan?->judul ?? '— Mandiri —' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card: Dosen Pembimbing --}}
    <div style="background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden; margin-bottom: 1rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.06);">
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 0.85rem 1.25rem;">
            <h4 style="margin: 0; font-weight: 600; color: #ffffff; font-size: 0.9rem; letter-spacing: 0.02em;">🎓 Dosen Pembimbing</h4>
        </div>
        <div style="padding: 1rem 1.25rem;">
            @if($record->dosenPembimbing)
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #003B7A, #0059b3); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 1rem;">
                        {{ strtoupper(substr($record->dosenPembimbing->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size: 0.95rem; font-weight: 600; color: #111827;">{{ $record->dosenPembimbing->name }}</div>
                        <div style="font-size: 0.8rem; color: #6b7280;">{{ ucfirst($record->dosenPembimbing->role) }}</div>
                    </div>
                </div>
            @else
                <div style="text-align: center; padding: 0.5rem 0; color: #9ca3af; font-style: italic;">
                    Belum ditentukan
                </div>
            @endif
        </div>
    </div>

    {{-- Card: Catatan --}}
    @if($record->catatan || $record->alasan_ditolak)
        <div style="background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.06);">
            <div style="background-color: #f9fafb; padding: 0.85rem 1.25rem; border-bottom: 1px solid #e5e7eb;">
                <h4 style="margin: 0; font-weight: 600; color: #111827; font-size: 0.9rem;">📝 Catatan</h4>
            </div>
            <div style="padding: 1rem 1.25rem;">
                @if($record->catatan)
                    <div style="background-color: #f9fafb; border-left: 3px solid #003B7A; padding: 0.75rem 1rem; border-radius: 0 0.5rem 0.5rem 0; margin-bottom: {{ $record->alasan_ditolak ? '0.75rem' : '0' }};">
                        <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; font-weight: 600; margin-bottom: 0.25rem;">Catatan</div>
                        <div style="font-size: 0.875rem; color: #374151; line-height: 1.5;">{{ $record->catatan }}</div>
                    </div>
                @endif
                @if($record->alasan_ditolak)
                    <div style="background-color: #fef2f2; border-left: 3px solid #ef4444; padding: 0.75rem 1rem; border-radius: 0 0.5rem 0.5rem 0;">
                        <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: #991b1b; font-weight: 600; margin-bottom: 0.25rem;">Alasan Ditolak</div>
                        <div style="font-size: 0.875rem; color: #991b1b; line-height: 1.5;">{{ $record->alasan_ditolak }}</div>
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>
