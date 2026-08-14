<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::passkeys();

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', fn () => view('dashboard'))->name('dashboard');
    Route::get('/profile', fn () => view('profile.index'))->name('profile.index');

    Route::middleware('can:access-admin')->group(function () {});
});

require __DIR__.'/auth.php';
