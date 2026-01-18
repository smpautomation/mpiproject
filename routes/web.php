<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\BackEndPdfController;
use App\Http\Controllers\ChartUploadController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Inertia\Inertia;

Route::get('/mpi-online-system', [FrontendController::class,'index'])->name('homePage');
Route::get('/', function(){
    return redirect('/mpi-online-system');
});
Route::get('/view', [FrontendController::class,'viewList'])->name('viewList');
Route::get('/automation', [FrontendController::class,'automation'])->name('automation');
Route::get('/initial_process', [FrontendController::class,'initial_process'])->name('initial_process');
Route::get('/massprod', [FrontendController::class,'massprod'])->name('massprod');
Route::get('/control_sheet', [FrontendController::class,'controlSheet'])->name('control_sheet');
Route::get('/htgraph', [FrontendController::class,'htgraph'])->name('htgraph');
Route::get('/ht_graph_patterns', [FrontendController::class,'heatTreatmentGraphPatterns'])->name('htgraph_patterns');
Route::get('/smpdata', [FrontendController::class,'smpData'])->name('smpdata');
Route::get('/mass_prod_monitoring',[FrontendController::class, 'massProdMonitoring'])->name('mass_prod_monitoring');
Route::get('/heat_treatment', [FrontendController::class, 'heatTreatment'])->name('heat_treatment');
Route::get('/ht_breaklots', [FrontendController::class, 'breakLots'])->name('ht_breaklots');
Route::get('/manage', [FrontendController::class,'manage'])->name('manage');
Route::get('/inspection', [FrontendController::class,'inspection'])->name('inspection');
Route::get('/reports', [FrontendController::class,'reports'])->name('reports');
Route::get('/approval', [FrontendController::class,'approval'])->name('approval');
Route::get('/admin', [FrontendController::class,'admin'])->name('admin');
Route::get('/data_ins', [FrontendController::class,'data_ins'])->name('data_ins');
Route::get('/create_pdf', [FrontendController::class,'generatePdf'])->name('create_pdf');
Route::get('/sec_additional', [FrontendController::class,'sec_additional'])->name('sec_additional');
Route::get('/email_form', [FrontendController::class,'emailForm'])->name('email');
Route::get('/email_form_manual', [FrontendController::class,'emailFormManual'])->name('email_form_manual');
Route::get('/mias_factor', [FrontendController::class,'mias_factor'])->name('mias_factor');
Route::get('/instructions', [FrontendController::class,'instructions'])->name('instructions');
Route::get('/coating', [FrontendController::class,'coating'])->name('coating');
Route::get('/furnace', [FrontendController::class,'furnace'])->name('furnace');
Route::get('/second_gbdp_models', [FrontendController::class,'second_gbdp_models'])->name('second_gbdp_models');
Route::get('/view_second_gbdp', [FrontendController::class,'viewSecondGbdp'])->name('view_second_gbdp');
Route::get('/second_heat_treatment', [FrontendController::class,'second_heat_treatment'])->name('second_heat_treatment');
Route::get('/film_pasting', [FrontendController::class,'filmPasting'])->name('film_pasting');
Route::get('/approval_checked', [FrontendController::class,'approval_checked'])->name('approval_checked');
Route::get('/approval_prepared', [FrontendController::class,'approval_prepared'])->name('approval_prepared');

Route::get('/generate-pdf', [PdfController::class, 'generate']);


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('/send-test-email', function(Request $request){

//     $validated = $request->validate([
//         'serial' => 'required|alpha_num',
//         'emails' => 'required|string',
//         'message' => 'nullable|string|max:5000',
//     ]);

//     $emailList = array_map('trim', explode(',', $validated['emails']));
//     $username = 'TestUser';

//     $customMessage = strip_tags($validated['message'] ?? '', '<p><br><b><i><strong><em><ul><ol><li>');

//     Mail::to($emailList)->send(new TestMail($username, $validated['serial'], $customMessage));
//     return 'Test Email Sent';
// });

Route::post('/upload-pdf', function (Request $request) {
    Log::info('Incoming PDF upload request:', [
        'massProd' => $request->input('massProd'),
        'originalFileName' => $request->file('pdf')->getClientOriginalName(),
    ]);

    $request->validate([
        'massProd' => 'required|string',
        'pdf' => 'required|file|mimes:pdf|max:102400',
    ]);

    $rawMassProd = $request->input('massProd');
    $massProd = preg_replace('/[^A-Za-z0-9\-\s#]/', '_', $rawMassProd);

    $originalName = $request->file('pdf')->getClientOriginalName();
    $baseName = pathinfo($originalName, PATHINFO_FILENAME);

    // Allow alphanumeric, space, hyphen, parentheses, and #
    $safeBaseName = preg_replace('/[^A-Za-z0-9\-\s\(\)#]/', '_', $baseName);

    // Replace slashes and whitespace sequences with underscore
    $safeBaseName = preg_replace('/[\/\s]+/', '_', $safeBaseName);

    $fileName = $safeBaseName . '.pdf';

    Log::info('Sanitized values:', [
        'sanitizedMassProd' => $massProd,
        'sanitizedFileName' => $fileName,
        'destinationPath' => public_path("files/$massProd"),
    ]);

    $destinationPath = public_path("files/$massProd");

    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }

    $request->file('pdf')->move($destinationPath, $fileName);

    return response()->json([
        'message' => 'PDF uploaded successfully.',
        'path' => "files/$massProd/$fileName",
    ]);
});

//Route::get('/export-data1/{furnace_no}', [ExportController::class, 'exportData1'])->name('exportData1');
//Route::get('/export-data2/{furnace_no}', [ExportController::class, 'exportData2'])->name('exportData2');
//Route::get('/export-data3/{furnace_no}', [ExportController::class, 'exportData3'])->name('exportData3');
Route::view('/test-export', 'test');

Route::get('/generate-pdf', function () {
    return Pdf::loadView('preview-pdf')->download('example.pdf');
});

Route::get('/reports/{serial}/pdf', [BackEndPdfController::class, 'generateAndMerge'])
    ->name('reports.pdf')
    ->where('serial', '[A-Za-z0-9\-\s#]+');

Route::post('/upload-chart', [ChartUploadController::class, 'store']);
Route::post('/upload-chart-sec', [ChartUploadController::class, 'storeSecondary']);

Route::get('/debug-export', function () {
    return app(\App\Services\TxtExportService::class)->exportData1('K40', '934TH');
});



require __DIR__.'/auth.php';
