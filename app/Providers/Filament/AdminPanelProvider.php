<?php

namespace App\Providers\Filament;

use App\Filament\Pages\AdminDashboard;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Pages\Auth\Login::class)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandName('SiMagang JTI')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                AdminDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->renderHook(
                \Filament\View\PanelsRenderHook::STYLES_AFTER,
                fn (): string => '
                    <style>
                        /* Sidebar background */
                        .fi-sidebar {
                            background-color: #003B7A !important;
                            border-right: 1px solid rgba(255, 255, 255, 0.1) !important;
                        }

                        /* Sidebar header / Brand / Logo area */
                        .fi-sidebar-header-ctn {
                            background-color: #003B7A !important;
                        }
                        .fi-sidebar-header {
                            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
                            padding-top: 1rem !important;
                            padding-bottom: 1rem !important;
                        }

                        /* Force brand/logo text to be white */
                        .fi-sidebar .fi-logo {
                            color: #ffffff !important;
                            font-weight: 800 !important;
                            font-size: 1.25rem !important;
                            letter-spacing: -0.02em !important;
                        }

                        /* Force group headers to be white */
                        .fi-sidebar .fi-sidebar-group-label {
                            color: rgba(255, 255, 255, 0.65) !important;
                            font-size: 0.7rem !important;
                            font-weight: 700 !important;
                            letter-spacing: 0.05em !important;
                            text-transform: uppercase !important;
                        }

                        /* Collapse button */
                        .fi-sidebar .fi-sidebar-group-collapse-btn {
                            color: rgba(255, 255, 255, 0.65) !important;
                        }
                        .fi-sidebar .fi-sidebar-group-collapse-btn:hover {
                            color: #ffffff !important;
                        }
                        .fi-sidebar .fi-sidebar-group-collapse-btn svg {
                            color: rgba(255, 255, 255, 0.65) !important;
                        }
                        .fi-sidebar .fi-sidebar-group-collapse-btn:hover svg {
                            color: #ffffff !important;
                        }

                        /* Inactive items - force white color on both label and icon */
                        .fi-sidebar .fi-sidebar-item:not(.fi-active) .fi-sidebar-item-btn {
                            background-color: transparent !important;
                        }
                        .fi-sidebar .fi-sidebar-item:not(.fi-active) .fi-sidebar-item-label {
                            color: #ffffff !important;
                            font-weight: 600 !important;
                        }
                        .fi-sidebar .fi-sidebar-item:not(.fi-active) .fi-sidebar-item-icon {
                            color: #ffffff !important;
                        }

                        /* Hover state for inactive items */
                        .fi-sidebar .fi-sidebar-item:not(.fi-active) .fi-sidebar-item-btn:hover {
                            background-color: rgba(255, 255, 255, 0.1) !important;
                        }
                        .fi-sidebar .fi-sidebar-item:not(.fi-active) .fi-sidebar-item-btn:hover .fi-sidebar-item-label {
                            color: #ffffff !important;
                        }
                        .fi-sidebar .fi-sidebar-item:not(.fi-active) .fi-sidebar-item-btn:hover .fi-sidebar-item-icon {
                            color: #ffffff !important;
                        }

                        /* Active items - orange background, white text & icon */
                        .fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
                            background-color: #f59e0b !important;
                            color: #ffffff !important;
                        }
                        .fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-label {
                            color: #ffffff !important;
                            font-weight: 700 !important;
                        }
                        .fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon {
                            color: #ffffff !important;
                        }

                        /* Group dividers */
                        .fi-sidebar-group {
                            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
                            padding-bottom: 0.5rem !important;
                            margin-bottom: 0.5rem !important;
                        }
                        .fi-sidebar-group:last-child {
                            border-bottom: none !important;
                        }

                        /* Tenant menu styling (if any) */
                        .fi-sidebar-nav-tenant-menu-ctn {
                            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
                            padding-bottom: 0.75rem !important;
                            margin-bottom: 0.75rem !important;
                        }
                    </style>
                '
            )
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
