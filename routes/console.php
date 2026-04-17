<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Send weekly meal plan reminder emails every Monday at 08:00
Schedule::command('reminders:weekly-meal-plan')->weeklyOn(1, '08:00');
