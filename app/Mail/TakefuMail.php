<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Services\SmpEmailExportService;

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

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->massPro . ' Reports',
        );
    }

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

    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        try {
            Log::info("Rendering email view...");
            $html = view('emails.takefu-email', [
                'customMessage' => $this->customMessage,
                'massPro' => $this->massPro
            ])->render();
            Log::info("View rendered successfully. Length: " . strlen($html));

            $mail = $this->html($html)->subject($this->massPro . ' Reports');

            $folderName = $this->massPro; // keep spaces
            $directory = public_path("files/{$folderName}"); // same as your actual folders
            if (!File::exists($directory)) {
                throw new \RuntimeException("The folder for {$folderName} does not exist.");
            }

            $files = File::files($directory);
            $pdfTxtAttached = 0;

            foreach ($files as $file) {
                $path = $file->getRealPath();
                $filename = strtolower($file->getFilename());

                if (str_ends_with($filename, '.pdf') || str_ends_with($filename, '.txt')) {
                    $mail->attach($path);
                    $pdfTxtAttached++;
                }
            }

            Log::info("PDF/TXT attachments added: {$pdfTxtAttached}");

            // Attach SMP Excel
            try {
                $parts = explode(' ', $this->massPro);
                $furnaceRaw = $parts[0];        // "K40"
                $massProd = $parts[1] ?? '';    // "501ST"
                $furnace = preg_replace('/^([A-Z])(\d+)$/', '$1-$2', strtoupper($furnaceRaw)); // "K-40"

                Log::info("Generating SMP Excel for furnace={$furnace}, massProd={$massProd}");

                $excelPath = app(SmpEmailExportService::class)->export($massProd, $furnace);

                if (File::exists($excelPath)) {
                    $mail->attach($excelPath);
                    Log::info("SMP Excel attached: {$excelPath}");
                } else {
                    Log::warning("SMP Excel file not found at {$excelPath}");
                }
            } catch (\Throwable $e) {
                Log::error("Failed to generate/attach SMP Excel: " . $e->getMessage());
            }

            return $mail;

        } catch (\Throwable $e) {
            Log::error('Error building mail: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }
}
