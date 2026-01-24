<?php

use App\Http\Controllers\IvaoWhazzupController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', static function () {
	return Inertia::render('welcome', [
		'canRegister' => Features::enabled(Features::registration()),
	]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
	Route::get('dashboard', static function () {
		return Inertia::render('dashboard');
	})->name('dashboard');

	Route::get('webeye', [IvaoWhazzupController::class, 'index'])->name('webeye');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
