@extends('layouts.resident')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div x-data="dashboardApp()" x-init="init()">
<!-- Welcome Card -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-md p-6 mb-6 text-white">
    <h2 class="text-2xl font-bold mb-2">Welcome back, {{ Auth::user()->first_name }}!</h2>
    <p class="text-blue-100">Resident ID: {{ Auth::user()->resident->resident_id ?? 'Pending' }}</p>
</div>

<!-- Stats Grid -->
<div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Pending Documents -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Pending Requests</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $pendingDocuments }}</p>
            </div>
            <div class="bg-yellow-100 rounded-full p-3">
                <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Completed Documents -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Completed</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $completedDocuments }}</p>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
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
            </div>
            <div class="bg-red-100 rounded-full p-3">
                <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Requests -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Requests</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalRequests }}</p>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="space-y-3">
            <button @click="showDocumentModal = true" 
               class="w-full flex items-center justify-between p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="font-medium text-gray-800">Request Document</span>
                </div>
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            
            <a href="{{ route('resident.complaints.create') }}" 
               class="flex items-center justify-between p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-yellow-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="font-medium text-gray-800">File a Complaint</span>
                </div>
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Announcements</h3>
        <div class="space-y-3">
            @forelse($recentAnnouncements as $announcement)
                <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                    <svg class="h-5 w-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800 text-sm">{{ $announcement->title }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $announcement->published_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm">No recent announcements</p>
            @endforelse
            <a href="{{ route('resident.announcements') }}" class="block text-center text-sm text-blue-600 hover:text-blue-800 mt-3">
                View All Announcements →
            </a>
        </div>
    </div>
</div>

<!-- Recent Document Requests -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-800">Recent Document Requests</h3>
        <a href="{{ route('resident.documents.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
            View All →
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Requested</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-if="recentRequests.length === 0">
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            No document requests yet. Click "Request Document" to get started →
                        </td>
                    </tr>
                </template>
                <template x-for="doc in recentRequests" :key="doc.id">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="doc.transaction_id"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="doc.document_type_display"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="doc.date_requested"></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                  :class="getStatusClass(doc.status)"
                                  x-text="doc.status"></span>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>

<!-- Document Request Modal -->
<div x-show="showDocumentModal" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     @keydown.escape.window="showDocumentModal = false">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="showDocumentModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
             @click="showDocumentModal = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div x-show="showDocumentModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            
            <form @submit.prevent="submitRequest()">
                <div class="bg-white px-6 pt-5 pb-4">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900">Request Document</h3>
                        <button type="button" @click="showDocumentModal = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="mt-6 space-y-4">
                        <!-- Document Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Document Type <span class="text-red-500">*</span>
                            </label>
                            <select x-model="form.document_type" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Document Type</option>
                                <option value="barangay_clearance">Barangay Clearance</option>
                                <option value="barangay_certificate">Barangay Certificate</option>
                                <option value="indigency_clearance">Indigency Clearance</option>
                                <option value="resident_certificate">Resident Certificate</option>
                            </select>
                        </div>

                        <!-- Purpose -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Purpose <span class="text-red-500">*</span>
                            </label>
                            <textarea x-model="form.purpose" required rows="3"
                                      placeholder="Enter the purpose of this document request..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <button type="button" @click="showDocumentModal = false"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" :disabled="submitting"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        <span x-show="!submitting">Submit Request</span>
                        <span x-show="submitting">Submitting...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

<script>
function dashboardApp() {
    return {
        showDocumentModal: false,
        submitting: false,
        recentRequests: @json($recentDocumentsJson),
        form: {
            document_type: '',
            purpose: ''
        },

        init() {
            // Initialize
        },

        async submitRequest() {
            if (!this.form.document_type || !this.form.purpose) {
                alert('Please fill in all required fields');
                return;
            }

            this.submitting = true;

            try {
                const response = await fetch('/api/user/document-request', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(this.form)
                });

                const data = await response.json();

                if (data.success) {
                    alert('Document request submitted successfully!');
                    
                    // Add new request to the top of the table
                    this.recentRequests.unshift({
                        id: data.request.id,
                        transaction_id: data.request.transaction_id,
                        document_type_display: this.formatDocumentType(this.form.document_type),
                        date_requested: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
                        status: 'Pending'
                    });

                    // Keep only recent 5
                    if (this.recentRequests.length > 5) {
                        this.recentRequests = this.recentRequests.slice(0, 5);
                    }

                    // Reset form and close modal
                    this.form = { document_type: '', purpose: '' };
                    this.showDocumentModal = false;
                    
                    // Reload page to update stats
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    alert(data.message || 'Failed to submit request');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to submit request. Please try again.');
            } finally {
                this.submitting = false;
            }
        },

        formatDocumentType(type) {
            return type.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        },

        getStatusClass(status) {
            const statusLower = status.toLowerCase();
            if (statusLower.includes('pending')) return 'bg-yellow-100 text-yellow-800';
            if (statusLower.includes('progress')) return 'bg-blue-100 text-blue-800';
            if (statusLower.includes('ready') || statusLower.includes('pickup')) return 'bg-green-100 text-green-800';
            if (statusLower.includes('completed')) return 'bg-gray-100 text-gray-800';
            if (statusLower.includes('rejected')) return 'bg-red-100 text-red-800';
            return 'bg-gray-100 text-gray-800';
        }
    };
}
</script>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection
