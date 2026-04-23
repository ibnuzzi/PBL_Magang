<?php
namespace App\Filament\Resources\DokumenPendaftaranResource\Pages;
use App\Filament\Resources\DokumenPendaftaranResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListDokumenPendaftaran extends ListRecords
{
    protected static string $resource = DokumenPendaftaranResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
