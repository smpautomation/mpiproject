<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TPMDataController;
use App\Http\Controllers\FurnaceDataController;
use App\Http\Controllers\LayerDataController;
use App\Http\Controllers\InspectionDataController;
use App\Http\Controllers\ReportDataController;
use App\Http\Controllers\ApproversController;
use App\Http\Controllers\BhModelController;
use App\Http\Controllers\CoatingController;
use App\Http\Controllers\CpkIhcModelController;
use App\Http\Controllers\DataInstructionsController;
use App\Http\Controllers\GxModelController;
use App\Http\Controllers\HeatTreatmentController;
use App\Http\Controllers\MieGxDataInstructionsController;
use App\Http\Controllers\MieGxDataInstructionsAggregateController;
use App\Http\Controllers\NormalSecAdditionalsController;
use App\Http\Controllers\StandardDataController;
use App\Http\Controllers\MiasFactorController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\RobModelController;
use App\Http\Controllers\TtmncModelController;
use App\Http\Controllers\UserLogController;
use App\Http\Controllers\VtModelController;
use App\Http\Controllers\BackEndPdfController;
use App\Http\Controllers\FilmPastingDataController;
use App\Http\Controllers\GbdpSecondCoatingController;
use App\Http\Controllers\HtGraphPatternsController;
use App\Http\Controllers\MassProductionController;
use App\Http\Controllers\SecondGBDPController;
use App\Http\Controllers\SecondGbdpModelsController;
use App\Http\Controllers\GbdpSecondHeatTreatmentController;
use App\Http\Controllers\SmpDataController;
use Illuminate\Support\Facades\Route;
use App\Mail\TakefuMail;
use App\Mail\RouteMail;
use App\Models\GbdpSecondCoating;
use App\Models\MassProduction;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

Route::get('/tpmdata', [TPMDataController::class, 'index']);
Route::get('/tpmdata/{id}', [TPMDataController::class, 'show']);
Route::get('/tpmboxes/{id}', [TPMDataController::class, 'showBoxes']);
Route::post('/tpmdata', [TPMDataController::class, 'store']);
Route::patch('/tpmdataupdate/{id}', [TPMDataController::class, 'updateTpmData']);
Route::patch('/tpmremarksupdate/{id}', [TPMDataController::class, 'updateRemarks']);
Route::patch('/tpmaggregateupdate/{id}', action: [TPMDataController::class, 'updateAggregateFunctions']);
Route::patch('/updatecategory/{id}', [TPMDataController::class, 'updateCategory']);
Route::patch('/updateboxes/{id}', [TPMDataController::class, 'updateBoxes']);
Route::delete('/tpmdata/{id}', [TPMDataController::class, 'destroy']);

Route::get('/reportdata', [ReportDataController::class, 'index']);
Route::get('/reportdata/{id}', [ReportDataController::class, 'show']);
Route::post('/reportdata', [ReportDataController::class, 'store']);
Route::patch('/reportdata/{id}', [ReportDataController::class, 'update']);
Route::delete('/reportdata/{id}', [ReportDataController::class, 'destroy']);

Route::get('/furnacedata', [FurnaceDataController::class, 'index']);
Route::get('/furnacedata/{id}', [FurnaceDataController::class, 'show']);
Route::post('/furnacedata', [FurnaceDataController::class, 'store']);
Route::patch('/furnacedata/{id}', [FurnaceDataController::class, 'update']);
Route::delete('/furnacedata/{id}', [FurnaceDataController::class, 'destroy']);

Route::get('/layerdata', [LayerDataController::class, 'index']);
Route::get('/layerdata/{id}', [LayerDataController::class, 'show']);
Route::post('/layerdata', [LayerDataController::class, 'store']);
Route::patch('/layerdata/{id}', [LayerDataController::class, 'update']);
Route::delete('/layerdata/{id}', [LayerDataController::class, 'destroy']);

Route::get('/inspectiondata', [InspectionDataController::class, 'index']);
Route::get('/inspectiondata/{id}', [InspectionDataController::class, 'show']);
Route::post('/inspectiondata', [InspectionDataController::class, 'store']);
Route::patch('/inspectiondata/{id}', [InspectionDataController::class, 'update']);
Route::delete('/inspectiondata/{id}', [InspectionDataController::class, 'destroy']);

Route::get('/approver', [ApproversController::class, 'index']);
Route::get('/approver/{id}', [ApproversController::class, 'show']);
Route::post('/approver', [ApproversController::class, 'store']);
Route::patch('/approver/{id}', [ApproversController::class, 'update']);
Route::delete('/approver/{id}', [ApproversController::class, 'destroy']);

Route::get('/standarddata', [StandardDataController::class, 'index']);
Route::get('/standarddata/{id}', [StandardDataController::class, 'show']);
Route::post('/standarddata', [StandardDataController::class, 'store']);
Route::patch('/standarddata/{id}', [StandardDataController::class, 'update']);
Route::delete('/standarddata/{id}', [StandardDataController::class, 'destroy']);

Route::get('/datainstructions', [DataInstructionsController::class, 'index']);
Route::get('/datainstructions/{id}', [DataInstructionsController::class, 'show']);
Route::post('/datainstructions', [DataInstructionsController::class, 'store']);
Route::patch('/datainstructions/{id}', [DataInstructionsController::class, 'update']);
Route::delete('/datainstructions/{id}', [DataInstructionsController::class, 'destroy']);

Route::get('/miegxdatainstructions', [MieGxDataInstructionsController::class, 'index']);
Route::get('/miegxdatainstructions/{id}', [MieGxDataInstructionsController::class, 'show']);
Route::post('/miegxdatainstructions', [MieGxDataInstructionsController::class, 'store']);
Route::patch('/miegxdatainstructions/{id}', [MieGxDataInstructionsController::class, 'update']);
Route::delete('/miegxdatainstructions/{id}', [MieGxDataInstructionsController::class, 'destroy']);

Route::get('/miegxdatainstructionsaggregate', [MieGxDataInstructionsAggregateController::class, 'index']);
Route::get('/miegxdatainstructionsaggregate/{id}', [MieGxDataInstructionsAggregateController::class, 'show']);
Route::post('/miegxdatainstructionsaggregate', [MieGxDataInstructionsAggregateController::class, 'store']);
Route::patch('/miegxdatainstructionsaggregate/{id}', [MieGxDataInstructionsAggregateController::class, 'update']);
Route::delete('/miegxdatainstructionsaggregate/{id}', [MieGxDataInstructionsAggregateController::class, 'destroy']);

Route::get('/nsadata', [NormalSecAdditionalsController::class, 'index']);
Route::get('/nsadata/{id}', [NormalSecAdditionalsController::class, 'show']);
Route::post('/nsadata', [NormalSecAdditionalsController::class, 'store']);
Route::patch('/nsadataupdate/{id}', [NormalSecAdditionalsController::class, 'updateNSAData']);
Route::patch('/nsadataupdatemiasfactor', [NormalSecAdditionalsController::class, 'updateNSA_MiasFactor']);
Route::patch('/nsaremarksupdate/{id}', [NormalSecAdditionalsController::class, 'updateRemarks']);
Route::patch('/nsaaggregateupdate/{id}', [NormalSecAdditionalsController::class, 'updateAggregateFunctions']);
Route::patch('/nsaupdatecategory/{id}', [NormalSecAdditionalsController::class, 'updateCategory']);
Route::delete('/nsadata/{id}', [NormalSecAdditionalsController::class, 'destroy']);
Route::delete('/nsaaggregate/{id}', [NormalSecAdditionalsController::class, 'destroyAggregate']);
Route::delete('/nsacategory/{id}', [NormalSecAdditionalsController::class, 'deleteCategory']);

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

Route::apiResource('userlogs',UserLogController::class);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login',[AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function (){
    Route::post('/logout',[AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::apiResource('users', UserController::class);
});

Route::post('/generate-pdf', [PdfController::class, 'generatePdf']);

Route::post('/send-takefu-email', function(Request $request) {

    $validated = $request->validate([
        'emails' => 'required|string',
        'message' => 'nullable|string|max:5000',
        'massPro' => 'required|string',
    ]);

    $emailList = array_map('trim', explode(',', $validated['emails']));

    // Append hardcoded recipients
    $emailList[] = 'automation2@smp.com.ph';
    $emailList[] = 'automation5@smp.com.ph';
    $emailList[] = 'myke@smp.com.ph';

    // Optionally remove duplicates (not required, but clean)
    $emailList = array_unique($emailList);

    $customMessage = strip_tags($validated['message'] ?? '', '<p><br><b><i><strong><em><ul><ol><li>');

    Log::info('Starting mail send');

    try {
        Mail::to($emailList)->send(new TakefuMail($validated['massPro'], $customMessage));
        Log::info('Mail sent successfully');
    } catch (\Throwable $e) {
        Log::error('Mail sending failed: ' . $e->getMessage());
        Log::error($e->getTraceAsString());

        return response()->json([
            'error' => 'Mail sending failed',
            'message' => $e->getMessage(),
        ], 500);
    }

    return response()->json(['message' => 'Email sent successfully'], 200);
});

Route::post('/send-takefu-email-manual', function(Request $request) {

    $validated = $request->validate([
        'emails' => 'required|string',
        'message' => 'nullable|string|max:5000',
        'massPro' => 'required|string',
        'selectedFiles' => 'required|array',
    ]);

    $emailList = array_map('trim', explode(',', $validated['emails']));
    $emailList[] = 'automation2@smp.com.ph';
    $emailList[] = 'automation5@smp.com.ph';
    $emailList[] = 'myke@smp.com.ph';
    $emailList = array_unique($emailList);

    $customMessage = strip_tags($validated['message'] ?? '', '<p><br><b><i><strong><em><ul><ol><li>');

    try {
        Mail::to($emailList)->send(new TakefuMail(
            $validated['massPro'],
            $customMessage,
            $validated['selectedFiles'] // PASS selected files
        ));
    } catch (\Throwable $e) {
        return response()->json(['error' => 'Mail sending failed', 'message' => $e->getMessage()], 500);
    }

    return response()->json(['message' => 'Email sent successfully'], 200);
});

Route::get('/files/{massPro}', function (Request $request, $massPro) {
    $directory = public_path("files/{$massPro}");

    if (!File::exists($directory)) {
        return response()->json([
            'files' => [],
            'message' => "No folder found for Mass Production: {$massPro}"
        ]);
    }

    $files = File::files($directory);

    // Return only file names (not full paths)
    $fileNames = array_map(fn($file) => $file->getFilename(), $files);

    return response()->json([
        'files' => $fileNames
    ]);
});

Route::get('/test-pdf-view', function () {
    return view('preview-pdf');
});

Route::get('/takefu-email', function () {
    return view('emails.takefu-email', [

        'message' => 'customMessage'
    ]);
});

Route::post('/route-email', function (Request $request) {
    $validated = $request->validate([
        'serial' => 'required|array',
        'emails' => 'required|string'
    ]);

    $emailList = array_map('trim', explode(',', $validated['emails']));
    Mail::to($emailList)->send(new RouteMail( $validated['serial']));
    return redirect()->route('approval')->with('success', 'Emails sent successfully!');
});

Route::post('/inspection/bulk-upload', [InspectionDataController::class, 'bulkUpload']);

Route::post('/mias-factor/bulk-upload', [MiasFactorController::class, 'bulkUpload']);
Route::apiResource('mias-factor', MiasFactorController::class);
Route::apiResource('vt-models',VtModelController::class);
Route::apiResource('cpk-ihc-models',CpkIhcModelController::class);
Route::apiResource('gx-models',GxModelController::class);
Route::apiResource('ttmnc-models',TtmncModelController::class);
Route::apiResource('bh-models',BhModelController::class);
Route::apiResource('rob-models',RobModelController::class);

Route::apiResource('ht-graph-patterns', HtGraphPatternsController::class);
Route::apiResource('furnace-data', FurnaceDataController::class);
Route::apiResource('second-gbdp-models', SecondGbdpModelsController::class);

Route::apiResource('coating-data',CoatingController::class);
Route::apiResource('heat-treatment-data',HeatTreatmentController::class);

Route::apiResource('mass-production',MassProductionController::class);

Route::apiResource('second_heat_treatment',GbdpSecondHeatTreatmentController::class);

Route::apiResource('gbdp-second-coating', GbdpSecondCoatingController::class);

Route::apiResource('film-pasting-data', FilmPastingDataController::class);

Route::apiResource('smp-data',SmpDataController::class);

Route::get('/film-pasting-data/{massProd}/layers', [FilmPastingDataController::class, 'getLayersByMassProd']);
Route::get('/coating-data/{massProd}/layers', [CoatingController::class, 'getLayersByMassProd']);
Route::get('/second-coating-data/{massProd}/layers', [GbdpSecondCoatingController::class, 'getLayersByMassProd']);
Route::get('/second-heat-treatment-data/{massProd}/layers', [GbdpSecondHeatTreatmentController::class, 'getLayersByMassProd']);
Route::get('/second-ht-data/{massprod}/layer/{layer}', [GbdpSecondHeatTreatmentController::class, 'getLayerData']);
Route::get('/second-coating-data/{massprod}/layer/{layer}', [GbdpSecondCoatingController::class, 'getLayerData']);

Route::get('/mass-production/by-mass-prod/{massprod}',[MassProductionController::class, 'getByMassProd']);
    //->where('massprod', '[A-Za-z0-9\-]+');
Route::patch('mass-production/by-mass-prod/{massprod}',[MassProductionController::class, 'updateByMassProd']);
Route::post('mass-production/by-mass-prod/{massprod}',[MassProductionController::class, 'uploadGraphs']);

Route::get('/reports/{serial}/generate-and-save', [BackEndPdfController::class, 'apiGenerateAndSave']);

Route::post('ht-graph-patterns/upload-graph', [HtGraphPatternsController::class, 'uploadGraphPattern']);
Route::get('htgraph-patterns/list', [HtGraphPatternsController::class, 'listGraphs']);

Route::patch('/patterns/{id}/update', [HtGraphPatternsController::class, 'update']);

Route::get('/coating-data/check', [CoatingController::class, 'checkExisting']);
Route::get('/coating-pending-add',[CoatingController::class, 'addPending']);
Route::get('/coating-pending-check',[CoatingController::class, 'checkPending']);

Route::get('/coating/get-data', [CoatingController::class, 'getCoatingData']);

Route::get('/approve-list-checked', [ReportDataController::class, 'getViewListChecked']);

Route::get('/approve-list-prepared', [ReportDataController::class, 'getViewListPrepared']);

Route::get('/mass-production-monitoring/{massprod}', [MassProductionController::class, 'massProductionMonitoring']);

Route::get('/mass-productions/{massprod}/layer/{layerNumber}/model',
    [MassProductionController::class, 'getLayerModel']);

Route::get('/mass-productions/{massprod}/layer/{layerNumber}/lotno',
[MassProductionController::class, 'getLayerLotno']);

Route::get(
    '/mass-productions/{massprod}/completed-layers',
    [MassProductionController::class, 'getAllHTCompletedLayers']
);

Route::get(
    '/mass-productions/{massprod}/coating-completed-layers',
    [MassProductionController::class, 'getAllCoatingCompleteLayers']
);

Route::get(
    '/mass-productions/{massprod}/filmpasting-completed-layers',
    [MassProductionController::class, 'getAllFilmPastingCompletedLayers']
);

Route::get(
    '/mass-productions/{massprod}/second-ht-completed-layers',
    [MassProductionController::class, 'getAllSecondHTCompletedLayers']
);

Route::get(
    '/mass-productions/{massprod}/layer-by-serial/{serial}',
    [MassProductionController::class, 'getLayerDataBySerial']
);

Route::get(
    '/mass-productions/{massprod}/layer-by-layerno/{layer}',
    [MassProductionController::class, 'getLayerDataByLayerNo']
);

Route::get('/mass-productions/{massprod}/smp-data',[MassProductionController::class, 'smpDataSummary']);

Route::get('/tpm/check-existing/{massprod}/{layer}', [TPMDataController::class, 'checkExisting']);
