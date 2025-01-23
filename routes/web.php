<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_middleware'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Named routes
    Route::get('/admin/documents', [AdminController::class, 'documents'])->name('admin.documents');
    Route::get('/admin/summary-of-players', [AdminController::class, 'summaryOfPlayers'])->name('admin.SummaryOfPlayers');
    
    // Document management routes
    Route::post('/admin/document/update/{playerId}/{fileName}/{docType}/{status}', [AdminController::class, 'updateDocument']);
    Route::post('/admin/document/delete/{playerId}/{fileName}/{docType}/{status}', [AdminController::class, 'deleteDocument']);
}); 