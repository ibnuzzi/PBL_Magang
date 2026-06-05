<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Logbook Magang - {{ $mahasiswa->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            padding: 20px;
            background-color: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 20px;
            margin: 5px 0;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 16px;
            margin: 5px 0;
            font-weight: normal;
        }

        .info-table {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
            font-size: 14px;
        }

        .info-table td.label {
            width: 180px;
            font-weight: bold;
        }

        .info-table td.colon {
            width: 10px;
        }

        .log-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        .log-table th, .log-table td {
            border: 1px solid #333;
            padding: 8px 12px;
            font-size: 13px;
            text-align: left;
        }

        .log-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .log-table td.center {
            text-align: center;
        }

        .status-badge {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .signatures {
            width: 100%;
            margin-top: 50px;
            page-break-inside: avoid;
        }

        .signatures td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            font-size: 13px;
        }

        .signature-space {
            height: 80px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }

        .no-print-bar {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #e5e7eb;
        }

        .btn-print {
            background-color: #4F46E5;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-print:hover {
            background-color: #4338CA;
        }
    </style>
</head>
<body>
    <div class="no-print no-print-bar">
        <span>Laporan Logbook Magang (Periode: {{ $tanggal_mulai->format('d/m/Y') }} - {{ $tanggal_selesai->format('d/m/Y') }})</span>
        <button onclick="window.print()" class="btn-print">Cetak Laporan / Simpan PDF</button>
    </div>

    <div class="header">
        <h1>Laporan Kegiatan Magang Harian (Logbook)</h1>
        <h2>Jurusan Teknologi Informasi - Politeknik Negeri Malang</h2>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Mahasiswa</td>
            <td class="colon">:</td>
            <td>{{ $mahasiswa->name }}</td>
        </tr>
        <tr>
            <td class="label">NIM</td>
            <td class="colon">:</td>
            <td>{{ $mahasiswa->nim }}</td>
        </tr>
        <tr>
            <td class="label">Program Studi</td>
            <td class="colon">:</td>
            <td>{{ $mahasiswa->programStudi?->nama ?? '—' }} ({{ $mahasiswa->programStudi?->jenjang ?? '' }})</td>
        </tr>
        <tr>
            <td class="label">Perusahaan Tempat Magang</td>
            <td class="colon">:</td>
            <td>{{ $pelaksanaan?->pendaftaran?->mitra?->nama ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Supervisor Industri</td>
            <td class="colon">:</td>
            <td>{{ $pelaksanaan?->nama_supervisor ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Dosen Pembimbing</td>
            <td class="colon">:</td>
            <td>{{ $pelaksanaan?->pendaftaran?->dosenPembimbing?->name ?? '—' }}</td>
        </tr>
    </table>

    <table class="log-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Hari / Tanggal</th>
                <th style="width: 10%;">Waktu Kerja</th>
                <th style="width: 35%;">Deskripsi Kegiatan</th>
                <th style="width: 25%;">Hasil / Output</th>
                <th style="width: 10%;">Status ACC</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logbooks as $index => $logbook)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>
                        {{ $logbook->tanggal->isoFormat('dddd') }},<br>
                        {{ $logbook->tanggal->format('d-m-Y') }}
                    </td>
                    <td class="center">Minggu {{ $logbook->minggu_ke }},<br>Hari {{ $logbook->hari_ke }}</td>
                    <td>
                        {{ $logbook->kegiatan }}
                        @if($logbook->foto_kegiatan)
                            <div style="margin-top: 8px;">
                                <img src="{{ asset('storage/' . $logbook->foto_kegiatan) }}" style="max-width: 120px; max-height: 120px; object-fit: cover; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                        @endif
                    </td>
                    <td>{{ $logbook->hasil }}</td>
                    <td class="center">
                        <span class="status-badge">
                            SV: {{ $logbook->status_supervisor }}<br>
                            DS: {{ $logbook->status_dosen }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center" style="padding: 20px;">Tidak ada entri logbook untuk rentang tanggal ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @php
        $dosenTtd = $logbooks->whereNotNull('ttd_dosen')->first()?->ttd_dosen;
        $supervisorTtd = $logbooks->whereNotNull('bukti_ttd_path')->first()?->bukti_ttd_path;
    @endphp
    <table class="signatures">
        <tr>
            <td>
                Menyetujui,<br>
                <strong>Supervisor Industri</strong>
                <div class="signature-space" style="display: flex; align-items: center; justify-content: center; height: 80px; margin: 10px 0;">
                    @if($supervisorTtd)
                        <img src="{{ asset('storage/' . $supervisorTtd) }}" style="max-height: 80px; object-fit: contain;">
                    @endif
                </div>
                <span class="signature-name">{{ $pelaksanaan?->nama_supervisor ?? '....................................' }}</span>
            </td>
            <td>
                Mengetahui,<br>
                <strong>Dosen Pembimbing</strong>
                <div class="signature-space" style="display: flex; align-items: center; justify-content: center; height: 80px; margin: 10px 0;">
                    @if($dosenTtd)
                        <img src="{{ asset('storage/' . $dosenTtd) }}" style="max-height: 80px; object-fit: contain;">
                    @endif
                </div>
                <span class="signature-name">{{ $pelaksanaan?->pendaftaran?->dosenPembimbing?->name ?? '....................................' }}</span><br>
                NIP: {{ $pelaksanaan?->pendaftaran?->dosenPembimbing?->nip ?? '....................................' }}
            </td>
            <td>
                Malang, {{ now()->format('d M Y') }}<br>
                <strong>Mahasiswa</strong>
                <div class="signature-space"></div>
                <span class="signature-name">{{ $mahasiswa->name }}</span><br>
                NIM: {{ $mahasiswa->nim }}
            </td>
        </tr>
    </table>

    <script>
        // Auto trigger print dialog when opened in print view
        window.addEventListener('DOMContentLoaded', () => {
            if (!window.location.search.includes('no_auto_print')) {
                // Short timeout to let layout settle
                setTimeout(() => {
                    window.print();
                }, 500);
            }
        });
    </script>
</body>
</html>
