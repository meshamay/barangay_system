<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequestRequest;
use App\Models\DocumentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DocumentRequestController extends Controller
{
    /**
     * Get all document requests for the authenticated user.
     */
    public function getUserRequests(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated',
            ], 401);
        }
        
        $requests = DocumentRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($req) use ($user) {
                return [
                    'id' => $req->id,
                    'transaction_id' => $req->transaction_id,
                    'last_name' => $user->last_name,
                    'first_name' => $user->first_name,
                    'document_type' => $req->document_type,
                    'purpose' => $req->purpose,
                    'date_requested' => $req->created_at->format('Y-m-d'),
                    'status' => $req->status,
                ];
            });

        // Calculate status counts
        $allRequests = DocumentRequest::where('user_id', $user->id)->get();
        $stats = [
            'pending' => $allRequests->where('status', 'Pending')->count(),
            'in_progress' => $allRequests->where('status', 'In Progress')->count(),
            'completed' => $allRequests->where('status', 'Completed')->count(),
        ];

        return response()->json([
            'requests' => $requests,
            'stats' => $stats,
        ]);
    }

    /**
     * Store a new document request.
     */
    public function store(StoreDocumentRequestRequest $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated',
            ], 401);
        }
        
        $validated = $request->validated();

        // Generate transaction ID
        $transactionId = $this->generateTransactionId($validated['document_type']);

        // Handle file upload for Indigency Clearance
        $filePath = null;
        if ($request->hasFile('requirement_file')) {
            $file = $request->file('requirement_file');
            $filename = time() . '_' . Str::slug($user->first_name . '_' . $user->last_name) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('requirements', $filename, 'public');
        }

        // Create document request
        $documentRequest = DocumentRequest::create([
            'transaction_id' => $transactionId,
            'user_id' => $user->id,
            'document_type' => $validated['document_type'],
            'purpose' => $validated['purpose'],
            'valid_id_type' => $validated['valid_id_type'],
            'valid_id_number' => $validated['valid_id_number'],
            'registered_voter' => $validated['registered_voter'],
            'length_of_residency' => $validated['length_of_residency'] ?? null,
            'barangay_id_number' => $validated['barangay_id_number'] ?? null,
            'civil_status' => $validated['civil_status'] ?? null,
            'employment_status' => $validated['employment_status'] ?? null,
            'monthly_income' => $validated['monthly_income'] ?? null,
            'requirement_file_path' => $filePath,
            'status' => 'Pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Request submitted successfully',
            'data' => [
                'transaction_id' => $transactionId,
                'id' => $documentRequest->id,
            ],
        ], 201);
    }

    /**
     * Generate a transaction ID based on document type.
     */
    private function generateTransactionId(string $documentType): string
    {
        $prefix = match($documentType) {
            'barangay_clearance' => 'DOC-BC',
            'barangay_certificate' => 'DOC-CERT',
            'resident_certificate' => 'DOC-RC',
            'indigency_clearance' => 'DOC-IC',
            default => 'DOC-REQ',
        };

        // Get the last ID from database and increment
        $lastRequest = DocumentRequest::whereRaw("transaction_id LIKE '{$prefix}-%'")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastRequest) {
            $lastNumber = (int) substr($lastRequest->transaction_id, strlen($prefix) + 1);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 10001; // Start from 10001
        }

        return $prefix . '-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }
}
