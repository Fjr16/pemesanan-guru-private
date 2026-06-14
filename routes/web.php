<?php

use App\Http\Controllers\Admin\SiswaController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

/* ================================================================
   PUBLIC ROUTES
================================================================ */

// Homepage
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Profil & jadwal tutor (publik)
// Route::get('/tutor/{tutor}',        [App\Http\Controllers\TutorController::class, 'show'])->name('tutor.show');
// Route::get('/tutor/{tutor}/booking',[App\Http\Controllers\TutorController::class, 'booking'])->name('tutor.booking');


/* ================================================================
   AUTH ROUTES (guest only)
================================================================ */
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login',  [App\Http\Controllers\Auth\LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);

    // Register
    Route::get('/register',  [App\Http\Controllers\Auth\RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

    // Lupa password
    // Route::get('/forgot-password',  [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showForm'])->name('password.request');
    // Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    // Route::get('/reset-password/{token}',  [App\Http\Controllers\Auth\ResetPasswordController::class, 'showForm'])->name('password.reset');
    // Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

});

// Logout (auth)
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/* ================================================================
   SISWA ROUTES  (role: siswa)
================================================================ */
// Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {

//     Route::get('/dashboard',  [App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard');

//     // Pemesanan
//     Route::get('/pemesanan',           [App\Http\Controllers\Siswa\PemesananController::class, 'index'])->name('pemesanan');
//     Route::post('/pemesanan',          [App\Http\Controllers\Siswa\PemesananController::class, 'store'])->name('pemesanan.store');
//     Route::get('/pemesanan/{booking}', [App\Http\Controllers\Siswa\PemesananController::class, 'show'])->name('pemesanan.show');
//     Route::delete('/pemesanan/{booking}/cancel', [App\Http\Controllers\Siswa\PemesananController::class, 'cancel'])->name('pemesanan.cancel');

//     // Pembayaran
//     Route::get('/pemesanan/{booking}/bayar',    [App\Http\Controllers\Siswa\PembayaranController::class, 'show'])->name('pembayaran.show');
//     Route::post('/pemesanan/{booking}/bayar',   [App\Http\Controllers\Siswa\PembayaranController::class, 'process'])->name('pembayaran.process');
//     Route::get('/pemesanan/{booking}/sukses',   [App\Http\Controllers\Siswa\PembayaranController::class, 'success'])->name('pembayaran.sukses');

//     // Profil siswa
//     Route::get('/profil',  [App\Http\Controllers\Siswa\ProfilController::class, 'show'])->name('profil');
//     Route::put('/profil',  [App\Http\Controllers\Siswa\ProfilController::class, 'update'])->name('profil.update');

//     // Ulasan
//     Route::post('/ulasan/{booking}', [App\Http\Controllers\Siswa\UlasanController::class, 'store'])->name('ulasan.store');

// });


/* ================================================================
   TUTOR ROUTES  (role: tutor)
================================================================ */
// Route::middleware(['auth', 'role:tutor'])->prefix('tutor-panel')->name('tutor.')->group(function () {

//     Route::get('/dashboard', [App\Http\Controllers\Tutor\DashboardController::class, 'index'])->name('dashboard');

//     // Jadwal ketersediaan
//     Route::get('/jadwal',         [App\Http\Controllers\Tutor\JadwalController::class, 'index'])->name('jadwal');
//     Route::post('/jadwal',        [App\Http\Controllers\Tutor\JadwalController::class, 'store'])->name('jadwal.store');
//     Route::delete('/jadwal/{id}', [App\Http\Controllers\Tutor\JadwalController::class, 'destroy'])->name('jadwal.destroy');

//     // Booking request dari siswa
//     Route::get('/pemesanan',                          [App\Http\Controllers\Tutor\PemesananController::class, 'index'])->name('pemesanan');
//     Route::post('/pemesanan/{booking}/terima',        [App\Http\Controllers\Tutor\PemesananController::class, 'terima'])->name('pemesanan.terima');
//     Route::post('/pemesanan/{booking}/tolak',         [App\Http\Controllers\Tutor\PemesananController::class, 'tolak'])->name('pemesanan.tolak');
//     Route::post('/pemesanan/{booking}/selesai',       [App\Http\Controllers\Tutor\PemesananController::class, 'selesai'])->name('pemesanan.selesai');

//     // Profil tutor
//     Route::get('/profil',  [App\Http\Controllers\Tutor\ProfilController::class, 'show'])->name('profil');
//     Route::put('/profil',  [App\Http\Controllers\Tutor\ProfilController::class, 'update'])->name('profil.update');

//     // Riwayat mengajar
//     Route::get('/riwayat', [App\Http\Controllers\Tutor\RiwayatController::class, 'index'])->name('riwayat');

// });


/* ================================================================
   ADMIN ROUTES  (role: admin)
================================================================ */
// Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Tutor
    Route::get('/tutor',                      [App\Http\Controllers\Admin\TutorController::class, 'index'])->name('tutor');
    Route::post('/tutor/{tutor}/verifikasi',  [App\Http\Controllers\Admin\TutorController::class, 'verifikasi'])->name('tutor.verifikasi');
    Route::post('/tutor/{tutor}/tolak',       [App\Http\Controllers\Admin\TutorController::class, 'tolak'])->name('tutor.tolak');
    Route::delete('/tutor/{tutor}',           [App\Http\Controllers\Admin\TutorController::class, 'destroy'])->name('tutor.destroy');

    // Manajemen Siswa
    Route::get('/siswa',           [SiswaController::class, 'index'])->name('siswa');
    Route::delete('/siswa/{user}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

    // Mata Pelajaran (CRUD)
    Route::get('/mata-pelajaran', [App\Http\Controllers\Admin\MataPelajaranController::class, 'index'])->name('mapel');

    // Semua transaksi / booking
    Route::get('/transaksi',           [App\Http\Controllers\Admin\TransaksiController::class, 'index'])->name('transaksi');
    Route::get('/transaksi/{booking}', [App\Http\Controllers\Admin\TransaksiController::class, 'show'])->name('transaksi.show');

    // Laporan / rekap
    Route::get('/laporan', [App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan');

// });


/* ================================================================
   API ROUTES (untuk AJAX internal — tanpa prefix /api di Sanctum)
   Diletakkan di web.php agar langsung pakai session auth
================================================================ */
Route::prefix('api')->name('api.')->group(function () {

    // Tutor search — publik
    Route::get('/tutor/search',   [App\Http\Controllers\Api\TutorSearchController::class, 'search'])->name('tutor.search');
    Route::get('/tutor/{tutor}/jadwal', [App\Http\Controllers\Api\TutorSearchController::class, 'jadwal'])->name('tutor.jadwal');

    // Mata pelajaran list — publik
    Route::get('/mata-pelajaran', [App\Http\Controllers\Api\MataPelajaranController::class, 'index'])->name('mapel.index');

    // Notifikasi — auth
    // Route::middleware('auth')->group(function () {
    //     Route::get('/notifications',          [App\Http\Controllers\Api\NotificationController::class, 'index'])->name('notifications.index');
    //     Route::post('/notifications/{id}/read',[App\Http\Controllers\Api\NotificationController::class, 'markRead'])->name('notifications.read');
    // });

    // // Booking via AJAX — siswa auth
    // Route::middleware(['auth', 'role:siswa'])->group(function () {
    //     Route::post('/booking', [App\Http\Controllers\Api\BookingController::class, 'store'])->name('booking.store');
    // });

    // // Konfirmasi booking via AJAX — tutor auth
    // Route::middleware(['auth', 'role:tutor'])->group(function () {
    //     Route::post('/booking/{booking}/terima', [App\Http\Controllers\Api\BookingController::class, 'terima'])->name('booking.terima');
    //     Route::post('/booking/{booking}/tolak',  [App\Http\Controllers\Api\BookingController::class, 'tolak'])->name('booking.tolak');
    // });

});
