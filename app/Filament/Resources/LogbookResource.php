<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogbookResource\Pages\CreateLogbook;
use App\Filament\Resources\LogbookResource\Pages\EditLogbook;
use App\Filament\Resources\LogbookResource\Pages\ListLogbook;
use App\Models\Logbook;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
use Illuminate\Database\Eloquent\Builder;

class LogbookResource extends Resource
{
    protected static ?string $model = Logbook::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string|\UnitEnum|null $navigationGroup = 'Pelaksanaan';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Logbook';

    protected static ?string $pluralModelLabel = 'Logbook';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Logbook')
                    ->columns(2)
                    ->schema([
                        auth()->user()?->role === 'mahasiswa'
                        ? \Filament\Forms\Components\Hidden::make('pelaksanaan_id')
                            ->default(fn() => \App\Models\PelaksanaanMagang::whereHas('pendaftaran', fn($q) => $q->where('mahasiswa_id', auth()->id()))->first()?->id)
                        : Select::make('pelaksanaan_id')
                            ->label('Pelaksanaan Magang')
                            ->relationship('pelaksanaan', 'id')
                            ->getOptionLabelFromRecordUsing(fn($record) => "#{$record->id} - {$record->pendaftaran->mahasiswa->name}")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false)
                            ->default(fn() => \App\Models\PelaksanaanMagang::whereHas('pendaftaran', fn($q) => $q->where('mahasiswa_id', auth()->id()))->first()?->id),

                        DatePicker::make('tanggal')
                            ->label('Tanggal')
                            ->required()
                            ->default(now()->toDateString())
                            ->disabled(fn() => auth()->user()?->role === 'mahasiswa')
                            ->dehydrated()
                            ->native(false)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (!$state)
                                    return;
                                $pelaksanaan = \App\Models\PelaksanaanMagang::whereHas('pendaftaran', function ($q) {
                                    $q->where('mahasiswa_id', auth()->id());
                                })->first();
                                if ($pelaksanaan && $pelaksanaan->tanggal_mulai) {
                                    $tanggal = \Carbon\Carbon::parse($state);
                                    $tanggalMulai = \Carbon\Carbon::parse($pelaksanaan->tanggal_mulai);
                                    $diffInDays = $tanggalMulai->diffInDays($tanggal, false);
                                    if ($diffInDays >= 0) {
                                        $set('minggu_ke', (int) ($diffInDays / 7) + 1);
                                        $set('hari_ke', $tanggal->dayOfWeekIso);
                                    }
                                }
                            }),

                        TextInput::make('minggu_ke')
                            ->label('Minggu Ke-')
                            ->numeric()
                            ->required()
                            ->disabled(fn() => auth()->user()?->role === 'mahasiswa')
                            ->dehydrated()
                            ->default(function () {
                                $pelaksanaan = \App\Models\PelaksanaanMagang::whereHas('pendaftaran', function ($q) {
                                    $q->where('mahasiswa_id', auth()->id());
                                })->first();
                                if ($pelaksanaan && $pelaksanaan->tanggal_mulai) {
                                    $diffInDays = \Carbon\Carbon::parse($pelaksanaan->tanggal_mulai)->diffInDays(now(), false);
                                    if ($diffInDays >= 0) {
                                        return (int) ($diffInDays / 7) + 1;
                                    }
                                }
                                return 1;
                            }),

                        TextInput::make('hari_ke')
                            ->label('Hari Ke-')
                            ->numeric()
                            ->required()
                            ->disabled(fn() => auth()->user()?->role === 'mahasiswa')
                            ->dehydrated()
                            ->default(function () {
                                return now()->dayOfWeekIso;
                            }),

                        Textarea::make('kegiatan')
                            ->label('Kegiatan')
                            ->required()
                            ->columnSpanFull(),

                        Textarea::make('hasil')
                            ->label('Hasil')
                            ->required()
                            ->columnSpanFull(),

                        FileUpload::make('foto_kegiatan')
                            ->label('Foto Kegiatan')
                            ->image()
                            ->directory('logbook-foto')
                            ->maxSize(2048)
                            ->nullable()
                            ->helperText('Unggah foto kegiatan magang (opsional).')
                            ->columnSpanFull(),
                    ]),

                Section::make('Status & Approval')
                    ->columns(2)
                    ->schema([
                        Select::make('status_supervisor')
                            ->label('Status Supervisor')
                            ->options([
                                'menunggu' => 'Menunggu',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak',
                            ])
                            ->default('menunggu')
                            ->native(false)
                            ->disabled(fn() => auth()->user()?->role === 'mahasiswa')
                            ->dehydrated(),

                        Select::make('status_dosen')
                            ->label('Status Dosen')
                            ->options([
                                'menunggu' => 'Menunggu',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak',
                            ])
                            ->default('menunggu')
                            ->native(false)
                            ->disabled(fn() => auth()->user()?->role === 'mahasiswa')
                            ->dehydrated(),

                        FileUpload::make('bukti_ttd_path')
                            ->label('Upload TTD Manual (Fallback Offline)')
                            ->image()
                            ->directory('logbook-ttd')
                            ->maxSize(2048)
                            ->helperText('Gunakan jika supervisor tidak dapat melakukan persetujuan digital via WhatsApp.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pelaksanaan.pendaftaran.mahasiswa.name')
                    ->label('Mahasiswa')
                    ->searchable()
                    ->sortable()
                    ->hidden(fn() => auth()->user()?->role === 'mahasiswa'),

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('minggu_ke')
                    ->label('Minggu')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('kegiatan')
                    ->label('Kegiatan')
                    ->limit(50)
                    ->wrap(),

                TextColumn::make('status_supervisor')
                    ->label('Supervisor')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'menunggu' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('status_dosen')
                    ->label('Dosen')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'menunggu' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status_supervisor')
                    ->label('Status Supervisor')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ]),
                SelectFilter::make('status_dosen')
                    ->label('Status Dosen')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ]),
            ])
            ->defaultSort('tanggal', 'desc')
            ->recordActions([
                Action::make('kirim_wa')
                    ->label('Kirim WA')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->visible(fn($record) => auth()->user()?->role === 'mahasiswa' && $record->status_supervisor === 'menunggu')
                    ->url(function ($record) {
                        $noHp = $record->pelaksanaan->no_hp_supervisor ?? '';
                        if (str_starts_with($noHp, '0')) {
                            $noHp = '62' . substr($noHp, 1);
                        }

                        $tokenRecord = $record->supervisorTokens()->where('is_used', false)->where('expired_at', '>', now())->first();
                        if (!$tokenRecord) {
                            $tokenRecord = $record->generateSupervisorToken($record->pelaksanaan->no_hp_supervisor ?? '');
                        }

                        $link = route('logbook.approve', ['token' => $tokenRecord->token]);
                        $namaMahasiswa = auth()->user()->name;
                        $tanggal = $record->tanggal->format('d-m-Y');

                        $message = "Halo Bapak/Ibu Supervisor, mohon kesediaan Bapak/Ibu untuk memeriksa dan menyetujui logbook harian magang saya ({$namaMahasiswa}) pada tanggal {$tanggal} melalui tautan berikut:\n\n{$link}";

                        return "https://wa.me/{$noHp}?text=" . urlencode($message);
                    })
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->headerActions([
                Action::make('cetak_laporan')
                    ->label('Cetak Laporan')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->visible(fn() => in_array(auth()->user()?->role, ['mahasiswa', 'admin']))
                    ->form([
                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->required()
                            ->default(now()->subMonth()->toDateString()),
                        DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->required()
                            ->default(now()->toDateString()),
                    ])
                    ->action(function (array $data) {
                        $query = http_build_query([
                            'tanggal_mulai' => $data['tanggal_mulai'],
                            'tanggal_selesai' => $data['tanggal_selesai'],
                        ]);
                        return redirect()->to(route('mahasiswa.logbook.print') . '?' . $query);
                    })
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLogbook::route('/'),
            'create' => CreateLogbook::route('/create'),
            'edit' => EditLogbook::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user) {
            if ($user->role === 'mahasiswa') {
                return $query->whereHas('pelaksanaan.pendaftaran', function ($q) use ($user) {
                    $q->where('mahasiswa_id', $user->id);
                });
            }
            if ($user->role === 'dosen') {
                return $query->whereHas('pelaksanaan.pendaftaran', function ($q) use ($user) {
                    $q->where('dosen_pembimbing_id', $user->id);
                });
            }
        }

        return $query;
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        if ($user && $user->role === 'mahasiswa') {
            return \App\Models\PelaksanaanMagang::whereHas('pendaftaran', function ($q) use ($user) {
                $q->where('mahasiswa_id', $user->id);
            })->where('status', 'berjalan')->exists();
        }
        return true;
    }

    public static function canAccess(): bool
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return $user && in_array($user->role, ['mahasiswa', 'dosen', 'koordinator', 'kps', 'kajur', 'admin']);
    }
}
