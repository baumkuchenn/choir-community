<?php

use App\Http\Controllers\Auth\PersonalInfoController as AuthPersonalInfoController;
use App\Http\Controllers\ConcertController;
use App\Http\Controllers\EticketController;
use App\Http\Controllers\PersonalInfoController;
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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/personal-info', [AuthPersonalInfoController::class, 'showForm'])->name('personal-info.show');
    Route::post('/personal-info', [AuthPersonalInfoController::class, 'store'])->name('personal-info.store');
});

//Profile
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Rute e-ticketing
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/eticket/myticket', [EticketController::class, 'myticket'])->name('eticket.myticket');
    Route::resource('eticket', EticketController::class);
    Route::post('/eticket/{id}/order', [EticketController::class, 'order'])->name('eticket.order');
    Route::post('/eticket/{id}/purchase', [EticketController::class, 'purchase'])->name('eticket.purchase');
    Route::post('/eticket/{id}/payment', [EticketController::class, 'payment'])->name('eticket.payment');
    Route::post('/eticket/{id}/invoice', [EticketController::class, 'invoice'])->name('eticket.invoice');
    Route::post('/eticket/{id}/feedback', [EticketController::class, 'feedback'])->name('eticket.feedback');
    // Route::post('/eticket/{id}/verifikasi', [EticketController::class, 'verifikasi'])->name('eticket.verifikasi');
});

require __DIR__ . '/auth.php';
