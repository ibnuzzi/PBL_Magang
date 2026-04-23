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

class LogbookResource extends Resource
{
    protected static ?string $model = Logbook::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string | \UnitEnum | null $navigationGroup = 'Pelaksanaan';

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
                        Select::make('pelaksanaan_id')
                            ->label('Pelaksanaan Magang')
                            ->relationship('pelaksanaan', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "#{$record->id} - {$record->pendaftaran->mahasiswa->name}")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),

                        DatePicker::make('tanggal')
                            ->label('Tanggal')
                            ->required()
                            ->native(false),

                        TextInput::make('minggu_ke')
                            ->label('Minggu Ke-')
                            ->numeric()
                            ->required()
                            ->minValue(1),

                        TextInput::make('hari_ke')
                            ->label('Hari Ke-')
                            ->numeric()
                            ->required()
                            ->minValue(1),

                        Textarea::make('kegiatan')
                            ->label('Kegiatan')
                            ->required()
                            ->columnSpanFull(),

                        Textarea::make('hasil')
                            ->label('Hasil')
                            ->required()
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
                            ->native(false),

                        Select::make('status_dosen')
                            ->label('Status Dosen')
                            ->options([
                                'menunggu' => 'Menunggu',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak',
                            ])
                            ->default('menunggu')
                            ->native(false),

                        FileUpload::make('bukti_ttd_path')
                            ->label('Bukti TTD')
                            ->image()
                            ->directory('logbook-ttd')
                            ->maxSize(2048),
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
                    ->sortable(),

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
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('status_dosen')
                    ->label('Dosen')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
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
            ->recordActions([EditAction::make()])
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

    public static function canAccess(): bool
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return $user && in_array($user->role, ['dosen', 'koordinator', 'kps', 'kajur', 'admin']);
    }
}
