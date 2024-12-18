<?php

namespace App\Http\Controllers;

use Filament\Facades\Filament;
use Filament\Http\Controllers\Auth\LogoutController as FilamentLogoutController;
use Illuminate\Http\Request;

class LogoutController extends FilamentLogoutController
{
    public function logout(Request $request)
    {
        Filament::auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
