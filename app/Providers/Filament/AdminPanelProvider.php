<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Facades\FilamentColor;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Widgets\StatusTugasChart;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // ✅ Registrasi warna tema utama
        FilamentColor::register([
            'primary' => [
                500 => 'rgb(255, 193, 7)', 
            ],
            'success' => [
                500 => 'rgb(34, 197, 94)',
            ],
            'danger' => [
                500 => 'rgb(239, 68, 68)',
            ],
            'gray' => [
                100 => 'rgb(243, 244, 246)',
                900 => 'rgb(17, 24, 39)',
                950 => 'rgb(15, 23, 42)',
            ],
        ]);
        
        
        

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()

            // ✅ Nama dan Branding
            ->brandName('Manajemen Tugas Mahasiswa')
            // ->brandLogo(asset('images/logo.png'))
            // ->brandLogoHeight('40px')

            // ✅ Warna login/dashboard (renderHook CSS)
            ->renderHook('head.end', fn () => new HtmlString('
                <style>
                    body {
                        background-color: #fff8e1 !important; /* Amber sangat terang */
                    }
                    .fi-btn {
                        background-color: rgb(255, 193, 7) !important;
                        color: #000 !important;
                    }
                    .fi-sidebar {
                        background-color: #fff3cd !important;
                    }
                </style>
            '))

            // ✅ Penemuan Resource, Page, Widget
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                StatusTugasChart::class,
            ])

            // ✅ Middleware
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
