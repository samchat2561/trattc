<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile as AuthEditProfile;
use App\Http\Middleware\SetLocale;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Auth\MultiFactor\Email\EmailAuthentication;
use Filament\Auth\Pages\Login;
use Filament\Auth\Pages\Register;
use Filament\Facades\Filament;
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
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Subham\FilamentDynamicSettings\Facades\Settings;
use Subham\FilamentDynamicSettings\FilamentDynamicSettingsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login(Login::class)
            ->registration(Register::class)
            ->passwordReset()
            ->emailVerification()
            ->emailChangeVerification()
            ->brandName('Laravel CMS')
            ->colors([
                'primary' => Color::Violet,
                'danger' => Color::Red,
                'gray' => Color::Zinc,
                'info' => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Amber,
            ])
            ->profile(AuthEditProfile::class, isSimple: false)
            ->brandName(function () {
                return Settings::get('app_name', 'general', 'Default app');
            })
            ->brandLogo(asset('images/logo.svg'))
            ->resourceCreatePageRedirect('index')
            ->resourceEditPageRedirect('index')
            ->font('Sarabun')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->plugin(
                new FilamentDynamicSettingsPlugin,
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetLocale::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
                fn (): string => Blade::render('@livewire(\'language-toggle\')'),
            )
            ->authMiddleware([
                Authenticate::class,
            ])
            ->multiFactorAuthentication([
                EmailAuthentication::make(),
                AppAuthentication::make(),
            ])
            ->databaseNotifications();
    }

    // app/Providers/Filament/AdminPanelProvider.php
    public function boot(): void
    {
        Filament::serving(function () {
            $user = auth()->user();

            if ($user) {
                // เช็คแบบครอบจักรวาล:
                // 1. เป็น Super Admin (ตามชื่อ role ใน config)
                // 2. หรือ มีสิทธิ์ชื่อใดชื่อหนึ่งในนี้
                $hasAccess = $user->hasRole('super_admin') ||
                             $user->hasRole('admin') || // เผื่อคุณใช้ role ชื่อ admin
                             $user->can('access_settings') ||
                             $user->can('AccessSettings');

                Config::set('filament-dynamic-settings.page.register', $hasAccess);
                Config::set('filament-dynamic-settings.resource.register', $hasAccess);
            }
        });
    }
}
