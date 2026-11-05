<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * A message sent from the public contact form to the organisation.
 * Plain text only, so nothing the visitor typed is rendered as HTML.
 */
class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    /** @param array{first_name:string,last_name:string,email:string,phone:?string,reason:string,message:string} $data */
    public function __construct(public array $data)
    {
    }

    public function envelope(): Envelope
    {
        $name = trim($this->data['first_name'].' '.$this->data['last_name']);

        return new Envelope(
            replyTo: [new Address($this->data['email'], $name)],
            subject: 'Website contact form: '.$this->reasonLabel().' — '.$name,
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.contact-message',
            with: ['reasonLabel' => $this->reasonLabel()],
        );
    }

    private function reasonLabel(): string
    {
        $reason = config('society.contact_reasons.'.$this->data['reason']);

        return $reason ? "{$reason['en']} / {$reason['ar']}" : $this->data['reason'];
    }
}
