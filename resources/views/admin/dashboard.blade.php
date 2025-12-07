@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<div x-data="dashboardApp()" x-init="init()" class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard</h1>
        <p class="text-gray-600">Overview of all requests and complaints</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Users</p>
                    <p class="text-3xl font-bold text-gray-800" x-text="stats.totalUsers">{{ $totalResidents }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Requests -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Requests</p>
                    <p class="text-3xl font-bold text-gray-800" x-text="stats.totalRequests">0</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Complaints -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Complaints</p>
                    <p class="text-3xl font-bold text-gray-800" x-text="stats.totalComplaints">{{ $activeComplaints }}</p>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Completed</p>
                    <p class="text-3xl font-bold text-gray-800" x-text="stats.completed">0</p>
                </div>
                <div class="bg-indigo-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <input 
                        type="text" 
                        x-model="searchQuery"
                        @input.debounce.500ms="fetchItems()"
                        placeholder="Search by name or Transaction ID..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select 
                    x-model="filterType"
                    @change="fetchItems()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="all">All Types</option>
                    <option value="complaint">Complaint</option>
                    <option value="document">Document Type</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select 
                    x-model="filterStatus"
                    @change="fetchItems()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
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

    <!-- All Requests and Complaints Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">All Transactions</h2>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>

        <!-- Table -->
        <div x-show="!loading">
            <!-- Empty State -->
            <div x-show="items.length === 0" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions found</h3>
                <p class="mt-1 text-sm text-gray-500">No transactions match your current filters.</p>
            </div>

            <!-- Table with Data -->
            <div x-show="items.length > 0" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Filed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Completed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="item in items" :key="item.transaction_id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900" x-text="item.transaction_id"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900" x-text="item.last_name"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900" x-text="item.first_name"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        :class="item.type === 'Complaint' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'"
                                        x-text="item.type"
                                    ></span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-700" x-text="item.description"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-500" x-text="item.date_filed"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-500" x-text="item.date_completed || '-'"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        :class="getStatusClass(item.status)"
                                        x-text="item.status"
                                    ></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div> <!-- End Alpine.js wrapper -->

<script>
function dashboardApp() {
    return {
        stats: {
            totalUsers: {{ $totalResidents }},
            totalRequests: 0,
            totalComplaints: {{ $activeComplaints }},
            completed: 0
        },
        items: [],
        loading: false,
        searchQuery: '',
        filterType: 'all',
        filterStatus: 'all',

        init() {
            this.fetchItems();
        },

        async fetchItems() {
            this.loading = true;
            
            try {
                // For now, combine data from backend
                const allItems = @json($allTransactions ?? []);
                
                this.items = allItems.filter(item => {
                    const matchesSearch = !this.searchQuery || 
                        item.transaction_id.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                        item.first_name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                        item.last_name.toLowerCase().includes(this.searchQuery.toLowerCase());
                    
                    const matchesType = this.filterType === 'all' || 
                        (this.filterType === 'complaint' && item.type === 'Complaint') ||
                        (this.filterType === 'document' && item.type !== 'Complaint');
                    
                    const matchesStatus = this.filterStatus === 'all' || 
                        item.status.toLowerCase().replace(' ', '_') === this.filterStatus;
                    
                    return matchesSearch && matchesType && matchesStatus;
                });

                // Update stats
                this.updateStats();
            } catch (error) {
                console.error('Error fetching items:', error);
            } finally {
                this.loading = false;
            }
        },

        updateStats() {
            const allItems = @json($allTransactions ?? []);
            this.stats.totalRequests = allItems.filter(i => i.type !== 'Complaint').length;
            this.stats.completed = allItems.filter(i => 
                i.status === 'Completed' || i.status === 'Resolved' || i.status === 'Closed'
            ).length;
        },

        clearFilters() {
            this.searchQuery = '';
            this.filterType = 'all';
            this.filterStatus = 'all';
            this.fetchItems();
        },

        getStatusClass(status) {
            const statusMap = {
                'Pending': 'bg-yellow-100 text-yellow-800',
                'In Progress': 'bg-blue-100 text-blue-800',
                'Processing': 'bg-blue-100 text-blue-800',
                'Completed': 'bg-green-100 text-green-800',
                'Approved': 'bg-green-100 text-green-800',
                'Resolved': 'bg-green-100 text-green-800',
                'Closed': 'bg-gray-100 text-gray-800',
                'Open': 'bg-yellow-100 text-yellow-800',
                'Rejected': 'bg-red-100 text-red-800'
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
