<?php

namespace App\Filament\Pages\Mahasiswa;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class ChatbotFAQ extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftEllipsis;

    protected static string | \UnitEnum | null $navigationGroup = 'Bantuan';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Chatbot FAQ Magang';

    protected static ?string $title = 'Chatbot Asisten Magang';

    protected string $view = 'filament.pages.mahasiswa.chatbot-faq';

    public string $userInput = '';
    public array $messages = [];

    public array $quickReplies = [
        'Apa saja jenis magang?',
        'Di mana saya boleh mendaftar magang SKS?',
        'Bolehkah mendaftar di 2 perusahaan sekaligus?',
        'Dokumen apa yang harus disiapkan?',
        'Bagaimana jika supervisor tidak punya akun?',
    ];

    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && $user->role === 'mahasiswa' && in_array((int)$user->semester, [6, 7]);
    }

    public function mount(): void
    {
        // Add welcome message
        $this->messages[] = [
            'sender' => 'bot',
            'text' => 'Halo! Saya adalah Asisten Magang JTI Polinema. Ada yang bisa saya bantu terkait pendaftaran, dokumen, logbook, atau penilaian magang?',
            'time' => now()->format('H:i'),
        ];
    }

    public function sendMessage(?string $text = null): void
    {
        $messageText = $text ?? $this->userInput;
        $messageText = trim($messageText);

        if (empty($messageText)) {
            return;
        }

        // Add user message
        $this->messages[] = [
            'sender' => 'user',
            'text' => $messageText,
            'time' => now()->format('H:i'),
        ];

        if (!$text) {
            $this->userInput = '';
        }

        // Generate response
        $replyText = $this->getResponse($messageText);

        // Add bot message
        $this->messages[] = [
            'sender' => 'bot',
            'text' => $replyText,
            'time' => now()->format('H:i'),
        ];
    }

    protected function getResponse(string $message): string
    {
        $normalized = strtolower(trim($message));
        
        $faqs = [
            [
                'keywords' => ['jenis', 'tipe', 'pembagian', 'kategori'],
                'q' => 'Apa saja jenis magang?',
                'a' => "Terdapat 3 jenis magang di JTI Polinema:\n\n1. **Magang Mandiri**: Dilakukan secara mandiri oleh mahasiswa. Tidak diakui sebagai SKS konversi mata kuliah, namun kampus dapat memfasilitasi dengan menerbitkan Surat Pengantar.\n2. **Magang Pilihan**: Diambil pada semester 6. Bersifat konversi nilai SKS dan hanya diperuntukkan bagi mahasiswa yang dinilai kompeten oleh koordinator berdasarkan IPK dan portofolio.\n3. **Magang Wajib**: Diambil pada semester 7. Bersifat wajib diikuti oleh seluruh mahasiswa JTI Polinema."
            ],
            [
                'keywords' => ['mandiri', 'magang mandiri', 'sks kampus', 'surat pengantar'],
                'q' => 'Apa itu Magang Mandiri?',
                'a' => "Magang Mandiri adalah jenis magang yang diinisiasi oleh mahasiswa sendiri. Jenis magang ini tidak diakui SKS konversi nilai mata kuliah, namun kampus memfasilitasi dengan menyediakan Surat Pengantar untuk membantu proses administrasi ke perusahaan tujuan."
            ],
            [
                'keywords' => ['pilihan', 'magang pilihan', 'semester 6', 'konversi'],
                'q' => 'Apa itu Magang Pilihan?',
                'a' => "Magang Pilihan ditujukan untuk mahasiswa semester 6 yang dinilai kompeten. Nilai magang ini dikonversi ke dalam mata kuliah SKS terkait. Pendaftarannya wajib dilakukan pada mitra resmi Polinema atau CTI."
            ],
            [
                'keywords' => ['wajib', 'magang wajib', 'semester 7'],
                'q' => 'Apa itu Magang Wajib?',
                'a' => "Magang Wajib adalah magang kurikulum di semester 7 yang wajib diikuti oleh seluruh mahasiswa. Pendaftarannya hanya boleh dilakukan ke perusahaan yang terdaftar sebagai mitra resmi Polinema atau CTI."
            ],
            [
                'keywords' => ['mitra', 'perusahaan', 'resmi', 'polinema', 'cti', 'sks', 'boleh daftar'],
                'q' => 'Di mana saya boleh mendaftar magang SKS?',
                'a' => "Untuk magang yang dikonversi ke SKS (Magang Pilihan & Magang Wajib), Anda **hanya diperbolehkan** mendaftar di perusahaan yang merupakan mitra resmi Polinema atau CTI. Pendaftaran ke perusahaan non-mitra resmi untuk magang SKS akan otomatis ditolak oleh sistem."
            ],
            [
                'keywords' => ['ganda', 'dua', 'banyak', 'sekaligus', 'lebih dari satu', 'perusahaan'],
                'q' => 'Bolehkah mendaftar di 2 perusahaan sekaligus?',
                'a' => "Tidak boleh. Mahasiswa **tidak diperkenankan** mendaftar ke lebih dari satu lowongan/perusahaan secara bersamaan. Anda harus menyelesaikan proses seleksi (diterima/ditolak/dibatalkan) terlebih dahulu sebelum dapat mengajukan lamaran ke perusahaan lain."
            ],
            [
                'keywords' => ['dokumen', 'berkas', 'siap', 'syarat', 'khs', 'proposal', 'ortu', 'cv', 'portfolio'],
                'q' => 'Dokumen apa saja yang harus disiapkan untuk pendaftaran?',
                'a' => "Dokumen wajib yang harus disiapkan saat mendaftar magang meliputi:\n1. KHS (Kartu Hasil Studi)\n2. Proposal Magang\n3. CV (Curriculum Vitae)\n4. Surat Izin Orang Tua\n5. Surat Integritas\n6. Portfolio Karya/Projek\n\n*(Catatan: Surat pengantar diterbitkan kampus setelah pendaftaran disetujui penuh)*"
            ],
            [
                'keywords' => ['alur', 'acc', 'persetujuan', 'koordinator', 'kps', 'kajur', 'wadir'],
                'q' => 'Bagaimana alur persetujuan pendaftaran magang?',
                'a' => "Alur persetujuan pendaftaran magang dilakukan secara berjenjang melalui sistem:\n**Koordinator Magang** &rarr; **KPS (Kepala Program Studi)** &rarr; **Kajur (Ketua Jurusan)** &rarr; **Wadir 1 (Wakil Direktur 1)**.\n\n*Pemeriksaan detail kelengkapan berkas dan kelayakan dilakukan utama oleh Koordinator Magang.*"
            ],
            [
                'keywords' => ['surat', 'output', 'pengantar', 'loa'],
                'q' => 'Apa saja surat yang dihasilkan dari proses pendaftaran magang?',
                'a' => "Sistem magang menghasilkan 2 output surat utama:\n1. **Surat Pengantar Magang**: Surat resmi dari kampus untuk diajukan ke industri (diterbitkan setelah disetujui Wadir 1).\n2. **Surat LOA (Letter of Acceptance)**: Surat balasan dari industri yang diunggah mahasiswa ke sistem sebagai bukti resmi diterima magang."
            ],
            [
                'keywords' => ['logbook', 'harian', 'isi', 'tulis', 'kegiatan', 'mingguan'],
                'q' => 'Bagaimana ketentuan pengisian logbook?',
                'a' => "Logbook wajib diisi harian oleh mahasiswa untuk mencatat kegiatan dan hasil. Setiap akhir minggu, logbook harus disetujui oleh supervisor industri, kemudian akan diverifikasi oleh Dosen Pembimbing."
            ],
            [
                'keywords' => ['undur', 'mundur', 'kemarin', 'tanggal', 'otomatis'],
                'q' => 'Mengapa saya tidak bisa mengisi logbook tanggal kemarin (isi mundur)?',
                'a' => "Tanggal pengisian logbook di-generate otomatis oleh sistem pada hari pengisian. Hal ini diterapkan untuk menegakkan kedisiplinan dan mencegah pengisian tanggal mundur (backdating)."
            ],
            [
                'keywords' => ['supervisor', 'akun', 'tidak punya', 'wa', 'whatsapp', 'tanda tangan', 'manual', 'ttd'],
                'q' => 'Bagaimana jika supervisor tidak punya akses/akun di sistem?',
                'a' => "Jika supervisor tidak memiliki akun digital, sistem menyediakan 2 solusi fallback:\n\n1. **Persetujuan WhatsApp Quick Link**: Anda dapat mengeklik tombol **'Kirim WA'** pada daftar logbook. Sistem akan mengirim link token khusus ke WhatsApp supervisor. Supervisor cukup mengeklik tombol Setuju di halaman tersebut tanpa perlu login.\n2. **Upload TTD Manual (Offline)**: Anda dapat mencetak logbook, meminta tanda tangan basah/tanda tangan offline supervisor, lalu memfoto/scan dan mengunggahnya ke kolom 'Upload TTD Manual' di halaman edit logbook."
            ],
            [
                'keywords' => ['nilai', 'penilaian', 'komponen', 'persentase', 'bobot'],
                'q' => 'Bagaimana komponen penilaian magang?',
                'a' => "Nilai magang terdiri dari 3 komponen utama:\n1. **Nilai Industri** (diinput admin berdasarkan nilai dari supervisor)\n2. **Nilai Dosen Pembimbing**\n3. **Nilai Penguji**\n\nMasing-masing memiliki persentase bobot dinamis yang dikonfigurasi oleh admin (total harus 100%)."
            ]
        ];

        $bestMatch = null;
        $highestScore = 0;
        
        // Clean input words
        $words = explode(' ', preg_replace('/[^\w\s]/', '', $normalized));
        
        foreach ($faqs as $faq) {
            $score = 0;
            
            // Check direct keyword match
            foreach ($faq['keywords'] as $keyword) {
                if (str_contains($normalized, $keyword)) {
                    $score += 3;
                }
            }
            
            // Word overlap matching
            $questionWords = explode(' ', preg_replace('/[^\w\s]/', '', strtolower($faq['q'])));
            $overlap = array_intersect($words, $questionWords);
            $score += count($overlap);
            
            if ($score > $highestScore) {
                $highestScore = $score;
                $bestMatch = $faq;
            }
        }

        if ($highestScore >= 2 && $bestMatch) {
            return $bestMatch['a'];
        }

        return "Maaf, saya belum memahami pertanyaan Anda. Pastikan pertanyaan Anda berkaitan dengan magang JTI.\n\nBerikut adalah beberapa topik yang dapat Anda tanyakan:\n- Jenis-jenis magang (Mandiri, Pilihan, Wajib)\n- Syarat kelayakan semester & IPK\n- Ketentuan pendaftaran mitra resmi Polinema / CTI\n- Solusi persetujuan logbook via WhatsApp / TTD manual\n- Komponen penilaian magang";
    }
}
