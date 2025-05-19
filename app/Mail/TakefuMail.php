<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class TakefuMail extends Mailable
{
    use Queueable, SerializesModels;
    public string $massPro;
    protected $customMessage;

    public function __construct(string $massPro, $customMessage = '')
    {
        $this->massPro = $massPro;
        $this->customMessage = $customMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->massPro . ' Reports',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.takefu-email',
            with: [
                'massPro' => $this->massPro,
                'customMessage' => $this->customMessage
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

        $mail = $this->view('emails.takefu-email', [
            'customMessage' => $this->customMessage,
            'massPro' => $this->massPro
            ])->subject($this->massPro . ' Reports');
        $directory = public_path("files/{$this->massPro}");
        if (!File::exists($directory)) {
            throw new \RuntimeException("The folder for this {$this->massPro} does not exist.");
        }
        $files = File::files($directory);
        $pdfFilesAttached = 0;

        foreach ($files as $file) {
            $path = $file->getRealPath();
            $filename = strtolower($file->getFilename());
            if (str_ends_with(strtolower($filename), '.pdf') || str_ends_with(strtolower($filename), '.txt')) {
                $mail->attach($path);
                $pdfFilesAttached++;
            }
        }
        // Optionally throw an error or skip sending if no PDFs found
        if ($pdfFilesAttached === 0) {
            throw new \RuntimeException("No PDF files found in the folder for this {$this->massPro}.");
        }
        return $mail;
    }
}
