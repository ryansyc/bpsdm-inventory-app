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
use App\Filament\Resources\ItemEntryResource;
use App\Models\ItemExit;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\Facades\Auth;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->colors([
                // 'primary' => Color::hex('#006799'),
                'primary' => Color::hex('#027D3D'),
                'secondary' => Color::hex('#00AFEF'),
                // 'primary' => Color::hex('#FCC134'),

            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
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
            ])

            ->brandLogo(asset('images/bpsdm.png'))
            ->brandLogoHeight('2.5rem')
            ->spa()
            ->sidebarWidth('300px')
            ->navigationItems(self::getNavigationItems());
    }

    public static function getNavigationItems(): array
    {
        $defaultNavigationItems = [
            NavigationItem::make('Stok Barang')
                ->icon('heroicon-s-cube')
                ->isActiveWhen(fn() => request()->fullUrlIs(ItemEntryResource::getUrl('index', ['id' => Auth::user()->department_id])))
                ->sort(2)
                ->url(fn() => ItemEntryResource::getUrl('index', ['id' => Auth::user()->department_id])),
        ];

        $customNavigationItems = Department::all()
            ->map(
                fn(Department $department) => NavigationItem::make($department->name)
                    ->group('Bidang')
                    ->icon('heroicon-s-cube')
                    ->isActiveWhen(fn() => request()->fullUrlIs(ItemEntryResource::getUrl('index', ['id' => $department->id])))
                    ->url(fn() => ItemEntryResource::getUrl('index', ['id' => $department->id]))
            )
            ->toArray();


        return array_merge($defaultNavigationItems, $customNavigationItems);
    }
}
