<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestingController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// added for sidebar routes
Route::middleware(['auth','verified'])->group(function () {
    Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/my-documents', [UserController::class, 'myDocuments'])->name('my-documents');
    Route::get('/my-documents/{type}', [UserController::class, 'myDocuments_sub'])->name('my-documents_sub');
    Route::get('/my-calendar', [UserController::class, 'myCalendar'])->name('my-calendar');
    Route::get('/my-players', [UserController::class, 'myPlayers'])->name('my-players');
    Route::get('/get-teams', [UserController::class, 'getTeams'])->name('get-teams');

});

// added for admin sidebar
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/documents', [AdminController::class, 'documents'])->name('admin.documents');
    Route::get('/calendar', [AdminController::class, 'calendar'])->name('admin.calendar');
    Route::get('/players-teams', [AdminController::class, 'playersTeams'])->name('admin.players-teams');
    Route::get('/user-management', [AdminController::class, 'usersManagement'])->name('admin.user-management');
    Route::get('/coach-approval', [AdminController::class, 'coachApproval'])->name('admin.coach-approval');
    Route::post('/update-status/{id}', [AdminController::class, 'updateStatus'])->name('admin.update-status');
    Route::get('/teams/{id}', [AdminController::class, 'showteam'])->name('admin.showteams');
});

Route::post('/testing-site', [TestingController::class, 'test'])->name('test.');

//Route for save team
Route::post('/save-team', [TestingController::class, 'addteam'])->name('test.addteam');

require __DIR__.'/auth.php';
require __DIR__.'/admin-auth.php';
