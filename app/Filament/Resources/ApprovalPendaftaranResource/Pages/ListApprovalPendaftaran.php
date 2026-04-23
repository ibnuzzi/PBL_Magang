<?php
namespace App\Filament\Resources\ApprovalPendaftaranResource\Pages;
use App\Filament\Resources\ApprovalPendaftaranResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListApprovalPendaftaran extends ListRecords
{
    protected static string $resource = ApprovalPendaftaranResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
