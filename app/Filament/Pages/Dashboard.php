<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\DashboardWidgets;

class Dashboard extends BaseDashboard
{
    // Set the title of the dashboard page
    protected static ?string $title = 'Resumen';
    
    // Set the heading of the dashboard page
    protected ?string $heading = 'Resumen';
}