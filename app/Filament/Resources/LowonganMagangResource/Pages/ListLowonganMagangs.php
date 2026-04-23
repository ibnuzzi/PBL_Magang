<?php

namespace App\Filament\Resources\LowonganMagangResource\Pages;

use App\Filament\Resources\LowonganMagangResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLowonganMagangs extends ListRecords
{
    protected static string $resource = LowonganMagangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
