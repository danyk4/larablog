<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewPostEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $data)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Congrats on the new post!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'new-post-email',
            with: ['title' => $this->data['title'], 'name' => $this->data['name']]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
