<?php

use App\Http\Controllers\Auth\PersonalInfoController as AuthPersonalInfoController;
use App\Http\Controllers\ButirPenilaianController;
use App\Http\Controllers\ChoirController;
use App\Http\Controllers\ConcertController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EticketController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\KotaController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PersonalInfoController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SeleksiController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketTypeController;
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
    //Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Rute e-ticketing
    Route::get('/eticket/myticket', [EticketController::class, 'myticket'])->name('eticket.myticket');
    Route::resource('eticket', EticketController::class);
    Route::post('/eticket/{id}/order', [EticketController::class, 'order'])->name('eticket.order');
    Route::post('/eticket/{id}/purchase', [EticketController::class, 'purchase'])->name('eticket.purchase');
    Route::post('/eticket/{id}/payment', [EticketController::class, 'payment'])->name('eticket.payment');
    Route::post('/eticket/{id}/invoice', [EticketController::class, 'invoice'])->name('eticket.invoice');
    Route::post('/eticket/{id}/feedback', [EticketController::class, 'feedback'])->name('eticket.feedback');

    //Rute Manajemen
    Route::prefix('management')->group(function () {
        Route::resource('/', ManagementController::class)->names('management');

        //Daftar atau buat choir
        Route::get('choir/join', [ChoirController::class, 'join'])->name('choir.join');
        Route::post('choir/join/{id}', [ChoirController::class, 'register'])->name('choir.register');
        Route::get('choir/join/detail/{id}', [ChoirController::class, 'detail'])->name('choir.detail');
        Route::get('choir/search', [ChoirController::class, 'search'])->name('choir.search');
        Route::resource('choir', ChoirController::class)
            ->except(['update', 'destroy']);
        Route::get('/kota/search', [KotaController::class, 'search'])->name('kota.search');

        Route::middleware(['auth', 'choir.member'])->group(function () {
            //Manajemen Choir
            Route::put('choir/{choir}', [ChoirController::class, 'update'])
                ->name('choir.update');
            Route::delete('choir/{choir}', [ChoirController::class, 'destroy'])
                ->name('choir.update');

            //Manajemen Event
            Route::resource('events', EventController::class);
            Route::post('/events/{id}/payment', [EventController::class, 'payment'])->name('events.payment');
            Route::post('/events/{id}/verification', [EventController::class, 'verifikasi'])->name('events.verification');
            Route::get('/events/check-in/{id}', [EventController::class, 'checkInShow'])->name('events.checkInShow');
            Route::post('/tickets/check-in/{id}', [TicketController::class, 'checkIn'])->name('tickets.checkIn');
            Route::resource('ticket-types', TicketTypeController::class);
            Route::put('/concerts/{id}', [ConcertController::class, 'update'])->name('concerts.update');

            //Manajemen Anggota
            Route::get('/members/setting', [MemberController::class, 'setting'])->name('members.setting');
            Route::get('/members/search', [MemberController::class, 'search'])->name('members.search');
            Route::resource('members', MemberController::class);
            Route::resource('butir-penilaian', ButirPenilaianController::class);
            Route::post('/seleksi/tambah-pendaftar', [SeleksiController::class, 'tambahPendaftar'])->name('seleksi.tambah-pendaftar');
            Route::get('/seleksi/{seleksi}/wawancara/{user}', [SeleksiController::class, 'wawancara'])->name('seleksi.wawancara');
            Route::post('/seleksi/wawancara/check-in', [SeleksiController::class, 'checkIn'])->name('seleksi.checkin-pendaftar');
            Route::put('/seleksi/wawancara/', [SeleksiController::class, 'simpanPendaftar'])->name('seleksi.simpan-pendaftar');
            Route::post('/seleksi/wawancara/lolos', [SeleksiController::class, 'lolos'])->name('seleksi.lolos-pendaftar');
            Route::resource('seleksi', SeleksiController::class);

            //Manajemen Roles
            Route::resource('roles', RoleController::class);
            Route::resource('divisions', DivisionController::class);
            Route::resource('positions', PositionController::class);

            Route::get('/calendar', [ManagementController::class, 'calendar'])->name('management.calendar');
            Route::post('/notification', [ManagementController::class, 'notification'])->name('management.notification');
        });
    });
});

require __DIR__ . '/auth.php';
