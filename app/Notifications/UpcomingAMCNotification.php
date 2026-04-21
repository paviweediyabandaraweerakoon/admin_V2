<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification class to alert users about upcoming AMC dates.
 * Sends an email notification with project details and a link to the project page.
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
        $date = $this->project->next_amc_date 
                ? $this->project->next_amc_date->format('Y-m-d') 
                : 'Not Set';

        return (new MailMessage)
            ->subject('Upcoming AMC Alert: ' . $this->project->project_name)
            ->greeting('Hello!')
            ->line('This is a reminder regarding the upcoming AMC for the project: ' . $this->project->project_name)
            ->line('Next AMC Date: ' . $date)
            ->action('View Project Details', url('/projects/' . $this->project->id))
            ->line('Please ensure all requirements are met for the upcoming invoice generation.');
    }
}