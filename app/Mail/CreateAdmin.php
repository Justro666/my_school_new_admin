<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CreateAdmin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $mail;

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
            subject: 'Admin Account Creation Successful',
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
            view: 'mail.AdminAccountCreate',
            with: [
                'email'=>$this->mail['email'],
                'password'=>$this->mail['password'],
                'route'=>$this->mail['route'],
                'logo' => $this->mail['logo'],
                'sitename' => $this->mail['sitename'],
                'facebook_link' => $this->mail['facebook_link'],
                'youtube_link1' => $this->mail['youtube_link1'],
                'youtube_link2' => $this->mail['youtube_link2'],
                'phone'=>$this->mail['phone']
            ]
        );
        dd($this->mail);
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
