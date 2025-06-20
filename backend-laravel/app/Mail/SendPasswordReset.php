<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPasswordReset extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject = 'Password reset';
    public $fromAddress = '';
    public $fromName = '';
    public $user = null;
    public $url = '';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(String $url, Object $user)
    {
        $mailConfig = config('mail.from');
        $this->fromName = $mailConfig['name'];
        $this->fromAddress = $mailConfig['address'];
        $this->user = $user;
        $this->url = $url;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address($this->fromAddress, $this->fromName),
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'email.password-reset',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
