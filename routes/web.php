<?php

use App\Http\Controllers\Admin\SiswaController;
use Illuminate\Support\Facades\Route;

/* ================================================================
   PUBLIC ROUTES
=============================================================== */

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/tutor/{tutor}', [App\Http\Controllers\TutorController::class, 'show'])->name('tutor.show');


/* ================================================================
   AUTH ROUTES (guest only)
=============================================================== */
Route::middleware('guest')->group(function () {

    Route::get('/login',  [App\Http\Controllers\Auth\LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])
        ->middleware('throttle:login');

    Route::get('/register',  [App\Http\Controllers\Auth\RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])
        ->middleware('throttle:register');

});

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/* ================================================================
   SISWA ROUTES  (role: siswa)
=============================================================== */
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/pemesanan',           [App\Http\Controllers\Siswa\PemesananController::class, 'index'])->name('pemesanan');
    Route::get('/pemesanan/{order}',   [App\Http\Controllers\Siswa\PemesananController::class, 'show'])->name('pemesanan.show');
    Route::delete('/pemesanan/{order}/cancel', [App\Http\Controllers\Siswa\PemesananController::class, 'cancel'])->name('pemesanan.cancel');

    Route::get('/pemesanan/{order}/bayar',   [App\Http\Controllers\Siswa\PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::post('/pemesanan/{order}/bayar',  [App\Http\Controllers\Siswa\PembayaranController::class, 'process'])->name('pembayaran.process');
    Route::get('/pemesanan/{order}/sukses',  [App\Http\Controllers\Siswa\PembayaranController::class, 'success'])->name('pembayaran.sukses');

    Route::get('/profil',            [App\Http\Controllers\Siswa\ProfileController::class, 'index'])->name('profil');
    Route::put('/profil',            [App\Http\Controllers\Siswa\ProfileController::class, 'update'])->name('profil.update');
    Route::put('/profil/password',   [App\Http\Controllers\Siswa\ProfileController::class, 'updatePassword'])->name('profil.password');

});


/* ================================================================
   TUTOR ROUTES  (role: tutor)
=============================================================== */
Route::middleware(['auth', 'role:tutor'])->prefix('tutor-panel')->name('tutor.')->group(function () {

    Route::get('/status', [App\Http\Controllers\Tutor\StatusController::class, 'pending'])->name('pending');

    Route::middleware('tutor.status')->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Tutor\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/jadwal',         [App\Http\Controllers\Tutor\JadwalController::class, 'index'])->name('jadwal');
        Route::post('/jadwal',        [App\Http\Controllers\Tutor\JadwalController::class, 'store'])->name('jadwal.store');
        Route::delete('/jadwal/{id}', [App\Http\Controllers\Tutor\JadwalController::class, 'destroy'])->name('jadwal.destroy');

        Route::get('/pemesanan',                     [App\Http\Controllers\Tutor\PemesananController::class, 'index'])->name('pemesanan');
        Route::get('/pemesanan/{order}',             [App\Http\Controllers\Tutor\PemesananController::class, 'show'])->name('pemesanan.show');
        Route::post('/pemesanan/{order}/terima',     [App\Http\Controllers\Tutor\PemesananController::class, 'terima'])->name('pemesanan.terima');
        Route::post('/pemesanan/{order}/tolak',      [App\Http\Controllers\Tutor\PemesananController::class, 'tolak'])->name('pemesanan.tolak');
        Route::post('/pemesanan/{order}/selesai',    [App\Http\Controllers\Tutor\PemesananController::class, 'selesai'])->name('pemesanan.selesai');

        Route::get('/profil',            [App\Http\Controllers\Tutor\ProfileController::class, 'index'])->name('profil');
        Route::put('/profil',            [App\Http\Controllers\Tutor\ProfileController::class, 'update'])->name('profil.update');
        Route::put('/profil/password',   [App\Http\Controllers\Tutor\ProfileController::class, 'updatePassword'])->name('profil.password');
        Route::put('/profil/photo',      [App\Http\Controllers\Tutor\ProfileController::class, 'updatePhoto'])->name('profil.photo');

        Route::post('/profil/subject',                    [App\Http\Controllers\Tutor\ProfileController::class, 'storeSubject'])->name('profil.subject.store');
        Route::put('/profil/subject/{subject}',           [App\Http\Controllers\Tutor\ProfileController::class, 'updateSubject'])->name('profil.subject.update');
        Route::delete('/profil/subject/{subject}',        [App\Http\Controllers\Tutor\ProfileController::class, 'destroySubject'])->name('profil.subject.destroy');

        Route::post('/profil/experience',                 [App\Http\Controllers\Tutor\ProfileController::class, 'storeExperience'])->name('profil.experience.store');
        Route::put('/profil/experience/{experience}',     [App\Http\Controllers\Tutor\ProfileController::class, 'updateExperience'])->name('profil.experience.update');
        Route::delete('/profil/experience/{experience}',  [App\Http\Controllers\Tutor\ProfileController::class, 'destroyExperience'])->name('profil.experience.destroy');

        Route::post('/profil/education',                  [App\Http\Controllers\Tutor\ProfileController::class, 'storeEducation'])->name('profil.education.store');
        Route::put('/profil/education/{education}',       [App\Http\Controllers\Tutor\ProfileController::class, 'updateEducation'])->name('profil.education.update');
        Route::delete('/profil/education/{education}',    [App\Http\Controllers\Tutor\ProfileController::class, 'destroyEducation'])->name('profil.education.destroy');

    });

});


/* ================================================================
   ADMIN ROUTES  (role: admin)
=============================================================== */
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Tutor
    Route::get('/tutor',                         [App\Http\Controllers\Admin\TutorController::class, 'index'])->name('tutor');
    Route::post('/tutor/{tutor}/verifikasi',     [App\Http\Controllers\Admin\TutorController::class, 'verifikasi'])->name('tutor.verifikasi');
    Route::post('/tutor/{tutor}/tolak',          [App\Http\Controllers\Admin\TutorController::class, 'tolak'])->name('tutor.tolak');
    Route::patch('/tutor/{tutor}/toggle-status', [App\Http\Controllers\Admin\TutorController::class, 'toggleStatus'])->name('tutor.toggle-status');
    Route::delete('/tutor/{tutor}',              [App\Http\Controllers\Admin\TutorController::class, 'destroy'])->name('tutor.destroy');

    // Manajemen Siswa
    Route::get('/siswa',           [SiswaController::class, 'index'])->name('siswa');
    Route::delete('/siswa/{user}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

    // Mata Pelajaran
    Route::get('/mata-pelajaran',                    [App\Http\Controllers\Admin\MataPelajaranController::class, 'index'])->name('mapel.index');
    Route::post('/mata-pelajaran',                   [App\Http\Controllers\Admin\MataPelajaranController::class, 'store'])->name('mapel.store');
    Route::patch('/mata-pelajaran/{id}/toggle-status', [App\Http\Controllers\Admin\MataPelajaranController::class, 'toggleStatus'])->name('mapel.toggle-status');

    // Transaksi
    Route::get('/transaksi',           [App\Http\Controllers\Admin\TransaksiController::class, 'index'])->name('transaksi');
    Route::get('/transaksi/{order}',   [App\Http\Controllers\Admin\TransaksiController::class, 'show'])->name('transaksi.show');
});


/* ================================================================
   API ROUTES (untuk AJAX internal)
=============================================================== */
Route::middleware('throttle:api')->prefix('api')->name('api.')->group(function () {

    Route::get('/tutor/search',          [App\Http\Controllers\Api\TutorSearchController::class, 'search'])->name('tutor.search');
    Route::get('/tutor/{tutor}/jadwal',  [App\Http\Controllers\Api\TutorSearchController::class, 'jadwal'])->name('tutor.jadwal');
    Route::get('/tutor/{tutor}/available-days', [App\Http\Controllers\Api\TutorSearchController::class, 'availableDays'])->name('tutor.available-days');
    Route::get('/mata-pelajaran',        [App\Http\Controllers\Api\MataPelajaranController::class, 'index'])->name('mapel.index');

    Route::middleware('auth')->post('/booking', [App\Http\Controllers\Api\BookingController::class, 'store'])->name('booking.store');

});
