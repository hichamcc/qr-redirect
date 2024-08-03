<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/links', [LinkController::class, 'index'])->name('links.index');
    Route::post('/links/generate', [LinkController::class, 'generate'])->name('links.generate');
    Route::patch('/links/{link}/update-url', [LinkController::class, 'updateRedirectUrl'])->name('links.update-url');
    Route::get('/links/download-csv/{fileName}', [LinkController::class, 'downloadCsv'])->name('links.download-csv');

    Route::get('/csv-exports', [LinkController::class, 'csvIndex'])->name('csv.index');
});

Route::get('/r/{code}', [RedirectController::class, 'handleRedirect'])->name('qr.redirect');



require __DIR__ . '/auth.php';
