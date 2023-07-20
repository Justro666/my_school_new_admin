<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Date;

class ReportCard extends Mailable
{
    use Queueable, SerializesModels;

    private $mail;
    private $month;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($maintable,$month)
    {
        $this->mail = $maintable;
        $this->month = $month;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        $date = date("M");
        return new Envelope(
            subject: 'Report Card for '. $date,
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
            view: 'mail.ReportCard',
            with: [
                'maintable' => $this->mail,
                'month' => $this->month
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
