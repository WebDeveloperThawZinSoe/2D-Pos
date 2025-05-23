<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\Facades\Auth;

class ShopPanelPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('shop_filament')
            ->path('shop')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->login()
            ->middleware(['auth', 'role:shop'])
            ->registration()
            ->discoverResources(in: app_path('Filament/ShopPanel/Resources'), for: 'App\\Filament\\ShopPanel\\Resources')
            ->discoverPages(in: app_path('Filament/ShopPanel/Pages'), for: 'App\\Filament\\ShopPanel\\Pages')
           
            ->discoverWidgets(in: app_path('Filament/ShopPanel/Widgets'), for: 'App\\Filament\\ShopPanel\\Widgets')
            ->widgets([
                // Widgets\StatsOverview::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->login()
            ->passwordReset()
            ->profile()
            ->pages([
                Pages\Dashboard::class,
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
