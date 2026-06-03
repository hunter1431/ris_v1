<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\RisController;
use App\Http\Controllers\Api\SignatureController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/verify-ris/{token}', [VerificationController::class, 'show']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard', DashboardController::class);
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/inventory', [InventoryController::class, 'index']);
    Route::get('/inventory/{item}', [InventoryController::class, 'show']);
    Route::post('/inventory', [InventoryController::class, 'store']);
    Route::put('/inventory/{item}', [InventoryController::class, 'update']);
    Route::delete('/inventory/{item}', [InventoryController::class, 'destroy']);
    Route::get('/ris', [RisController::class, 'index']);
    Route::post('/ris', [RisController::class, 'store']);
    Route::post('/ris/{ris}/submit', [RisController::class, 'submit']);
    Route::post('/ris/{ris}/approve', [RisController::class, 'approve']);
    Route::post('/ris/{ris}/issue', [RisController::class, 'issue']);
    Route::get('/ris/{ris}/pdf', [RisController::class, 'pdf']);
    Route::get('/signatures', [SignatureController::class, 'index']);
    Route::post('/signatures', [SignatureController::class, 'store']);
    Route::get('/reports/ris-summary', [ReportController::class, 'risSummary']);
    Route::get('/reports/inventory', [ReportController::class, 'inventory']);
});
