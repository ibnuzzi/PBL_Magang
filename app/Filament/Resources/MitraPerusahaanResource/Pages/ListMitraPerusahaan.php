<?php

namespace App\Filament\Resources\MitraPerusahaanResource\Pages;

use App\Filament\Resources\MitraPerusahaanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMitraPerusahaan extends ListRecords
{
    protected static string $resource = MitraPerusahaanResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
