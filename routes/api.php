<?php

use App\Http\Controllers\DocumentRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Document Request API Routes (protected by auth - using web middleware for session-based auth)
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/user/document-requests', [\App\Http\Controllers\Resident\DocumentRequestController::class, 'getUserRequests']);
    Route::post('/user/document-request', [\App\Http\Controllers\Resident\DocumentRequestController::class, 'store']);
    
    // Complaint API Routes
    Route::get('/user/complaints', [\App\Http\Controllers\Resident\ComplaintController::class, 'getUserComplaints']);
    Route::post('/user/complaint', [\App\Http\Controllers\Resident\ComplaintController::class, 'store']);
});

// Admin Document Management API Routes (protected by auth + admin role)
Route::middleware(['web', 'auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/document-requests', [\App\Http\Controllers\Admin\AdminDocumentController::class, 'getAllRequests']);
    Route::get('/document-requests/stats', [\App\Http\Controllers\Admin\AdminDocumentController::class, 'getStats']);
    Route::get('/document-requests/{id}', [\App\Http\Controllers\Admin\AdminDocumentController::class, 'show']);
    Route::put('/document-requests/{id}', [\App\Http\Controllers\Admin\AdminDocumentController::class, 'updateStatus']);
    
    // Admin Complaint Stats API
    Route::get('/admin/complaints/stats', [\App\Http\Controllers\Admin\ComplaintController::class, 'getStats']);
});
