<?php

namespace App\Filament\Resources\PendaftaranMagangResource\Pages;

use App\Filament\Resources\PendaftaranMagangResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPendaftaranMagang extends ListRecords
{
    protected static string $resource = PendaftaranMagangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('info_kuota')
                ->label('Info Kuota Dosen')
                ->icon('heroicon-o-information-circle')
                ->color('info')
                ->modalHeading('Informasi Kuota Dosen')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup')
                ->visible(fn () => \Illuminate\Support\Facades\Auth::user()?->role === 'koordinator')
                ->modalContent(function () {
                    $dosenData = \App\Models\User::whereIn('role', ['dosen', 'koordinator', 'kps', 'kajur'])
                        ->where('is_active', true)
                        ->get()
                        ->map(function ($dosen) {
                            $currentLoad = \App\Models\PendaftaranMagang::where('dosen_pembimbing_id', $dosen->id)
                                ->whereIn('status', \App\Models\PendaftaranMagang::activeStatuses())
                                ->count();
                            $quota = $dosen->kuota_bimbingan ?? 5;
                            $sisa = max(0, $quota - $currentLoad);
                            return (object)[
                                'name' => $dosen->name,
                                'sisa' => $sisa,
                                'quota' => $quota,
                                'percent' => $quota > 0 ? round((($quota - $sisa) / $quota) * 100) : 0,
                            ];
                        });

                    return view('filament.partials.kuota-dosen', ['dosenData' => $dosenData]);
                }),
            \Filament\Actions\Action::make('auto_plot_semua')
                ->label('Auto Plot Semua')
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Auto Plot Dosen Pembimbing')
                ->modalDescription('Aksi ini akan mendata seluruh mahasiswa yang telah disetujui namun belum memiliki Dosen Pembimbing, dan otomatis memplot mereka secara merata ke dosen yang masih memiliki sisa kuota.')
                ->visible(fn () => \Illuminate\Support\Facades\Auth::user()?->role === 'koordinator')
                ->action(function () {
                    $records = \App\Models\PendaftaranMagang::whereNull('dosen_pembimbing_id')
                        ->whereIn('status', \App\Models\PendaftaranMagang::activeStatuses())
                        ->pluck('id')->toArray();
                    
                    if (empty($records)) {
                        \Filament\Notifications\Notification::make()
                            ->title('Tidak Ada Data')
                            ->body('Semua mahasiswa pendaftar aktif sudah memiliki pembimbing.')
                            ->warning()
                            ->send();
                        return;
                    }

                    $result = app(\App\Services\PendaftaranService::class)->plotDosenOtomatis($records);
                    if ($result['success']) {
                        \Filament\Notifications\Notification::make()->title('Plotting Berhasil')->body($result['message'])->success()->send();
                    } else {
                        \Filament\Notifications\Notification::make()->title('Plotting Gagal')->body($result['message'])->danger()->send();
                    }
                }),
        ];
    }
}
