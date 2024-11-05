<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LogoutController;
use Illuminate\Support\Facades\URL;

Route::get('/invoice/{id}', [ExportController::class, 'invoice'])->name('invoice');
Route::get('/mutation', [ExportController::class, 'mutation'])->name('mutation');
Route::post('/auth/logout', [LogoutController::class, 'logout'])->name('filament.admin.auth.logout');
