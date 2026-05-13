<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Auth\Register;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
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
            ->login(Login::class)
            ->registration(Register::class)
            ->registrationRouteSlug('daftar-kontributor')
            ->profile(EditProfile::class, isSimple: false)
            ->brandLogo(asset('images/logo-kemenag.png'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/logo-kemenag.png'))
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->renderHook(
                PanelsRenderHook::SIDEBAR_FOOTER,
                fn (): string => '<div style="padding: 0 1.5rem 1rem; font-size: 11px; line-height: 1.35; color: rgb(156 163 175);"><p style="margin: 0;">&copy; '.now()->year.' SIRITA Kemenag Tana Toraja</p><p style="margin: 0.25rem 0 0;">Dikelola oleh HDI Kemenag Tana Toraja</p></div>',
            )
            ->renderHook(
                PanelsRenderHook::SCRIPTS_AFTER,
                fn (): string => <<<'HTML'
                    <script>
                        (() => {
                            const isFirefox = navigator.userAgent.toLowerCase().includes('firefox')

                            if (! isFirefox) {
                                return
                            }

                            if ('scrollRestoration' in history) {
                                history.scrollRestoration = 'manual'
                            }

                            const isDashboard = () => {
                                const path = window.location.pathname.replace(/\/+$/, '')

                                return path === '/admin'
                            }

                            const normalizeDashboardScroll = () => {
                                if (! isDashboard()) {
                                    return
                                }

                                window.requestAnimationFrame(() => {
                                    document.documentElement.scrollTop = 0
                                    document.body.scrollTop = 0
                                    window.scrollTo(0, 0)
                                })
                            }

                            window.addEventListener('pageshow', normalizeDashboardScroll)
                            window.addEventListener('popstate', normalizeDashboardScroll)
                            document.addEventListener('livewire:navigated', normalizeDashboardScroll)
                            document.addEventListener('DOMContentLoaded', normalizeDashboardScroll)
                        })()
                    </script>
                HTML,
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                ValidateCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
