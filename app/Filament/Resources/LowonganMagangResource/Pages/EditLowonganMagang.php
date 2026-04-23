<?php

namespace App\Filament\Resources\LowonganMagangResource\Pages;

use App\Filament\Resources\LowonganMagangResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLowonganMagang extends EditRecord
{
    protected static string $resource = LowonganMagangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
