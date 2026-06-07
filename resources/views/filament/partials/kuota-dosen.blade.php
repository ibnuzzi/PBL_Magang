<div style="font-family: inherit;">
    <div style="margin-bottom: 1.5rem; color: #4b5563; font-size: 0.875rem; line-height: 1.5;">
        <p style="margin: 0 0 0.5rem 0;">Fitur Plotting otomatis digunakan untuk membagikan dosen pembimbing kepada mahasiswa yang telah disetujui secara merata, memprioritaskan dosen yang masih memiliki kuota kosong.</p>
    </div>

    <div style="background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
        <div style="background-color: #f9fafb; padding: 1rem 1.25rem; border-bottom: 1px solid #e5e7eb;">
            <h4 style="margin: 0; font-weight: 600; color: #111827; font-size: 1rem;">Statistik Sisa Kuota Dosen</h4>
        </div>
        
        <div style="padding: 0 1.25rem;">
            @foreach($dosenData as $dosen)
                @php
                    $isFull = $dosen->sisa <= 0;
                    $barColor = $isFull ? '#ef4444' : '#10b981'; // Red if full, Green if available
                    $bgColor = $isFull ? '#fef2f2' : '#ecfdf5';
                    $textColor = $isFull ? '#991b1b' : '#065f46';
                @endphp
                <div style="padding: 1rem 0; border-bottom: 1px solid #f3f4f6; {{ $loop->last ? 'border-bottom: none;' : '' }}">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <span style="font-weight: 500; color: #374151; font-size: 0.95rem;">{{ $dosen->name }}</span>
                        <span style="background-color: {{ $bgColor }}; color: {{ $textColor }}; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                            {{ $isFull ? 'Kuota Penuh' : 'Sisa: ' . $dosen->sisa }}
                        </span>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div style="width: 100%; background-color: #e5e7eb; border-radius: 9999px; height: 0.5rem; overflow: hidden;">
                        <div style="background-color: {{ $barColor }}; height: 100%; border-radius: 9999px; width: {{ $dosen->percent }}%; transition: width 0.5s ease;"></div>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 0.35rem; font-size: 0.7rem; color: #6b7280;">
                        <span>Terisi: {{ $dosen->quota - $dosen->sisa }} Mahasiswa</span>
                        <span>Total Kuota: {{ $dosen->quota }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
