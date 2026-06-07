<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengumumanResource\Pages\CreatePengumuman;
use App\Filament\Resources\PengumumanResource\Pages\EditPengumuman;
use App\Filament\Resources\PengumumanResource\Pages\ListPengumuman;
use App\Models\Pengumuman;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class PengumumanResource extends Resource
{
    protected static ?string $model = Pengumuman::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static string | \UnitEnum | null $navigationGroup = 'Informasi';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Pengumuman';

    protected static ?string $pluralModelLabel = 'Pengumuman';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pengumuman')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('judul')
                            ->label('Judul')
                            ->required()
                            ->maxLength(200)
                            ->columnSpanFull(),

                        RichEditor::make('konten')
                            ->label('Konten')
                            ->required()
                            ->columnSpanFull(),

                        Select::make('pembuat_id')
                            ->label('Pembuat')
                            ->relationship('pembuat', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),

                        Select::make('target_role')
                            ->label('Target Role')
                            ->options([
                                'all' => 'Semua',
                                'mahasiswa' => 'Mahasiswa',
                                'dosen' => 'Dosen',
                                'koordinator' => 'Koordinator',
                                'kps' => 'KPS',
                                'kajur' => 'Kajur',
                                'admin' => 'Admin',
                            ])
                            ->default('all')
                            ->required()
                            ->native(false),

                        Select::make('jenis_magang')
                            ->label('Jenis Magang')
                            ->options([
                                'all' => 'Semua',
                                'reguler' => 'Reguler',
                                'mandiri' => 'Mandiri',
                                'cti' => 'CTI',
                            ])
                            ->default('all')
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

                        Toggle::make('is_published')
                            ->label('Dipublikasikan')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('pembuat.name')
                    ->label('Pembuat')
                    ->sortable(),

                TextColumn::make('target_role')
                    ->label('Target')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('tanggal_selesai')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->sortable(),

                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('target_role')
                    ->options([
                        'all' => 'Semua',
                        'mahasiswa' => 'Mahasiswa',
                        'dosen' => 'Dosen',
                    ]),
                TernaryFilter::make('is_published')
                    ->label('Published'),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPengumuman::route('/'),
            'create' => CreatePengumuman::route('/create'),
            'edit' => EditPengumuman::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return $user && in_array($user->role, ['koordinator', 'admin']);
    }
}
