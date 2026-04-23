<?php
namespace App\Filament\Resources\ParameterPenilaianResource\Pages;
use App\Filament\Resources\ParameterPenilaianResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListParameterPenilaian extends ListRecords
{
    protected static string $resource = ParameterPenilaianResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
