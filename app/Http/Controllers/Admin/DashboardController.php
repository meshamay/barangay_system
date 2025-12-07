<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DocumentRequest;
use App\Models\Complaint;
use App\Models\Announcement;
use App\Models\BarangayOfficial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard with statistics.
     */
    public function index()
    {
        // User statistics
        $totalResidents = User::where('role', 'resident')->count();
        $pendingResidents = User::where('role', 'resident')->where('account_status', 'pending')->count();
        $activeResidents = User::where('role', 'resident')->where('is_active', true)->count();

        // Document request statistics
        $totalDocuments = DocumentRequest::count();
        $pendingDocuments = DocumentRequest::where('status', 'Pending')->count();
        $completedDocuments = DocumentRequest::where('status', 'Completed')->count();
        $inProgressDocuments = DocumentRequest::where('status', 'In Progress')->count();

        // Complaint statistics
        $totalComplaints = Complaint::count();
        $openComplaints = Complaint::where('status', 'Open')->count();
        $resolvedComplaints = Complaint::where('status', 'Resolved')->count();
        $inProgressComplaints = Complaint::where('status', 'In Progress')->count();

        // Recent activities
        $recentDocuments = DocumentRequest::with('user')
            ->latest()
            ->limit(5)
            ->get();

        $recentComplaints = Complaint::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Monthly document requests (last 6 months)
        $monthlyDocuments = DocumentRequest::select(
            DB::raw('strftime(\'%Y-%m\', created_at) as month'),
            DB::raw('count(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'totalResidents',
            'pendingResidents',
            'activeResidents',
            'totalDocuments',
            'pendingDocuments',
            'completedDocuments',
            'inProgressDocuments',
            'totalComplaints',
            'openComplaints',
            'resolvedComplaints',
            'inProgressComplaints',
            'recentDocuments',
            'recentComplaints',
            'monthlyDocuments'
        ));
    }

    /**
     * Display reports and analytics page.
     */
    public function reports(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        
        // Date range for selected month/year
        $startDate = $request->input('start_date', date('Y-m-01', strtotime("$year-$month-01")));
        $endDate = $request->input('end_date', date('Y-m-t', strtotime("$year-$month-01")));

        // === STATISTICS CARDS ===
        $totalUsers = User::count();
        $totalResidents = User::where('role', 'resident')->count();
        $totalStaff = User::whereIn('role', ['admin', 'super_admin'])->count();
        $archivedAccounts = User::where('is_active', false)->count();

        // === CHART 1: POPULATION BY GENDER ===
        $populationByGender = User::where('role', 'resident')
            ->select('gender', DB::raw('count(*) as count'))
            ->groupBy('gender')
            ->get();

        // === CHART 2: TOTAL REQUEST & COMPLAINT (Selected Month) ===
        $totalRequestsMonth = DocumentRequest::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalComplaintsMonth = Complaint::whereBetween('created_at', [$startDate, $endDate])->count();

        // === CHART 3: MOST REQUESTED DOCUMENT (Selected Month) ===
        $documentsByType = DocumentRequest::select('document_type', DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('document_type')
            ->orderByDesc('count')
            ->get();

        // === CHART 4: REQUEST STATUS SUMMARY (Selected Month) ===
        $documentsByStatus = DocumentRequest::select('status', DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get();

        // === CHART 5: MOST REPORTED COMPLAINTS (Selected Month) ===
        $complaintsByType = Complaint::select('complaint_type', DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('complaint_type')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // === CHART 6: COMPLAINT STATUS SUMMARY (Selected Month) ===
        $complaintsByStatus = Complaint::select('status', DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get();

        // === YEARLY TREND DATA (for additional analysis) ===
        $monthlyTrends = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthStart = date('Y-m-01', strtotime("$year-$m-01"));
            $monthEnd = date('Y-m-t', strtotime("$year-$m-01"));
            
            $monthlyTrends[] = [
                'month' => date('F', strtotime("$year-$m-01")),
                'documents' => DocumentRequest::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'complaints' => Complaint::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
            ];
        }

        return view('admin.reports', compact(
            'year',
            'month',
            'startDate',
            'endDate',
            'totalUsers',
            'totalResidents',
            'totalStaff',
            'archivedAccounts',
            'populationByGender',
            'totalRequestsMonth',
            'totalComplaintsMonth',
            'documentsByType',
            'documentsByStatus',
            'complaintsByType',
            'complaintsByStatus',
            'monthlyTrends'
        ));
    }
}
