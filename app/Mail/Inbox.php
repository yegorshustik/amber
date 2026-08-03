<?php

namespace App\Mail;

use App\Models\Inbox\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Inbox extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Application $application,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from : new Address(
                address: env('MAIL_FROM_ADDRESS'),
            ),
            subject: __('inbox.subject', ['form' => $this->application->form->title]),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'amber::mail.inbox',
            with: [
                'subject' => $this->subject,
                'inbox' => $this->application,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
