<?php
namespace App\Filament\Resources\PenilaianResource\Pages;
use App\Filament\Resources\PenilaianResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListPenilaian extends ListRecords
{
    protected static string $resource = PenilaianResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
