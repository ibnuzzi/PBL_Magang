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
                    ->schema([
                        Select::make('mahasiswa_id')
                            ->label('Mahasiswa')
                            ->relationship('mahasiswa', 'name', fn ($query) => $query->where('role', 'mahasiswa'))
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
                            ->visible(fn ($record) => $record?->lowongan_id !== null),

                        Select::make('mitra_id')
                            ->label('Mitra Perusahaan')
                            ->relationship('mitra', 'nama')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled()
                            ->native(false),

                        Select::make('dosen_pembimbing_id')
                            ->label('Dosen Pembimbing')
                            ->relationship('dosenPembimbing', 'name', fn ($query) => $query->whereIn('role', ['dosen', 'koordinator', 'kps', 'kajur']))
                            ->searchable()
                            ->preload()
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
                            ->visible(fn ($record) => $record?->status === PendaftaranMagang::STATUS_DITOLAK),
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
                    ->placeholder('— Mandiri —'),

                TextColumn::make('mitra.nama')
                    ->label('Perusahaan')
                    ->searchable()
                    ->sortable()
                    ->limit(25),

                TextColumn::make('dosenPembimbing.name')
                    ->label('Dosen Pembimbing')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('jenis_magang')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pilihan' => 'primary',
                        'mandiri' => 'warning',
                        'wajib' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => (new PendaftaranMagang(['status' => $state]))->status_color)
                    ->formatStateUsing(fn (string $state): string => PendaftaranMagang::statusOptions()[$state] ?? ucfirst(str_replace('_', ' ', $state)))
                    ->wrap(),

                TextColumn::make('tanggal_daftar')
                    ->label('Tanggal Daftar')
                    ->dateTime('d M Y')
                    ->sortable(),
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
                        && Auth::user()?->role === 'koordinator')
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
                        && Auth::user()?->role === 'koordinator')
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
                        return $requiredRole && $user->role === $requiredRole;
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
                        return $requiredRole && $user->role === $requiredRole;
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
