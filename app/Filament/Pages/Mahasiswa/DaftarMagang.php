<?php

namespace App\Filament\Pages\Mahasiswa;

use App\Models\DokumenPendaftaran;
use App\Models\MitraPerusahaan;
use App\Models\PendaftaranMagang;
use App\Services\PendaftaranService;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class DaftarMagang extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPencilSquare;

    protected static string | \UnitEnum | null $navigationGroup = 'Magang';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Daftar Magang Mandiri';

    protected static ?string $title = 'Pendaftaran Magang Mandiri';

    protected string $view = 'filament.pages.mahasiswa.daftar-magang';

    // Form state
    public ?string $jenis_mitra = 'existing'; // existing or baru
    public ?int $mitra_id = null;

    // Mitra baru fields
    public ?string $nama_perusahaan = null;
    public ?string $alamat = null;
    public ?string $bidang_usaha = null;
    public ?string $nama_pic = null;
    public ?string $jabatan_pic = null;
    public ?string $no_hp_pic = null;
    public ?string $email_pic = null;

    public ?string $catatan = null;

    public static function canAccess(): bool
    {
        return Auth::user()?->role === 'mahasiswa';
    }

    public function submitMandiri(): void
    {
        $user = Auth::user();

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

        $mitraId = $this->mitra_id;

        // Jika mitra baru, create dulu
        if ($this->jenis_mitra === 'baru') {
            $this->validate([
                'nama_perusahaan' => 'required|string|max:200',
                'alamat' => 'required|string',
                'bidang_usaha' => 'required|string|max:100',
                'nama_pic' => 'required|string|max:150',
                'jabatan_pic' => 'required|string|max:100',
                'no_hp_pic' => 'required|string|max:20',
                'email_pic' => 'required|email|max:150',
            ]);

            $mitra = MitraPerusahaan::create([
                'nama' => $this->nama_perusahaan,
                'alamat' => $this->alamat,
                'bidang_usaha' => $this->bidang_usaha,
                'nama_pic' => $this->nama_pic,
                'jabatan_pic' => $this->jabatan_pic,
                'no_hp_pic' => $this->no_hp_pic,
                'email_pic' => $this->email_pic,
                'status_verifikasi' => 'menunggu',
                'diajukan_oleh' => $user->id,
            ]);

            $mitraId = $mitra->id;
        } else {
            $this->validate([
                'mitra_id' => 'required|exists:mitra_perusahaan,id',
            ]);
        }

        // Buat draft pendaftaran mandiri
        $pendaftaran = app(PendaftaranService::class)->createDraftMandiri($user, $mitraId);

        Notification::make()
            ->title('Pendaftaran mandiri berhasil dibuat!')
            ->body('Silakan upload dokumen yang diperlukan (termasuk LOA dari perusahaan) lalu submit.')
            ->success()
            ->send();

        $this->redirect(StatusPendaftaran::getUrl());
    }
}
