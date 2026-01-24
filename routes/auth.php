<?php

use App\Http\Controllers\IvaoOAuthController;

Route::get('/login/oauth/ivao', [IvaoOAuthController::class, 'redirect'])->name('login.oauth.ivao');
Route::get('/login/oauth/ivao/callback', [IvaoOAuthController::class, 'callback'])->name('login.oauth.ivao.callback');
