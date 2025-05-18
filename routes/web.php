<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PdfController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Inertia\Inertia;

Route::get('/', [FrontendController::class,'index'])->name('homePage');
Route::get('/view', [FrontendController::class,'viewList'])->name('viewList');
Route::get('/manage', [FrontendController::class,'manage'])->name('manage');
Route::get('/inspection', [FrontendController::class,'inspection'])->name('inspection');
Route::get('/reports', [FrontendController::class,'reports'])->name('reports');
Route::get('/approval', [FrontendController::class,'approval'])->name('approval');
Route::get('/settings', [FrontendController::class,'settings'])->name('settings');
Route::get('/data_ins', [FrontendController::class,'data_ins'])->name('data_ins');
Route::get('/create_pdf', [FrontendController::class,'generatePdf'])->name('create_pdf');
Route::get('/sec_additional', [FrontendController::class,'sec_additional'])->name('sec_additional');
Route::get('/email_form', [FrontendController::class,'emailForm'])->name('email');

Route::get('/generate-pdf', [PdfController::class, 'generate']);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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

Route::post('/upload-pdf', function (Request $request) { //Saves to folder /files/(massprodnumber)
    $request->validate([
        'massProd' => 'required|string',
        'pdf' => 'required|file|mimes:pdf|max:51200' // 50 MB
    ]);

    $massProd = $request->input('massProd');
    $file = $request->file('pdf');

    // Define target path inside /public/files/{massProd}
    $destinationPath = public_path("files/$massProd");

    // Ensure folder exists
    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }

    // Store the uploaded file
    $fileName = $file->getClientOriginalName();
    $file->move($destinationPath, $fileName);

    return response()->json([
        'message' => 'PDF uploaded successfully.',
        'path' => "files/$massProd/$fileName"
    ]);
});

Route::get('/export-data1/{furnace_no}', [ExportController::class, 'exportData1'])->name('exportData1');
Route::get('/export-data3/{furnace_no}', [ExportController::class, 'exportData3'])->name('exportData3');
Route::view('/test-export', 'test');

Route::get('/generate-pdf', function () {
    return Pdf::loadView('preview-pdf')->download('example.pdf');
});

require __DIR__.'/auth.php';
