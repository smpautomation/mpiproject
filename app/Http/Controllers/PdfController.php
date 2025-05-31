<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function generatePdf(Request $request)
    {
        // Validate incoming data (optional but recommended)
        $validated = $request->validate([
            'chartImage' => 'required|string',
            'data' => 'required|array',
            // Add other expected fields validations
        ]);

        $chartImage = $validated['chartImage'];
        $data = $validated['data'];

        // Prepare data to pass to blade
        $pdfData = [
            'chartImage' => $chartImage,
            'data' => $data,
            // Add other variables you want to use in blade
        ];

        $pdf = Pdf::loadView('pdf.preview-pdf', $pdfData);

        return $pdf->stream('report.pdf');
    }
}
