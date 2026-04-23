<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MitraPerusahaanResource\Pages\CreateMitraPerusahaan;
use App\Filament\Resources\MitraPerusahaanResource\Pages\EditMitraPerusahaan;
use App\Filament\Resources\MitraPerusahaanResource\Pages\ListMitraPerusahaan;
use App\Models\MitraPerusahaan;
use BackedEnum;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class MitraPerusahaanResource extends Resource
{
    protected static ?string $model = MitraPerusahaan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string | \UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Mitra Perusahaan';

    protected static ?string $pluralModelLabel = 'Mitra Perusahaan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Perusahaan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Perusahaan')
                            ->required()
                            ->maxLength(200),

                        TextInput::make('bidang_usaha')
                            ->label('Bidang Usaha')
                            ->required()
                            ->maxLength(100),

                        Textarea::make('alamat')
                            ->label('Alamat')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('kuota_mahasiswa')
                            ->label('Kuota Mahasiswa')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        Toggle::make('is_resmi_polinema')
                            ->label('Resmi Polinema'),

                        Toggle::make('is_cti')
                            ->label('CTI'),

                        Select::make('status_verifikasi')
                            ->label('Status Verifikasi')
                            ->options([
                                'terverifikasi' => 'Terverifikasi',
                                'menunggu' => 'Menunggu Verifikasi',
                                'ditolak' => 'Ditolak',
                            ])
                            ->default('terverifikasi')
                            ->required()
                            ->native(false)
                            ->visible(fn () => in_array(Auth::user()?->role, ['koordinator', 'admin'])),
                    ]),

                Section::make('Kontak PIC')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama_pic')
                            ->label('Nama PIC')
                            ->required()
                            ->maxLength(150),

                        TextInput::make('jabatan_pic')
                            ->label('Jabatan PIC')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('no_hp_pic')
                            ->label('No. HP PIC')
                            ->required()
                            ->tel()
                            ->maxLength(20),

                        TextInput::make('email_pic')
                            ->label('Email PIC')
                            ->required()
                            ->email()
                            ->maxLength(150),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Perusahaan')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('bidang_usaha')
                    ->label('Bidang Usaha')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_pic')
                    ->label('PIC')
                    ->searchable(),

                TextColumn::make('kuota_mahasiswa')
                    ->label('Kuota')
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('status_verifikasi')
                    ->label('Verifikasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'terverifikasi' => 'success',
                        'menunggu' => 'warning',
                        'ditolak' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state))),

                IconColumn::make('is_resmi_polinema')
                    ->label('Resmi')
                    ->boolean(),

                IconColumn::make('is_cti')
                    ->label('CTI')
                    ->boolean(),

                TextColumn::make('pengaju.name')
                    ->label('Diajukan Oleh')
                    ->placeholder('— Admin/Koordinator —')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status_verifikasi')
                    ->label('Status Verifikasi')
                    ->options([
                        'terverifikasi' => 'Terverifikasi',
                        'menunggu' => 'Menunggu',
                        'ditolak' => 'Ditolak',
                    ]),

                TernaryFilter::make('is_resmi_polinema')
                    ->label('Resmi Polinema'),

                TernaryFilter::make('is_cti')
                    ->label('CTI'),
            ])
            ->recordActions([
                EditAction::make(),

                // Verifikasi mitra yang diajukan mahasiswa
                Action::make('verifikasi_mitra')
                    ->label('Verifikasi')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Mitra Perusahaan')
                    ->modalDescription('Pastikan data perusahaan sudah benar sebelum diverifikasi.')
                    ->visible(fn ($record) => $record->status_verifikasi === 'menunggu'
                        && in_array(Auth::user()?->role, ['koordinator', 'admin']))
                    ->action(function ($record) {
                        $record->update(['status_verifikasi' => 'terverifikasi']);
                        Notification::make()->title('Mitra berhasil diverifikasi')->success()->send();
                    }),

                Action::make('tolak_mitra')
                    ->label('Tolak')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status_verifikasi === 'menunggu'
                        && in_array(Auth::user()?->role, ['koordinator', 'admin']))
                    ->action(function ($record) {
                        $record->update(['status_verifikasi' => 'ditolak']);
                        Notification::make()->title('Mitra ditolak')->danger()->send();
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
            'index' => ListMitraPerusahaan::route('/'),
            'create' => CreateMitraPerusahaan::route('/create'),
            'edit' => EditMitraPerusahaan::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && in_array($user->role, ['koordinator', 'admin']);
    }
}
