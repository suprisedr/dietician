<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountUnlockedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $dietician) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Mindfulnutrico Account Has Been Activated',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.account-unlocked');
    }
}
