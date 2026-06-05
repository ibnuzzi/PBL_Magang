<?php

namespace App\Filament\Pages\Mahasiswa;

use App\Models\DokumenPendaftaran;
use App\Models\PendaftaranMagang;
use App\Services\PendaftaranService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class StatusPendaftaran extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string | \UnitEnum | null $navigationGroup = 'Magang';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Status Pendaftaran';

    protected static ?string $title = 'Status Pendaftaran Magang';

    protected string $view = 'filament.pages.mahasiswa.status-pendaftaran';

    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && $user->role === 'mahasiswa' && in_array((int)$user->semester, [6, 7]);
    }

    public function getPendaftaranProperty()
    {
        $pendaftarans = PendaftaranMagang::where('mahasiswa_id', Auth::user()->id)
            ->with(['lowongan', 'mitra', 'dokumen', 'dosenPembimbing', 'approval.approver', 'surat'])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($pendaftarans as $pendaftaran) {
            $hasPublishedLoa = $pendaftaran->surat->where('jenis_surat', 'loa')->where('status', 'diterbitkan')->isNotEmpty();
            $hasPublishedPengantar = $pendaftaran->surat->where('jenis_surat', 'pengantar')->where('status', 'diterbitkan')->isNotEmpty();

            $flow = PendaftaranMagang::statusFlow();
            $currentIndex = array_search($pendaftaran->status, $flow);

            if ($hasPublishedLoa) {
                $loaIndex = array_search(PendaftaranMagang::STATUS_LOA, $flow);
                if ($currentIndex !== false && $currentIndex < $loaIndex) {
                    app(PendaftaranService::class)->loaDiterima($pendaftaran);
                }
            } elseif ($hasPublishedPengantar) {
                $pengantarIndex = array_search(PendaftaranMagang::STATUS_SURAT_TERBIT, $flow);
                if ($currentIndex !== false && $currentIndex < $pengantarIndex) {
                    app(PendaftaranService::class)->terbitkanSurat($pendaftaran);
                }
            }
        }

        return $pendaftarans;
    }

    /**
     * Submit pendaftaran (draft → menunggu_verifikasi_dokumen).
     */
    public function submitPendaftaran(int $pendaftaranId): void
    {
        $pendaftaran = PendaftaranMagang::where('id', $pendaftaranId)
            ->where('mahasiswa_id', Auth::user()->id)
            ->firstOrFail();

        try {
            app(PendaftaranService::class)->submitPendaftaran($pendaftaran);

            Notification::make()
                ->title('Pendaftaran berhasil disubmit!')
                ->body('Pendaftaran Anda sedang menunggu verifikasi dokumen oleh koordinator.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal submit')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Batalkan pendaftaran.
     */
    public function batalkanPendaftaran(int $pendaftaranId): void
    {
        $pendaftaran = PendaftaranMagang::where('id', $pendaftaranId)
            ->where('mahasiswa_id', Auth::user()->id)
            ->whereIn('status', [PendaftaranMagang::STATUS_DRAFT, PendaftaranMagang::STATUS_DOKUMEN_KURANG])
            ->firstOrFail();

        app(PendaftaranService::class)->batalkanPendaftaran($pendaftaran, 'Dibatalkan oleh mahasiswa');

        Notification::make()
            ->title('Pendaftaran dibatalkan')
            ->warning()
            ->send();
    }

    /**
     * Get status steps for stepper visualization.
     */
    public function getStatusSteps(): array
    {
        return [
            PendaftaranMagang::STATUS_DRAFT => 'Draft',
            PendaftaranMagang::STATUS_MENUNGGU_VERIFIKASI => 'Verifikasi Dokumen',
            PendaftaranMagang::STATUS_MENUNGGU_KOORDINATOR => 'Approval Koordinator',
            PendaftaranMagang::STATUS_MENUNGGU_KPS => 'Approval KPS',
            PendaftaranMagang::STATUS_MENUNGGU_KAJUR => 'Approval Kajur',
            PendaftaranMagang::STATUS_MENUNGGU_WADIR1 => 'Approval Wadir 1',
            PendaftaranMagang::STATUS_DISETUJUI_PENUH => 'Disetujui',
            PendaftaranMagang::STATUS_SURAT_TERBIT => 'Surat Terbit',
            PendaftaranMagang::STATUS_LOA => 'LOA Diterima',
            PendaftaranMagang::STATUS_BERJALAN => 'Berjalan',
            PendaftaranMagang::STATUS_SELESAI => 'Selesai',
        ];
    }
}
