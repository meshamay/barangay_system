<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // User must be authenticated via auth:sanctum middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'document_type' => 'required|string|in:barangay_clearance,barangay_certificate,resident_certificate,indigency_clearance',
            'purpose' => 'required|string|max:500',
            'valid_id_type' => 'required|string|max:100',
            'valid_id_number' => 'required|string|max:100',
            'registered_voter' => 'required|boolean',
            
            // Conditional fields based on document type
            'length_of_residency' => 'required_if:document_type,barangay_certificate,barangay_clearance,resident_certificate|nullable|string|max:100',
            'barangay_id_number' => 'nullable|string|max:100',
            'civil_status' => 'nullable|string|max:50',
            'employment_status' => 'nullable|string|max:100',
            'monthly_income' => 'nullable|numeric|min:0',
            
            // File upload for Indigency Clearance
            'requirement_file' => 'required_if:document_type,indigency_clearance|nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'document_type.required' => 'Please select a document type.',
            'document_type.in' => 'Invalid document type selected.',
            'purpose.required' => 'Purpose is required.',
            'valid_id_type.required' => 'Valid ID type is required.',
            'valid_id_number.required' => 'Valid ID number is required.',
            'registered_voter.required' => 'Please indicate if you are a registered voter.',
            'length_of_residency.required_if' => 'Length of residency is required for this document type.',
            'requirement_file.required_if' => 'A requirement file is required for Indigency Clearance.',
            'requirement_file.mimes' => 'File must be a PDF, JPG, JPEG, or PNG.',
            'requirement_file.max' => 'File size must not exceed 5MB.',
        ];
    }
}
