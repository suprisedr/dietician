<?php

namespace App\Mail;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PatientConsentMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Patient $patient,
        public User    $dietician,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Consent to Capture and Process Personal Health Information',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.patient-consent');
    }
}
