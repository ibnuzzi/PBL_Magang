<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DokumenPendaftaranResource\Pages\CreateDokumenPendaftaran;
use App\Filament\Resources\DokumenPendaftaranResource\Pages\EditDokumenPendaftaran;
use App\Filament\Resources\DokumenPendaftaranResource\Pages\ListDokumenPendaftaran;
use App\Models\DokumenPendaftaran;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
use Filament\Notifications\Notification;
use App\Services\NotifikasiService;
use Illuminate\Support\Facades\Auth;

class DokumenPendaftaranResource extends Resource
{
    protected static ?string $model = DokumenPendaftaran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string | \UnitEnum | null $navigationGroup = 'Pendaftaran';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Dokumen Pendaftaran';

    protected static ?string $pluralModelLabel = 'Dokumen Pendaftaran';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Dokumen')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('pendaftaran_id')
                            ->label('Pendaftaran')
                            ->relationship('pendaftaran', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "#{$record->id} - {$record->mahasiswa->name}")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),

                        Select::make('jenis_dokumen')
                            ->label('Jenis Dokumen')
                            ->options(DokumenPendaftaran::jenisOptions())
                            ->required()
                            ->native(false),

                        FileUpload::make('file_path')
                            ->label('File Dokumen')
                            ->required()
                            ->directory('dokumen-pendaftaran')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->maxSize(5120)
                            ->columnSpanFull(),

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

                        Textarea::make('keterangan_reject')
                            ->label('Keterangan Reject')
                            ->visible(fn (callable $get) => $get('status') === 'ditolak'),
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

                TextColumn::make('jenis_dokumen')
                    ->label('Jenis Dokumen')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => DokumenPendaftaran::jenisOptions()[$state] ?? strtoupper(str_replace('_', ' ', $state))),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('created_at')
                    ->label('Diupload')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ]),

                SelectFilter::make('jenis_dokumen')
                    ->options(DokumenPendaftaran::jenisOptions()),
            ])
            ->recordActions([
                EditAction::make(),
                \Filament\Actions\DeleteAction::make(),

                // Approve dokumen
                Action::make('approve_dokumen')
                    ->label('Setujui')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'menunggu'
                        && in_array(Auth::user()?->role, ['koordinator', 'admin']))
                    ->action(function ($record) {
                        $record->update(['status' => 'disetujui']);
                        Notification::make()->title('Dokumen disetujui')->success()->send();
                    }),

                // Tolak dokumen
                Action::make('tolak_dokumen')
                    ->label('Tolak')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('keterangan_reject')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->visible(fn ($record) => $record->status === 'menunggu'
                        && in_array(Auth::user()?->role, ['koordinator', 'admin']))
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'ditolak',
                            'keterangan_reject' => $data['keterangan_reject'],
                        ]);
                        app(NotifikasiService::class)->notifyDokumenDitolak(
                            $record->pendaftaran,
                            DokumenPendaftaran::jenisOptions()[$record->jenis_dokumen] ?? $record->jenis_dokumen
                        );
                        Notification::make()->title('Dokumen ditolak')->danger()->send();
                    }),
            ])
            ->groups([
                \Filament\Tables\Grouping\Group::make('pendaftaran.mahasiswa.name')
                    ->label('Mahasiswa')
                    ->collapsible(),
            ])
            ->defaultGroup('pendaftaran.mahasiswa.name')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDokumenPendaftaran::route('/'),
            'create' => CreateDokumenPendaftaran::route('/create'),
            'edit' => EditDokumenPendaftaran::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && in_array($user->role, ['koordinator', 'admin']);
    }
}
