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
    //Check if the AMC is due today or in 7 days
    $isToday = $this->project->next_amc_date->isToday();

    // Set the label and message based on the AMC date
    if ($isToday) {
        $label   = "Due Today";
        $message = "is due TODAY";
    } else {
        $label   = "Reminder (7 Days)";
        $message = "will be due in 7 days";
    }
    // Build the email message
    return (new MailMessage)
        ->subject("AMC {$label}: {$this->project->project_name}")
        ->greeting('Hello!')
        ->line("This is a reminder that the AMC for '{$this->project->project_name}' {$message}.")
        ->line('Next AMC Date: ' . $this->project->next_amc_date->format('Y-m-d'))
        ->action('View Project Details', url('/projects/' . $this->project->id));
    }
}