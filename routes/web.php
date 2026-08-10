<?php

use Illuminate\Support\Facades\Route;

// Mengarahkan semua halaman web ke tampilan utama Vue.js (Blade app)
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');