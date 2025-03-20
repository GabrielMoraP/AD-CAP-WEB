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
    // Function to define the statistics to be displayed in the widget
    protected function getStats(): array
    {
        $stats = [];  // Initialize an empty array to store stats

        // Check if the logged-in user is an 'Administrador'
        if (auth()->user()->role === 'Administrador') {
            // Add the 'Usuarios' stat to the widget
            $stats[] = Stat::make('Usuarios', User::count())  // Get the total count of users
                            ->description('Numero total de usuarios')  // Description for the stat
                            ->descriptionIcon('heroicon-o-users', IconPosition::Before)  // Icon before the description
                            ->chart([0, User::count()])  // Display a simple chart (just a count here)
                            ->color('primary')  // Set the color of the stat
                            ->extraAttributes([  // Additional HTML attributes for the stat
                                'onclick' => "window.location.href = '/users';",  // Redirect to the users page when clicked
                                'class' => 'transition hover:text-primary-500 cursor-pointer',  // Add hover effect and cursor pointer
                            ]);
        }

        // Add the 'Propiedades' stat to the widget
        $stats[] = Stat::make('Propiedades', Property::count())  // Get the total count of properties
                        ->description('Numero total de propiedades')  // Description for the stat
                        ->descriptionIcon('heroicon-o-building-office', IconPosition::Before)  // Icon before the description
                        ->chart([0, Property::count()])  // Display a simple chart (just a count here)
                        ->color('primary')  // Set the color of the stat
                        ->extraAttributes([  // Additional HTML attributes for the stat
                            'onclick' => "window.location.href = '/properties';",  // Redirect to the properties page when clicked
                            'class' => 'transition hover:text-primary-500 cursor-pointer',  // Add hover effect and cursor pointer
                        ]);

        // Add the 'Terrenos' stat to the widget
        $stats[] = Stat::make('Terrenos', Land::count())  // Get the total count of lands
                        ->description('Numero total de terrenos')  // Description for the stat
                        ->descriptionIcon('heroicon-o-map', IconPosition::Before)  // Icon before the description
                        ->chart([0, Land::count()])  // Display a simple chart (just a count here)
                        ->color('primary')  // Set the color of the stat
                        ->extraAttributes([  // Additional HTML attributes for the stat
                            'onclick' => "window.location.href = '/lands';",  // Redirect to the lands page when clicked
                            'class' => 'transition hover:text-primary-500 cursor-pointer',  // Add hover effect and cursor pointer
                        ]);

        // Add the 'Ubicaciones' stat to the widget
        $stats[] = Stat::make('Ubicaciones', Ubication::count())  // Get the total count of ubications
                        ->description('Numero total de ubicaciones')  // Description for the stat
                        ->descriptionIcon('heroicon-o-bookmark', IconPosition::Before)  // Icon before the description
                        ->chart([0, Ubication::count()])  // Display a simple chart (just a count here)
                        ->color('primary')  // Set the color of the stat
                        ->extraAttributes([  // Additional HTML attributes for the stat
                            'onclick' => "window.location.href = '/ubications';",  // Redirect to the ubications page when clicked
                            'class' => 'transition hover:text-primary-500 cursor-pointer',  // Add hover effect and cursor pointer
                        ]);

        // Add the 'Zonas' stat to the widget
        $stats[] = Stat::make('Zonas', Zone::count())  // Get the total count of zones
                        ->description('Numero total de zonas')  // Description for the stat
                        ->descriptionIcon('heroicon-o-tag', IconPosition::Before)  // Icon before the description
                        ->chart([0, Zone::count()])  // Display a simple chart (just a count here)
                        ->color('primary')  // Set the color of the stat
                        ->extraAttributes([  // Additional HTML attributes for the stat
                            'onclick' => "window.location.href = '/zones';",  // Redirect to the zones page when clicked
                            'class' => 'transition hover:text-primary-500 cursor-pointer',  // Add hover effect and cursor pointer
                        ]);
        // Return the array of stats
        return $stats;
    }
}