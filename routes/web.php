<?php

use App\Livewire\Home;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogoutController;

Route::get('/', Home::class)->name('/');

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
});

Route::get('logout', [LogoutController::class, 'logout'])->name('logout');
