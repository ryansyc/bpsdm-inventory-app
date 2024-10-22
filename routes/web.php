<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;

Route::get('/pdf/generate/{id}', [PDFController::class, 'generatePDF'])->name('pdf.generate');
