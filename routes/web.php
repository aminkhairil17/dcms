<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotulenController;
use Filament\Http\Middleware\Authenticate;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([Authenticate::class])->group(function () {
    Route::get('/notulen/view/{id}', [NotulenController::class, 'view'])->name('notulen.view');
});
