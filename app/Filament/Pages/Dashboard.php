<?php

namespace App\Filament\Pages;

use App\Models\Logbook;
use App\Models\MitraPerusahaan;
use App\Models\ParameterPenilaian;
use App\Models\PendaftaranMagang;
use App\Models\User;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard Admin';

    public function getTitle(): \Illuminate\Contracts\Support\Htmlable|string
    {
        return static::$title;
    }

    protected string $view = 'filament.pages.dashboard';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Beranda';

    protected static ?int $navigationSort = -2;

    // Livewire Properties for Parameter Penilaian
    public $parameter_id = 0;
    public $bobot_industri = 40;
    public $bobot_dosen = 35;
    public $bobot_penguji = 25;
    public $parameter_last_updated = 'Default';

    public function mount()
    {
        $activeParam = ParameterPenilaian::where('is_active', true)->first();
        if ($activeParam) {
            $this->parameter_id = $activeParam->id;
            $this->bobot_industri = floatval($activeParam->bobot_industri);
            $this->bobot_dosen = floatval($activeParam->bobot_dosen);
            $this->bobot_penguji = floatval($activeParam->bobot_penguji);
            $this->parameter_last_updated = $activeParam->updated_at->translatedFormat('d F Y');
        }
    }

    public function saveParameter()
    {
        $total = $this->bobot_industri + $this->bobot_dosen + $this->bobot_penguji;
        
        if ($total !== 100) {
            Notification::make()
                ->title('Total bobot nilai harus 100%. Saat ini: ' . $total . '%')
                ->danger()
                ->send();
            return;
        }

        if ($this->parameter_id != 0) {
            $param = ParameterPenilaian::find($this->parameter_id);
            if ($param) {
                $param->update([
                    'bobot_industri' => $this->bobot_industri,
                    'bobot_dosen' => $this->bobot_dosen,
                    'bobot_penguji' => $this->bobot_penguji,
                ]);
            }
        } else {
            $newParam = ParameterPenilaian::create([
                'tahun_akademik' => 'Current',
                'bobot_industri' => $this->bobot_industri,
                'bobot_dosen' => $this->bobot_dosen,
                'bobot_penguji' => $this->bobot_penguji,
                'konversi_grade' => [],
                'is_active' => true,
            ]);
            $this->parameter_id = $newParam->id;
        }

        Notification::make()
            ->title('Parameter penilaian berhasil diperbarui.')
            ->success()
            ->send();
    }

    public function toggleUserStatus($userId)
    {
        $user = User::find($userId);
        if (!$user) return;

        if (auth()->id() === $user->id) {
            Notification::make()
                ->title('Anda tidak dapat menonaktifkan akun sendiri.')
                ->danger()
                ->send();
            return;
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        Notification::make()
            ->title("User {$user->name} berhasil {$status}.")
            ->success()
            ->send();
    }

    protected function getViewData(): array
    {
        $stats = [
            ['title' => 'Total User Aktif', 'value' => User::count(), 'trend' => '+14 bulan ini', 'trend_dir' => 'up', 'icon' => 'users', 'color' => 'sky'],
            ['title' => 'Mahasiswa Aktif', 'value' => User::where('role', 'mahasiswa')->count(), 'trend' => '+8 minggu ini', 'trend_dir' => 'up', 'icon' => 'academic', 'color' => 'indigo'],
            ['title' => 'Mitra Terdaftar', 'value' => MitraPerusahaan::count(), 'trend' => '+3 bulan ini', 'trend_dir' => 'up', 'icon' => 'briefcase', 'color' => 'amber'],
            ['title' => 'Pendaftaran Baru', 'value' => PendaftaranMagang::where('status', PendaftaranMagang::STATUS_MENUNGGU_VERIFIKASI)->count(), 'trend' => 'Menunggu verifikasi', 'trend_dir' => 'wait', 'icon' => 'document', 'color' => 'emerald'],
            ['title' => 'Logbook Hari Ini', 'value' => Logbook::whereDate('tanggal', today())->count(), 'trend' => 'Normal', 'trend_dir' => 'neutral', 'icon' => 'book', 'color' => 'slate'],
            ['title' => 'Nilai Belum Proses', 'value' => PendaftaranMagang::where('status', PendaftaranMagang::STATUS_SELESAI)->count(), 'trend' => 'Perhatian diperlukan', 'trend_dir' => 'down', 'icon' => 'exclamation', 'color' => 'rose'],
        ];

        $users = User::latest()->take(5)->get()->map(function($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'role' => ucfirst($u->role),
                'status' => $u->is_active,
                'initial' => strtoupper(substr($u->name, 0, 2)),
                'role_color' => $u->role == 'admin' ? 'rose' : ($u->role == 'mahasiswa' ? 'sky' : 'emerald')
            ];
        });

        return [
            'stats' => $stats,
            'users' => $users,
        ];
    }
}
