<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ImportCompletedEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $importData;

    // Retry limit if internet goes down
    public $tries = 5;

    // Wait seconds before retrying (backoff)
    public $backoff = 60; // 1 minute

    /**
     * Create a new message instance.
     */
    public function __construct($importData)
    {
        $this->importData = $importData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ CSV Import Completed Successfully',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.import_summary',
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
