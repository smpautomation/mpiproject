<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class RouteMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $serial;

    public function __construct(array $serial)
    {
        $this->serial = $serial;
        //$this->serial = preg_replace('/[^A-Za-z0-9_\-]/', '', $serial);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'MPI Reports for Approval',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.route-email',
            with: [
                'serial' => $this->serial
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        //need to sanitize the serial number.

        $mail = $this->view('emails.route-email', [
            'serial' => $this->serial
            ])->subject('MPI Reports for Approval');

        return $mail;
    }
}
