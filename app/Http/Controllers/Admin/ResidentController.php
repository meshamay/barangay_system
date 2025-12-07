<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    /**
     * Display a listing of residents.
     */
    public function index()
    {
        $residents = User::where('role', 'resident')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.residents.index', compact('residents'));
    }

    /**
     * Display the specified resident.
     */
    public function show($id)
    {
        $resident = User::where('role', 'resident')->findOrFail($id);
        
        // Get resident's statistics
        $stats = [
            'total_documents' => $resident->documentRequests()->count(),
            'pending_documents' => $resident->documentRequests()->where('status', 'Pending')->count(),
            'completed_documents' => $resident->documentRequests()->where('status', 'Completed')->count(),
            'total_complaints' => $resident->complaints()->count(),
            'open_complaints' => $resident->complaints()->where('status', 'Open')->count(),
        ];

        return view('admin.residents.show', compact('resident', 'stats'));
    }

    /**
     * Approve a resident account.
     */
    public function approve($id)
    {
        $resident = User::where('role', 'resident')->findOrFail($id);
        
        // Update resident status or any approval logic
        // This depends on your User model structure
        
        return redirect()
            ->route('admin.residents.show', $resident->id)
            ->with('success', 'Resident approved successfully');
    }

    /**
     * Reject a resident account.
     */
    public function reject($id)
    {
        $resident = User::where('role', 'resident')->findOrFail($id);
        
        // Update resident status or any rejection logic
        // This depends on your User model structure
        
        return redirect()
            ->route('admin.residents.index')
            ->with('success', 'Resident rejected successfully');
    }
}
