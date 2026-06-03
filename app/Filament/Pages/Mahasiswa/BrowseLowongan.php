<?php

namespace App\Filament\Pages\Mahasiswa;

use App\Models\LowonganMagang;
use App\Models\PendaftaranMagang;
use App\Services\PendaftaranService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class BrowseLowongan extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static string | \UnitEnum | null $navigationGroup = 'Magang';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Browse Lowongan';

    protected static ?string $title = 'Lowongan Magang Tersedia';

    protected string $view = 'filament.pages.mahasiswa.browse-lowongan';

    public string $search = '';
    public string $filterJenis = '';
    public string $filterProdi = '';
    public ?int $selectedDetailId = null;

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'mahasiswa';
    }

    public function getLowonganProperty()
    {
        $query = LowonganMagang::query()
            ->with(['mitra'])
            ->open();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('judul', 'like', "%{$this->search}%")
                    ->orWhereHas('mitra', fn ($mq) => $mq->where('nama', 'like', "%{$this->search}%"));
            });
        }

        if ($this->filterJenis) {
            $query->where('jenis_magang', $this->filterJenis);
        }

        return $query->orderBy('tanggal_tutup', 'asc')->get();
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

    public function daftarLowongan(int $lowonganId): void
    {
        $lowongan = LowonganMagang::findOrFail($lowonganId);
        $user = auth()->user();

        // Cek status_magang — hanya bisa daftar jika tidak_aktif atau ditolak
        if (!$user->canApplyMagang()) {
            Notification::make()
                ->title('Tidak dapat mendaftar')
                ->body('Status magang Anda saat ini: "' . $user->status_magang_label . '". ' . $user->status_magang_keterangan)
                ->danger()
                ->duration(8000)
                ->send();
            return;
        }

        // Cek apakah sudah pernah daftar ke lowongan ini
        $existing = PendaftaranMagang::where('mahasiswa_id', $user->id)
            ->where('lowongan_id', $lowonganId)
            ->whereNotIn('status', [PendaftaranMagang::STATUS_DITOLAK, PendaftaranMagang::STATUS_DIBATALKAN])
            ->exists();

        if ($existing) {
            Notification::make()
                ->title('Anda sudah mendaftar ke lowongan ini')
                ->warning()
                ->send();
            return;
        }

        // Cek syarat
        $errors = $lowongan->checkSyarat($user);
        if (!empty($errors)) {
            Notification::make()
                ->title('Tidak memenuhi syarat')
                ->body(implode("\n", $errors))
                ->danger()
                ->send();
            return;
        }

        // Buat draft pendaftaran
        $pendaftaran = app(PendaftaranService::class)->createDraftPilihan($user, $lowongan);

        Notification::make()
            ->title('Pendaftaran berhasil dibuat!')
            ->body('Silakan upload dokumen yang diperlukan lalu submit pendaftaran Anda.')
            ->success()
            ->send();

        $this->redirect(StatusPendaftaran::getUrl());
    }
}
