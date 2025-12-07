@extends('layouts.resident')

@section('title', 'My Complaints')
@section('page-title', 'Complaints')

@section('content')
<div x-data="complaintApp()" x-init="init()">
    <!-- Stats Cards -->
    <div class="grid md:grid-cols-3 gap-6 mb-6">
        <!-- Open Cases -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Open Case</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2" x-text="stats.open">0</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">In Progress</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2" x-text="stats.in_progress">0</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Case Resolved -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Case Resolved</p>
                    <p class="text-3xl font-bold text-green-600 mt-2" x-text="stats.resolved">0</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">All Complaints</h2>
            
            <!-- New Complaint Button -->
            <button @click="openModal()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium flex items-center space-x-2">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>New Complaint</span>
            </button>
        </div>
    </div>

    <!-- Complaints Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Complaint Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Filed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-if="loading">
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <svg class="animate-spin h-8 w-8 mx-auto text-blue-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </td>
                        </tr>
                    </template>
                    
                    <template x-if="!loading && complaints.length === 0">
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No complaints filed yet. Click "New Complaint" to submit one.
                            </td>
                        </tr>
                    </template>

                    <template x-for="complaint in complaints" :key="complaint.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="complaint.transaction_id"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="complaint.name"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="complaint.complaint_type"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="formatDate(complaint.date_filed)"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="complaint.status_class" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" x-text="complaint.status"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a :href="'/resident/complaints/' + complaint.id" class="text-blue-600 hover:text-blue-900">View Details</a>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Complaint Modal -->
    <div x-show="showModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         @keydown.escape.window="closeModal()">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal()"></div>
            
            <div class="relative bg-white rounded-lg shadow-xl max-w-3xl w-full p-6 my-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">File a Complaint</h3>
                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitComplaint()" class="space-y-4">
                    <!-- Incident Details -->
                    <div class="border-b pb-4">
                        <h4 class="text-md font-semibold text-gray-700 mb-3">Incident Details</h4>
                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Incident Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       x-model="formData.incident_date" 
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Incident Time <span class="text-red-500">*</span>
                                </label>
                                <input type="time" 
                                       x-model="formData.incident_time" 
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Location
                                </label>
                                <input type="text" 
                                       x-model="formData.incident_location" 
                                       placeholder="e.g., Purok 5"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Defendant Information -->
                    <div class="border-b pb-4">
                        <h4 class="text-md font-semibold text-gray-700 mb-3">Defendant Information</h4>
                        <div class="grid md:grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Name of Defendant <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       x-model="formData.defendant_name" 
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Defendant Address <span class="text-red-500">*</span>
                                </label>
                                <textarea x-model="formData.defendant_address" 
                                          required
                                          rows="2"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Complaint Details -->
                    <div class="border-b pb-4">
                        <h4 class="text-md font-semibold text-gray-700 mb-3">Complaint Details</h4>
                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Type of Complaint <span class="text-red-500">*</span>
                                </label>
                                <select x-model="formData.complaint_type" 
                                        required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Type</option>
                                    <option value="Noise Complaint">Noise Complaint</option>
                                    <option value="Property Dispute">Property Dispute</option>
                                    <option value="Harassment">Harassment</option>
                                    <option value="Vandalism">Vandalism</option>
                                    <option value="Theft">Theft</option>
                                    <option value="Physical Assault">Physical Assault</option>
                                    <option value="Verbal Abuse">Verbal Abuse</option>
                                    <option value="Environmental">Environmental</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Level of Urgency <span class="text-red-500">*</span>
                                </label>
                                <select x-model="formData.urgency_level" 
                                        required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Level</option>
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                    <option value="Urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Detailed Statement <span class="text-red-500">*</span>
                            </label>
                            <textarea x-model="formData.complaint_statement" 
                                      required
                                      rows="5"
                                      placeholder="Please provide a detailed description of the incident (minimum 20 characters)"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Minimum 20 characters</p>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" 
                                @click="closeModal()"
                                class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" 
                                :disabled="submitting"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2">
                            <svg x-show="submitting" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="submitting ? 'Submitting...' : 'Submit Complaint'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div x-show="showSuccessModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeSuccessModal()"></div>
            
            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                        <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Request Submitted Successfully!</h3>
                    <p class="text-sm text-gray-600 mb-1">Your complaint has been filed.</p>
                    <p class="text-sm text-gray-600 mb-4">Transaction ID: <span class="font-semibold" x-text="successTransactionId"></span></p>
                    <button @click="closeSuccessModal()" 
                            class="w-full bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function complaintApp() {
    return {
        loading: false,
        submitting: false,
        showModal: false,
        showSuccessModal: false,
        successTransactionId: '',
        complaints: [],
        stats: {
            open: 0,
            in_progress: 0,
            resolved: 0
        },
        formData: {
            incident_date: '',
            incident_time: '',
            incident_location: '',
            defendant_name: '',
            defendant_address: '',
            complaint_type: '',
            urgency_level: '',
            complaint_statement: ''
        },

        init() {
            this.fetchComplaints();
        },

        async fetchComplaints() {
            this.loading = true;
            try {
                const response = await fetch('/api/user/complaints', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch complaints');
                }

                const data = await response.json();
                
                if (data.status === 'success') {
                    this.complaints = data.data;
                    this.stats = data.counts;
                }
            } catch (error) {
                console.error('Error fetching complaints:', error);
            } finally {
                this.loading = false;
            }
        },

        openModal() {
            this.showModal = true;
            this.formData = {
                incident_date: '',
                incident_time: '',
                incident_location: '',
                defendant_name: '',
                defendant_address: '',
                complaint_type: '',
                urgency_level: '',
                complaint_statement: ''
            };
        },

        closeModal() {
            this.showModal = false;
            this.formData = {};
        },

        async submitComplaint() {
            if (this.submitting) return;

            // Validate minimum statement length
            if (this.formData.complaint_statement.length < 20) {
                alert('Please provide a detailed statement (minimum 20 characters)');
                return;
            }

            this.submitting = true;
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                const response = await fetch('/api/user/complaint', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(this.formData),
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    // Close form modal
                    this.closeModal();
                    
                    // Show success modal
                    this.successTransactionId = result.data.transaction_id;
                    this.showSuccessModal = true;
                    
                    // Refresh complaints list
                    await this.fetchComplaints();
                } else {
                    alert(result.message || 'An error occurred. Please try again.');
                }
            } catch (error) {
                console.error('Error submitting complaint:', error);
                alert('An error occurred while submitting your complaint. Please try again.');
            } finally {
                this.submitting = false;
            }
        },

        closeSuccessModal() {
            this.showSuccessModal = false;
            this.successTransactionId = '';
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        }
    }
}
</script>

<style>
[x-cloak] {
    display: none !important;
}
</style>
@endsection
