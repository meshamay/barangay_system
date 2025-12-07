<?php

use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('/announcements/{id}', [\App\Http\Controllers\AnnouncementController::class, 'show'])->name('announcements.show');
Route::get('/officials', [\App\Http\Controllers\OfficialController::class, 'index'])->name('officials.index');

// Auth Routes
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\RegisterController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\Auth\RegisterController::class, 'logout'])->name('logout');
Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::get('/password/request', function () { return view('auth.forgot-password'); })->name('password.request');

// Resident Routes (Protected)
Route::middleware(['auth', 'role:resident'])->prefix('resident')->name('resident.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Resident\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [\App\Http\Controllers\Resident\DashboardController::class, 'profile'])->name('profile');
    Route::get('/announcements', [\App\Http\Controllers\Resident\DashboardController::class, 'announcements'])->name('announcements');
    
    // Document Requests
    Route::resource('documents', \App\Http\Controllers\Resident\DocumentRequestController::class);
    Route::get('/documents-api/list', [\App\Http\Controllers\DocumentRequestController::class, 'getUserRequests'])->name('documents.api.list');
    Route::post('/documents-api/store', [\App\Http\Controllers\DocumentRequestController::class, 'store'])->name('documents.api.store');
    
    // Complaints
    Route::resource('complaints', \App\Http\Controllers\Resident\ComplaintController::class);
});

// Admin Routes (Protected)
Route::middleware(['auth', 'role:admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [\App\Http\Controllers\Admin\DashboardController::class, 'reports'])->name('reports');
    
    // Resident Management
    Route::get('/residents', [\App\Http\Controllers\Admin\ResidentController::class, 'index'])->name('residents.index');
    Route::get('/residents/{resident}', [\App\Http\Controllers\Admin\ResidentController::class, 'show'])->name('residents.show');
    Route::put('/residents/{resident}/approve', [\App\Http\Controllers\Admin\ResidentController::class, 'approve'])->name('residents.approve');
    Route::put('/residents/{resident}/reject', [\App\Http\Controllers\Admin\ResidentController::class, 'reject'])->name('residents.reject');
    
    // Document Requests
    Route::get('/documents', [\App\Http\Controllers\Admin\AdminDocumentController::class, 'index'])->name('documents.index');
    
    // Complaints
    Route::get('/complaints', [\App\Http\Controllers\Admin\ComplaintController::class, 'index'])->name('complaints.index');
    Route::put('/complaints/{complaint}/status', [\App\Http\Controllers\Admin\ComplaintController::class, 'updateStatus'])->name('complaints.update-status');
    
    // Announcements
    Route::resource('announcements', \App\Http\Controllers\Admin\AnnouncementController::class);
    
    // Officials
    Route::resource('officials', \App\Http\Controllers\Admin\OfficialController::class);
});

// Super Admin Routes (Protected)
Route::middleware(['auth', 'role:super_admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    // Staff Management
    Route::resource('staff', \App\Http\Controllers\SuperAdmin\StaffController::class);
    
    // Audit Logs
    Route::get('/audit-logs', [\App\Http\Controllers\SuperAdmin\AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/export', [\App\Http\Controllers\SuperAdmin\AuditLogController::class, 'export'])->name('audit-logs.export');
});

// Test/Debug Routes (Remove in production)
Route::middleware('auth')->group(function () {
    // Test route to view all document requests with full details
    Route::get('/test/documents', function () {
        $documents = \App\Models\DocumentRequest::with('user')
            ->latest()
            ->get()
            ->map(function ($doc) {
                return [
                    'id' => $doc->id,
                    'transaction_id' => $doc->transaction_id,
                    'user' => $doc->user ? [
                        'id' => $doc->user->id,
                        'name' => $doc->user->name,
                        'email' => $doc->user->email,
                    ] : null,
                    'document_type' => $doc->document_type,
                    'purpose' => $doc->purpose,
                    'valid_id_number' => $doc->valid_id_number,
                    'registered_voter' => $doc->registered_voter,
                    'civil_status' => $doc->civil_status,
                    'status' => $doc->status,
                    'requirement_file_path' => $doc->requirement_file_path,
                    'created_at' => $doc->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $doc->updated_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'total' => $documents->count(),
            'documents' => $documents,
            'stats' => [
                'pending' => \App\Models\DocumentRequest::where('status', 'Pending')->count(),
                'in_progress' => \App\Models\DocumentRequest::where('status', 'In Progress')->count(),
                'completed' => \App\Models\DocumentRequest::where('status', 'Completed')->count(),
                'rejected' => \App\Models\DocumentRequest::where('status', 'Rejected')->count(),
            ]
        ], 200, [], JSON_PRETTY_PRINT);
    });

    // Test route to view current user's documents only
    Route::get('/test/my-documents', function () {
        $user = auth()->user();
        $documents = \App\Models\DocumentRequest::where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'total_documents' => $documents->count(),
            'documents' => $documents,
        ], 200, [], JSON_PRETTY_PRINT);
    });

    // Test route to check database structure
    Route::get('/test/db-info', function () {
        $columns = \DB::select('PRAGMA table_info(document_requests)');
        
        return response()->json([
            'table' => 'document_requests',
            'columns' => $columns,
            'total_records' => \App\Models\DocumentRequest::count(),
            'sample_record' => \App\Models\DocumentRequest::first(),
        ], 200, [], JSON_PRETTY_PRINT);
    });
});
