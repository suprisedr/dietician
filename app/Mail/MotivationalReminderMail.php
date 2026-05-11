<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MotivationalReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  int                 $variant   1 or 2 — alternates each week
     * @param  EmailTemplate|null  $template  Custom template set by the dietician
     */
    public function __construct(
        public Patient          $patient,
        public User             $dietician,
        public int              $variant = 1,
        public ?EmailTemplate   $template = null,
    ) {}

    public function envelope(): Envelope
    {
        $vars = [
            'patient_name'      => $this->patient->name,
            'patient_full_name' => $this->patient->full_name,
            'dietician_name'    => $this->dietician->name,
        ];

        if ($this->template?->subject) {
            $subject = $this->template->resolveSubject($vars);
        } else {
            $defaults = [
                1 => 'Keep Going — Your Meal Plan Is Working 🌿',
                2 => 'Midweek Motivation — Stay On Track 💪',
            ];
            $subject = $defaults[$this->variant] ?? $defaults[1];
        }

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.motivational-reminder',
            with: [
                'patient'   => $this->patient,
                'dietician' => $this->dietician,
                'variant'   => $this->variant,
                'template'  => $this->template,
            ],
        );
    }
}
