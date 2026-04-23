<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApprovalPendaftaranResource\Pages\CreateApprovalPendaftaran;
use App\Filament\Resources\ApprovalPendaftaranResource\Pages\EditApprovalPendaftaran;
use App\Filament\Resources\ApprovalPendaftaranResource\Pages\ListApprovalPendaftaran;
use App\Models\ApprovalPendaftaran;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
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

class ApprovalPendaftaranResource extends Resource
{
    protected static ?string $model = ApprovalPendaftaran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckCircle;

    protected static string | \UnitEnum | null $navigationGroup = 'Pendaftaran';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Approval Pendaftaran';

    protected static ?string $pluralModelLabel = 'Approval Pendaftaran';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Approval')
                    ->columns(2)
                    ->schema([
                        Select::make('pendaftaran_id')
                            ->label('Pendaftaran')
                            ->relationship('pendaftaran', 'id')
                            ->getOptionLabelFromRecordUsing(fn($record) => "#{$record->id} - {$record->mahasiswa->name}")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),

                        Select::make('approver_id')
                            ->label('Approver')
                            ->relationship('approver', 'name', fn($query) => $query->whereIn('role', ['koordinator', 'kps', 'kajur', 'wadir1', 'admin']))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),

                        Select::make('level')
                            ->label('Level Approval')
                            ->options([
                                'koordinator' => 'Koordinator',
                                'kps' => 'KPS',
                                'kajur' => 'Kajur',
                                'wadir1' => 'Wadir 1',
                            ])
                            ->required()
                            ->native(false),

                        TextInput::make('urutan_level')
                            ->label('Urutan Level')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(4),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'menunggu' => 'Menunggu',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak',
                            ])
                            ->default('menunggu')
                            ->required()
                            ->native(false),

                        DateTimePicker::make('diproses_at')
                            ->label('Diproses Pada'),

                        Textarea::make('catatan')
                            ->label('Catatan')
                            ->columnSpanFull(),
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

                TextColumn::make('approver.name')
                    ->label('Approver')
                    ->searchable(),

                TextColumn::make('level')
                    ->label('Level')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'koordinator' => 'info',
                        'kps' => 'primary',
                        'kajur' => 'warning',
                        'wadir1' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => strtoupper($state)),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'menunggu' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('diproses_at')
                    ->label('Diproses')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('level')
                    ->options([
                        'koordinator' => 'Koordinator',
                        'kps' => 'KPS',
                        'kajur' => 'Kajur',
                        'wadir1' => 'Wadir 1',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ]),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApprovalPendaftaran::route('/'),
            'create' => CreateApprovalPendaftaran::route('/create'),
            'edit' => EditApprovalPendaftaran::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return $user && in_array($user->role, ['koordinator', 'kps', 'kajur', 'wadir1', 'admin']);
    }
}
