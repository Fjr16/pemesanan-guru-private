<?php

use App\Http\Controllers\Api\MidtransCallbackController;
use Illuminate\Support\Facades\Route;

Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle'])
    ->name('midtrans.callback');
