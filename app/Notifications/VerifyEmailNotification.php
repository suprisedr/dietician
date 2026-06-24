<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify Your Email — Mindfulnutrico')
            ->view('emails.verify-email', [
                'userName'        => $notifiable->name ?? 'there',
                'verificationUrl' => $verificationUrl,
                'logoUrl'         => asset('images/mindful-nutrico.png'),
            ]);
    }
}
