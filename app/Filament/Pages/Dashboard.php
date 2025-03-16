<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\DashboardWidgets;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Resumen';
    protected ?string $heading = 'Resumen';
}
