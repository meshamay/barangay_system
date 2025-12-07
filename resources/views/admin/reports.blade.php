@extends('layouts.admin')

@section('title', 'Reports & Analytics')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Reports & Analytics</h1>
                <p class="text-gray-600 mt-1">Barangay Management System Analytics</p>
            </div>
            <div class="flex gap-4 items-center">
                <!-- Year Selector -->
                <select id="yearSelector" class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                
                <!-- Month Selector -->
                <select id="monthSelector" class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $index => $monthName)
                        <option value="{{ $index + 1 }}" {{ $month == $index + 1 ? 'selected' : '' }}>{{ $monthName }}</option>
                    @endforeach
                </select>
                
                <!-- Export Button -->
                <button onclick="openExportModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow-lg transition duration-150">
                    <i class="fas fa-download mr-2"></i>EXPORT
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</div>
                <div class="text-gray-600 text-sm mt-1">TOTAL USERS</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="text-3xl font-bold text-gray-800">{{ $totalResidents }}</div>
                <div class="text-gray-600 text-sm mt-1">TOTAL REGISTERED RESIDENTS</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="text-3xl font-bold text-gray-800">{{ $totalStaff }}</div>
                <div class="text-gray-600 text-sm mt-1">TOTAL REGISTERED STAFFS</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                <div class="text-3xl font-bold text-gray-800">{{ $archivedAccounts }}</div>
                <div class="text-gray-600 text-sm mt-1">ARCHIVED ACCOUNTS</div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Chart 1: Population by Gender -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">POPULATION BY GENDER ({{ $year }})</h3>
                <canvas id="genderChart" height="300"></canvas>
            </div>

            <!-- Chart 2: Total Request & Complaint -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">TOTAL REQUEST & COMPLAINT (Month of {{ date('F', mktime(0, 0, 0, $month, 1)) }})</h3>
                <canvas id="requestComplaintChart" height="300"></canvas>
            </div>

            <!-- Chart 3: Most Requested Document -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">MOST REQUESTED DOCUMENT (Month of {{ date('F', mktime(0, 0, 0, $month, 1)) }})</h3>
                <canvas id="documentTypeChart" height="300"></canvas>
            </div>

            <!-- Chart 4: Request Status Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">REQUEST STATUS SUMMARY (Month of {{ date('F', mktime(0, 0, 0, $month, 1)) }})</h3>
                <canvas id="requestStatusChart" height="300"></canvas>
            </div>

            <!-- Chart 5: Most Reported Complaints -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">MOST REPORTED COMPLAINTS (Month of {{ date('F', mktime(0, 0, 0, $month, 1)) }})</h3>
                <canvas id="complaintTypeChart" height="300"></canvas>
            </div>

            <!-- Chart 6: Complaint Status Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">COMPLAINT STATUS SUMMARY (Month of {{ date('F', mktime(0, 0, 0, $month, 1)) }})</h3>
                <canvas id="complaintStatusChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div id="exportModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-6 pb-4 border-b">
            <div class="flex items-center gap-3">
                <img src="/images/barangay-logo.png" alt="Logo" class="h-12 w-12" onerror="this.style.display='none'">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Export Reports</h3>
                    <p class="text-sm text-gray-600">Select export options</p>
                </div>
            </div>
            <button onclick="closeExportModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
        </div>

        <!-- Modal Body -->
        <form id="exportForm" action="{{ route('admin.reports.export') }}" method="GET">
            <!-- Date Selection -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Select Period</label>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Year</label>
                        <select name="export_year" class="w-full rounded-lg border-gray-300">
                            @for($y = now()->year; $y >= now()->year - 5; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Month</label>
                        <select name="export_month" class="w-full rounded-lg border-gray-300">
                            @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $index => $monthName)
                                <option value="{{ $index + 1 }}" {{ $month == $index + 1 ? 'selected' : '' }}>{{ $monthName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Export Format -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Export Format</label>
                <div class="space-y-3">
                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="format" value="pdf" class="mr-3" checked>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900">📄 PDF Document</div>
                            <div class="text-xs text-gray-600">Print-friendly format with charts and tables</div>
                        </div>
                    </label>
                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="format" value="excel" class="mr-3">
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900">📊 Excel Spreadsheet</div>
                            <div class="text-xs text-gray-600">Editable data format for further analysis</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Sections to Include -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Sections to Include</label>
                <div class="space-y-2">
                    <label class="flex items-center p-3 hover:bg-gray-50 rounded">
                        <input type="checkbox" name="sections[]" value="gender" class="mr-3" checked>
                        <span class="text-sm text-gray-700">☑ POPULATION BY GENDER</span>
                    </label>
                    <label class="flex items-center p-3 hover:bg-gray-50 rounded">
                        <input type="checkbox" name="sections[]" value="request_complaint" class="mr-3" checked>
                        <span class="text-sm text-gray-700">☑ TOTAL REQUEST & COMPLAINT</span>
                    </label>
                    <label class="flex items-center p-3 hover:bg-gray-50 rounded">
                        <input type="checkbox" name="sections[]" value="document_type" class="mr-3" checked>
                        <span class="text-sm text-gray-700">☑ MOST REQUESTED DOCUMENT</span>
                    </label>
                    <label class="flex items-center p-3 hover:bg-gray-50 rounded">
                        <input type="checkbox" name="sections[]" value="request_status" class="mr-3" checked>
                        <span class="text-sm text-gray-700">☑ REQUEST STATUS SUMMARY</span>
                    </label>
                    <label class="flex items-center p-3 hover:bg-gray-50 rounded">
                        <input type="checkbox" name="sections[]" value="complaint_type" class="mr-3" checked>
                        <span class="text-sm text-gray-700">☑ MOST REPORTED COMPLAINTS</span>
                    </label>
                    <label class="flex items-center p-3 hover:bg-gray-50 rounded">
                        <input type="checkbox" name="sections[]" value="complaint_status" class="mr-3" checked>
                        <span class="text-sm text-gray-700">☑ COMPLAINT STATUS SUMMARY</span>
                    </label>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" onclick="closeExportModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    CANCEL
                </button>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                    EXPORT
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart 1: Population by Gender
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($populationByGender->pluck('gender')->map(fn($g) => ucfirst($g ?? 'Unknown'))) !!},
            datasets: [{
                label: 'Population',
                data: {!! json_encode($populationByGender->pluck('count')) !!},
                backgroundColor: ['#EC4899', '#3B82F6', '#9CA3AF'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Chart 2: Total Request & Complaint
    const reqComplaintCtx = document.getElementById('requestComplaintChart').getContext('2d');
    new Chart(reqComplaintCtx, {
        type: 'bar',
        data: {
            labels: ['Document Requests', 'Complaints'],
            datasets: [{
                label: 'Count',
                data: [{{ $totalRequestsMonth }}, {{ $totalComplaintsMonth }}],
                backgroundColor: ['#1E40AF', '#60A5FA'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Chart 3: Most Requested Document
    const docTypeCtx = document.getElementById('documentTypeChart').getContext('2d');
    new Chart(docTypeCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($documentsByType->pluck('document_type')->map(function($type) {
                return str_replace('_', ' ', ucwords($type, '_'));
            })) !!},
            datasets: [{
                label: 'Requests',
                data: {!! json_encode($documentsByType->pluck('count')) !!},
                backgroundColor: ['#F97316', '#10B981', '#FBBF24', '#8B5CF6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Chart 4: Request Status Summary
    const reqStatusCtx = document.getElementById('requestStatusChart').getContext('2d');
    new Chart(reqStatusCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($documentsByStatus->pluck('status')) !!},
            datasets: [{
                label: 'Count',
                data: {!! json_encode($documentsByStatus->pluck('count')) !!},
                backgroundColor: ['#EF4444', '#F97316', '#3B82F6', '#10B981'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Chart 5: Most Reported Complaints
    const complaintTypeCtx = document.getElementById('complaintTypeChart').getContext('2d');
    new Chart(complaintTypeCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($complaintsByType->pluck('complaint_type')) !!},
            datasets: [{
                label: 'Complaints',
                data: {!! json_encode($complaintsByType->pluck('count')) !!},
                backgroundColor: ['#F97316', '#10B981', '#FBBF24', '#8B5CF6', '#EC4899'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    // Chart 6: Complaint Status Summary
    const complaintStatusCtx = document.getElementById('complaintStatusChart').getContext('2d');
    new Chart(complaintStatusCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($complaintsByStatus->pluck('status')) !!},
            datasets: [{
                label: 'Count',
                data: {!! json_encode($complaintsByStatus->pluck('count')) !!},
                backgroundColor: ['#EF4444', '#F97316', '#3B82F6', '#10B981'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Modal Functions
    function openExportModal() {
        document.getElementById('exportModal').classList.remove('hidden');
    }

    function closeExportModal() {
        document.getElementById('exportModal').classList.add('hidden');
    }

    // Year/Month change handlers
    document.getElementById('yearSelector').addEventListener('change', function() {
        updateReports();
    });

    document.getElementById('monthSelector').addEventListener('change', function() {
        updateReports();
    });

    function updateReports() {
        const year = document.getElementById('yearSelector').value;
        const month = document.getElementById('monthSelector').value;
        window.location.href = `{{ route('admin.reports') }}?year=${year}&month=${month}`;
    }
</script>
@endpush
@endsection
