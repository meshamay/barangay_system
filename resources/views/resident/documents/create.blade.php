@extends('layouts.resident')

@section('title', 'Request Document')
@section('page-title', 'Request Document')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-6">
            <h2 class="text-2xl font-bold text-white">Request New Document</h2>
            <p class="text-blue-100 mt-2">Fill out the form below to request an official document</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('resident.documents.store') }}" class="p-8">
            @csrf

            <div class="space-y-6">
                <!-- Document Type -->
                <div>
                    <label for="document_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Document Type *
                    </label>
                    <select name="document_type" id="document_type" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('document_type') border-red-500 @enderror">
                        <option value="">Choose a document</option>
                        <option value="barangay_clearance" {{ old('document_type') == 'barangay_clearance' ? 'selected' : '' }}>
                            Barangay Clearance
                        </option>
                        <option value="barangay_certificate" {{ old('document_type') == 'barangay_certificate' ? 'selected' : '' }}>
                            Barangay Certificate
                        </option>
                        <option value="indigency_clearance" {{ old('document_type') == 'indigency_clearance' ? 'selected' : '' }}>
                            Indigency Clearance
                        </option>
                        <option value="resident_certificate" {{ old('document_type') == 'resident_certificate' ? 'selected' : '' }}>
                            Resident Certificate
                        </option>
                    </select>
                    @error('document_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Document Descriptions -->
                <div x-data="{ selectedDoc: '{{ old('document_type', '') }}' }" x-init="$watch('selectedDoc', value => document.getElementById('document_type').value = value)">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div @click="selectedDoc = 'barangay_clearance'" 
                             :class="selectedDoc === 'barangay_clearance' ? 'border-blue-600 bg-blue-50' : 'border-gray-200'"
                             class="border-2 rounded-lg p-4 cursor-pointer hover:border-blue-400 transition">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-6 w-6 mt-1">
                                    <div :class="selectedDoc === 'barangay_clearance' ? 'bg-blue-600' : 'bg-gray-300'" 
                                         class="h-6 w-6 rounded-full flex items-center justify-center">
                                        <svg x-show="selectedDoc === 'barangay_clearance'" class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-semibold text-gray-800">Barangay Clearance</h4>
                                    <p class="text-xs text-gray-600 mt-1">Proof of residency and good standing</p>
                                </div>
                            </div>
                        </div>

                        <div @click="selectedDoc = 'barangay_certificate'" 
                             :class="selectedDoc === 'barangay_certificate' ? 'border-blue-600 bg-blue-50' : 'border-gray-200'"
                             class="border-2 rounded-lg p-4 cursor-pointer hover:border-blue-400 transition">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-6 w-6 mt-1">
                                    <div :class="selectedDoc === 'barangay_certificate' ? 'bg-blue-600' : 'bg-gray-300'" 
                                         class="h-6 w-6 rounded-full flex items-center justify-center">
                                        <svg x-show="selectedDoc === 'barangay_certificate'" class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-semibold text-gray-800">Barangay Certificate</h4>
                                    <p class="text-xs text-gray-600 mt-1">Official proof of residency and identity</p>
                                </div>
                            </div>
                        </div>

                        <div @click="selectedDoc = 'indigency_clearance'" 
                             :class="selectedDoc === 'indigency_clearance' ? 'border-blue-600 bg-blue-50' : 'border-gray-200'"
                             class="border-2 rounded-lg p-4 cursor-pointer hover:border-blue-400 transition">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-6 w-6 mt-1">
                                    <div :class="selectedDoc === 'indigency_clearance' ? 'bg-blue-600' : 'bg-gray-300'" 
                                         class="h-6 w-6 rounded-full flex items-center justify-center">
                                        <svg x-show="selectedDoc === 'indigency_clearance'" class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-semibold text-gray-800">Indigency Clearance</h4>
                                    <p class="text-xs text-gray-600 mt-1">For residents with low or no income</p>
                                </div>
                            </div>
                        </div>

                        <div @click="selectedDoc = 'resident_certificate'" 
                             :class="selectedDoc === 'resident_certificate' ? 'border-blue-600 bg-blue-50' : 'border-gray-200'"
                             class="border-2 rounded-lg p-4 cursor-pointer hover:border-blue-400 transition">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-6 w-6 mt-1">
                                    <div :class="selectedDoc === 'resident_certificate' ? 'bg-blue-600' : 'bg-gray-300'" 
                                         class="h-6 w-6 rounded-full flex items-center justify-center">
                                        <svg x-show="selectedDoc === 'resident_certificate'" class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-semibold text-gray-800">Resident Certificate</h4>
                                    <p class="text-xs text-gray-600 mt-1">Confirms residence and duration of stay</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Purpose -->
                <div>
                    <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">
                        Purpose of Request *
                    </label>
                    <textarea name="purpose" id="purpose" rows="4" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('purpose') border-red-500 @enderror"
                              placeholder="Please specify the purpose for this document request...">{{ old('purpose') }}</textarea>
                    @error('purpose')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Be as specific as possible about why you need this document.</p>
                </div>

                <!-- Important Notice -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-blue-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-800 mb-1">Processing Information</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Your request will be processed within 1 day</li>
                                <li>• You will receive an email notification when your document is ready</li>
                                <li>• Pick up your document at the barangay office during office hours</li>
                                <li>• Bring a valid ID when claiming your document</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <a href="{{ route('resident.documents.index') }}" class="text-gray-600 hover:text-gray-800">
                        ← Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                        Submit Request
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
