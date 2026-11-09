<?php

use App\Http\Controllers\Api\MidtransCallbackController;
use App\Http\Controllers\Siswa\PembayaranController;
use Illuminate\Support\Facades\Route;

Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle'])
    ->name('midtrans.callback');

// redirect url when status transaction failed
Route::get('/pemesanan/failed', [PembayaranController::class, 'failed']);
