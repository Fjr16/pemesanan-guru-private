<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    public function pending()
    {
        $user  = Auth::user();
        $tutor = $user->tutor;

        return view('pages.tutor.pending', [
            'user'   => $user,
            'tutor'  => $tutor,
            'status' => $tutor->status ?? 'pending',
        ]);
    }
}
