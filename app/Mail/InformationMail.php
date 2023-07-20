<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InformationMail extends Mailable
{
    use Queueable, SerializesModels;

    private $mail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($maildata)
    {
        $this->mail = $maildata;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Information Mail - Ex;braiN Education',
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
            view: 'mail.InformationMail',
            with: [
                'logo' => $this->mail['logo'],
                'sitename' => $this->mail['sitename'],
                'facebook' => $this->mail['facebook'],
                'youtube1' => $this->mail['youtube1'],
                'youtube2' => $this->mail['youtube2'],
                'youtube3' => $this->mail['youtube3'],
                'address' => $this->mail['address'],
                'email' => $this->mail['email'],
                'phone' => $this->mail['phone'],
                'copyright' => $this->mail['copyright'],
                'name' => $this->mail['name'],
                'title' => $this->mail['title'],
                'description' => $this->mail['description']
            ]
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
