<?php

namespace App\Observers;

use App\Models\Tutor;

class TutorObserver
{
    /**
     * Handle the tutor "created" event.
     */
    public function created(Tutor $tutor): void
    {
        //
    }

    /**
     * Handle the tutor "updated" event.
     */
    public function updated(Tutor $tutor): void
    {
        if($tutor->wasChanged(['latitude','langitude'])){
            $tutor->cacheDistances()->delete();
        }
    }

    /**
     * Handle the tutor "deleted" event.
     */
    public function deleted(Tutor $tutor): void
    {
        //
    }

    /**
     * Handle the tutor "restored" event.
     */
    public function restored(Tutor $tutor): void
    {
        //
    }

    /**
     * Handle the tutor "force deleted" event.
     */
    public function forceDeleted(Tutor $tutor): void
    {
        //
    }
}
