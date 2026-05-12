<?php

namespace App\Filament\Widgets;

use App\Models\ParameterPenilaian;
use Filament\Widgets\Widget;
use Filament\Notifications\Notification;

class AssessmentParametersWidget extends Widget
{
    protected string $view = 'filament.widgets.assessment-parameters-widget';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = [
        'default' => 12,
        'xl' => 4,
    ];

    public $bobot_industri;
    public $bobot_dosen;
    public $bobot_penguji;
    public $total;

    public function mount()
    {
        $parameter = ParameterPenilaian::where('is_active', true)->first();
        if ($parameter) {
            $this->bobot_industri = (int) $parameter->bobot_industri;
            $this->bobot_dosen = (int) $parameter->bobot_dosen;
            $this->bobot_penguji = (int) $parameter->bobot_penguji;
        } else {
            $this->bobot_industri = 40;
            $this->bobot_dosen = 35;
            $this->bobot_penguji = 25;
        }
        $this->updateTotal();
    }

    public function updated($propertyName)
    {
        $this->updateTotal();
    }

    public function updateTotal()
    {
        $this->total = $this->bobot_industri + $this->bobot_dosen + $this->bobot_penguji;
    }

    public function save()
    {
        $parameter = ParameterPenilaian::where('is_active', true)->first();
        if ($parameter) {
            $parameter->update([
                'bobot_industri' => $this->bobot_industri,
                'bobot_dosen' => $this->bobot_dosen,
                'bobot_penguji' => $this->bobot_penguji,
            ]);

            Notification::make()
                ->title('Berhasil disimpan')
                ->success()
                ->send();
        }
    }
}
