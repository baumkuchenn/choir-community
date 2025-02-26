<?php

use App\Http\Controllers\ConcertController;
use App\Http\Controllers\EticketController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

//Debug
Route::get('/debug', function () {
    return route('profile.update');
});

//Tampilan awal
Route::get('/', [EticketController::class, 'index']);

//Eticketing ----------------------------------------------------------------------------------
//Email verification waktu registrasi
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

//Profile
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Rute e-ticketing
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('eticket', EticketController::class);;
    Route::get('/eticket/{id}/purchase', [EticketController::class, 'purchase'])->name('eticket.purchase');
});

require __DIR__ . '/auth.php';
