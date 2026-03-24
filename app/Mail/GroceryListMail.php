<?php

namespace App\Mail;

use App\Models\GroceryList;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GroceryListMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public GroceryList $groceryList) {}

    public function envelope(): Envelope
    {
        $listName = $groceryList->name ?? 'Grocery List';

        return new Envelope(
            subject: 'Your Grocery List — ' . ($this->groceryList->name ?: 'Grocery List #' . $this->groceryList->id),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.grocery-list',
            with: [
                'groceryList' => $this->groceryList,
                'byCategory'  => $this->groceryList->items
                    ->sortBy('checked')
                    ->groupBy('category'),
            ],
        );
    }
}
