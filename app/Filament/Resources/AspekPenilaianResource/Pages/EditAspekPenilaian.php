<?php
namespace App\Filament\Resources\AspekPenilaianResource\Pages;
use App\Filament\Resources\AspekPenilaianResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
class EditAspekPenilaian extends EditRecord
{
    protected static string $resource = AspekPenilaianResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
}
