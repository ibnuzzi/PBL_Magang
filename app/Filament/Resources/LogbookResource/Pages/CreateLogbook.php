<?php
namespace App\Filament\Resources\LogbookResource\Pages;
use App\Filament\Resources\LogbookResource;
use Filament\Resources\Pages\CreateRecord;
class CreateLogbook extends CreateRecord
{
    protected static string $resource = LogbookResource::class;

    protected function beforeCreate(): void
    {
        $data = $this->data;
        $existing = \App\Models\Logbook::where('pelaksanaan_id', $data['pelaksanaan_id'])
            ->where('tanggal', $data['tanggal'])
            ->first();

        if ($existing) {
            $this->redirect($this->getResource()::getUrl('edit', ['record' => $existing->id]));
            
            \Filament\Notifications\Notification::make()
                ->title('Logbook Hari Ini Sudah Ada')
                ->body('Anda dialihkan ke halaman edit logbook yang sudah ada.')
                ->warning()
                ->send();
                
            $this->halt();
        }
    }
}
