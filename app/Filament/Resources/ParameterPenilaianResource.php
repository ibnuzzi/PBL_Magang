<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParameterPenilaianResource\Pages\CreateParameterPenilaian;
use App\Filament\Resources\ParameterPenilaianResource\Pages\EditParameterPenilaian;
use App\Filament\Resources\ParameterPenilaianResource\Pages\ListParameterPenilaian;
use App\Models\ParameterPenilaian;
use BackedEnum;
use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Components\Section;
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

class ParameterPenilaianResource extends Resource
{
    protected static ?string $model = ParameterPenilaian::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string | \UnitEnum | null $navigationGroup = 'Penilaian';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Parameter Penilaian';

    protected static ?string $pluralModelLabel = 'Parameter Penilaian';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Parameter')
                    ->columns(2)
                    ->schema([
                        TextInput::make('tahun_akademik')
                            ->label('Tahun Akademik')
                            ->required()
                            ->placeholder('2024/2025')
                            ->maxLength(20),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),

                        TextInput::make('bobot_industri')
                            ->label('Bobot Industri (%)')
                            ->numeric()
                            ->required()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100),

                        TextInput::make('bobot_dosen')
                            ->label('Bobot Dosen (%)')
                            ->numeric()
                            ->required()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100),

                        TextInput::make('bobot_penguji')
                            ->label('Bobot Penguji (%)')
                            ->numeric()
                            ->required()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100),
                    ]),

                Section::make('Konversi Grade')
                    ->schema([
                        KeyValue::make('konversi_grade')
                            ->label('Konversi Grade (Grade => Range Nilai)')
                            ->keyLabel('Grade')
                            ->valueLabel('Minimal Nilai')
                            ->default([
                                'A' => '85',
                                'AB' => '80',
                                'B' => '75',
                                'BC' => '70',
                                'C' => '60',
                                'D' => '50',
                                'E' => '0',
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tahun_akademik')
                    ->label('Tahun Akademik')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bobot_industri')
                    ->label('Industri')
                    ->suffix('%')
                    ->alignCenter(),

                TextColumn::make('bobot_dosen')
                    ->label('Dosen')
                    ->suffix('%')
                    ->alignCenter(),

                TextColumn::make('bobot_penguji')
                    ->label('Penguji')
                    ->suffix('%')
                    ->alignCenter(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                TextColumn::make('aspek_count')
                    ->label('Aspek')
                    ->counts('aspek')
                    ->alignCenter(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListParameterPenilaian::route('/'),
            'create' => CreateParameterPenilaian::route('/create'),
            'edit' => EditParameterPenilaian::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return $user && in_array($user->role, ['koordinator', 'admin']);
    }
}
