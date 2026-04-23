<?php

namespace App\Filament\Pages\Admin;

use App\Models\LowonganMagang;
use App\Models\PendaftaranMagang;
use App\Models\User;
use App\Services\PendaftaranService;
use App\Services\SyaratCheckService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class BulkPenempatan extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string | \UnitEnum | null $navigationGroup = 'Lowongan';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Bulk Penempatan';

    protected static ?string $title = 'Penempatan Magang Massal';

    protected string $view = 'filament.pages.admin.bulk-penempatan';

    // State
    public ?int $selectedLowonganId = null;
    public array $selectedMahasiswa = [];
    public string $searchMahasiswa = '';

    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && in_array($user->role, ['koordinator', 'admin']);
    }

    public function getLowonganListProperty()
    {
        return LowonganMagang::with('mitra')
            ->where('jenis_magang', 'wajib')
            ->where('is_published', true)
            ->where('is_full', false)
            ->orderBy('tanggal_tutup', 'asc')
            ->get();
    }

    public function getSelectedLowonganProperty(): ?LowonganMagang
    {
        if (!$this->selectedLowonganId) return null;
        return LowonganMagang::with('mitra')->find($this->selectedLowonganId);
    }

    public function getMahasiswaListProperty()
    {
        $query = User::where('role', 'mahasiswa')
            ->where('is_active', true)
            ->with('programStudi')
            ->orderBy('name');

        if ($this->searchMahasiswa) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->searchMahasiswa}%")
                    ->orWhere('nim', 'like', "%{$this->searchMahasiswa}%");
            });
        }

        return $query->get();
    }

    public function getEligibilityDataProperty(): array
    {
        if (!$this->selectedLowongan) return ['eligible' => [], 'ineligible' => []];

        $mahasiswaList = $this->mahasiswaList;
        return app(SyaratCheckService::class)->filterEligibleMahasiswa($mahasiswaList, $this->selectedLowongan);
    }

    public function toggleMahasiswa(int $id): void
    {
        if (in_array($id, $this->selectedMahasiswa)) {
            $this->selectedMahasiswa = array_values(array_diff($this->selectedMahasiswa, [$id]));
        } else {
            $this->selectedMahasiswa[] = $id;
        }
    }

    public function selectAllEligible(): void
    {
        $this->selectedMahasiswa = collect($this->eligibilityData['eligible'])
            ->pluck('mahasiswa.id')
            ->toArray();
    }

    public function deselectAll(): void
    {
        $this->selectedMahasiswa = [];
    }

    public function processBulkPenempatan(): void
    {
        $lowongan = $this->selectedLowongan;

        if (!$lowongan) {
            Notification::make()->title('Pilih lowongan terlebih dahulu')->danger()->send();
            return;
        }

        if (empty($this->selectedMahasiswa)) {
            Notification::make()->title('Pilih minimal 1 mahasiswa')->warning()->send();
            return;
        }

        $sisaKuota = $lowongan->kuota - $lowongan->kuota_terisi;
        if (count($this->selectedMahasiswa) > $sisaKuota) {
            Notification::make()
                ->title('Jumlah melebihi sisa kuota')
                ->body("Sisa kuota: {$sisaKuota}, dipilih: " . count($this->selectedMahasiswa))
                ->danger()
                ->send();
            return;
        }

        $pendaftaranService = app(PendaftaranService::class);
        $syaratService = app(SyaratCheckService::class);
        $sukses = 0;
        $gagal = 0;

        foreach ($this->selectedMahasiswa as $mahasiswaId) {
            $mahasiswa = User::find($mahasiswaId);
            if (!$mahasiswa) continue;

            // Double-check eligibility
            $errors = $syaratService->checkSyarat($mahasiswa, $lowongan);
            if (!empty($errors) || $syaratService->hasExistingPendaftaran($mahasiswa, $lowongan)) {
                $gagal++;
                continue;
            }

            try {
                // Create pendaftaran dan langsung set status ke menunggu_approval_koordinator
                $pendaftaran = PendaftaranMagang::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'lowongan_id' => $lowongan->id,
                    'mitra_id' => $lowongan->mitra_id,
                    'jenis_magang' => 'wajib',
                    'status' => PendaftaranMagang::STATUS_MENUNGGU_KOORDINATOR,
                    'tanggal_daftar' => now(),
                    'catatan' => 'Penempatan massal oleh koordinator',
                ]);
                $sukses++;
            } catch (\Exception $e) {
                $gagal++;
            }
        }

        $this->selectedMahasiswa = [];

        Notification::make()
            ->title("Penempatan selesai: {$sukses} berhasil" . ($gagal > 0 ? ", {$gagal} gagal" : ''))
            ->color($gagal > 0 ? 'warning' : 'success')
            ->send();
    }
}
