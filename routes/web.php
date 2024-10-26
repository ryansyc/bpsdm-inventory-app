<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;

Route::get('/invoice/{id}', [ExportController::class, 'invoice'])->name('invoice');
Route::get('/mutation', [ExportController::class, 'mutation'])->name('mutation');
