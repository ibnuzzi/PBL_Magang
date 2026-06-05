<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Logbook Magang JTI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4F46E5;
            --primary-hover: #4338CA;
            --success: #10B981;
            --success-hover: #059669;
            --danger: #EF4444;
            --danger-hover: #DC2626;
            --background: #F3F4F6;
            --card-bg: #FFFFFF;
            --text-main: #1F2937;
            --text-muted: #6B7280;
            --border: #E5E7EB;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --background: #0F172A;
                --card-bg: #1E293B;
                --text-main: #F9FAFB;
                --text-muted: #9CA3AF;
                --border: #334155;
            }
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--background);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            transition: background-color 0.3s ease;
        }

        .container {
            width: 100%;
            max-width: 650px;
            background-color: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border);
            overflow: hidden;
            animation: fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            background: linear-gradient(135deg, var(--primary), #6366F1);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 30px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 15px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 5px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .grid-item label {
            display: block;
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .grid-item span {
            font-size: 16px;
            font-weight: 500;
        }

        .detail-card {
            background-color: var(--background);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid var(--border);
        }

        .detail-item {
            margin-bottom: 15px;
        }

        .detail-item:last-child {
            margin-bottom: 0;
        }

        .detail-item label {
            display: block;
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .detail-item p {
            font-size: 15px;
            line-height: 1.6;
            white-space: pre-wrap;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background-color: var(--card-bg);
            color: var(--text-main);
            font-family: inherit;
            font-size: 14px;
            resize: vertical;
            min-height: 80px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        .actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 20px;
            border-radius: 12px;
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .btn-success {
            background-color: var(--success);
            color: white;
        }

        .btn-success:hover {
            background-color: var(--success-hover);
            transform: translateY(-1px);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: var(--danger-hover);
            transform: translateY(-1px);
        }

        .footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
            border-top: 1px solid var(--border);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Persetujuan Logbook Harian</h1>
            <p>Sistem Informasi Manajemen Magang JTI Polinema</p>
        </div>
        <div class="content">
            <h2 class="section-title">Informasi Mahasiswa</h2>
            <div class="grid">
                <div class="grid-item">
                    <label>Nama Mahasiswa</label>
                    <span>{{ $logbook->pelaksanaan->pendaftaran->mahasiswa->name }}</span>
                </div>
                <div class="grid-item">
                    <label>NIM</label>
                    <span>{{ $logbook->pelaksanaan->pendaftaran->mahasiswa->nim }}</span>
                </div>
                <div class="grid-item" style="grid-column: span 2;">
                    <label>Perusahaan Mitra</label>
                    <span>{{ $logbook->pelaksanaan->pendaftaran->mitra->nama }}</span>
                </div>
            </div>

            <h2 class="section-title">Detail Aktivitas</h2>
            <div class="grid">
                <div class="grid-item">
                    <label>Tanggal Kegiatan</label>
                    <span>{{ $logbook->tanggal->format('d M Y') }}</span>
                </div>
                <div class="grid-item">
                    <label>Periode Kegiatan</label>
                    <span>Minggu ke-{{ $logbook->minggu_ke }}, Hari ke-{{ $logbook->hari_ke }}</span>
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-item" style="margin-bottom: 20px;">
                    <label>Deskripsi Kegiatan</label>
                    <p>{{ $logbook->kegiatan }}</p>
                </div>
                <div class="detail-item" style="margin-bottom: 20px;">
                    <label>Hasil / Output Kegiatan</label>
                    <p>{{ $logbook->hasil }}</p>
                </div>
                @if($logbook->foto_kegiatan)
                <div class="detail-item">
                    <label>Foto Kegiatan</label>
                    <div style="margin-top: 8px;">
                        <a href="{{ asset('storage/' . $logbook->foto_kegiatan) }}" target="_blank">
                            <img src="{{ asset('storage/' . $logbook->foto_kegiatan) }}" style="max-width: 100%; max-height: 300px; border-radius: 8px; border: 1px solid var(--border); object-fit: contain;">
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <form action="{{ route('logbook.approve.process', ['token' => $token->token]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="catatan">Catatan Tambahan (Opsional)</label>
                    <textarea name="catatan" id="catatan" class="form-control" placeholder="Tulis catatan jika ada pekerjaan yang tidak sesuai atau masukan lainnya..."></textarea>
                </div>

                <div class="actions">
                    <button type="submit" name="action" value="reject" class="btn btn-danger">
                        Tolak Kegiatan
                    </button>
                    <button type="submit" name="action" value="approve" class="btn btn-success">
                        Setujui Kegiatan
                    </button>
                </div>
            </form>
        </div>
        <div class="footer">
            &copy; 2026 Jurusan Teknologi Informasi - Politeknik Negeri Malang
        </div>
    </div>
</body>
</html>
