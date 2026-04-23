<?php
namespace App\Filament\Resources\PelaksanaanMagangResource\Pages;
use App\Filament\Resources\PelaksanaanMagangResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListPelaksanaanMagang extends ListRecords
{
    protected static string $resource = PelaksanaanMagangResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
