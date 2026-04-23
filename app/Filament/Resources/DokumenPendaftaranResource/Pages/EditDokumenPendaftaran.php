<?php
namespace App\Filament\Resources\DokumenPendaftaranResource\Pages;
use App\Filament\Resources\DokumenPendaftaranResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
class EditDokumenPendaftaran extends EditRecord
{
    protected static string $resource = DokumenPendaftaranResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
}
