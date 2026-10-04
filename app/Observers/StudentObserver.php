<?php

namespace App\Observers;

use App\Models\Student;

class StudentObserver
{
    /**
     * Handle the student "created" event.
     */
    public function created(Student $student): void
    {
        //
    }

    /**
     * Handle the student "updated" event.
     */
    public function updated(Student $student): void
    {
        if($student->wasChanged(['latitude_dom','langitude_dom'])){
           $student->cacheDistances()->delete();
        }
    }

    /**
     * Handle the student "deleted" event.
     */
    public function deleted(Student $student): void
    {
        //
    }

    /**
     * Handle the student "restored" event.
     */
    public function restored(Student $student): void
    {
        //
    }

    /**
     * Handle the student "force deleted" event.
     */
    public function forceDeleted(Student $student): void
    {
        //
    }
}
