<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DocumentRequestController extends Controller
{
    /**
     * Display a listing of the user's document requests.
     */
    public function index()
    {
        return view('resident.documents.index');
    }

    /**
     * Get all document requests for the authenticated user (API endpoint).
     */
    public function getUserRequests()
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
            ->map(function ($docRequest) use ($user) {
                $formData = json_decode($docRequest->form_data, true) ?? [];
                
                return [
                    'id' => $docRequest->id,
                    'transaction_id' => $docRequest->transaction_id,
                    'last_name' => $formData['last_name'] ?? $user->last_name ?? '',
                    'first_name' => $formData['first_name'] ?? $user->first_name ?? '',
                    'document_type' => str_replace('_', ' ', ucwords($docRequest->document_type)),
                    'purpose' => $docRequest->purpose,
                    'date_requested' => $docRequest->created_at->format('Y-m-d'),
                    'status' => ucwords(str_replace('_', ' ', $docRequest->status)),
                    'status_class' => $this->getStatusClass($docRequest->status),
                ];
            });

        // Count by status
        $counts = [
            'pending' => DocumentRequest::where('user_id', $user->id)->where('status', 'Pending')->count(),
            'in_progress' => DocumentRequest::where('user_id', $user->id)->where('status', 'In Progress')->count(),
            'completed' => DocumentRequest::where('user_id', $user->id)->where('status', 'Completed')->count(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $requests,
            'counts' => $counts,
        ]);
    }

    /**
     * Store a newly created document request.
     */
    public function store(Request $request)
    {
        // Validate common fields (accept snake_case from form)
        $rules = [
            'document_type' => 'required|in:barangay_clearance,barangay_certificate,indigency_clearance,resident_certificate',
            'purpose' => 'required|string|max:500',
        ];

        $validated = $request->validate($rules);

        $user = Auth::user();
        
        // Handle file upload for Indigency Clearance
        $filePath = null;
        if ($request->hasFile('requirement_file')) {
            $file = $request->file('requirement_file');
            $filename = time() . '_' . Str::slug($user->name) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('requirements', $filename, 'public');
        }

        // Generate transaction ID
        $transactionId = $this->generateTransactionId($validated['document_type']);

        // Prepare form data
        $formData = array_merge($validated, [
            'file_path' => $filePath,
        ]);

        // Remove file from form_data if exists (already stored separately)
        unset($formData['requirement_file']);

        // Create document request with default values for required fields
        $documentRequest = DocumentRequest::create([
            'transaction_id' => $transactionId,
            'user_id' => $user->id,
            'document_type' => $validated['document_type'],
            'purpose' => $validated['purpose'],
            'valid_id_type' => 'Not Provided',  // Default value
            'valid_id_number' => '',            // Default empty value
            'registered_voter' => false,        // Default value
            'status' => 'Pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Request submitted successfully',
            'data' => [
                'transaction_id' => $transactionId,
                'document_type' => $validated['document_type'],
            ],
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('resident.documents.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $document = DocumentRequest::where('user_id', Auth::id())
            ->findOrFail($id);
        
        return view('resident.documents.show', compact('document'));
    }

    /**
     * Generate unique transaction ID.
     */
    private function generateTransactionId($documentType)
    {
        // Map document types (snake_case) to short codes
        $codes = [
            'barangay_clearance' => 'BC',
            'barangay_certificate' => 'BCERT',
            'indigency_clearance' => 'IC',
            'resident_certificate' => 'RC',
        ];

        $code = $codes[$documentType] ?? 'DOC';
        
        // Get the last document request to generate sequential number
        $lastRequest = DocumentRequest::where('transaction_id', 'like', "DOC-{$code}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastRequest) {
            $lastNumber = (int) substr($lastRequest->transaction_id, strrpos($lastRequest->transaction_id, '-') + 1);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 10001; // Start from 10001
        }

        return "DOC-{$code}-{$newNumber}";
    }

    /**
     * Get CSS class for status badge.
     */
    private function getStatusClass($status)
    {
        return match ($status) {
            'Pending' => 'bg-yellow-100 text-yellow-800',
            'In Progress' => 'bg-blue-100 text-blue-800',
            'Completed' => 'bg-green-100 text-green-800',
            'Rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
