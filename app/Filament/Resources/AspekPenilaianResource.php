<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AspekPenilaianResource\Pages\CreateAspekPenilaian;
use App\Filament\Resources\AspekPenilaianResource\Pages\EditAspekPenilaian;
use App\Filament\Resources\AspekPenilaianResource\Pages\ListAspekPenilaian;
use App\Models\AspekPenilaian;
use BackedEnum;
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

class AspekPenilaianResource extends Resource
{
    protected static ?string $model = AspekPenilaian::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static string | \UnitEnum | null $navigationGroup = 'Penilaian';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Aspek Penilaian';

    protected static ?string $pluralModelLabel = 'Aspek Penilaian';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Aspek')
                    ->columns(2)
                    ->schema([
                        Select::make('parameter_id')
                            ->label('Parameter Penilaian')
                            ->relationship('parameter', 'tahun_akademik')
                            ->required()
                            ->preload()
                            ->native(false),

                        Select::make('penilai')
                            ->label('Penilai')
                            ->options([
                                'industri' => 'Industri',
                                'dosen' => 'Dosen',
                                'penguji' => 'Penguji',
                            ])
                            ->required()
                            ->native(false),

                        TextInput::make('nama_aspek')
                            ->label('Nama Aspek')
                            ->required()
                            ->maxLength(150),

                        TextInput::make('bobot_aspek')
                            ->label('Bobot Aspek (%)')
                            ->numeric()
                            ->required()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100),

                        TextInput::make('urutan')
                            ->label('Urutan')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('parameter.tahun_akademik')
                    ->label('Tahun Akademik')
                    ->sortable(),

                TextColumn::make('penilai')
                    ->label('Penilai')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'industri' => 'success',
                        'dosen' => 'primary',
                        'penguji' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('nama_aspek')
                    ->label('Nama Aspek')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bobot_aspek')
                    ->label('Bobot')
                    ->suffix('%')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('urutan')
                    ->label('Urutan')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('penilai')
                    ->options([
                        'industri' => 'Industri',
                        'dosen' => 'Dosen',
                        'penguji' => 'Penguji',
                    ]),
                SelectFilter::make('parameter_id')
                    ->label('Tahun Akademik')
                    ->relationship('parameter', 'tahun_akademik'),
            ])
            ->defaultSort('urutan')
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAspekPenilaian::route('/'),
            'create' => CreateAspekPenilaian::route('/create'),
            'edit' => EditAspekPenilaian::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return $user && in_array($user->role, ['koordinator', 'admin']);
    }
}
