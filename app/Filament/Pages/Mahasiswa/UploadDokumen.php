<?php

namespace App\Filament\Pages\Mahasiswa;

use App\Models\DokumenPendaftaran;
use App\Models\PendaftaranMagang;
use BackedEnum;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Livewire\WithFileUploads;

class UploadDokumen extends Page implements HasForms
{
    use InteractsWithForms, WithFileUploads;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowUp;

    protected static string | \UnitEnum | null $navigationGroup = 'Magang';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Upload Dokumen';

    protected static ?string $title = 'Upload Dokumen Pendaftaran';

    protected string $view = 'filament.pages.mahasiswa.upload-dokumen';

    public ?int $selectedPendaftaranId = null;
    public $uploadedFiles = [];

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && $user->role === 'mahasiswa' && in_array((int)$user->semester, [6, 7]);
    }

    public function mount(): void
    {
        // Auto-select first draft pendaftaran
        $firstDraft = PendaftaranMagang::where('mahasiswa_id', auth()->id())
            ->whereIn('status', [
                PendaftaranMagang::STATUS_DRAFT,
                PendaftaranMagang::STATUS_DOKUMEN_KURANG,
                PendaftaranMagang::STATUS_MENUNGGU_VERIFIKASI,
            ])
            ->first();

        if ($firstDraft) {
            $this->selectedPendaftaranId = $firstDraft->id;
        }
    }

    public function getPendaftaranListProperty()
    {
        return PendaftaranMagang::where('mahasiswa_id', auth()->id())
            ->whereIn('status', [
                PendaftaranMagang::STATUS_DRAFT,
                PendaftaranMagang::STATUS_DOKUMEN_KURANG,
                PendaftaranMagang::STATUS_MENUNGGU_VERIFIKASI,
            ])
            ->with(['mitra', 'lowongan'])
            ->get();
    }

    public function getSelectedPendaftaranProperty(): ?PendaftaranMagang
    {
        if (!$this->selectedPendaftaranId) return null;

        return PendaftaranMagang::where('id', $this->selectedPendaftaranId)
            ->where('mahasiswa_id', auth()->id())
            ->with(['dokumen', 'lowongan', 'mitra'])
            ->first();
    }

    public function getRequiredDokumenProperty(): array
    {
        $pendaftaran = $this->selectedPendaftaran;
        if (!$pendaftaran) return [];

        // Jika lowongan punya dokumen_required, gunakan itu
        if ($pendaftaran->lowongan && !empty($pendaftaran->lowongan->dokumen_required)) {
            return $pendaftaran->lowongan->dokumen_required;
        }

        // Default berdasarkan jenis magang
        return match ($pendaftaran->jenis_magang) {
            'mandiri' => DokumenPendaftaran::dokumenMandiri(),
            default => DokumenPendaftaran::dokumenPilihan(),
        };
    }

    public function getExistingDokumenProperty(): array
    {
        $pendaftaran = $this->selectedPendaftaran;
        if (!$pendaftaran) return [];

        return $pendaftaran->dokumen->keyBy('jenis_dokumen')->toArray();
    }

    public function uploadDokumen(string $jenisDokumen): void
    {
        $pendaftaran = $this->selectedPendaftaran;

        if (!$pendaftaran || !$pendaftaran->canUploadDokumen()) {
            Notification::make()
                ->title('Tidak bisa upload dokumen pada status ini')
                ->danger()
                ->send();
            return;
        }

        $fileKey = "uploadedFiles.{$jenisDokumen}";

        if (!isset($this->uploadedFiles[$jenisDokumen]) || empty($this->uploadedFiles[$jenisDokumen])) {
            Notification::make()
                ->title('Pilih file terlebih dahulu')
                ->warning()
                ->send();
            return;
        }

        $file = $this->uploadedFiles[$jenisDokumen];
        $filePath = $file->store('dokumen-pendaftaran', 'public');

        // Update or create dokumen
        DokumenPendaftaran::updateOrCreate(
            [
                'pendaftaran_id' => $pendaftaran->id,
                'jenis_dokumen' => $jenisDokumen,
            ],
            [
                'file_path' => $filePath,
                'status' => 'menunggu',
                'keterangan_reject' => null,
            ]
        );

        $this->uploadedFiles[$jenisDokumen] = null;

        Notification::make()
            ->title('Dokumen berhasil diupload')
            ->body(DokumenPendaftaran::jenisOptions()[$jenisDokumen] ?? $jenisDokumen)
            ->success()
            ->send();
    }
}
