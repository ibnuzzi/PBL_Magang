<?php
namespace App\Filament\Resources\SuratMagangResource\Pages;
use App\Filament\Resources\SuratMagangResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
class EditSuratMagang extends EditRecord
{
    protected static string $resource = SuratMagangResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
}
