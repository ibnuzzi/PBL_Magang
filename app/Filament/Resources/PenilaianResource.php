<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenilaianResource\Pages\CreatePenilaian;
use App\Filament\Resources\PenilaianResource\Pages\EditPenilaian;
use App\Filament\Resources\PenilaianResource\Pages\ListPenilaian;
use App\Models\Penilaian;
use BackedEnum;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Illuminate\Support\Facades\Auth;

class PenilaianResource extends Resource
{
    protected static ?string $model = Penilaian::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string | \UnitEnum | null $navigationGroup = 'Penilaian';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Penilaian';

    protected static ?string $pluralModelLabel = 'Penilaian';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Penilaian')
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

                        Select::make('parameter_id')
                            ->label('Parameter Penilaian')
                            ->relationship('parameter', 'tahun_akademik')
                            ->required()
                            ->preload()
                            ->native(false),

                        Select::make('penguji_id')
                            ->label('Dosen Penguji')
                            ->relationship('penguji', 'name', fn ($query) => $query->whereIn('role', ['dosen', 'koordinator', 'kps', 'kajur']))
                            ->searchable()
                            ->preload()
                            ->native(false),
                    ]),

                Section::make('Nilai')
                    ->columns(3)
                    ->schema([
                        TextInput::make('nilai_industri')
                            ->label('Nilai Industri')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(100),

                        TextInput::make('nilai_dosen')
                            ->label('Nilai Dosen')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(100),

                        TextInput::make('nilai_penguji')
                            ->label('Nilai Penguji')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(100),

                        TextInput::make('nilai_akhir')
                            ->label('Nilai Akhir')
                            ->numeric()
                            ->default(0)
                            ->disabled(),

                        TextInput::make('grade')
                            ->label('Grade')
                            ->maxLength(2)
                            ->disabled(),

                        TextInput::make('predikat')
                            ->label('Predikat')
                            ->maxLength(50)
                            ->disabled(),
                    ]),

                Toggle::make('sudah_dikonversi')
                    ->label('Sudah Dikonversi')
                    ->disabled(),
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

                TextColumn::make('parameter.tahun_akademik')
                    ->label('Tahun')
                    ->sortable(),

                TextColumn::make('penguji.name')
                    ->label('Penguji')
                    ->toggleable(),

                TextColumn::make('nilai_industri')
                    ->label('Industri')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('nilai_dosen')
                    ->label('Dosen')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('nilai_penguji')
                    ->label('Penguji')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('nilai_akhir')
                    ->label('Akhir')
                    ->alignCenter()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('grade')
                    ->label('Grade')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'A', 'AB' => 'success',
                        'B', 'BC' => 'primary',
                        'C' => 'warning',
                        'D', 'E' => 'danger',
                        default => 'gray',
                    })
                    ->alignCenter(),

                IconColumn::make('sudah_dikonversi')
                    ->label('Konversi')
                    ->boolean(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPenilaian::route('/'),
            'create' => CreatePenilaian::route('/create'),
            'edit' => EditPenilaian::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && in_array($user->role, ['dosen', 'koordinator', 'kps', 'kajur', 'admin']);
    }
}
