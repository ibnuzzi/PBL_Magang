<?php

namespace App\Filament\Pages;

use App\Models\Logbook;
use App\Models\MitraPerusahaan;
use App\Models\ParameterPenilaian;
use App\Models\PendaftaranMagang;
use App\Models\Penilaian;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class AdminDashboard extends Page
{
    // Non-static in Filament v4
    protected string $view = 'filament.pages.admin-dashboard';

    // Parameter Penilaian live fields
    public $bobotIndustri = 40;
    public $bobotDosen = 35;
    public $bobotPenguji = 25;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-home';
    }

    public static function getNavigationLabel(): string
    {
        return 'Beranda';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'dashboard-admin';
    }

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'Dashboard Admin';
    }

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return '';
    }

    public function mount(): void
    {
        $param = ParameterPenilaian::where('is_active', true)->latest()->first();
        if ($param) {
            $this->bobotIndustri = (int) $param->bobot_industri;
            $this->bobotDosen = (int) $param->bobot_dosen;
            $this->bobotPenguji = (int) $param->bobot_penguji;
        }
    }

    public function getStats(): array
    {
        $totalUserAktif = User::where('is_active', true)->count();
        $mahasiswaAktif = User::where('role', 'mahasiswa')->where('is_active', true)->count();
        $mitraTerdaftar = MitraPerusahaan::count();
        $pendaftaranBaru = PendaftaranMagang::where('status', 'menunggu_verifikasi_dokumen')->count();
        $logbookHariIni = Logbook::whereDate('tanggal', today())->count();
        $nilaiBelumProses = Penilaian::whereNull('nilai_akhir')->count();

        return compact(
            'totalUserAktif',
            'mahasiswaAktif',
            'mitraTerdaftar',
            'pendaftaranBaru',
            'logbookHariIni',
            'nilaiBelumProses'
        );
    }

    public function getRecentUsers(): \Illuminate\Database\Eloquent\Collection
    {
        return User::latest()->take(5)->get();
    }

    public function getActiveParameter(): ?ParameterPenilaian
    {
        return ParameterPenilaian::where('is_active', true)->latest()->first();
    }

    public function simpanParameter(): void
    {
        $total = $this->bobotIndustri + $this->bobotDosen + $this->bobotPenguji;

        if ($total !== 100) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Total bobot harus 100%. Saat ini: ' . $total . '%',
            ]);
            return;
        }

        $param = ParameterPenilaian::where('is_active', true)->latest()->first();

        if ($param) {
            $param->update([
                'bobot_industri' => $this->bobotIndustri,
                'bobot_dosen' => $this->bobotDosen,
                'bobot_penguji' => $this->bobotPenguji,
            ]);
        } else {
            ParameterPenilaian::create([
                'tahun_akademik' => date('Y') . '/' . (date('Y') + 1),
                'bobot_industri' => $this->bobotIndustri,
                'bobot_dosen' => $this->bobotDosen,
                'bobot_penguji' => $this->bobotPenguji,
                'is_active' => true,
            ]);
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Parameter penilaian berhasil disimpan!',
        ]);
    }

    public function toggleUserStatus(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['is_active' => !$user->is_active]);
    }

    protected function getViewData(): array
    {
        return [
            'stats' => $this->getStats(),
            'recentUsers' => $this->getRecentUsers(),
            'activeParameter' => $this->getActiveParameter(),
            'today' => Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }
}
