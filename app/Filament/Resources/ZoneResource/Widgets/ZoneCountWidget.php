<?php

namespace App\Filament\Resources\ZoneResource\Widgets;

use App\Models\Zone;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Enums\IconPosition;

class ZoneCountWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Zonas', Zone::count())
                ->description('Numero total de zonas')
                ->descriptionIcon('heroicon-o-tag', IconPosition::Before)
                ->chart([0, Zone::count()])
                ->color('primary'),
        ];
    }
}
