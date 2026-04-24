<?php

namespace App\Observers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectObserver
{
    public function saving(Project $project): void
    {
        if ($project->isDirty(['launch_date', 'amc_durations_month'])) {
            $project->next_amc_date = $project->calculateNextAmcDate();
        }
    }
    /**
     * Handle the Project "creating" event.
     */
    public function creating(Project $project): void
    {
        if (Auth::check()) {
            $project->created_by = Auth::id();
        }
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
        if (Auth::check()) {
            $project->updated_by = Auth::id();
            }
    }

    public function updated(Project $project): void
    {
        //
    }
    
    /**
     * Handle the Project "deleting" event.
     */
    public function deleting(Project $project): void
    {
        // Soft delete - set updated_by for audit trail
        if (Auth::check()) {
        $project->updated_by = Auth::id();
        $project->saveQuietly();
        }
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event - no action needed as it's permanently removed.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}