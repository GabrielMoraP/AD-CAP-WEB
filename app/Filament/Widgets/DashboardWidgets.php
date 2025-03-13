<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Property;
use App\Models\Land;
use App\Models\Ubication;
use App\Models\Zone;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Enums\IconPosition;

class DashboardWidgets extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Usuarios', User::count())
                ->description('Numero total de usuarios')
                ->descriptionIcon('heroicon-o-users', IconPosition::Before)
                ->chart([0, User::count()])
                ->color('primary'),

            Stat::make('Propiedades', Property::count())
                ->description('Numero total de propiedades')
                ->descriptionIcon('heroicon-o-building-office', IconPosition::Before)
                ->chart([0, Property::count()])
                ->color('primary'),

            Stat::make('Terrenos', Land::count())
                ->description('Numero total de terrenos')
                ->descriptionIcon('heroicon-o-map', IconPosition::Before)
                ->chart([0, Land::count()])
                ->color('primary'),

            Stat::make('Ubicaciones', Ubication::count())
                ->description('Numero total de ubicaciones')
                ->descriptionIcon('heroicon-o-bookmark', IconPosition::Before)
                ->chart([0, Ubication::count()])
                ->color('primary'),

            Stat::make('Zonas', Zone::count())
                ->description('Numero total de zonas')
                ->descriptionIcon('heroicon-o-tag', IconPosition::Before)
                ->chart([0, Zone::count()])
                ->color('primary'),
        ];
    }
}