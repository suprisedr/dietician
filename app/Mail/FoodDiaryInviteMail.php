<?php

namespace App\Mail;

use App\Models\FoodDiary;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FoodDiaryInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public FoodDiary $diary,
        public User      $dietician,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Daily Food Diary — ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.food-diary-invite',
            with: [
                'diary'     => $this->diary,
                'dietician' => $this->dietician,
                'link'      => route('food-diary.patient-show', $this->diary->patient_token),
            ],
        );
    }
}
