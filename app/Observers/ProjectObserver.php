<?php

namespace App\Observers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectObserver
{
    /**
     * Handle the Project "creating" event.
     */
    public function creating(Project $project): void
    {
        $project->created_by = Auth::id();
        $project->calculateNextAmcDate();
    }

    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "updating" event.
     */
    public function updating(Project $project): void
    {
        $project->updated_by = Auth::id();

        if ($project->isDirty(['launch_date', 'amc_durations_month'])) {
            $project->calculateNextAmcDate();
        }
    }

    public function updated(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}
