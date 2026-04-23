<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelaksanaanMagangResource\Pages\CreatePelaksanaanMagang;
use App\Filament\Resources\PelaksanaanMagangResource\Pages\EditPelaksanaanMagang;
use App\Filament\Resources\PelaksanaanMagangResource\Pages\ListPelaksanaanMagang;
use App\Models\PelaksanaanMagang;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
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

class PelaksanaanMagangResource extends Resource
{
    protected static ?string $model = PelaksanaanMagang::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static string | \UnitEnum | null $navigationGroup = 'Pelaksanaan';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Pelaksanaan Magang';

    protected static ?string $pluralModelLabel = 'Pelaksanaan Magang';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pelaksanaan')
                    ->columns(2)
                    ->schema([
                        Select::make('pendaftaran_id')
                            ->label('Pendaftaran')
                            ->relationship('pendaftaran', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "#{$record->id} - {$record->mahasiswa->name} ({$record->mitra->nama})")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'berjalan' => 'Berjalan',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->default('berjalan')
                            ->required()
                            ->native(false),

                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->required()
                            ->native(false),

                        DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->required()
                            ->native(false)
                            ->afterOrEqual('tanggal_mulai'),
                    ]),

                Section::make('Data Supervisor')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama_supervisor')
                            ->label('Nama Supervisor')
                            ->required()
                            ->maxLength(150),

                        TextInput::make('jabatan_supervisor')
                            ->label('Jabatan Supervisor')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('no_hp_supervisor')
                            ->label('No. HP Supervisor')
                            ->required()
                            ->tel()
                            ->maxLength(20),
                    ]),

                Section::make('Statistik')
                    ->columns(2)
                    ->schema([
                        TextInput::make('total_hari_kerja')
                            ->label('Total Hari Kerja')
                            ->numeric()
                            ->default(0)
                            ->disabled(),

                        TextInput::make('total_logbook_terisi')
                            ->label('Total Logbook Terisi')
                            ->numeric()
                            ->default(0)
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pendaftaran.mahasiswa.name')
                    ->label('Mahasiswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pendaftaran.mitra.nama')
                    ->label('Perusahaan')
                    ->limit(25)
                    ->searchable(),

                TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('tanggal_selesai')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('nama_supervisor')
                    ->label('Supervisor')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'berjalan' => 'success',
                        'selesai' => 'primary',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('total_logbook_terisi')
                    ->label('Logbook')
                    ->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'berjalan' => 'Berjalan',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ]),
            ])
            ->defaultSort('tanggal_mulai', 'desc')
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPelaksanaanMagang::route('/'),
            'create' => CreatePelaksanaanMagang::route('/create'),
            'edit' => EditPelaksanaanMagang::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return $user && in_array($user->role, ['dosen', 'koordinator', 'admin']);
    }
}
