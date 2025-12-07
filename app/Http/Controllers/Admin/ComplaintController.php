<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    /**
     * Display a listing of all complaints.
     */
    public function index()
    {
        $complaints = Complaint::with(['user', 'assignedAdmin'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get statistics
        $stats = [
            'total' => Complaint::count(),
            'open' => Complaint::where('status', 'Open')->count(),
            'in_progress' => Complaint::where('status', 'In Progress')->count(),
            'resolved' => Complaint::where('status', 'Resolved')->count(),
            'closed' => Complaint::where('status', 'Closed')->count(),
        ];

        return view('admin.complaints.index', compact('complaints', 'stats'));
    }

    /**
     * Display the specified complaint.
     */
    public function show($id)
    {
        $complaint = Complaint::with(['user', 'assignedAdmin'])->findOrFail($id);
        
        return view('admin.complaints.show', compact('complaint'));
    }

    /**
     * Update the status of a complaint.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Open,In Progress,Resolved,Closed',
            'admin_remarks' => 'nullable|string|max:1000',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $complaint = Complaint::findOrFail($id);
        
        // Update status
        $complaint->status = $validated['status'];
        
        // Update remarks if provided
        if (isset($validated['admin_remarks'])) {
            $complaint->admin_remarks = $validated['admin_remarks'];
        }
        
        // Assign to admin if provided
        if (isset($validated['assigned_to'])) {
            $complaint->assigned_to = $validated['assigned_to'];
        } else if (!$complaint->assigned_to) {
            // Auto-assign to current admin if not already assigned
            $complaint->assigned_to = Auth::id();
        }
        
        // Set resolved_at timestamp when status changes to Resolved
        if ($validated['status'] === 'Resolved' && !$complaint->resolved_at) {
            $complaint->resolved_at = now();
        }
        
        $complaint->save();

        return redirect()
            ->route('admin.complaints.index')
            ->with('success', 'Complaint status updated successfully');
    }
}
