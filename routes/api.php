<?php

use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminProjectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramerController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/akses', [AuthController::class, 'akses']);

// master akses
Route::middleware(['auth:sanctum', 'akses:master_admin'])->group(function () {

    // pelanggan
    Route::post('/save-pelanggan', [PelangganController::class, 'save']);
    Route::get('/load-pelanggan', [PelangganController::class, 'load']);
    Route::put('/update-pelanggan/{id}', [PelangganController::class, 'update']);
    Route::delete('/delete-pelanggan/{id}', [PelangganController::class, 'delete']);

    // project
    Route::post('/upload-file-project', [ProjectController::class, 'uploadFile']);
    Route::post('/save-project', [ProjectController::class, 'save']);
    Route::get('/load-project', [ProjectController::class, 'load']);
    Route::put('/update-project/{id}', [ProjectController::class, 'update']);

    // programmer
    Route::get('/users', [ProgramerController::class, 'index']);
    Route::post('/users', [ProgramerController::class, 'store']);
    Route::delete('/users/{id}', [ProgramerController::class, 'destroy']);
    Route::get('/unassigned-projects', [ProgramerController::class, 'unassignedProjects']);
    Route::post('/assign-projects', [ProgramerController::class, 'assignProjects']);
    Route::get('/user-projects/{id}', [ProgramerController::class, 'getUserProjects']);
    Route::post('/unassign-project', [ProgramerController::class, 'unassignProject']);

    // profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/update-profile', [ProfileController::class, 'update']);
    Route::post('/update-foto-profile', [ProfileController::class, 'updatePhoto']);
});

// admin akses
Route::middleware(['auth:sanctum', 'akses:admin'])->group(function () {

    // project admin
    Route::get('/admin/project', [AdminProjectController::class, 'getProjectsByUser']);
    Route::post('/admin/project/selesaikan', [AdminProjectController::class, 'selesaikanProject']);
    Route::get('/project/check-file/{id}', [AdminProjectController::class, 'checkFile']);

    // profile admin
    Route::get('/profile-admin', [AdminProfileController::class, 'show']);
    Route::post('/update-profile-admin', [AdminProfileController::class, 'update']);
    Route::post('/update-foto-profile-admin', [AdminProfileController::class, 'updatePhoto']);
});
