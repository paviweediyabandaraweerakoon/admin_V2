<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification class to alert users about upcoming AMC dates.
 *
 * This notification is triggered when a project's next AMC date is approaching (e.g., within 7 days).
 * It sends an email to the relevant users with details about the project and the upcoming AMC.
 */

class UpcomingAMCNotification extends Notification
{
    use Queueable;

    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    // Define the email representation of the notification
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Upcoming AMC Alert: ' . $this->project->project_name)
            ->greeting('Hello!')
            ->line('This is a reminder that the AMC for the project "' . $this->project->project_name . '" is due in 7 days.')
            ->line('Next AMC Date: ' . $this->project->next_amc_date->format('Y-m-d'))
            ->action('View Project Details', url('/projects/' . $this->project->id))
            ->line('Please check if everything is ready for invoice generation.');
    }
}