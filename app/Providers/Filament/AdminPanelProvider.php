<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use App\Filament\Auth\Login;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Widgets\DashboardWidgets;
use App\Filament\Resources\LandResource;
use App\Filament\Resources\PropertyResource;
use App\Filament\Resources\UbicationResource;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\ZoneResource;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('/')
            ->login(Login::class)
            ->profile(EditProfile::class)
            ->colors([
                'primary' => Color::hex('#4e73df'),
                'gray' => Color::Slate,
            ])
            ->sidebarFullyCollapsibleOnDesktop()
            ->font('Poppins')
            ->pages([
                Dashboard::class,
            ])
            ->resources([
                PropertyResource::class,
                LandResource::class,
                UbicationResource::class,
                UserResource::class,
                ZoneResource::class,
            ])
            ->widgets([
                DashboardWidgets::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
