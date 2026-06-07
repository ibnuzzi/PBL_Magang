<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranMagangResource\Pages\CreatePendaftaranMagang;
use App\Filament\Resources\PendaftaranMagangResource\Pages\EditPendaftaranMagang;
use App\Filament\Resources\PendaftaranMagangResource\Pages\ListPendaftaranMagang;
use App\Models\PendaftaranMagang;
use App\Services\PendaftaranService;
use BackedEnum;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class PendaftaranMagangResource extends Resource
{
    protected static ?string $model = PendaftaranMagang::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string | \UnitEnum | null $navigationGroup = 'Pendaftaran';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Pendaftaran Magang';

    protected static ?string $pluralModelLabel = 'Pendaftaran Magang';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pendaftaran')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('mahasiswa_id')
                            ->label('Mahasiswa')
                            ->relationship('mahasiswa', 'name', fn($query) => $query->where('role', 'mahasiswa'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled()
                            ->native(false),

                        Select::make('lowongan_id')
                            ->label('Lowongan')
                            ->relationship('lowongan', 'judul')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->visible(fn($record) => $record?->lowongan_id !== null),

                        Select::make('mitra_id')
                            ->label('Mitra Perusahaan')
                            ->relationship('mitra', 'nama')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled()
                            ->native(false),

                        Select::make('jenis_magang')
                            ->label('Jenis Magang')
                            ->options([
                                'pilihan' => 'Pilihan',
                                'mandiri' => 'Mandiri',
                                'wajib' => 'Wajib',
                            ])
                            ->required()
                            ->disabled()
                            ->native(false),

                        Select::make('status')
                            ->label('Status')
                            ->options(PendaftaranMagang::statusOptions())
                            ->required()
                            ->native(false),

                        Textarea::make('catatan')
                            ->label('Catatan')
                            ->columnSpanFull(),

                        Textarea::make('alasan_ditolak')
                            ->label('Alasan Ditolak')
                            ->columnSpanFull()
                            ->visible(fn($record) => $record?->status === PendaftaranMagang::STATUS_DITOLAK),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mahasiswa.name')
                    ->label('Mahasiswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('lowongan.judul')
                    ->label('Lowongan')
                    ->searchable()
                    ->sortable()
                    ->limit(25)
                    ->placeholder('— Mandiri —')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('mitra.nama')
                    ->label('Perusahaan')
                    ->searchable()
                    ->sortable()
                    ->limit(25)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('dosenPembimbing.name')
                    ->label('Dosen Pembimbing')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('jenis_magang')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pilihan' => 'primary',
                        'mandiri' => 'warning',
                        'wajib' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => (new PendaftaranMagang(['status' => $state]))->status_color)
                    ->formatStateUsing(fn(string $state): string => PendaftaranMagang::statusOptions()[$state] ?? ucfirst(str_replace('_', ' ', $state)))
                    ->wrap(),

                TextColumn::make('tanggal_daftar')
                    ->label('Tanggal Daftar')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(PendaftaranMagang::statusOptions())
                    ->multiple(),

                SelectFilter::make('jenis_magang')
                    ->label('Jenis Magang')
                    ->options([
                        'pilihan' => 'Pilihan',
                        'mandiri' => 'Mandiri',
                        'wajib' => 'Wajib',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('lihat_detail')
                    ->label('View')
                    ->icon(Heroicon::OutlinedEye)
                    ->color('gray')
                    ->modalHeading('Detail Pendaftaran Magang')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn($record) => view('filament.partials.view-pendaftaran', ['record' => $record])),
                EditAction::make(),

                // Verifikasi Dokumen — Koordinator cek kelengkapan
                Action::make('verifikasi_lengkap')
                    ->label('Dokumen Lengkap')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Dokumen Lengkap')
                    ->modalDescription('Apakah Anda yakin semua dokumen sudah lengkap dan sesuai?')
                    ->visible(fn ($record) => $record->status === PendaftaranMagang::STATUS_MENUNGGU_VERIFIKASI
                        && in_array(Auth::user()?->role, ['koordinator', 'admin']))
                    ->action(function ($record) {
                        app(PendaftaranService::class)->verifikasiDokumen($record, true);
                        Notification::make()->title('Dokumen diverifikasi lengkap')->success()->send();
                    }),

                Action::make('dokumen_kurang')
                    ->label('Dokumen Kurang')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Dokumen Kurang')
                    ->modalDescription('Tandai bahwa dokumen belum lengkap. Mahasiswa akan diminta melengkapi.')
                    ->visible(fn ($record) => $record->status === PendaftaranMagang::STATUS_MENUNGGU_VERIFIKASI
                        && in_array(Auth::user()?->role, ['koordinator', 'admin']))
                    ->action(function ($record) {
                        app(PendaftaranService::class)->verifikasiDokumen($record, false);
                        Notification::make()->title('Dokumen ditandai kurang')->warning()->send();
                    }),

                // Approve — sesuai level user
                Action::make('approve')
                    ->label('Approve')
                    ->icon(Heroicon::OutlinedHandThumbUp)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Pendaftaran')
                    ->form([
                        Textarea::make('catatan')
                            ->label('Catatan (opsional)'),
                    ])
                    ->visible(function ($record) {
                        $user = Auth::user();
                        if (!$user) return false;
                        $levelMap = [
                            PendaftaranMagang::STATUS_MENUNGGU_KOORDINATOR => 'koordinator',
                            PendaftaranMagang::STATUS_MENUNGGU_KPS => 'kps',
                            PendaftaranMagang::STATUS_MENUNGGU_KAJUR => 'kajur',
                            PendaftaranMagang::STATUS_MENUNGGU_WADIR1 => 'wadir1',
                        ];
                        $requiredRole = $levelMap[$record->status] ?? null;
                        return $requiredRole && ($user->role === $requiredRole || $user->role === 'admin');
                    })
                    ->action(function ($record, array $data) {
                        app(PendaftaranService::class)->processApproval(
                            $record,
                            Auth::user(),
                            'approve',
                            $data['catatan'] ?? null
                        );
                        Notification::make()->title('Pendaftaran di-approve')->success()->send();
                    }),

                // Tolak
                Action::make('tolak')
                    ->label('Tolak')
                    ->icon(Heroicon::OutlinedHandThumbDown)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pendaftaran')
                    ->form([
                        Textarea::make('catatan')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->visible(function ($record) {
                        $user = Auth::user();
                        if (!$user) return false;
                        $approvalStatuses = [
                            PendaftaranMagang::STATUS_MENUNGGU_KOORDINATOR,
                            PendaftaranMagang::STATUS_MENUNGGU_KPS,
                            PendaftaranMagang::STATUS_MENUNGGU_KAJUR,
                            PendaftaranMagang::STATUS_MENUNGGU_WADIR1,
                        ];
                        $levelMap = [
                            PendaftaranMagang::STATUS_MENUNGGU_KOORDINATOR => 'koordinator',
                            PendaftaranMagang::STATUS_MENUNGGU_KPS => 'kps',
                            PendaftaranMagang::STATUS_MENUNGGU_KAJUR => 'kajur',
                            PendaftaranMagang::STATUS_MENUNGGU_WADIR1 => 'wadir1',
                        ];
                        $requiredRole = $levelMap[$record->status] ?? null;
                        return $requiredRole && ($user->role === $requiredRole || $user->role === 'admin');
                    })
                    ->action(function ($record, array $data) {
                        app(PendaftaranService::class)->processApproval(
                            $record,
                            Auth::user(),
                            'reject',
                            $data['catatan']
                        );
                        Notification::make()->title('Pendaftaran ditolak')->danger()->send();
                    }),

                // Terbitkan Surat Magang — setelah disetujui penuh
                Action::make('terbitkan_surat')
                    ->label('Terbitkan Surat')
                    ->icon(Heroicon::OutlinedEnvelope)
                    ->color('success')
                    ->form([
                        TextInput::make('nomor_surat')
                            ->label('Nomor Surat')
                            ->required(),
                        FileUpload::make('file_path')
                            ->label('File PDF Surat')
                            ->required()
                            ->directory('surat-magang')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120),
                    ])
                    ->visible(fn ($record) => $record->status === PendaftaranMagang::STATUS_DISETUJUI_PENUH
                        && in_array(Auth::user()?->role, ['koordinator', 'admin']))
                    ->action(function ($record, array $data) {
                        \App\Models\SuratMagang::create([
                            'pendaftaran_id' => $record->id,
                            'jenis_surat' => 'pengantar',
                            'nomor_surat' => $data['nomor_surat'],
                            'file_path' => $data['file_path'],
                            'status' => 'diterbitkan',
                            'diterbitkan_at' => now(),
                        ]);
                        Notification::make()->title('Surat Magang berhasil diterbitkan!')->success()->send();
                    }),

                // Unggah LOA Magang — setelah surat pengantar terbit
                Action::make('unggah_loa')
                    ->label('Unggah LOA')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->form([
                        TextInput::make('nomor_surat')
                            ->label('Nomor LOA')
                            ->required(),
                        FileUpload::make('file_path')
                            ->label('File PDF LOA')
                            ->required()
                            ->directory('surat-magang')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120),
                    ])
                    ->visible(fn ($record) => $record->status === PendaftaranMagang::STATUS_SURAT_TERBIT
                        && in_array(Auth::user()?->role, ['koordinator', 'admin']))
                    ->action(function ($record, array $data) {
                        \App\Models\SuratMagang::create([
                            'pendaftaran_id' => $record->id,
                            'jenis_surat' => 'loa',
                            'nomor_surat' => $data['nomor_surat'],
                            'file_path' => $data['file_path'],
                            'status' => 'diterbitkan',
                            'diterbitkan_at' => now(),
                        ]);
                        Notification::make()->title('Dokumen LOA berhasil diunggah!')->success()->send();
                    }),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                    \Filament\Actions\BulkAction::make('plot_otomatis')
                        ->label('Plot Otomatis')
                        ->icon('heroicon-o-sparkles')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->modalHeading('Plot Dosen Pembimbing Otomatis')
                        ->modalDescription('Apakah Anda yakin ingin memplot dosen pembimbing secara otomatis untuk mahasiswa yang dipilih? Mahasiswa akan didistribusikan secara merata ke dosen yang masih memiliki kuota.')
                        ->visible(fn() => \Illuminate\Support\Facades\Auth::user()?->role === 'koordinator')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $result = app(\App\Services\PendaftaranService::class)->plotDosenOtomatis($records->pluck('id')->toArray());
                            if ($result['success']) {
                                \Filament\Notifications\Notification::make()->title('Plotting Berhasil')->body($result['message'])->success()->send();
                            } else {
                                \Filament\Notifications\Notification::make()->title('Plotting Gagal')->body($result['message'])->danger()->send();
                            }
                        }),
                    \Filament\Actions\BulkAction::make('plot_manual')
                        ->label('Plot Manual')
                        ->icon('heroicon-o-user-plus')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Plot Dosen Pembimbing Manual')
                        ->modalDescription('Pilih dosen pembimbing untuk mahasiswa yang dicentang. Pastikan kuota dosen mencukupi.')
                        ->visible(fn() => \Illuminate\Support\Facades\Auth::user()?->role === 'koordinator')
                        ->form([
                            \Filament\Forms\Components\Select::make('dosen_id')
                                ->label('Pilih Dosen Pembimbing')
                                ->options(function () {
                                    return \App\Models\User::whereIn('role', ['dosen', 'koordinator', 'kps', 'kajur'])
                                        ->where('is_active', true)
                                        ->get()
                                        ->mapWithKeys(function ($dosen) {
                                            $currentLoad = \App\Models\PendaftaranMagang::where('dosen_pembimbing_id', $dosen->id)
                                                ->whereIn('status', \App\Models\PendaftaranMagang::activeStatuses())
                                                ->count();
                                            $quota = $dosen->kuota_bimbingan ?? 5;
                                            $sisa = $quota - $currentLoad;
                                            return [$dosen->id => "{$dosen->name} (Sisa Kuota: {$sisa})"];
                                        });
                                })
                                ->searchable()
                                ->required(),
                        ])
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data) {
                            $result = app(\App\Services\PendaftaranService::class)->plotDosenManual($records->pluck('id')->toArray(), $data['dosen_id']);
                            if ($result['success']) {
                                \Filament\Notifications\Notification::make()->title('Plotting Berhasil')->body($result['message'])->success()->send();
                            } else {
                                \Filament\Notifications\Notification::make()->title('Plotting Gagal')->body($result['message'])->danger()->send();
                            }
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPendaftaranMagang::route('/'),
            'create' => CreatePendaftaranMagang::route('/create'),
            'edit' => EditPendaftaranMagang::route('/{record}/edit'),
        ];
    }

    /**
     * Koordinator, admin, dan structural roles bisa akses.
     */
    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && in_array($user->role, ['koordinator', 'admin', 'kps', 'kajur', 'wadir1']);
    }
}
