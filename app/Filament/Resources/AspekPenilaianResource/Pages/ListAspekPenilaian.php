<?php
namespace App\Filament\Resources\AspekPenilaianResource\Pages;
use App\Filament\Resources\AspekPenilaianResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListAspekPenilaian extends ListRecords
{
    protected static string $resource = AspekPenilaianResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
