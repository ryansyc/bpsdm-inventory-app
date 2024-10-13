<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Auth\Login;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\ItemResource;
use App\Models\User;
use Filament\Navigation\NavigationGroup;
use Illuminate\Support\Facades\Auth;
use App\Filament\Widgets\ItemsWidget;
use Filament\Pages\Dashboard;
use App\Filament\Resources\UserResource;
use Filament\View\LegacyComponents\Widget;
use Illuminate\Auth\AuthManager;

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
                // Start the session before anything else
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,                   // Start session first
                AuthenticateSession::class,            // Authenticate the session
                ShareErrorsFromSession::class,         // Handle error sharing for validation
                VerifyCsrfToken::class,                // CSRF protection middleware
                SubstituteBindings::class,             // Resolve route-model bindings
                DisableBladeIconComponents::class,     // Filament-specific middleware
                DispatchServingFilamentEvent::class,   // Filament-specific event middleware
            ])
            ->authMiddleware([
                Authenticate::class, // Enforce authentication here
            ])

            ->brandLogo(asset('images/bpsdm.png'))
            ->brandLogoHeight('2.5rem')
            ->spa()
            ->sidebarWidth('300px')

            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Gudang Utama'),
                NavigationGroup::make()
                    ->label('Stok Barang Bidang')
            ])

            ->navigationItems(self::getNavigationItems());
    }

    public static function getNavigationItems(): array
    {
        $defaultNavigationItems = [
            NavigationItem::make('Stok Barang')
                ->icon('heroicon-s-cube')
                ->isActiveWhen(fn() => request()->fullUrlIs(ItemResource::getUrl('index', ['id' => Auth::user()->id])))
                ->sort(2)
                ->url(fn() => ItemResource::getUrl('index', ['id' => Auth::user()->id])),
        ];

        $customNavigationItems = User::all()
            ->map(
                fn(User $user) => NavigationItem::make($user->name)
                    ->group('Stok Barang Bidang')
                    ->icon('heroicon-s-cube')
                    ->isActiveWhen(fn() => request()->fullUrlIs(ItemResource::getUrl('index', ['id' => $user->id])))
                    ->url(fn() => ItemResource::getUrl('index', ['id' => $user->id]))
                    ->visible(fn() => Auth::user()->role === 'super-admin' &&  $user->id !== Auth::user()->id),
            )
            ->toArray();


        return array_merge($defaultNavigationItems, $customNavigationItems);
    }
}
