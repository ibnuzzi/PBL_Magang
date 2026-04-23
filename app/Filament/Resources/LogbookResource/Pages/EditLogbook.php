<?php
namespace App\Filament\Resources\LogbookResource\Pages;
use App\Filament\Resources\LogbookResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
class EditLogbook extends EditRecord
{
    protected static string $resource = LogbookResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
}
