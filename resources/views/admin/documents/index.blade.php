@extends('layouts.admin')

@section('content')
<div x-data="documentManagementApp()" x-init="init()" class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Document Request Management</h1>
        <p class="text-gray-600">View, search, and manage all document requests from residents</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Requests -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Requests</p>
                    <p class="text-3xl font-bold text-gray-800" x-text="stats.total">0</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Pending</p>
                    <p class="text-3xl font-bold text-gray-800" x-text="stats.pending">0</p>
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

        <!-- Completed -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Completed</p>
                    <p class="text-3xl font-bold text-gray-800" x-text="stats.completed">0</p>
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
                        @input.debounce.500ms="fetchRequests()"
                        placeholder="Search by name or Transaction ID..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Document Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Document Type</label>
                <select 
                    x-model="filterDocumentType"
                    @change="fetchRequests()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="all">All Types</option>
                    <option value="barangay_clearance">Barangay Clearance</option>
                    <option value="barangay_certificate">Barangay Certificate</option>
                    <option value="indigency_clearance">Indigency Clearance</option>
                    <option value="resident_certificate">Resident Certificate</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select 
                    x-model="filterStatus"
                    @change="fetchRequests()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="all">All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Ready for Pickup">Ready for Pickup</option>
                    <option value="Completed">Completed</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
        </div>

        <!-- Clear Filters Button -->
        <div class="mt-4 flex justify-end">
            <button 
                @click="clearFilters()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
            >
                Clear Filters
            </button>
        </div>
    </div>

    <!-- Document Requests Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">All Document Requests</h2>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>

        <!-- Table -->
        <div x-show="!loading">
            <!-- Empty State -->
            <div x-show="requests.length === 0" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No document requests found</h3>
                <p class="mt-1 text-sm text-gray-500">No requests match your current filters.</p>
            </div>

            <!-- Table with Data -->
            <div x-show="requests.length > 0" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resident Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Requested</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="request in requests" :key="request.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900" x-text="request.transaction_id"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900" x-text="request.resident_name"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700" x-text="request.document_type_display"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-500" x-text="request.date_requested"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        :class="getStatusClass(request.status)"
                                        x-text="request.status"
                                    ></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <!-- View Details -->
                                        <button 
                                            @click="viewDetails(request.id)"
                                            class="text-blue-600 hover:text-blue-900 transition-colors"
                                            title="View Details"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        
                                        <!-- Update Status -->
                                        <button 
                                            @click="openStatusModal(request)"
                                            class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                            title="Update Status"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                        </button>
                                        
                                        <!-- Mark Complete -->
                                        <button 
                                            x-show="request.status !== 'Completed' && request.status !== 'Rejected'"
                                            @click="markComplete(request.id)"
                                            class="text-green-600 hover:text-green-900 transition-colors"
                                            title="Mark as Completed"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
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

    <!-- View Details Modal -->
    <div x-show="showDetailsModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true"
    >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showDetailsModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 @click="closeDetailsModal()"
            ></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="showDetailsModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full"
            >
                <div class="bg-white px-6 pt-5 pb-4">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                        <h3 class="text-2xl font-semibold text-gray-900">Document Request Details</h3>
                        <button @click="closeDetailsModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Content -->
                    <div x-show="loadingDetails" class="flex justify-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>

                    <div x-show="!loadingDetails && requestDetails" class="mt-6 space-y-6">
                        <!-- Transaction Info -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Transaction ID</p>
                                    <p class="text-lg font-semibold text-gray-900" x-text="requestDetails?.transaction_id"></p>
                                </div>
                                <span 
                                    class="px-3 py-1 text-sm font-semibold rounded-full"
                                    :class="getStatusClass(requestDetails?.status)"
                                    x-text="requestDetails?.status"
                                ></span>
                            </div>
                        </div>

                        <!-- Resident Information -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Resident Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Full Name</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="requestDetails?.resident?.name"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Email</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="requestDetails?.resident?.email"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Phone</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="requestDetails?.resident?.phone"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Address</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="requestDetails?.resident?.address"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Document Details -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Document Details</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Document Type</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="requestDetails?.document_type"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Date Requested</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="requestDetails?.date_requested"></p>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-sm text-gray-600">Purpose</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="requestDetails?.purpose"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Valid ID Type</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="requestDetails?.valid_id_type"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Valid ID Number</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="requestDetails?.valid_id_number"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Registered Voter</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="requestDetails?.registered_voter"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Length of Residency</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="requestDetails?.length_of_residency"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information (for Indigency) -->
                        <div x-show="requestDetails?.civil_status || requestDetails?.employment_status || requestDetails?.monthly_income">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Additional Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div x-show="requestDetails?.civil_status">
                                    <p class="text-sm text-gray-600">Civil Status</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="requestDetails?.civil_status"></p>
                                </div>
                                <div x-show="requestDetails?.employment_status">
                                    <p class="text-sm text-gray-600">Employment Status</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="requestDetails?.employment_status"></p>
                                </div>
                                <div x-show="requestDetails?.monthly_income">
                                    <p class="text-sm text-gray-600">Monthly Income</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="requestDetails?.monthly_income"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Requirement File -->
                        <div x-show="requestDetails?.requirement_file">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Attached File</h4>
                            <a 
                                :href="requestDetails?.requirement_file" 
                                target="_blank"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                View Attached File
                            </a>
                        </div>

                        <!-- Processing Information -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Processing Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div x-show="requestDetails?.processed_by">
                                    <p class="text-sm text-gray-600">Processed By</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="requestDetails?.processed_by"></p>
                                </div>
                                <div x-show="requestDetails?.processed_at">
                                    <p class="text-sm text-gray-600">Processed At</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="requestDetails?.processed_at"></p>
                                </div>
                                <div x-show="requestDetails?.released_at">
                                    <p class="text-sm text-gray-600">Released At</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="requestDetails?.released_at"></p>
                                </div>
                                <div class="col-span-2" x-show="requestDetails?.remarks">
                                    <p class="text-sm text-gray-600">Remarks</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="requestDetails?.remarks"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <button 
                        @click="closeDetailsModal()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div x-show="showStatusModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true"
    >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showStatusModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 @click="closeStatusModal()"
            ></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="showStatusModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            >
                <form @submit.prevent="submitStatusUpdate()">
                    <div class="bg-white px-6 pt-5 pb-4">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-900">Update Request Status</h3>
                            <button type="button" @click="closeStatusModal()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Modal Content -->
                        <div class="mt-6 space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Transaction ID</p>
                                <p class="text-lg font-semibold text-gray-900" x-text="selectedRequest?.transaction_id"></p>
                            </div>

                            <!-- Status Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    x-model="statusForm.status"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                    <option value="">Select Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Ready for Pickup">Ready for Pickup</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
                            </div>

                            <!-- Remarks -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Remarks (Optional)
                                </label>
                                <textarea 
                                    x-model="statusForm.remarks"
                                    rows="4"
                                    placeholder="Add any notes or remarks about this status update..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                ></textarea>
                                <p class="mt-1 text-xs text-gray-500">Max 500 characters</p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                        <button 
                            type="button"
                            @click="closeStatusModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            :disabled="submitting"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            <span x-show="!submitting">Update Status</span>
                            <span x-show="submitting">Updating...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
function documentManagementApp() {
    return {
        // State
        loading: false,
        loadingDetails: false,
        submitting: false,
        requests: [],
        stats: {
            total: 0,
            pending: 0,
            in_progress: 0,
            completed: 0
        },
        
        // Filters
        searchQuery: '',
        filterDocumentType: 'all',
        filterStatus: 'all',
        
        // Modals
        showDetailsModal: false,
        showStatusModal: false,
        requestDetails: null,
        selectedRequest: null,
        
        // Forms
        statusForm: {
            status: '',
            remarks: ''
        },
        
        // Initialize
        init() {
            this.fetchStats();
            this.fetchRequests();
        },
        
        // Fetch statistics
        async fetchStats() {
            try {
                const response = await fetch('/api/document-requests/stats', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    this.stats = data.stats;
                }
            } catch (error) {
                console.error('Error fetching stats:', error);
            }
        },
        
        // Fetch all requests with filters
        async fetchRequests() {
            this.loading = true;
            
            try {
                const params = new URLSearchParams();
                if (this.searchQuery) params.append('search', this.searchQuery);
                if (this.filterDocumentType !== 'all') params.append('document_type', this.filterDocumentType);
                if (this.filterStatus !== 'all') params.append('status', this.filterStatus);
                
                const response = await fetch(`/api/document-requests?${params}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    this.requests = data.requests;
                }
            } catch (error) {
                console.error('Error fetching requests:', error);
                alert('Failed to load document requests');
            } finally {
                this.loading = false;
            }
        },
        
        // View details
        async viewDetails(id) {
            this.showDetailsModal = true;
            this.loadingDetails = true;
            
            try {
                const response = await fetch(`/api/document-requests/${id}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    this.requestDetails = data.request;
                }
            } catch (error) {
                console.error('Error fetching details:', error);
                alert('Failed to load request details');
                this.closeDetailsModal();
            } finally {
                this.loadingDetails = false;
            }
        },
        
        closeDetailsModal() {
            this.showDetailsModal = false;
            this.requestDetails = null;
        },
        
        // Open status modal
        openStatusModal(request) {
            this.selectedRequest = request;
            this.statusForm.status = request.status;
            this.statusForm.remarks = request.remarks || '';
            this.showStatusModal = true;
        },
        
        closeStatusModal() {
            this.showStatusModal = false;
            this.selectedRequest = null;
            this.statusForm = { status: '', remarks: '' };
        },
        
        // Submit status update
        async submitStatusUpdate() {
            if (!this.statusForm.status) {
                alert('Please select a status');
                return;
            }
            
            this.submitting = true;
            
            try {
                const response = await fetch(`/api/document-requests/${this.selectedRequest.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(this.statusForm)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Status updated successfully!');
                    this.closeStatusModal();
                    this.fetchStats();
                    this.fetchRequests();
                } else {
                    alert('Failed to update status');
                }
            } catch (error) {
                console.error('Error updating status:', error);
                alert('Failed to update status');
            } finally {
                this.submitting = false;
            }
        },
        
        // Mark as complete
        async markComplete(id) {
            if (!confirm('Mark this request as completed?')) {
                return;
            }
            
            try {
                const response = await fetch(`/api/document-requests/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        status: 'Completed',
                        remarks: 'Document released to resident'
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Request marked as completed!');
                    this.fetchStats();
                    this.fetchRequests();
                } else {
                    alert('Failed to mark as completed');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to mark as completed');
            }
        },
        
        // Clear filters
        clearFilters() {
            this.searchQuery = '';
            this.filterDocumentType = 'all';
            this.filterStatus = 'all';
            this.fetchRequests();
        },
        
        // Get status badge classes
        getStatusClass(status) {
            const classes = {
                'Pending': 'bg-yellow-100 text-yellow-800',
                'In Progress': 'bg-blue-100 text-blue-800',
                'Ready for Pickup': 'bg-indigo-100 text-indigo-800',
                'Completed': 'bg-green-100 text-green-800',
                'Rejected': 'bg-red-100 text-red-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        }
    };
}
</script>

<style>
[x-cloak] {
    display: none !important;
}
</style>
@endsection
