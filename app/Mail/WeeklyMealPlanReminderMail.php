<?php

namespace App\Mail;

use App\Models\MealPlannerWeek;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyMealPlanReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Patient          $patient,
        public User             $dietician,
        public ?MealPlannerWeek $week = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Weekly Meal Plan Reminder — ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.weekly-meal-plan-reminder',
            with: [
                'patient'   => $this->patient,
                'dietician' => $this->dietician,
                'week'      => $this->week,
            ],
        );
    }
}
