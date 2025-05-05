<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/send-test-email/{emails}', function($emails){
    $emailList = array_map('trim', explode(',', $emails)); 
    Mail::to($emailList)->send(new TestMail('Test'));
    return 'Test email sent!';
});

require __DIR__.'/auth.php';
