<?php

use Illuminate\Support\Facades\Schedule;

/**
 * Schedule the unified AMC automation command to run daily at midnight.
 * * Logic:
 * - Identifies projects due in 7 days and sends reminders.
 * - Identifies projects due today and generates automated invoices.
 */
Schedule::command('amc:generate-invoices')->dailyAt('00:00');