<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
	return Inertia::render('Welcome');
})->name('home');

Route::get('salgsprodukter', function () {
	return Inertia::render('Salgsprodukter');
})->middleware(['auth', 'verified'])->name('salgsprodukter');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
