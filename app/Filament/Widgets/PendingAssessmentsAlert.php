<?php

namespace App\Filament\Widgets;

use App\Models\Penilaian;
use Filament\Widgets\Widget;

class PendingAssessmentsAlert extends Widget
{
    protected string $view = 'filament.widgets.pending-assessments-alert';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected int $count = 0;

    public function mount(): void
    {
        $this->count = Penilaian::whereNull('nilai_akhir')->count();
    }

    protected function getViewData(): array
    {
        return [
            'count' => $this->count,
        ];
    }
}
