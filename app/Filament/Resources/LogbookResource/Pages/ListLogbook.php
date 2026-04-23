<?php
namespace App\Filament\Resources\LogbookResource\Pages;
use App\Filament\Resources\LogbookResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListLogbook extends ListRecords
{
    protected static string $resource = LogbookResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
