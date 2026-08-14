<?php

use Illuminate\Support\Facades\Route;

// 1. Route Dashboard (Dibutuhkan untuk redirect setelah Login & Register)
Route::get('/dashboard', function () {
    return view('app');
})->middleware(['auth'])->name('dashboard');

// 2. Import Route Autentikasi dari auth.php (Login, Register, Logout, dll)
require __DIR__.'/auth.php';

// 3. Wildcard Route untuk Vue.js SPA (HARUS ditaruh di PALING BAWAH)
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');