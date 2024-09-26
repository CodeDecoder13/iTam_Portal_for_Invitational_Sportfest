<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\DocumentCheckerController;
use App\Http\Controllers\PlayerDocumentController;
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
    Route::get('/my-documents/{type}/{sport_category}/{name}', [UserController::class, 'myDocuments_sub'])->name('my-documents.sub');
    Route::post('/upload/player/documents/{playerId}', [UserController::class, 'uploadPlayerDocuments'])->name('upload.player.documents');
    Route::get('/my-calendar', [UserController::class, 'myCalendar'])->name('my-calendar');
    Route::get('/my-players', [UserController::class, 'myPlayers'])->name('my-players');
    Route::get('/add-teams', [UserController::class, 'addTeams'])->name('add-teams');
    Route::post('/store/team', [UserController::class, 'storeTeam'])->name('store.team');
    Route::get('/add-players', [UserController::class, 'addPlayers'])->name('add-players');
    Route::post('/store-players', [UserController::class, 'storePlayers'])->name('store.players');
    Route::post('/update-players', [UserController::class, 'updatePlayers'])->name('update.players');
    Route::get('/select-team', [UserController::class, 'selectTeam'])->name('select-team');
    Route::post('/upload/player/{id}/birth_certificate', [UserController::class, 'uploadPlayerDocuments'])->name('upload.player.birth_certificate');
    Route::post('/upload/player/{id}/parental_consent', [UserController::class, 'uploadPlayerDocuments'])->name('upload.player.parental_consent');
    Route::post('/player/{playerId}/upload-document', [UserController::class, 'uploadDocument'])->name('upload.player.document');
    Route::get('/player/{id}/view-birth-certificate', [UserController::class, 'viewBirthCertificate'])->name('player.viewBirthCertificate');
    Route::get('/player/{id}/view-parental-consent', [UserController::class, 'viewParentalConsent'])->name('player.viewParentalConsent');
    Route::delete('/delete/player/birth_certificate/{id}', [UserController::class, 'deleteBirthCertificate'])->name('delete.player.birth_certificate');
    Route::delete('/delete/player/parental_consent/{id}', [UserController::class, 'deleteParentalConsent'])->name('delete.player.parental_consent');
    Route::get('/player/{playerId}/download-document', [UserController::class, 'downloadDocument'])->name('download.player.document');
   
});

// added for admin sidebar
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/documents', [AdminController::class, 'documents'])->name('admin.documents');
    Route::get('/calendar', [AdminController::class, 'calendar'])->name('admin.calendar');
    Route::get('/school-management', [AdminController::class, 'schoolManagement'])->name('admin.school-management');
    Route::get('/user-management', [AdminController::class, 'usersManagement'])->name('admin.user-management');
    Route::get('/coach-approval', [AdminController::class, 'coachApproval'])->name('admin.coach-approval');
    Route::post('/update-status/{id}', [AdminController::class, 'updateStatus'])->name('admin.update-status');
    Route::get('/teams/{id}', [AdminController::class, 'showteam'])->name('admin.showteams');
    Route::get('/players-team-documents', [AdminController::class, 'teamdocuments'])->name('admin.playersTeamDocuments');
    Route::get('/summary-of-players', [AdminController::class, 'documentChecker'])->name('admin.SummaryOfPlayers');
    
});

// added for school management
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/school-management/card-school-management/{id}', [AdminController::class, 'cardSchoolManagement'])->name('admin.card-school-management');
    Route::get('/player-management/{id}', [AdminController::class, 'playerManagement'])->name('admin.player-management');
    Route::get('/team-management/{id}', [AdminController::class, 'teamManagement'])->name('admin.team-management');
    Route::get('/document-management', [AdminController::class, 'documentManagement'])->name('admin.document-management');
    Route::post('/store-team/{id}', [AdminController::class, 'storeTeam'])->name('admin.store-team');
    Route::delete('/delete-team/{id}', [AdminController::class, 'deleteTeam'])->name('admin.delete-team');
    Route::get('/logs-management/{id}', [AdminController::class, 'logsManagement'])->name('admin.logs-management');
    Route::get('/document-management/{id}', [AdminController::class, 'documentManagement'])->name('admin.document-management');
});
// usermanagement routes
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::post('/store-admin-accounts', [AdminController::class, 'storeAdmin'])->name('admin.store-admin');
    Route::post('/admin/update-admin', [AdminController::class, 'updateAdmin'])->name('admin.update.admin');
    Route::delete('/admin/delete', [AdminController::class, 'deleteAdmin'])->name('delete.admin');
});
// coach approval routes
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::post('/admin/coach/update', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/delete-coach', [AdminController::class, 'deleteCoach'])->name('delete.coach');
    Route::post('/store-user-accounts', [AdminController::class, 'storeUser'])->name('admin.store-user');
});
// added for Document Module
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {     
    Route::get('/player-document/{playerId}/{documentType}', [PlayerDocumentController::class, 'getDocument']);
    Route::get('/player-comments/{playerId}/{documentType}', [PlayerDocumentController::class, 'getComments']);
    Route::post('/player-comments', [PlayerDocumentController::class, 'addComment']);
});
//added for document checker
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::post('/document/approve/{player}/{document}', [DocumentCheckerController::class, 'approveDocument'])->name('document.approve');
    Route::post('/document/reject/{player}/{document}', [DocumentCheckerController::class, 'rejectDocument'])->name('document.reject');
    Route::get('/document/download/{player}/{document}', [DocumentCheckerController::class, 'downloadDocument'])->name('document.download');
    Route::delete('/document/delete/{player}/{document}', [DocumentCheckerController::class, 'deleteDocument'])->name('document.delete');

    //suggest Dwei: para di crowded route and functions
    Route::post('/document/update/{player}/{document}/{type}/{update}', [DocumentCheckerController::class, 'updateDocument'])->name('document.update');
    Route::get('/summary-of-players', [AdminController::class, 'documentCheckerFilter'])->name('admin.SummaryOfPlayers');

});
// route for myplayer page
Route::middleware(['auth','verified'])->group(function () {
Route::delete('/player/delete', [UserController::class, 'deletePlayer'])->name('player.delete');

});



Route::get('/testing-site', [TestingController::class, 'test'])->name('test.');

//Route for save team
Route::post('/save-team', [TestingController::class, 'addteam'])->name('test.addteam');

require __DIR__.'/auth.php';
require __DIR__.'/admin-auth.php';
