<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Run the reminder command every hour — each dietician's configured day/hour is checked inside
Schedule::command('reminders:weekly-meal-plan')->hourly();
