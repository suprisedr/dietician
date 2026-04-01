<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class AdminNewDieticianMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $verifyUrl;
    public string $hpcsaLookupUrl = 'https://isystems.hpcsa.co.za/iregister/';

    public function __construct(public User $dietician)
    {
        $this->verifyUrl = URL::signedRoute('admin.verify-dietician', ['user' => $dietician->id]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Dietician Registration — HPCSA Verification Required',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.admin-new-dietician');
    }
}
