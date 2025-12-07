@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<!-- Stats Grid -->
<div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Residents -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Residents</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalResidents }}</p>
                <p class="text-sm text-green-600 mt-1">↑ {{ $newResidentsThisMonth }} this month</p>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Pending Registrations -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Pending Registrations</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $pendingRegistrations }}</p>
                <a href="{{ route('admin.residents.index', ['status' => 'pending']) }}" class="text-sm text-blue-600 hover:text-blue-800 mt-1 inline-block">
                    Review now →
                </a>
            </div>
            <div class="bg-yellow-100 rounded-full p-3">
                <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Document Requests -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Document Requests</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $pendingDocuments }}</p>
                <a href="{{ route('admin.documents.index', ['status' => 'pending']) }}" class="text-sm text-blue-600 hover:text-blue-800 mt-1 inline-block">
                    View requests →
                </a>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Active Complaints -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Active Complaints</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $activeComplaints }}</p>
                <a href="{{ route('admin.complaints.index', ['status' => 'pending']) }}" class="text-sm text-blue-600 hover:text-blue-800 mt-1 inline-block">
                    Manage complaints →
                </a>
            </div>
            <div class="bg-red-100 rounded-full p-3">
                <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid md:grid-cols-2 gap-6 mb-6">
    <!-- Monthly Activity Chart -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Activity</h3>
        <div class="h-64 flex items-end justify-between space-x-2">
            @foreach($monthlyActivity as $month => $count)
                <div class="flex-1 bg-blue-600 rounded-t hover:bg-blue-700 transition cursor-pointer relative group"
                     style="height: {{ ($count / max($monthlyActivity)) * 100 }}%">
                    <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition">
                        {{ $count }} requests
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex justify-between mt-4 text-xs text-gray-500">
            <span>Jan</span>
            <span>Feb</span>
            <span>Mar</span>
            <span>Apr</span>
            <span>May</span>
            <span>Jun</span>
        </div>
    </div>

    <!-- Demographics -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Resident Demographics</h3>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Male</span>
                    <span class="font-semibold text-gray-800">{{ $maleCount }} ({{ $malePercentage }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $malePercentage }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Female</span>
                    <span class="font-semibold text-gray-800">{{ $femaleCount }} ({{ $femalePercentage }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-pink-600 h-2 rounded-full" style="width: {{ $femalePercentage }}%"></div>
                </div>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-200">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Document Types Requested</h4>
            <div class="space-y-2">
                @foreach($documentTypes as $type => $count)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ ucwords(str_replace('_', ' ', $type)) }}</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity & Quick Actions -->
<div class="grid md:grid-cols-3 gap-6">
    <!-- Recent Registrations -->
    <div class="md:col-span-2 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Recent Registrations</h3>
            <a href="{{ route('admin.residents.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentRegistrations as $registration)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $registration->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $registration->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $registration->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$registration->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($registration->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">No recent registrations</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="space-y-3">
            <a href="{{ route('admin.announcements.create') }}" 
               class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
                <span class="text-sm font-medium text-gray-800">Post Announcement</span>
            </a>

            <a href="{{ route('admin.officials.create') }}" 
               class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition">
                <svg class="h-5 w-5 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                <span class="text-sm font-medium text-gray-800">Add Official</span>
            </a>

            <a href="{{ route('admin.reports') }}" 
               class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                <svg class="h-5 w-5 text-purple-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-sm font-medium text-gray-800">Generate Report</span>
            </a>

            @if(Auth::user()->role === 'super_admin')
                <a href="{{ route('superadmin.staff.create') }}" 
                   class="flex items-center p-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                    <svg class="h-5 w-5 text-indigo-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-800">Add Staff Member</span>
                </a>
            @endif
        </div>

        <div class="mt-6 pt-6 border-t border-gray-200">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">System Status</h4>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Database</span>
                    <span class="flex items-center text-xs text-green-600">
                        <span class="h-2 w-2 bg-green-600 rounded-full mr-1"></span>
                        Online
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Last Backup</span>
                    <span class="text-xs text-gray-500">Today, 12:00 AM</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
