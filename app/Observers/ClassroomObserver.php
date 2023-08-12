<?php

namespace App\Observers;

use App\Models\ClassroomModel;
use Illuminate\Support\Str;

class ClassroomObserver
{
    /**
     * Handle the ClassroomModel "created" event.
     */
    public function creating(ClassroomModel $classroom): void
    {
        $classroom->code = Str::random(8);
    }

    /**
     * Handle the ClassroomModel "updated" event.
     */
    public function updated(ClassroomModel $classroom): void
    {
        //
    }

    /**
     * Handle the ClassroomModel "deleted" event.
     */
    public function deleted(ClassroomModel $classroom): void
    {
        if($classroom->isForceDeleting()){
            return;
        }
        $classroom->status = 'deleted';
        $classroom->save();
    }

    /**
     * Handle the ClassroomModel "restored" event.
     */
    public function restored(ClassroomModel $classroom): void
    {
        $classroom->status = 'active';
        $classroom->save();
    }

    /**
     * Handle the ClassroomModel "force deleted" event.
     */
    public function forceDeleted(ClassroomModel $classroom): void
    {
        $classroom->deleteCoverImage($classroom->cover_image_path);
    }
}
