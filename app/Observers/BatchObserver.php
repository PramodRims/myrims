<?php

namespace App\Observers;

use App\Models\Batch;
use Carbon\Carbon;

class BatchObserver
{
    /**
     * Handle the Batch "created" event.
     */
    public function created(Batch $batch): void
    {
        $batch->name = 'BATCH-' . $batch->id . '-' . Carbon::parse($batch->start_date)->format('d-m-Y') . '-' . Carbon::parse($batch->end_date)->format('d-m-Y');

        // Save the updated name without triggering events again
        $batch->saveQuietly();
    }

    /**
     * Handle the Batch "updated" event.
     */
    public function updated(Batch $batch): void
    {
        //
    }

    /**
     * Handle the Batch "deleted" event.
     */
    public function deleted(Batch $batch): void
    {
        //
    }

    /**
     * Handle the Batch "restored" event.
     */
    public function restored(Batch $batch): void
    {
        //
    }

    /**
     * Handle the Batch "force deleted" event.
     */
    public function forceDeleted(Batch $batch): void
    {
        //
    }
}
