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
        try {
            \Log::info("Attempting to render email view...");

            // Force the Blade view to render here inside try/catch
            try {
                $html = view('emails.takefu-email', [
                    'customMessage' => $this->customMessage,
                    'massPro' => $this->massPro
                ])->render();

                \Log::info("View rendered successfully. Length: " . strlen($html));
            } catch (\Throwable $e) {
                \Log::error("View rendering failed: " . $e->getMessage());
                \Log::error($e->getTraceAsString());
                throw $e;
            }

            // Build the email using rendered HTML
            $mail = $this->html($html)->subject($this->massPro . ' Reports');

            $directory = public_path("files/{$this->massPro}");
            \Log::info("Resolved directory path: {$directory}");

            if (!File::exists($directory)) {
                throw new \RuntimeException("The folder for this {$this->massPro} does not exist.");
            }

            $files = File::files($directory);
            \Log::info("Files found in directory {$directory}: " . json_encode(array_map(fn($f) => $f->getFilename(), $files)));

            $pdfFilesAttached = 0;

            foreach ($files as $file) {
                $path = $file->getRealPath();
                $filename = strtolower($file->getFilename());
                \Log::info("Attempting to attach file: {$path}");

                if (str_ends_with($filename, '.pdf') || str_ends_with($filename, '.txt')) {
                    $mail->attach($path);
                    $pdfFilesAttached++;
                }
            }

            \Log::info("Building mail with attachments count: {$pdfFilesAttached}");

            if ($pdfFilesAttached === 0) {
                throw new \RuntimeException("No PDF files found in the folder for this {$this->massPro}.");
            }

            return $mail;

        } catch (\Throwable $e) {
            \Log::error('Error building mail: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            throw $e; // Let Laravel error handler handle it
        }
    }
}
