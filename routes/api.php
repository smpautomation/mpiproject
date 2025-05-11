<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\TPMDataController;
use App\Http\Controllers\FurnaceDataController;
use App\Http\Controllers\LayerDataController;
use App\Http\Controllers\InspectionDataController;
use App\Http\Controllers\ReportDataController;
use App\Http\Controllers\ApproversController;
use App\Http\Controllers\DataInstructionsController;
use App\Http\Controllers\MieGxDataInstructionsController;
use App\Http\Controllers\MieGxDataInstructionsAggregateController;
use App\Http\Controllers\NormalSecAdditionalsController;
use App\Http\Controllers\StandardDataController;
use Illuminate\Support\Facades\Route;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

Route::get('/tpmdata', [TPMDataController::class, 'index']);
Route::get('/tpmdata/{id}', [TPMDataController::class, 'show']);
Route::post('/tpmdata', [TPMDataController::class, 'store']);
Route::patch('/tpmdataupdate/{id}', [TPMDataController::class, 'updateTpmData']);
Route::patch('/tpmremarksupdate/{id}', [TPMDataController::class, 'updateRemarks']);
Route::patch('/tpmaggregateupdate/{id}', [TPMDataController::class, 'updateAggregateFunctions']);
Route::patch('/updatecategory/{id}', [TPMDataController::class, 'updateCategory']);
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
Route::patch('/nsaremarksupdate/{id}', [NormalSecAdditionalsController::class, 'updateRemarks']);
Route::patch('/nsaaggregateupdate/{id}', [NormalSecAdditionalsController::class, 'updateAggregateFunctions']);
Route::patch('/nsaupdatecategory/{id}', [NormalSecAdditionalsController::class, 'updateCategory']);
Route::delete('/nsadata/{id}', [NormalSecAdditionalsController::class, 'destroy']);

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);


Route::post('/send-test-email', function(Request $request){

    $validated = $request->validate([
        'serial' => 'required|alpha_num',
        'emails' => 'required|string',
        'message' => 'nullable|string|max:5000',
    ]);

    $emailList = array_map('trim', explode(',', $validated['emails'])); 
    $username = 'TestUser';

    $customMessage = strip_tags($validated['message'] ?? '', '<p><br><b><i><strong><em><ul><ol><li>');

    Mail::to($emailList)->send(new TestMail($username, $validated['serial'], $customMessage));
    return 'Test Email Sent';
});

Route::get('/test-email', function () {
    return view('emails.test', [
        'username' => 'PreviewUser',
        'message' => 'customMessage'
    ]);
});