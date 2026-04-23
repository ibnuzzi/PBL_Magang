<?php
namespace App\Filament\Resources\SuratMagangResource\Pages;
use App\Filament\Resources\SuratMagangResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListSuratMagang extends ListRecords
{
    protected static string $resource = SuratMagangResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
