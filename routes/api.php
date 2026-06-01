<?php

use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\RisController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/inventory', [InventoryController::class, 'index']);
    Route::get('/ris', [RisController::class, 'index']);
    Route::post('/ris', [RisController::class, 'store']);
    Route::post('/ris/{ris}/submit', [RisController::class, 'submit']);
    Route::post('/ris/{ris}/approve', [RisController::class, 'approve']);
    Route::post('/ris/{ris}/issue', [RisController::class, 'issue']);
    Route::get('/ris/{ris}/pdf', [RisController::class, 'pdf']);
    Route::get('/reports/ris-summary', [ReportController::class, 'risSummary']);
    Route::get('/reports/inventory', [ReportController::class, 'inventory']);
});
