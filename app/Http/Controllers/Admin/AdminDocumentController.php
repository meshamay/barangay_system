<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDocumentController extends Controller
{
    /**
     * Display the admin document requests page.
     */
    public function index()
    {
        return view('admin.documents.index');
    }

    /**
     * Get all document requests with user details.
     * GET /api/document-requests
     */
    public function getAllRequests(Request $request)
    {
        $query = DocumentRequest::with(['user', 'processor']);

        // Apply filters if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('document_type') && $request->document_type !== 'all') {
            $query->where('document_type', $request->document_type);
        }

        // Apply search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('transaction_id', 'like', "%{$searchTerm}%")
                    ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('first_name', 'like', "%{$searchTerm}%")
                            ->orWhere('last_name', 'like', "%{$searchTerm}%")
                            ->orWhere('middle_name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Order by most recent first
        $requests = $query->orderBy('created_at', 'desc')->get();

        // Format the data for frontend
        $formattedRequests = $requests->map(function ($request) {
            return [
                'id' => $request->id,
                'transaction_id' => $request->transaction_id,
                'resident_name' => $request->user 
                    ? $request->user->first_name . ' ' . $request->user->last_name 
                    : 'N/A',
                'document_type' => $request->document_type,
                'document_type_display' => $this->formatDocumentType($request->document_type),
                'purpose' => $request->purpose,
                'date_requested' => $request->created_at->format('M d, Y'),
                'date_requested_full' => $request->created_at->format('F d, Y h:i A'),
                'status' => $request->status,
                'processed_by_name' => $request->processor 
                    ? $request->processor->first_name . ' ' . $request->processor->last_name 
                    : null,
                'released_at' => $request->released_at 
                    ? $request->released_at->format('M d, Y') 
                    : null,
                'remarks' => $request->remarks,
            ];
        });

        return response()->json([
            'success' => true,
            'requests' => $formattedRequests,
        ]);
    }

    /**
     * Get statistics for dashboard cards.
     * GET /api/document-requests/stats
     */
    public function getStats()
    {
        $stats = [
            'total' => DocumentRequest::count(),
            'pending' => DocumentRequest::where('status', 'Pending')->count(),
            'in_progress' => DocumentRequest::where('status', 'In Progress')->count(),
            'completed' => DocumentRequest::where('status', 'Completed')->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
        ]);
    }

    /**
     * Get a single document request details.
     * GET /api/document-requests/{id}
     */
    public function show($id)
    {
        $request = DocumentRequest::with(['user', 'processor'])->findOrFail($id);

        $data = [
            'id' => $request->id,
            'transaction_id' => $request->transaction_id,
            'status' => $request->status,
            
            // Resident Information
            'resident' => [
                'name' => $request->user->first_name . ' ' . $request->user->last_name,
                'email' => $request->user->email,
                'phone' => $request->user->phone ?? 'N/A',
                'address' => $request->user->address ?? 'N/A',
            ],
            
            // Document Details
            'document_type' => $this->formatDocumentType($request->document_type),
            'purpose' => $request->purpose,
            'valid_id_type' => $request->valid_id_type,
            'valid_id_number' => $request->valid_id_number,
            'registered_voter' => $request->registered_voter ? 'Yes' : 'No',
            'length_of_residency' => $request->length_of_residency ?? 'N/A',
            'barangay_id_number' => $request->barangay_id_number ?? 'N/A',
            'civil_status' => $request->civil_status ?? 'N/A',
            'employment_status' => $request->employment_status ?? 'N/A',
            'monthly_income' => $request->monthly_income 
                ? '₱' . number_format((float) $request->monthly_income, 2) 
                : 'N/A',
            'requirement_file' => $request->requirement_file_path 
                ? asset('storage/' . $request->requirement_file_path) 
                : null,
            
            // Processing Information
            'date_requested' => $request->created_at->format('F d, Y h:i A'),
            'processed_by' => $request->processor 
                ? $request->processor->first_name . ' ' . $request->processor->last_name 
                : null,
            'processed_at' => $request->processed_at 
                ? $request->processed_at->format('F d, Y h:i A') 
                : null,
            'released_at' => $request->released_at 
                ? $request->released_at->format('F d, Y h:i A') 
                : null,
            'remarks' => $request->remarks ?? 'No remarks',
        ];

        return response()->json([
            'success' => true,
            'request' => $data,
        ]);
    }

    /**
     * Update document request status.
     * PUT /api/document-requests/{id}
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Ready for Pickup,Completed,Rejected',
            'remarks' => 'nullable|string|max:500',
        ]);

        $documentRequest = DocumentRequest::findOrFail($id);
        
        $documentRequest->status = $request->status;
        $documentRequest->remarks = $request->remarks;
        
        // Set processed_by on first status change from Pending
        if ($documentRequest->status !== 'Pending' && !$documentRequest->processed_by) {
            $documentRequest->processed_by = Auth::id();
            $documentRequest->processed_at = now();
        }
        
        // Set released_at when marked as Completed
        if ($request->status === 'Completed' && !$documentRequest->released_at) {
            $documentRequest->released_at = now();
        }
        
        $documentRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Document request status updated successfully',
            'request' => [
                'id' => $documentRequest->id,
                'status' => $documentRequest->status,
                'processed_by' => $documentRequest->processor 
                    ? $documentRequest->processor->first_name . ' ' . $documentRequest->processor->last_name 
                    : null,
                'released_at' => $documentRequest->released_at 
                    ? $documentRequest->released_at->format('M d, Y') 
                    : null,
            ],
        ]);
    }

    /**
     * Format document type for display.
     */
    private function formatDocumentType($type)
    {
        $types = [
            'barangay_clearance' => 'Barangay Clearance',
            'barangay_certificate' => 'Barangay Certificate',
            'indigency_clearance' => 'Indigency Clearance',
            'resident_certificate' => 'Resident Certificate',
        ];

        return $types[$type] ?? $type;
    }
}
