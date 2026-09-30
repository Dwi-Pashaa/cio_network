<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GenericNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $mailSubject;
    public string $title;
    public string $messageBody;
    public ?string $actionUrl;
    public ?string $actionText;
    public array $metaDetails;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $subject,
        string $title,
        string $messageBody,
        ?string $actionUrl = null,
        ?string $actionText = null,
        array $metaDetails = []
    ) {
        $this->mailSubject = $subject;
        $this->title = $title;
        $this->messageBody = $messageBody;
        $this->actionUrl = $actionUrl;
        $this->actionText = $actionText;
        $this->metaDetails = $metaDetails;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->mailSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notification',
            with: [
                'title'       => $this->title,
                'messageBody' => $this->messageBody,
                'actionUrl'   => $this->actionUrl,
                'actionText'  => $this->actionText,
                'metadata'    => $this->metaDetails,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
