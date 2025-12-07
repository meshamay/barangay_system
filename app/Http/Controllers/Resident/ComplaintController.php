<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    /**
     * Display a listing of user's complaints.
     */
    public function index()
    {
        return view('resident.complaints.index');
    }

    /**
     * Get all complaints for the authenticated user (API endpoint).
     */
    public function getUserComplaints()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated',
            ], 401);
        }
        
        $complaints = Complaint::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($complaint) use ($user) {
                return [
                    'id' => $complaint->id,
                    'transaction_id' => $complaint->transaction_id,
                    'name' => $user->name,
                    'complaint_type' => $complaint->complaint_type,
                    'date_filed' => $complaint->created_at->format('Y-m-d'),
                    'status' => $complaint->status,
                    'status_class' => $this->getStatusClass($complaint->status),
                    'urgency_level' => $complaint->urgency_level,
                    'incident_date' => $complaint->incident_date->format('Y-m-d'),
                    'defendant_name' => $complaint->defendant_name,
                ];
            });

        // Count by status
        $counts = [
            'open' => Complaint::where('user_id', $user->id)->where('status', 'Open')->count(),
            'in_progress' => Complaint::where('user_id', $user->id)->where('status', 'In Progress')->count(),
            'resolved' => Complaint::where('user_id', $user->id)->where('status', 'Resolved')->count(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $complaints,
            'counts' => $counts,
        ]);
    }

    /**
     * Store a newly created complaint.
     */
    public function store(Request $request)
    {
        // Validate the complaint form
        $validated = $request->validate([
            'incident_date' => 'required|date',
            'incident_time' => 'required',
            'incident_location' => 'nullable|string|max:255',
            'defendant_name' => 'required|string|max:255',
            'defendant_address' => 'required|string|max:500',
            'complaint_type' => 'required|in:Noise Complaint,Property Dispute,Harassment,Vandalism,Theft,Physical Assault,Verbal Abuse,Environmental,Other',
            'urgency_level' => 'required|in:Low,Medium,High,Urgent',
            'complaint_statement' => 'required|string|min:20',
        ]);

        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated',
            ], 401);
        }

        // Generate transaction ID
        $transactionId = $this->generateTransactionId();

        // Create complaint
        $complaint = Complaint::create([
            'transaction_id' => $transactionId,
            'user_id' => $user->id,
            'incident_date' => $validated['incident_date'],
            'incident_time' => $validated['incident_time'],
            'incident_location' => $validated['incident_location'],
            'defendant_name' => $validated['defendant_name'],
            'defendant_address' => $validated['defendant_address'],
            'complaint_type' => $validated['complaint_type'],
            'urgency_level' => $validated['urgency_level'],
            'complaint_statement' => $validated['complaint_statement'],
            'status' => 'Open',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Complaint submitted successfully',
            'data' => [
                'transaction_id' => $transactionId,
                'complaint_type' => $validated['complaint_type'],
            ],
        ], 200);
    }

    /**
     * Display the specified complaint.
     */
    public function show(string $id)
    {
        $complaint = Complaint::where('user_id', Auth::id())
            ->findOrFail($id);
        
        return view('resident.complaints.show', compact('complaint'));
    }

    /**
     * Generate unique transaction ID for complaints.
     */
    private function generateTransactionId()
    {
        // Get the last complaint to generate sequential number
        $lastComplaint = Complaint::where('transaction_id', 'like', "CMP-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastComplaint) {
            $lastNumber = (int) substr($lastComplaint->transaction_id, 4); // Remove "CMP-"
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 10001; // Start from 10001
        }

        return "CMP-{$newNumber}";
    }

    /**
     * Get CSS class for status badge.
     */
    private function getStatusClass($status)
    {
        return match ($status) {
            'Open' => 'bg-yellow-100 text-yellow-800',
            'In Progress' => 'bg-blue-100 text-blue-800',
            'Resolved' => 'bg-green-100 text-green-800',
            'Closed' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
