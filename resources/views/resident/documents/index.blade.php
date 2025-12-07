@extends('layouts.resident')

@section('title', 'My Document Requests')
@section('page-title', 'Document Requests')

@section('content')
<div x-data="documentRequestApp()" x-init="init()">
    <!-- Stats Cards -->
    <div class="grid md:grid-cols-3 gap-6 mb-6">
        <!-- Pending -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pending</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2" x-text="stats.pending">0</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
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

        <!-- Completed -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Completed</p>
                    <p class="text-3xl font-bold text-green-600 mt-2" x-text="stats.completed">0</p>
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
            <h2 class="text-lg font-semibold text-gray-800">All Requests</h2>
            
            <!-- Document Request Dropdown -->
            <div class="relative" x-data="{ dropdownOpen: false }">
                <button @click="dropdownOpen = !dropdownOpen" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium flex items-center space-x-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>New Document Request</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="dropdownOpen" 
                     @click.away="dropdownOpen = false"
                     x-transition
                     class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl z-50 py-2">
                    <button @click="openModal('Barangay Clearance'); dropdownOpen = false" 
                            class="w-full text-left px-4 py-3 hover:bg-blue-50 flex items-center space-x-3">
                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <div>
                            <p class="font-medium text-gray-800">Barangay Clearance</p>
                            <p class="text-xs text-gray-500">Proof of residency</p>
                        </div>
                    </button>
                    
                    <button @click="openModal('Barangay Certificate'); dropdownOpen = false" 
                            class="w-full text-left px-4 py-3 hover:bg-green-50 flex items-center space-x-3">
                        <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        <div>
                            <p class="font-medium text-gray-800">Barangay Certificate</p>
                            <p class="text-xs text-gray-500">Official certification</p>
                        </div>
                    </button>
                    
                    <button @click="openModal('Indigency Clearance'); dropdownOpen = false" 
                            class="w-full text-left px-4 py-3 hover:bg-yellow-50 flex items-center space-x-3">
                        <svg class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <div>
                            <p class="font-medium text-gray-800">Indigency Clearance</p>
                            <p class="text-xs text-gray-500">For low income residents</p>
                        </div>
                    </button>
                    
                    <button @click="openModal('Resident Certificate'); dropdownOpen = false" 
                            class="w-full text-left px-4 py-3 hover:bg-purple-50 flex items-center space-x-3">
                        <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </svg>
                        <div>
                            <p class="font-medium text-gray-800">Resident Certificate</p>
                            <p class="text-xs text-gray-500">Proof of residence</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Requested</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-if="loading">
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <svg class="animate-spin h-8 w-8 mx-auto text-blue-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </td>
                        </tr>
                    </template>
                    
                    <template x-if="!loading && requests.length === 0">
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                No document requests yet. Click "New Document Request" to get started.
                            </td>
                        </tr>
                    </template>

                    <template x-for="request in requests" :key="request.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="request.transaction_id"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="request.last_name"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="request.first_name"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="request.document_type"></td>
                            <td class="px-6 py-4 text-sm text-gray-600" x-text="request.purpose"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="formatDate(request.date_requested)"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="request.status_class" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" x-text="request.status"></span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Container -->
    <div x-show="currentModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         @keydown.escape.window="closeModal()">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal()"></div>
            
            <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full p-6 my-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-800" x-text="currentModal + ' Application'"></h3>
                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Form for Barangay Clearance, Barangay Certificate, Resident Certificate -->
                <form x-show="['Barangay Clearance', 'Barangay Certificate', 'Resident Certificate'].includes(currentModal)" 
                      @submit.prevent="submitForm()"
                      class="space-y-4">
                    
                    <!-- Personal Information -->
                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   x-model="formData.first_name" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Middle Name
                            </label>
                            <input type="text" 
                                   x-model="formData.middle_name"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   x-model="formData.last_name" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Date of Birth <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   x-model="formData.birthdate" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Place of Birth <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   x-model="formData.birthplace" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Civil Status <span class="text-red-500">*</span>
                            </label>
                            <select x-model="formData.civil_status" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Civil Status</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Widowed">Widowed</option>
                                <option value="Separated">Separated</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Length of Residency <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   x-model="formData.length_of_residency" 
                                   placeholder="e.g., 5 years"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Valid ID Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   x-model="formData.valid_id_number" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Registered Voter <span class="text-red-500">*</span>
                            </label>
                            <select x-model="formData.registered_voter" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Purpose of Request <span class="text-red-500">*</span>
                        </label>
                        <textarea x-model="formData.purpose" 
                                  required
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>

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
                            <span x-text="submitting ? 'Submitting...' : 'Submit Request'"></span>
                        </button>
                    </div>
                </form>

                <!-- Form for Indigency Clearance -->
                <form x-show="currentModal === 'Indigency Clearance'" 
                      @submit.prevent="submitForm()"
                      enctype="multipart/form-data"
                      class="space-y-4">
                    
                    <!-- Personal Information -->
                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   x-model="formData.first_name" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Middle Name
                            </label>
                            <input type="text" 
                                   x-model="formData.middle_name"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   x-model="formData.last_name" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Date of Birth <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   x-model="formData.birthdate" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Place of Birth <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   x-model="formData.birthplace" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Civil Status <span class="text-red-500">*</span>
                        </label>
                        <select x-model="formData.civil_status" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Civil Status</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Separated">Separated</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Certificate of being Indigent <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               x-model="formData.certificate_type" 
                               placeholder="e.g., DSWD Certificate"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Other Purpose
                        </label>
                        <textarea x-model="formData.other_purpose" 
                                  rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Requirement File (PDF/JPG/PNG, Max 5MB) <span class="text-red-500">*</span>
                        </label>
                        <input type="file" 
                               @change="handleFileChange($event)"
                               accept=".pdf,.jpg,.jpeg,.png"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Upload supporting documents (DSWD Certificate, etc.)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Purpose of Request <span class="text-red-500">*</span>
                        </label>
                        <textarea x-model="formData.purpose" 
                                  required
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>

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
                            <span x-text="submitting ? 'Submitting...' : 'Submit Request'"></span>
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
                    <p class="text-sm text-gray-600 mb-1">Your document request has been submitted.</p>
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
function documentRequestApp() {
    return {
        loading: false,
        submitting: false,
        currentModal: null,
        showSuccessModal: false,
        successTransactionId: '',
        requests: [],
        stats: {
            pending: 0,
            in_progress: 0,
            completed: 0
        },
        formData: {},
        selectedFile: null,

        init() {
            this.fetchRequests();
        },

        async fetchRequests() {
            this.loading = true;
            try {
                const response = await fetch('/api/user/document-requests', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch requests');
                }

                const data = await response.json();
                
                if (data.status === 'success') {
                    this.requests = data.data;
                    this.stats = data.counts;
                }
            } catch (error) {
                console.error('Error fetching requests:', error);
            } finally {
                this.loading = false;
            }
        },

        openModal(documentType) {
            this.currentModal = documentType;
            
            // Convert display name to snake_case for API
            const documentTypeMap = {
                'Barangay Clearance': 'barangay_clearance',
                'Barangay Certificate': 'barangay_certificate',
                'Indigency Clearance': 'indigency_clearance',
                'Resident Certificate': 'resident_certificate'
            };
            
            this.formData = {
                document_type: documentTypeMap[documentType] || documentType.toLowerCase().replace(/ /g, '_'),
                first_name: '',
                middle_name: '',
                last_name: '',
                birthdate: '',
                birthplace: '',
                civil_status: '',
                length_of_residency: '',
                valid_id_number: '',
                registered_voter: '',
                certificate_type: '',
                other_purpose: '',
                purpose: ''
            };
            this.selectedFile = null;
        },

        closeModal() {
            this.currentModal = null;
            this.formData = {};
            this.selectedFile = null;
        },

        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must not exceed 5MB');
                    event.target.value = '';
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Only PDF, JPG, and PNG files are allowed');
                    event.target.value = '';
                    return;
                }
                
                this.selectedFile = file;
            }
        },

        async submitForm() {
            if (this.submitting) return;

            this.submitting = true;
            
            try {
                let formDataToSend;
                
                // If Indigency Clearance with file, use FormData
                if (this.currentModal === 'Indigency Clearance' && this.selectedFile) {
                    formDataToSend = new FormData();
                    
                    // Append all form fields
                    for (const key in this.formData) {
                        if (this.formData[key]) {
                            formDataToSend.append(key, this.formData[key]);
                        }
                    }
                    
                    // Append file
                    formDataToSend.append('requirement_file', this.selectedFile);
                } else {
                    // For other document types, use JSON
                    formDataToSend = this.formData;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                const headers = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                };

                // Only add Content-Type for JSON requests
                if (!(formDataToSend instanceof FormData)) {
                    headers['Content-Type'] = 'application/json';
                    headers['Accept'] = 'application/json';
                }

                const response = await fetch('/api/user/document-request', {
                    method: 'POST',
                    headers: headers,
                    body: formDataToSend instanceof FormData ? formDataToSend : JSON.stringify(formDataToSend),
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    // Close form modal
                    this.closeModal();
                    
                    // Show success modal
                    this.successTransactionId = result.data.transaction_id;
                    this.showSuccessModal = true;
                    
                    // Refresh requests list
                    await this.fetchRequests();
                } else {
                    alert(result.message || 'An error occurred. Please try again.');
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                alert('An error occurred while submitting your request. Please try again.');
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
