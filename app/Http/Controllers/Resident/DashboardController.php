<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Complaint;
use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Get statistics
        $pendingDocuments = DocumentRequest::where('user_id', $user->id)
            ->where('status', 'Pending')
            ->count();
        
        $completedDocuments = DocumentRequest::where('user_id', $user->id)
            ->where('status', 'Completed')
            ->count();
        
        $totalRequests = DocumentRequest::where('user_id', $user->id)->count();

        // Complaint statistics
        $activeComplaints = Complaint::where('user_id', $user->id)
            ->whereIn('status', ['Open', 'In Progress'])
            ->count();

        // Get recent document requests (for table)
        $recentDocuments = DocumentRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Transform for Alpine.js
        $recentDocumentsJson = $recentDocuments->map(function($doc) {
            return [
                'id' => $doc->id,
                'transaction_id' => $doc->transaction_id,
                'document_type_display' => str_replace('_', ' ', ucwords($doc->document_type)),
                'date_requested' => $doc->created_at->format('M d, Y'),
                'status' => ucwords(str_replace('_', ' ', $doc->status))
            ];
        });

        // Get recent announcements
        $recentAnnouncements = Announcement::where('is_active', true)
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('resident.dashboard', compact(
            'pendingDocuments',
            'completedDocuments',
            'activeComplaints',
            'totalRequests',
            'recentDocuments',
            'recentDocumentsJson',
            'recentAnnouncements'
        ));
    }

    public function profile()
    {
        return view('resident.profile');
    }

    public function announcements()
    {
        $announcements = Announcement::where('is_active', true)
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('resident.announcements', compact('announcements'));
    }
}
