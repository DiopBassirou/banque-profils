<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminProfilController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Routes Publiques
Route::get('/profils', [ProfilController::class, 'index']);
Route::post('/profils', [ProfilController::class, 'store'])->middleware('throttle:profils-store');

// Auth Admin (Sanctum SPA — cookies)
Route::post('/admin/login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/admin/logout', [AdminAuthController::class, 'logout']);

// Routes Admin (Protégées par Sanctum SPA)
Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    Route::get('/me', [AdminAuthController::class, 'me']);
    Route::get('/profils', [AdminProfilController::class, 'index']);
    Route::put('/profils/{profil}', [AdminProfilController::class, 'update']);
    Route::patch('/profils/{profil}/status', [AdminProfilController::class, 'updateStatus']);
    Route::delete('/profils/{profil}', [AdminProfilController::class, 'destroy']);
});
