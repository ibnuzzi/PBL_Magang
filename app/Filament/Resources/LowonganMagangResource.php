<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LowonganMagangResource\Pages\CreateLowonganMagang;
use App\Filament\Resources\LowonganMagangResource\Pages\EditLowonganMagang;
use App\Filament\Resources\LowonganMagangResource\Pages\ListLowonganMagangs;
use App\Filament\Resources\LowonganMagangResource\Schemas\LowonganMagangForm;
use App\Filament\Resources\LowonganMagangResource\Tables\LowonganMagangsTable;
use App\Models\LowonganMagang;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class LowonganMagangResource extends Resource
{
    protected static ?string $model = LowonganMagang::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | \UnitEnum | null $navigationGroup = 'Lowongan';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Lowongan Magang';

    protected static ?string $pluralModelLabel = 'Lowongan Magang';

    protected static ?string $recordTitleAttribute = 'judul';

    public static function form(Schema $schema): Schema
    {
        return LowonganMagangForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LowonganMagangsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLowonganMagangs::route('/'),
            'create' => CreateLowonganMagang::route('/create'),
            'edit' => EditLowonganMagang::route('/{record}/edit'),
        ];
    }

    /**
     * Hanya koordinator dan admin yang bisa akses resource ini.
     */
    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && in_array($user->role, ['koordinator', 'admin']);
    }
}
