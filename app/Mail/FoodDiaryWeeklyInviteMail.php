<?php

namespace App\Mail;

use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FoodDiaryWeeklyInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array   $entries,
        public Carbon  $weekStart,
        public User    $dietician,
        public Patient $patient,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Weekly Food Diary (' . $this->weekStart->format('d M') . ' – ' . $this->weekStart->copy()->endOfWeek()->format('d M Y') . ') — ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.food-diary-weekly-invite',
            with: [
                'entries'   => $this->entries,
                'weekStart' => $this->weekStart,
                'weekEnd'   => $this->weekStart->copy()->endOfWeek(),
                'dietician' => $this->dietician,
                'patient'   => $this->patient,
            ],
        );
    }
}
