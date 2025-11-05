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
use Illuminate\Support\Facades\Log;

class TakefuMail_Manual extends Mailable
{
    use Queueable, SerializesModels;
    public string $massPro;
    protected $customMessage;
    protected array $selectedFiles;

    public function __construct(string $massPro, string $customMessage = '', array $selectedFiles = [])
    {
        $this->massPro = $massPro;
        $this->customMessage = $customMessage;
        $this->selectedFiles = $selectedFiles;
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
            view: 'emails.takefu-email-manual',
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
            Log::info("Attempting to render email view...");

            // Force the Blade view to render here inside try/catch
            try {
                $html = view('emails.takefu-email-manual', [
                    'customMessage' => $this->customMessage,
                    'massPro' => $this->massPro
                ])->render();

                Log::info("View rendered successfully. Length: " . strlen($html));
            } catch (\Throwable $e) {
                Log::error("View rendering failed: " . $e->getMessage());
                Log::error($e->getTraceAsString());
                throw $e;
            }

            // Build the email using rendered HTML
            $mail = $this->html($html)->subject($this->massPro . ' Reports');

            $directory = public_path("files/{$this->massPro}");

            if (!File::exists($directory)) {
                throw new \RuntimeException("The folder for this {$this->massPro} does not exist.");
            }

            $pdfFilesAttached = 0;

            foreach ($this->selectedFiles as $fileName) {
                $filePath = $directory . DIRECTORY_SEPARATOR . $fileName;
                if (File::exists($filePath)) {
                    $mail->attach($filePath);
                    $pdfFilesAttached++;
                } else {
                    Log::warning("Selected file does not exist: {$filePath}");
                }
            }

            if ($pdfFilesAttached === 0) {
                throw new \RuntimeException("No valid files selected for {$this->massPro}.");
            }

            return $mail;

        } catch (\Throwable $e) {
            Log::error('Error building mail: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e; // Let Laravel error handler handle it
        }
    }
}
