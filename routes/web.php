<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;

Route::get('/', function () {
	return Inertia::render('Welcome');
})->name('home');

Route::get('salgsprodukter', function () {
	return Inertia::render('Salgsprodukter');
})->middleware(['auth', 'verified'])->name('salgsprodukter');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

Route::post('/formsubmitted', function (Request $request) {
	$validation = $request->validate([
		'fullname' => 'required|min:3|max:30',
		'email' => 'required|min:3|max:30|email',
	]);

	$fullname = $request->input('fullname');
	$email = $request->input('email');

	return "Name: $fullname, Email: $email, Validation: $validation";
})->name('formsubmitted');
