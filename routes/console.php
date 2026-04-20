<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Inspiring;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the AMC reminder command to run daily
Schedule::command('amc:send-reminders')->daily();

// Schedule the AMC invoice generation command to run daily
Schedule::command('amc:generate-invoices')->daily();