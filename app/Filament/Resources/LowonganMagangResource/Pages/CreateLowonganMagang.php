<?php

namespace App\Filament\Resources\LowonganMagangResource\Pages;

use App\Filament\Resources\LowonganMagangResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateLowonganMagang extends CreateRecord
{
    protected static string $resource = LowonganMagangResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['pembuat_id'] = Auth::user()->id;
        return $data;
    }
}
