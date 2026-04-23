<?php
namespace App\Filament\Resources\PelaksanaanMagangResource\Pages;
use App\Filament\Resources\PelaksanaanMagangResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
class EditPelaksanaanMagang extends EditRecord
{
    protected static string $resource = PelaksanaanMagangResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
}
