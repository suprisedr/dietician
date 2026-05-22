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

class HealthJourneyWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  Patient  $patient
     * @param  User  $dietician
     * @param  EmailTemplate|null  $template  Custom template set by the dietician
     */
    public function __construct(
        public Patient          $patient,
        public User             $dietician,
        public ?EmailTemplate   $template = null,
    ) {}

    public function envelope(): Envelope
    {
        $subject = 'Your Health Journey Starts Here';

        if ($this->template?->subject) {
            $subject = $this->template->resolveSubject([
                'patient_name'      => $this->patient->name,
                'patient_full_name' => $this->patient->full_name,
                'dietician_name'    => $this->dietician->name,
            ]);
        }

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        // Use custom template if available, otherwise use template view
        if ($this->template) {
            return new Content(
                view: 'emails.template-generic',
                with: [
                    'heading'    => $this->template->heading,
                    'body_html'  => $this->template->resolveBody([
                        'patient_name'      => $this->patient->name,
                        'patient_full_name' => $this->patient->full_name,
                        'dietician_name'    => $this->dietician->name,
                    ]),
                    'cta_text'   => $this->template->cta_text,
                    'cta_url'    => $this->template->cta_url,
                ],
            );
        }

        return new Content(
            view: 'emails.health-journey-welcome',
            with: [
                'patient'   => $this->patient,
                'dietician' => $this->dietician,
            ],
        );
    }
}
