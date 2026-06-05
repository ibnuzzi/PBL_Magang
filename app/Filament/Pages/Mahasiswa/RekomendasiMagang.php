<?php

namespace App\Filament\Pages\Mahasiswa;

use App\Models\LowonganMagang;
use App\Models\PendaftaranMagang;
use App\Services\SawRecommendationService;
use App\Services\PendaftaranService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Livewire\WithFileUploads;

class RekomendasiMagang extends Page
{
    use WithFileUploads;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string | \UnitEnum | null $navigationGroup = 'Magang';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Rekomendasi Magang';

    protected static ?string $title = 'Rekomendasi Magang AI';

    protected string $view = 'filament.pages.mahasiswa.rekomendasi-magang';

    // State Profil (Read-Only)
    public ?float $ipk = null;
    public ?int $semester = null;
    public string $programStudiName = 'Tidak terdaftar';

    // State Profil (Editable)
    public ?string $skills = '';
    public ?string $pengalaman = '';

    // File Uploads
    public $cvFile;
    public $portfolioFile;
    public ?string $cvPath = null;
    public ?string $portfolioPath = null;

    // Bobot SAW
    public int $weightCv = 40;
    public int $weightPortfolio = 40;
    public int $weightIpk = 20;

    // Perhitungan
    public array $recommendations = [];
    public ?int $selectedDetailId = null;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && $user->role === 'mahasiswa' && in_array((int)$user->semester, [6, 7]);
    }

    public function mount(): void
    {
        $user = auth()->user();

        // Load read-only profile data
        $this->ipk = $user->ipk !== null ? (float) $user->ipk : null;
        $this->semester = $user->semester;
        if ($user->programStudi) {
            $this->programStudiName = $user->programStudi->nama . ' (' . $user->programStudi->jenjang . ')';
        }

        // Load editable profile data
        $this->skills = $user->skills;
        $this->pengalaman = $user->pengalaman;
        $this->cvPath = $user->cv_path;
        $this->portfolioPath = $user->portfolio_path;
    }

    public function getSelectedDetailProperty()
    {
        return $this->selectedDetailId ? LowonganMagang::with('mitra')->find($this->selectedDetailId) : null;
    }

    public function openDetail(int $id): void
    {
        $this->selectedDetailId = $id;
    }

    public function closeDetail(): void
    {
        $this->selectedDetailId = null;
    }

    public function simpanProfilDanRekomendasi(): void
    {
        // 1. Validate weights sum up to 100%
        $totalWeight = $this->weightCv + $this->weightPortfolio + $this->weightIpk;
        if ($totalWeight !== 100) {
            Notification::make()
                ->title('Total Bobot Harus 100%')
                ->body("Jumlah saat ini: {$totalWeight}%. Silakan sesuaikan agar total tepat 100%.")
                ->danger()
                ->send();
            return;
        }

        $user = auth()->user();

        // 2. Save skills and pengalaman
        $user->update([
            'skills' => $this->skills,
            'pengalaman' => $this->pengalaman,
        ]);

        $recommendationService = app(SawRecommendationService::class);

        // 3. Handle CV PDF upload & extraction
        if ($this->cvFile) {
            $this->validate([
                'cvFile' => 'file|mimes:pdf|max:5120',
            ], [
                'cvFile.mimes' => 'CV harus berupa berkas PDF.',
                'cvFile.max' => 'Ukuran berkas CV maksimal 5 MB.',
            ]);

            $cvPath = $this->cvFile->store('cv-uploads', 'public');
            $user->cv_path = $cvPath;

            $fullPath = storage_path('app/public/' . $cvPath);
            $extractedText = $recommendationService->extractTextFromPdf($fullPath);
            $user->cv_text = $extractedText;
            
            $user->save();
            $this->cvPath = $cvPath;
            $this->cvFile = null;
        }

        // 4. Handle Portfolio PDF upload & extraction
        if ($this->portfolioFile) {
            $this->validate([
                'portfolioFile' => 'file|mimes:pdf|max:5120',
            ], [
                'portfolioFile.mimes' => 'Portofolio harus berupa berkas PDF.',
                'portfolioFile.max' => 'Ukuran berkas portofolio maksimal 5 MB.',
            ]);

            $portfolioPath = $this->portfolioFile->store('portfolio-uploads', 'public');
            $user->portfolio_path = $portfolioPath;

            $fullPath = storage_path('app/public/' . $portfolioPath);
            $extractedText = $recommendationService->extractTextFromPdf($fullPath);
            $user->portfolio_text = $extractedText;
            
            $user->save();
            $this->portfolioPath = $portfolioPath;
            $this->portfolioFile = null;
        }

        // 5. Calculate SAW recommendations
        $weights = [
            'cv' => $this->weightCv,
            'portfolio' => $this->weightPortfolio,
            'ipk' => $this->weightIpk,
        ];

        $this->recommendations = $recommendationService->calculateRecommendations($user, $weights);

        Notification::make()
            ->title('Perhitungan SAW Selesai!')
            ->body('Profil berhasil disimpan dan rekomendasi tempat magang telah diperbarui.')
            ->success()
            ->send();
    }

    public function daftarLowongan(int $lowonganId): void
    {
        $lowongan = LowonganMagang::findOrFail($lowonganId);
        $user = auth()->user();

        // Cek pendaftaran aktif global
        $hasActive = PendaftaranMagang::where('mahasiswa_id', $user->id)
            ->whereNotIn('status', [
                PendaftaranMagang::STATUS_DITOLAK,
                PendaftaranMagang::STATUS_DIBATALKAN,
                PendaftaranMagang::STATUS_SELESAI
            ])
            ->exists();

        if ($hasActive) {
            Notification::make()
                ->title('Gagal Mendaftar')
                ->body('Anda masih memiliki pendaftaran magang yang aktif. Harap selesaikan seleksi atau batalkan terlebih dahulu.')
                ->danger()
                ->send();
            return;
        }

        // Check requirements
        $errors = $lowongan->checkSyarat($user);
        if (!empty($errors)) {
            Notification::make()
                ->title('Tidak memenuhi syarat')
                ->body(implode("\n", $errors))
                ->danger()
                ->send();
            return;
        }

        try {
            // Create draft pendaftaran
            $pendaftaran = app(PendaftaranService::class)->createDraftPilihan($user, $lowongan);

            Notification::make()
                ->title('Pendaftaran berhasil dibuat!')
                ->body('Silakan upload dokumen yang diperlukan lalu submit pendaftaran Anda.')
                ->success()
                ->send();

            $this->redirect(StatusPendaftaran::getUrl());
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal Mendaftar')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
