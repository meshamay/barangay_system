@extends('layouts.admin')

@section('content')
<div x-data="complaintManagementApp()" x-init="init()" class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Complaint Management</h1>
        <p class="text-gray-600">View, search, and manage all complaints from residents</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Complaints -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Complaints</p>
                    <p class="text-3xl font-bold text-gray-800" x-text="stats.total">0</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Open -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Open</p>
                    <p class="text-3xl font-bold text-gray-800" x-text="stats.open">0</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">In Progress</p>
                    <p class="text-3xl font-bold text-gray-800" x-text="stats.in_progress">0</p>
                </div>
                <div class="bg-indigo-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Resolved/Closed -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Resolved</p>
                    <p class="text-3xl font-bold text-gray-800" x-text="stats.resolved + stats.closed">0</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search Input -->
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <input 
                        type="text" 
                        x-model="searchQuery"
                        @input.debounce.500ms="fetchComplaints()"
                        placeholder="Search by resident name or subject..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select 
                    x-model="filterCategory"
                    @change="fetchComplaints()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="all">All Categories</option>
                    <option value="noise">Noise</option>
                    <option value="garbage">Garbage</option>
                    <option value="infrastructure">Infrastructure</option>
                    <option value="safety">Safety</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select 
                    x-model="filterStatus"
                    @change="fetchComplaints()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="all">All Status</option>
                    <option value="Open">Open</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Resolved">Resolved</option>
                    <option value="Closed">Closed</option>
                </select>
            </div>
        </div>

        <div class="mt-4 flex justify-between items-center">
            <button 
                @click="clearFilters()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
            >
                Clear Filters
            </button>
        </div>
    </div>

    <!-- Complaints Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">All Complaints</h2>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>

        <!-- Table -->
        <div x-show="!loading">
            <!-- Empty State -->
            <div x-show="complaints.length === 0" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No complaints found</h3>
                <p class="mt-1 text-sm text-gray-500">No complaints match your current filters.</p>
            </div>

            <!-- Table with Data -->
            <div x-show="complaints.length > 0" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resident Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Filed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="complaint in complaints" :key="complaint.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900" x-text="'#' + complaint.id"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900" x-text="complaint.resident_name"></span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-700" x-text="complaint.subject"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600" x-text="complaint.category_display"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-500" x-text="complaint.date_filed"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        :class="getStatusClass(complaint.status)"
                                        x-text="complaint.status"
                                    ></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <!-- View Details -->
                                        <button 
                                            @click="viewDetails(complaint.id)"
                                            class="text-blue-600 hover:text-blue-900"
                                        >
                                            View
                                        </button>
                                        
                                        <!-- Update Status -->
                                        <button 
                                            @click="openStatusModal(complaint)"
                                            class="text-indigo-600 hover:text-indigo-900"
                                        >
                                            Update
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
function complaintManagementApp() {
    return {
        loading: false,
        complaints: [],
        stats: {
            total: 0,
            open: 0,
            in_progress: 0,
            resolved: 0,
            closed: 0
        },
        searchQuery: '',
        filterCategory: 'all',
        filterStatus: 'all',
        
        init() {
            this.fetchStats();
            this.fetchComplaints();
        },
        
        async fetchStats() {
            try {
                // For now, use the initial values from the backend
                this.stats = {
                    total: {{ $totalCount }},
                    open: {{ $openCount }},
                    in_progress: {{ $inProgressCount }},
                    resolved: {{ $resolvedCount }},
                    closed: {{ $closedCount }}
                };
            } catch (error) {
                console.error('Error fetching stats:', error);
            }
        },
        
        async fetchComplaints() {
            this.loading = true;
            
            try {
                const params = new URLSearchParams();
                if (this.searchQuery) params.append('search', this.searchQuery);
                if (this.filterCategory !== 'all') params.append('category', this.filterCategory);
                if (this.filterStatus !== 'all') params.append('status', this.filterStatus);
                
                // For now, use the backend data and filter client-side
                const allComplaints = @json($complaintsJson);
                
                this.complaints = allComplaints.filter(c => {
                    const matchesSearch = !this.searchQuery || 
                        c.resident_name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                        c.subject.toLowerCase().includes(this.searchQuery.toLowerCase());
                    const matchesCategory = this.filterCategory === 'all' || c.category === this.filterCategory;
                    const matchesStatus = this.filterStatus === 'all' || c.status === this.filterStatus;
                    return matchesSearch && matchesCategory && matchesStatus;
                });
            } catch (error) {
                console.error('Error fetching complaints:', error);
                alert('Failed to load complaints');
            } finally {
                this.loading = false;
            }
        },
        
        viewDetails(id) {
            window.location.href = `/admin/complaints/${id}`;
        },
        
        openStatusModal(complaint) {
            // Navigate to detail page for now
            window.location.href = `/admin/complaints/${complaint.id}`;
        },
        
        clearFilters() {
            this.searchQuery = '';
            this.filterCategory = 'all';
            this.filterStatus = 'all';
            this.fetchComplaints();
        },
        
        getStatusClass(status) {
            const statusMap = {
                'Open': 'bg-yellow-100 text-yellow-800',
                'In Progress': 'bg-blue-100 text-blue-800',
                'Resolved': 'bg-green-100 text-green-800',
                'Closed': 'bg-gray-100 text-gray-800'
            };
            return statusMap[status] || 'bg-gray-100 text-gray-800';
        }
    };
}
</script>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection
