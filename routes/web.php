<?php

use App\Livewire\Home;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogoutController;
use App\Livewire\AnekdotAssessment;
use App\Livewire\ArtworkAssessment;
use App\Livewire\CheklistAssessment;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

Route::get('/', Home::class)->name('/');
Route::get('assessment-cheklist', CheklistAssessment::class)->name('cheklist');
Route::get('assessment-anekdot', AnekdotAssessment::class)->name('anekdot');
Route::get('assessment-hasil-karya', ArtworkAssessment::class)->name('artwork');

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
});

Route::get('logout', [LogoutController::class, 'logout'])->name('logout');
Route::get('/media/{media}', function (Media $media) {
    return response()->file($media->getPath());
})->name('media.show');

// Route::get('chats', function () {
//     return view('wirechat.chats.chats');
// })->middleware(['web','auth'])->name('chats');