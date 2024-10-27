<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Auth\Login;
use App\Models\Department;
use App\Filament\Resources\ExitInvoiceResource;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\Facades\Auth;
use Filapanel\ClassicTheme\ClassicThemePlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->brandLogo(asset('images/bpsdm.png'))
            ->brandLogoHeight('2.5rem')
            ->default()
            ->id('admin')
            ->path('')
            ->sidebarWidth('300px')
            ->spa()
            ->viteTheme('resources/css/filament/admin/theme.css')


            ->authMiddleware([
                Authenticate::class,
            ])
            ->colors([
                'primary' => Color::hex('#027D3D'),
                'secondary' => Color::hex('#00AFEF'),
                'tertiary' => Color::hex('#FCC134'),
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->login(Login::class)
            ->navigationItems(self::getNavigationItems())
            ->pages([])
            ->plugin(ClassicThemePlugin::make())
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
            ]);
    }

    public static function getNavigationItems(): array
    {
        $defaultNavigationItems = [
            NavigationItem::make('Barang Keluar')
                ->icon('heroicon-s-arrow-up-tray')
                ->sort(5)
                ->isActiveWhen(fn() => request()->fullUrlIs(ExitInvoiceResource::getUrl('index', ['id' => Auth::user()->department_id])))
                ->url(fn() => ExitInvoiceResource::getUrl('index', ['id' => Auth::user()->department_id])),
        ];

        $customNavigationItems = Department::all()
            ->map(
                fn(Department $department) => NavigationItem::make($department->name)
                    ->group('Bidang')
                    // ->icon('heroicon-s-building-office-2')
                    ->isActiveWhen(fn() => request()->fullUrlIs(ExitInvoiceResource::getUrl('index', ['id' => $department->id])))
                    ->url(fn() => ExitInvoiceResource::getUrl('index', ['id' => $department->id]))
                    ->visible(fn() => Auth::user()->department_id !== $department->id)
            )
            ->toArray();

        return array_merge($defaultNavigationItems, $customNavigationItems);
    }
}
