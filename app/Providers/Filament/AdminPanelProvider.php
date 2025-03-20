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
use Filament\Enums\ThemeMode;
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
    /**
     * Define the Filament panel settings.
     *
     * @param Panel $panel
     * @return Panel
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            // Set the default settings for the panel
            ->default()
            ->id('app')  // The unique ID for the panel
            ->path('/')  // The URL path for the panel (root path in this case)
            
            // Define the login and profile pages
            ->login(Login::class)
            ->profile(EditProfile::class)
            
            // Set the colors for the panel's UI elements
            ->colors([
                'primary' => Color::hex('#4e73df'), // Primary color for the panel (Blue)
                'gray' => Color::Slate, // Gray color for the panel's secondary elements
            ])
            
            // Set the default theme mode to Light
            ->defaultThemeMode(ThemeMode::Light)
            
            // Make the sidebar fully collapsible on desktop screens
            ->sidebarFullyCollapsibleOnDesktop()
            
            // Set the font for the panel's UI
            ->font('Poppins')
            
            // Define the pages to be included in the panel
            ->pages([
                Dashboard::class, // Dashboard page for the admin panel
            ])
            
            // Define the resources to manage within the panel
            ->resources([
                PropertyResource::class,   // Resource for managing properties
                LandResource::class,       // Resource for managing lands
                UbicationResource::class,  // Resource for managing ubications
                UserResource::class,       // Resource for managing users
                ZoneResource::class,       // Resource for managing zones
            ])
            
            // Define the widgets to display on the dashboard
            ->widgets([
                DashboardWidgets::class, // Custom widgets for the dashboard
            ])
            
            // Add middleware for session, cookies, and CSRF protection
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
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class, // Custom theme middleware
            ])
            
            // Define authentication middleware for the admin panel
            ->authMiddleware([
                Authenticate::class, // Middleware for authenticating users
            ])
            
            // Load custom plugins for the panel (e.g., theme plugin)
            ->plugin(
                \Hasnayeen\Themes\ThemesPlugin::make() // Enable themes plugin for the panel
            );
    }
}