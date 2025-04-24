<?php

use App\Http\Controllers\Auth\PersonalInfoController as AuthPersonalInfoController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ButirPenilaianController;
use App\Http\Controllers\ChoirController;
use App\Http\Controllers\ConcertController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EticketController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ForumMemberController;
use App\Http\Controllers\KotaController;
use App\Http\Controllers\KuponController;
use App\Http\Controllers\LatihanController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PanitiaController;
use App\Http\Controllers\PanitiaDivisiController;
use App\Http\Controllers\PanitiaJabatanController;
use App\Http\Controllers\PenyanyiController;
use App\Http\Controllers\PersonalInfoController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SeleksiController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketTypeController;
use App\Http\Controllers\TopicController;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

//Debug
Route::get('/debug/verification-link', function () {
    $user = \App\Models\User::find(6); // or your test user
    return \Illuminate\Support\Facades\URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
    );
});

//Tampilan awal dan eticket
Route::get('/', [EticketController::class, 'index']);
Route::get('/eticket', [EticketController::class, 'index'])->name('eticket.index');
Route::get('/eticket/show/{id}', [EticketController::class, 'show'])->name('eticket.show');
Route::get('/eticket/search', [EticketController::class, 'search'])->name('eticket.search');

//Forum
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/search', [ForumController::class, 'search'])->name('forum.search');
Route::get('/forum/show/{slug}', [ForumController::class, 'show'])->name('forum.show');
Route::get('/posts/show/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts/comment/{post}', [PostController::class, 'comment'])->name('posts.comment.show');

//Isi Profil setelah buat akun ----------------------------------------------------------------------------------
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

    //Rute forum
    //Forum
    Route::resource('forum', ForumController::class)->except(['index', 'show']);
    Route::post('/forum/{slug}/masuk', [ForumController::class, 'masuk'])->name('forum.masuk');
    Route::post('/forum/{slug}/keluar', [ForumController::class, 'keluar'])->name('forum.keluar');
    Route::get('/forum/{slug}/pengaturan', [ForumController::class, 'pengaturan'])->name('forum.pengaturan');

    //Member
    Route::get('/forum/member/search', [ForumMemberController::class, 'search'])->name('forum-member.search');
    Route::resource('forum-member', ForumMemberController::class);

    //Notifikasi
    Route::get('/forum/notification', [ForumController::class, 'notification'])->name('forum.notification');
    Route::get('/forum/notifications/read/{id}', [ForumController::class, 'readAndRedirect'])->name('forum-notification.readAndRedirect');

    //Postingan
    Route::post('/forum/{slug}/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/react', [PostController::class, 'react'])->name('posts.react');
    Route::get('/forum/{slug}/topik', [TopicController::class, 'index'])->name('topik.index');
    Route::post('/forum/{slug}/topik', [TopicController::class, 'store'])->name('topik.store');
    Route::delete('/forum/{slug}/topik', [TopicController::class, 'destroy'])->name('topik.destroy');


    //Rute e-ticketing
    Route::get('/eticket/myticket', [EticketController::class, 'myticket'])->name('eticket.myticket');
    Route::resource('eticket', EticketController::class)->except(['index', 'show']);
    Route::get('/eticket/{id}/kupon', [EticketController::class, 'kupon'])->name('eticket.kupon.use');
    Route::post('/eticket/{id}/order', [EticketController::class, 'order'])->name('eticket.order');
    Route::post('/eticket/{id}/purchase', [EticketController::class, 'purchase'])->name('eticket.purchase');
    Route::post('/eticket/{id}/payment', [EticketController::class, 'payment'])->name('eticket.payment');
    Route::post('/eticket/{id}/invoice', [EticketController::class, 'invoice'])->name('eticket.invoice');
    Route::post('/eticket/{id}/ticket', [EticketController::class, 'ticket'])->name('eticket.ticket');
    Route::post('/eticket/{id}/feedback', [EticketController::class, 'feedback'])->name('eticket.feedback');

    //Rute Manajemen
    Route::prefix('management')->group(function () {
        Route::resource('/', ManagementController::class)->names('management');

        //Daftar atau buat choir
        Route::get('choir/join', [ChoirController::class, 'join'])->name('choir.join');
        Route::post('choir/join/{id}', [ChoirController::class, 'register'])->name('choir.register');
        Route::get('choir/join/detail/{id}', [ChoirController::class, 'detail'])->name('choir.detail');
        Route::get('choir/search', [ChoirController::class, 'search'])->name('choir.search');
        Route::resource('choir', ChoirController::class)->except(['show', 'update', 'destroy']);
        Route::get('/kota/search', [KotaController::class, 'search'])->name('kota.search');

        //Manajemen Event untuk panit eksternal juga
        //Manajemen Konser
        Route::middleware('akses:akses-eticket-semua')->group(function () {
            Route::get('/events', [EventController::class, 'index'])->name('events.index');
            Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

            Route::post('/events/{id}/payment', [EventController::class, 'payment'])->name('events.payment');
            Route::post('/events/{id}/verification', [EventController::class, 'verifikasi'])->name('events.verification');
            Route::get('/events/check-in/{id}', [EventController::class, 'checkInShow'])->name('events.checkInShow');
            Route::post('/tickets/check-in/{id}', [TicketController::class, 'checkIn'])->name('tickets.checkIn');
            Route::get('/ticket-types/{event}/create', [TicketTypeController::class, 'create'])->name('ticket-types.create');
            Route::resource('ticket-types', TicketTypeController::class)->except(['create']);
            Route::get('/kupon/create/{event}/{tipe}', [KuponController::class, 'create'])->name('kupon.create');
            Route::resource('kupon', KuponController::class)->except(['create']);
            Route::put('/concerts/{id}', [ConcertController::class, 'update'])->name('concerts.update');
        });

        Route::middleware(['akses:akses-event', 'akses:akses-eticket-semua'])->group(function () {
            Route::get('events/search-lalu', [EventController::class, 'searchEventLalu'])->name('events.search.lalu');
            Route::get('events/search-selanjutnya', [EventController::class, 'searchEventSelanjutnya'])->name('events.search.selanjutnya');
        });

        Route::middleware(['auth', 'choir.member'])->group(function () {
            //Manajemen Choir
            Route::middleware('akses:akses-admin')->group(function () {
                Route::get('choir/profile/{choir}', [ChoirController::class, 'show'])->name('choir.profile');
                Route::put('choir/{choir}', [ChoirController::class, 'update'])->name('choir.update');
                Route::delete('choir/{choir}', [ChoirController::class, 'destroy'])->name('choir.update');
            });

            //Manajemen Event
            Route::middleware('akses:akses-event')->group(function () {
                Route::get('events/search-choir', [EventController::class, 'searchChoir'])->name('events.search.choir');
                Route::resource('events', EventController::class)->except(['index', 'show']);
                Route::resource('latihans', LatihanController::class);
            });

            //Manajemen Anggota
            Route::middleware('akses:akses-member')->group(function () {
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
                Route::get('/penyanyi/search', [PenyanyiController::class, 'search'])->name('penyanyi.search');
                Route::get('/penyanyi/create/{event}', [PenyanyiController::class, 'create'])->name('penyanyi.create');
                Route::resource('penyanyi', PenyanyiController::class)->except(['create']);

                //Kepanitiaan
                Route::get('/panitia/setting/{event}', [PanitiaController::class, 'setting'])->name('panitia.setting');
                Route::get('/panitia/search', [PanitiaController::class, 'search'])->name('panitia.search');
                Route::get('/panitia/create/{event}', [PanitiaController::class, 'create'])->name('panitia.create');
                Route::resource('panitia', PanitiaController::class)->except(['create']);
                Route::get('/panitia-jabatan/{event}/create', [PanitiaJabatanController::class, 'create'])->name('panitia-jabatan.create');
                Route::get('/panitia-jabatan/{event}/{jabatan}/edit', [PanitiaJabatanController::class, 'edit'])->name('panitia-jabatan.edit');
                Route::resource('panitia-jabatan', PanitiaJabatanController::class)->except(['create', 'edit']);
                Route::get('/panitia-divisi/{event}/create', [PanitiaDivisiController::class, 'create'])->name('panitia-divisi.create');
                Route::get('/panitia-divisi/{event}/{divisi}/edit', [PanitiaDivisiController::class, 'edit'])->name('panitia-divisi.edit');
                Route::post('/panitia-divisi/{event}/ambil-kegiatan-lain', [PanitiaDivisiController::class, 'ambilKegiatanLain'])->name('panitia-divisi.ambil-kegiatan-lain');
                Route::resource('panitia-divisi', PanitiaDivisiController::class)->except(['create', 'edit']);
            });

            //Manajemen Roles
            Route::middleware('akses:akses-roles')->group(function () {
                Route::resource('roles', RoleController::class);
                Route::resource('divisions', DivisionController::class);
                Route::resource('positions', PositionController::class);
            });

            //Kalender dan notifikasi
            Route::get('/calendar', [ManagementController::class, 'calendar'])->name('management.calendar.index');
            Route::get('/calendar/show', [ManagementController::class, 'calendarShow'])->name('management.calendar.show');
            Route::get('/notification', [ManagementController::class, 'notification'])->name('management.notification');
            Route::post('/notifications/read/{id}', [NotificationController::class, 'readAndRedirect'])->name('notifications.readAndRedirect');
            Route::post('/events/{event}/daftar', [ManagementController::class, 'daftar'])->name('management.event.daftar');
        });
    });
});

require __DIR__ . '/auth.php';
