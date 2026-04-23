<?php
namespace App\Filament\Resources\ParameterPenilaianResource\Pages;
use App\Filament\Resources\ParameterPenilaianResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
class EditParameterPenilaian extends EditRecord
{
    protected static string $resource = ParameterPenilaianResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
}
