<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use iio\libmergepdf\Merger;

class BackEndPdfController extends Controller
{


public function generateAndMerge($serial){

    $data = [
        'serial' => $serial,
    ];

    $portrait = Pdf::loadView('pdf.report_page1_portrait', $data)
        ->setPaper('a4', 'portrait')
        ->output();

    $landscape = Pdf::loadView('pdf.report_page2_landscape', $data)
        ->setPaper('a4', 'landscape')
        ->output();

    $merger = new Merger();
    $merger->addRaw($portrait);
    $merger->addRaw($landscape);
    $mergedPdf = $merger->merge();

    return response($mergedPdf,200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="report.pdf"');

    }
}