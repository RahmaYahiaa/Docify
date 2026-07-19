<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GenericMail extends Mailable
{
    use Queueable, SerializesModels;


    public $title;
    public $body;
    public $data;


    public function __construct(
        string $title,
        string $body,
        array $data = []
    ) {
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
    }



    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->title,
        );
    }



    public function content(): Content
    {
        return new Content(
            view: 'emails.generic',
            with: [
                'title' => $this->title,
                'body'  => $this->body,
                'data'  => $this->data,
            ]
        );
    }



    public function attachments(): array
    {
        return [];
    }
}
