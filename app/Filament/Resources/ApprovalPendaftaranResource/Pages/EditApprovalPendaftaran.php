<?php
namespace App\Filament\Resources\ApprovalPendaftaranResource\Pages;
use App\Filament\Resources\ApprovalPendaftaranResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
class EditApprovalPendaftaran extends EditRecord
{
    protected static string $resource = ApprovalPendaftaranResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
}
