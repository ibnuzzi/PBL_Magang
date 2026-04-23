<?php

namespace App\Filament\Resources\MitraPerusahaanResource\Pages;

use App\Filament\Resources\MitraPerusahaanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMitraPerusahaan extends EditRecord
{
    protected static string $resource = MitraPerusahaanResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
