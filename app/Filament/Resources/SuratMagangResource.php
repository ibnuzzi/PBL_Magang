<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratMagangResource\Pages\CreateSuratMagang;
use App\Filament\Resources\SuratMagangResource\Pages\EditSuratMagang;
use App\Filament\Resources\SuratMagangResource\Pages\ListSuratMagang;
use App\Models\SuratMagang;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
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

class SuratMagangResource extends Resource
{
    protected static ?string $model = SuratMagang::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static string | \UnitEnum | null $navigationGroup = 'Pelaksanaan';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Surat Magang';

    protected static ?string $pluralModelLabel = 'Surat Magang';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Surat')
                    ->columns(2)
                    ->schema([
                        Select::make('pendaftaran_id')
                            ->label('Pendaftaran')
                            ->relationship('pendaftaran', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "#{$record->id} - {$record->mahasiswa->name}")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),

                        Select::make('jenis_surat')
                            ->label('Jenis Surat')
                            ->options([
                                'pengantar' => 'Surat Pengantar',
                                'loa' => 'Letter of Acceptance (LOA)',
                            ])
                            ->required()
                            ->native(false),

                        TextInput::make('nomor_surat')
                            ->label('Nomor Surat')
                            ->required()
                            ->maxLength(100),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'diterbitkan' => 'Diterbitkan',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->default('draft')
                            ->required()
                            ->native(false),

                        FileUpload::make('file_path')
                            ->label('File Surat')
                            ->required()
                            ->directory('surat-magang')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120)
                            ->columnSpanFull(),

                        DateTimePicker::make('diterbitkan_at')
                            ->label('Tanggal Diterbitkan'),
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

                TextColumn::make('jenis_surat')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pengantar' => 'primary',
                        'loa' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),

                TextColumn::make('nomor_surat')
                    ->label('Nomor Surat')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'diterbitkan' => 'success',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('diterbitkan_at')
                    ->label('Diterbitkan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('jenis_surat')
                    ->options([
                        'pengantar' => 'Surat Pengantar',
                        'loa' => 'LOA',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'diterbitkan' => 'Diterbitkan',
                        'dibatalkan' => 'Dibatalkan',
                    ]),
            ])
            ->groups([
                \Filament\Tables\Grouping\Group::make('pendaftaran.mahasiswa.name')
                    ->label('Mahasiswa')
                    ->collapsible(),
            ])
            ->defaultGroup('pendaftaran.mahasiswa.name')
            ->recordActions([
                EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSuratMagang::route('/'),
            'create' => CreateSuratMagang::route('/create'),
            'edit' => EditSuratMagang::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return $user && in_array($user->role, ['koordinator', 'admin']);
    }
}
