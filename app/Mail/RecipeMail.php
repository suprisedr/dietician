<?php

namespace App\Mail;

use App\Models\Patient;
use App\Models\Recipe;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecipeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Recipe  $recipe,
        public Patient $patient,
        public ?string $note = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recipe Recommendation: ' . $this->recipe->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.recipe',
        );
    }
}
