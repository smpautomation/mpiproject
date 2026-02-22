<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Services\SmpEmailExportService;
use App\Models\MassProduction;

class TakefuMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $massPro;
    protected $customMessage;
    protected array $additionalFiles;

    public function __construct(string $massPro, $customMessage = '', array $additionalFiles = [])
    {
        $this->massPro = $massPro;
        $this->customMessage = $customMessage;
        $this->additionalFiles = $additionalFiles;
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

            $this->verifyFormatType($this->massPro);

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

            // --- Attach additional uploaded Excel files ---
            $uploadedAttached = 0;
            foreach ($this->additionalFiles as $file) {
                if ($file instanceof \Illuminate\Http\UploadedFile) {
                    $mail->attach($file->getRealPath(), [
                        'as' => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType(),
                    ]);
                    $uploadedAttached++;
                }
            }
            Log::info("Additional uploaded Excel files attached: {$uploadedAttached}");

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

    public function verifyFormatType(string $masspro)
    {
        $parts = preg_split('/\s+/', trim($masspro));

        $rawFurnace = $parts[0] ?? '';
        $massProd   = $parts[1] ?? '';

        // Turn K40 -> K-40, K63 -> K-63, etc
        $furnace = preg_replace('/^([A-Z]+)(\d+)$/', '$1-$2', strtoupper($rawFurnace));

        // 1. Fetch MassProduction row
        $mp = MassProduction::where('furnace', $furnace)
            ->where('mass_prod', $massProd)
            ->first();

        if (!$mp) {
            throw new \RuntimeException("MassProduction not found for {$furnace} {$massProd}");
        }

        // 2. Define layer map
        $layers = [
            '1'   => 'layer_1',
            '2'   => 'layer_2',
            '3'   => 'layer_3',
            '4'   => 'layer_4',
            '5'   => 'layer_5',
            '6'   => 'layer_6',
            '7'   => 'layer_7',
            '8'   => 'layer_8',
            '9'   => 'layer_9',
            '9.5' => 'layer_9_5',
        ];

        $flaggedLayers = [];

        // 3. Flag layers that exist (not null / not empty)
        foreach ($layers as $layerNo => $column) {
            $value = $mp->$column ?? null;

            if ($value !== null && trim((string)$value) !== '') {
                $flaggedLayers[$layerNo] = $column;
            }
        }

        // 4. Validate format_type for flagged layers
        foreach ($flaggedLayers as $layerNo => $column) {
            $formatColumn = $column . '_format_type'; // e.g. layer_1_format_type

            $formatValue = $mp->$formatColumn ?? null;

            if ($formatValue === null || trim((string)$formatValue) === '') {
                throw new \RuntimeException("Format type missing for layer {$layerNo} ({$formatColumn})");
            }
        }

        // 5. Passed
        Log::info("Format type validation PASSED for {$furnace} {$massProd}", [
            'layers' => array_keys($flaggedLayers),
        ]);
    }

}
