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

        // New registrations this month
        $newResidentsThisMonth = User::where('role', 'resident')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        // Pending registrations count (alias for clarity in the view)
        $pendingRegistrations = $pendingResidents;

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
        $activeComplaints = Complaint::whereIn('status', ['Open', 'In Progress'])->count();

        // Recent activities
        $recentDocuments = DocumentRequest::with('user')
            ->latest()
            ->limit(5)
            ->get();

        $recentComplaints = Complaint::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Recent resident registrations (for table)
        $recentRegistrations = User::where('role', 'resident')
            ->latest()
            ->limit(5)
            ->get()
            ->each(function ($user) {
                $user->status = $user->account_status; // normalize field name for the view
            });

        // Monthly document requests (last 6 months)
        $monthlyDocuments = DocumentRequest::select(
            DB::raw('strftime(\'%Y-%m\', created_at) as month'),
            DB::raw('count(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Monthly activity data for the bar chart (last 6 months, fill missing with zeros)
        $monthlyActivity = [];
        for ($i = 5; $i >= 0; $i--) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = (clone $start)->endOfMonth();
            $label = $start->format('M');
            $monthlyActivity[$label] = DocumentRequest::whereBetween('created_at', [$start, $end])->count();
        }
        $monthlyActivityMax = max($monthlyActivity ?: [0]) ?: 1; // avoid div-by-zero in view

        // Demographics
        $maleCount = User::where('role', 'resident')->where('gender', 'male')->count();
        $femaleCount = User::where('role', 'resident')->where('gender', 'female')->count();
        $totalGender = max($maleCount + $femaleCount, 1);
        $malePercentage = round(($maleCount / $totalGender) * 100, 1);
        $femalePercentage = round(($femaleCount / $totalGender) * 100, 1);

        // Document types distribution
        $documentTypes = DocumentRequest::select('document_type', DB::raw('count(*) as count'))
            ->groupBy('document_type')
            ->pluck('count', 'document_type')
            ->toArray();

        // Combine all transactions (Document Requests + Complaints)
        $documentRequests = DocumentRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($doc) {
                return [
                    'transaction_id' => $doc->transaction_id,
                    'first_name' => $doc->user->first_name ?? '',
                    'last_name' => $doc->user->last_name ?? '',
                    'type' => ucwords(str_replace('_', ' ', $doc->document_type)),
                    'description' => ucwords(str_replace('_', ' ', $doc->document_type)),
                    'date_filed' => $doc->created_at->format('M d, Y'),
                    'date_completed' => $doc->status === 'Completed' ? ($doc->updated_at->format('M d, Y')) : null,
                    'status' => $doc->status,
                    'sort_date' => $doc->created_at->timestamp
                ];
            });

        $complaints = Complaint::with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($complaint) {
                return [
                    'transaction_id' => 'C-' . str_pad($complaint->id, 5, '0', STR_PAD_LEFT),
                    'first_name' => $complaint->user->first_name ?? '',
                    'last_name' => $complaint->user->last_name ?? '',
                    'type' => 'Complaint',
                    'description' => $complaint->subject,
                    'date_filed' => $complaint->created_at->format('M d, Y'),
                    'date_completed' => in_array($complaint->status, ['Resolved', 'Closed']) ? ($complaint->updated_at->format('M d, Y')) : null,
                    'status' => $complaint->status,
                    'sort_date' => $complaint->created_at->timestamp
                ];
            });

        $allTransactions = $documentRequests->concat($complaints)->sortByDesc('sort_date')->values();

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
            'activeComplaints',
            'recentDocuments',
            'recentComplaints',
            'monthlyDocuments',
            'monthlyActivity',
            'monthlyActivityMax',
            'maleCount',
            'femaleCount',
            'malePercentage',
            'femalePercentage',
            'documentTypes',
            'recentRegistrations',
            'newResidentsThisMonth',
            'pendingRegistrations',
            'allTransactions'
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

    /**
     * Export reports to PDF or Excel.
     */
    public function exportReports(Request $request)
    {
        $year = $request->input('export_year', now()->year);
        $month = $request->input('export_month', now()->month);
        $format = $request->input('format', 'pdf');
        $sections = $request->input('sections', []);
        
        $startDate = date('Y-m-01', strtotime("$year-$month-01"));
        $endDate = date('Y-m-t', strtotime("$year-$month-01"));

        // Gather data
        $data = [
            'year' => $year,
            'month' => date('F', strtotime("$year-$month-01")),
            'sections' => $sections,
        ];

        if (in_array('gender', $sections)) {
            $data['populationByGender'] = User::where('role', 'resident')
                ->select('gender', DB::raw('count(*) as count'))
                ->groupBy('gender')
                ->get();
        }

        if (in_array('request_complaint', $sections)) {
            $data['totalRequests'] = DocumentRequest::whereBetween('created_at', [$startDate, $endDate])->count();
            $data['totalComplaints'] = Complaint::whereBetween('created_at', [$startDate, $endDate])->count();
        }

        if (in_array('document_type', $sections)) {
            $data['documentsByType'] = DocumentRequest::select('document_type', DB::raw('count(*) as count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('document_type')
                ->orderByDesc('count')
                ->get();
        }

        if (in_array('request_status', $sections)) {
            $data['documentsByStatus'] = DocumentRequest::select('status', DB::raw('count(*) as count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('status')
                ->get();
        }

        if (in_array('complaint_type', $sections)) {
            $data['complaintsByType'] = Complaint::select('complaint_type', DB::raw('count(*) as count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('complaint_type')
                ->orderByDesc('count')
                ->get();
        }

        if (in_array('complaint_status', $sections)) {
            $data['complaintsByStatus'] = Complaint::select('status', DB::raw('count(*) as count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('status')
                ->get();
        }

        if ($format === 'excel') {
            return $this->exportToExcel($data);
        }

        return $this->exportToPdf($data);
    }

    /**
     * Export to Excel format.
     */
    private function exportToExcel($data)
    {
        $filename = 'barangay_report_' . $data['year'] . '_' . $data['month'] . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Barangay Management System - Report']);
            fputcsv($file, ['Period: ' . $data['month'] . ' ' . $data['year']]);
            fputcsv($file, ['Generated: ' . now()->format('Y-m-d H:i:s')]);
            fputcsv($file, []);

            foreach ($data['sections'] as $section) {
                switch ($section) {
                    case 'gender':
                        fputcsv($file, ['POPULATION BY GENDER']);
                        fputcsv($file, ['Gender', 'Count']);
                        foreach ($data['populationByGender'] ?? [] as $row) {
                            fputcsv($file, [ucfirst($row->gender ?? 'Unknown'), $row->count]);
                        }
                        fputcsv($file, []);
                        break;

                    case 'document_type':
                        fputcsv($file, ['MOST REQUESTED DOCUMENTS']);
                        fputcsv($file, ['Document Type', 'Count']);
                        foreach ($data['documentsByType'] ?? [] as $row) {
                            fputcsv($file, [str_replace('_', ' ', ucwords($row->document_type, '_')), $row->count]);
                        }
                        fputcsv($file, []);
                        break;

                    case 'complaint_type':
                        fputcsv($file, ['MOST REPORTED COMPLAINTS']);
                        fputcsv($file, ['Complaint Type', 'Count']);
                        foreach ($data['complaintsByType'] ?? [] as $row) {
                            fputcsv($file, [$row->complaint_type, $row->count]);
                        }
                        fputcsv($file, []);
                        break;
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to PDF format (simplified version).
     */
    private function exportToPdf($data)
    {
        // For now, return a simple HTML that can be printed as PDF
        return response()->view('admin.reports-pdf', $data)
            ->header('Content-Type', 'text/html');
    }
}
