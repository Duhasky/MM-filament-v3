<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Pages\Dashboard as FilamentDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends FilamentDashboard
{
    use HasFiltersForm;

    public function filtersForm(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('start_date'),
            Forms\Components\DatePicker::make('end_date'),
        ]);
    }
    public static function getNavigationIcon(): string | Htmlable | null
    {
        return null;
    }
}