<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewDieticianMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $verifyUrl;
    public string $hpcsaLookupUrl = 'https://hpcsaonline.custhelp.com/app/i_reg_form';

    public function __construct(public User $dietician)
    {
        $token           = hash_hmac('sha256', $dietician->id . $dietician->email, config('app.key'));
        $this->verifyUrl = route('admin.verify-dietician', ['user' => $dietician->id, 'token' => $token]);
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
