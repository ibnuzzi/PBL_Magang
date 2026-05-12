<?php

namespace App\Filament\Widgets;

use App\Models\Logbook;
use App\Models\MitraPerusahaan;
use App\Models\PendaftaranMagang;
use App\Models\User;
use App\Models\Penilaian;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Total User Aktif', User::where('is_active', true)->count())
                ->description('+14 bulan ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition',
                ]),
            Stat::make('Mahasiswa Aktif', User::where('role', 'mahasiswa')->where('is_active', true)->count())
                ->description('+8 minggu ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),
            Stat::make('Mitra Terdaftar', MitraPerusahaan::count())
                ->description('+3 bulan ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning'),
            Stat::make('Pendaftaran Baru', PendaftaranMagang::where('status_pendaftaran', 'menunggu')->count())
                ->description('5 menunggu')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),
            Stat::make('Logbook Hari Ini', Logbook::whereDate('tanggal_kegiatan', now())->count())
                ->description('Normal')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Nilai Belum Proses', Penilaian::whereNull('nilai_akhir')->count())
                ->description('Perlu tindakan')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
