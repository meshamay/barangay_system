# Admin Document Management - API Documentation

## Overview
This system manages document requests for a Barangay Management System. Admins and Super Admins can view all requests, search/filter them, and update their status.

---

## Database Structure

### Table: `document_requests`

| Column | Type | Description |
|--------|------|-------------|
| `id` | Integer (PK) | Unique ID for each request |
| `transaction_id` | String | Auto-generated ID (e.g., "DOC-BC-10001") |
| `user_id` | Integer (FK) | Reference to `users` table (resident who made the request) |
| `document_type` | Enum | `barangay_clearance`, `barangay_certificate`, `indigency_clearance`, `resident_certificate` |
| `purpose` | Text | Purpose of the document request |
| `valid_id_type` | String | Type of valid ID provided |
| `valid_id_number` | String | Valid ID number |
| `registered_voter` | Boolean | Whether resident is a registered voter |
| `length_of_residency` | String | How long they've lived in the barangay |
| `barangay_id_number` | String | Barangay ID number (if applicable) |
| `civil_status` | String | Single, Married, Widowed, etc. |
| `employment_status` | String | Employed, Unemployed, etc. |
| `monthly_income` | Decimal | Monthly income (for Indigency Clearance) |
| `requirement_file_path` | String | Path to uploaded file |
| `status` | Enum | `Pending`, `In Progress`, `Ready for Pickup`, `Completed`, `Rejected` |
| `remarks` | Text | Admin notes/comments |
| `processed_by` | Integer (FK) | Reference to `users` table (admin who processed) |
| `processed_at` | Timestamp | When processing started |
| `released_at` | Timestamp | When document was released/completed |
| `created_at` | Timestamp | Request creation date |
| `updated_at` | Timestamp | Last update date |

---

## API Endpoints

### Base URL
All endpoints are prefixed with `/api`

### Authentication
All endpoints require authentication. Include session cookie or API token.

---

### 1. Get All Document Requests

**Endpoint:** `GET /api/document-requests`

**Purpose:** Fetch all document requests with optional filtering and search

**Query Parameters:**
| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `status` | string | Filter by status | `?status=Pending` |
| `document_type` | string | Filter by document type | `?document_type=barangay_clearance` |
| `search` | string | Search by name or transaction ID | `?search=Juan` |

**Response:**
```json
{
  "success": true,
  "requests": [
    {
      "id": 1,
      "transaction_id": "DOC-BC-10001",
      "resident_name": "Juan Dela Cruz",
      "document_type": "barangay_clearance",
      "document_type_display": "Barangay Clearance",
      "purpose": "Employment",
      "date_requested": "Dec 07, 2025",
      "date_requested_full": "December 07, 2025 10:30 AM",
      "status": "Pending",
      "processed_by_name": null,
      "released_at": null,
      "remarks": null
    }
  ]
}
```

**Status Values:**
- `Pending` - New request, not yet processed
- `In Progress` - Admin is working on it
- `Ready for Pickup` - Document is ready
- `Completed` - Document has been released
- `Rejected` - Request was denied

**Document Types:**
- `barangay_clearance` - Barangay Clearance
- `barangay_certificate` - Barangay Certificate
- `indigency_clearance` - Indigency Clearance
- `resident_certificate` - Resident Certificate

---

### 2. Get Statistics

**Endpoint:** `GET /api/document-requests/stats`

**Purpose:** Get counts for the dashboard cards

**Response:**
```json
{
  "success": true,
  "stats": {
    "total": 150,
    "pending": 25,
    "in_progress": 10,
    "completed": 115
  }
}
```

**Use these numbers for:**
- "Total Requests" card
- "Pending" card
- "In Progress" card
- "Completed" card

---

### 3. Get Single Request Details

**Endpoint:** `GET /api/document-requests/{id}`

**Purpose:** View full details of a specific request (for the "eye" icon)

**Example:** `GET /api/document-requests/1`

**Response:**
```json
{
  "success": true,
  "request": {
    "id": 1,
    "transaction_id": "DOC-BC-10001",
    "status": "Pending",
    "resident": {
      "name": "Juan Dela Cruz",
      "email": "juan@example.com",
      "phone": "09123456789",
      "address": "123 Barangay Street"
    },
    "document_type": "Barangay Clearance",
    "purpose": "Employment",
    "valid_id_type": "National ID",
    "valid_id_number": "1234-5678-9012",
    "registered_voter": "Yes",
    "length_of_residency": "5 years",
    "barangay_id_number": "BR-12345",
    "civil_status": "Single",
    "employment_status": "Employed",
    "monthly_income": "₱25,000.00",
    "requirement_file": "http://localhost/storage/requirements/file.pdf",
    "date_requested": "December 07, 2025 10:30 AM",
    "processed_by": null,
    "processed_at": null,
    "released_at": null,
    "remarks": "No remarks"
  }
}
```

---

### 4. Update Request Status

**Endpoint:** `PUT /api/document-requests/{id}`

**Purpose:** Change the status of a request (for update/check icons)

**Example:** `PUT /api/document-requests/1`

**Request Body:**
```json
{
  "status": "In Progress",
  "remarks": "Processing document"
}
```

**Allowed Status Values:**
- `Pending`
- `In Progress`
- `Ready for Pickup`
- `Completed`
- `Rejected`

**Response:**
```json
{
  "success": true,
  "message": "Document request status updated successfully",
  "request": {
    "id": 1,
    "status": "In Progress",
    "processed_by": "Admin User",
    "released_at": null
  }
}
```

**Automatic Actions:**
- When status changes from `Pending` → any other status:
  - `processed_by` is set to current admin's ID
  - `processed_at` is set to current timestamp

- When status is set to `Completed`:
  - `released_at` is set to current timestamp

---

## Frontend Integration Guide

### 1. Dashboard Cards (Top Section)

**Call:** `GET /api/document-requests/stats`

**Display:**
```
┌─────────────┬─────────────┬─────────────┬─────────────┐
│ TOTAL       │ PENDING     │ IN PROGRESS │ COMPLETED   │
│ 150         │ 25          │ 10          │ 115         │
└─────────────┴─────────────┴─────────────┴─────────────┘
```

### 2. Document Requests Table

**Call:** `GET /api/document-requests?status=all&search=`

**Table Columns:**
- Transaction ID
- Last Name (from `resident_name`)
- First Name (from `resident_name`)
- Document Type (use `document_type_display`)
- Purpose
- Date Requested (use `date_requested`)
- Status
- Actions (👁️ View, 🔄 Update, ✅ Complete)

### 3. Action Buttons

**View Button (👁️):**
- Call: `GET /api/document-requests/{id}`
- Show modal/drawer with full details

**Update Button (🔄):**
- Call: `PUT /api/document-requests/{id}`
- Body: `{ "status": "In Progress", "remarks": "..." }`
- Refresh table after success

**Complete Button (✅):**
- Call: `PUT /api/document-requests/{id}`
- Body: `{ "status": "Completed", "remarks": "Document released" }`
- Refresh table after success

### 4. Search and Filter

**Search by name or transaction ID:**
```javascript
GET /api/document-requests?search=Juan
```

**Filter by status:**
```javascript
GET /api/document-requests?status=Pending
```

**Filter by document type:**
```javascript
GET /api/document-requests?document_type=barangay_clearance
```

**Combine filters:**
```javascript
GET /api/document-requests?status=Pending&document_type=barangay_clearance&search=Juan
```

---

## Status Workflow

```
Pending → In Progress → Ready for Pickup → Completed
   ↓
Rejected
```

**Recommended Status Flow:**
1. **Pending** - Initial state when resident submits
2. **In Progress** - Admin starts processing
3. **Ready for Pickup** - Document is prepared
4. **Completed** - Resident has picked up document
5. **Rejected** - Request was denied

---

## Error Handling

All endpoints return errors in this format:

```json
{
  "success": false,
  "message": "Error description here",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

**Common HTTP Status Codes:**
- `200` - Success
- `401` - Unauthenticated
- `403` - Unauthorized (not admin)
- `404` - Resource not found
- `422` - Validation error
- `500` - Server error

---

## Testing the API

### Using Browser (for GET requests)
```
http://localhost:8000/api/document-requests
http://localhost:8000/api/document-requests/stats
http://localhost:8000/api/document-requests/1
```

### Using cURL (for PUT requests)
```bash
# Update status
curl -X PUT http://localhost:8000/api/document-requests/1 \
  -H "Content-Type: application/json" \
  -d '{"status":"In Progress","remarks":"Processing"}'
```

### Using JavaScript (fetch)
```javascript
// Get all requests
const response = await fetch('/api/document-requests');
const data = await response.json();

// Update status
const updateResponse = await fetch('/api/document-requests/1', {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
  },
  body: JSON.stringify({
    status: 'In Progress',
    remarks: 'Processing document'
  })
});
```

---

## Quick Start for Frontend Developer

1. **Load the page:**
   - Call `/api/document-requests/stats` to fill dashboard cards
   - Call `/api/document-requests` to populate the table

2. **Add search functionality:**
   - On input change, call `/api/document-requests?search={searchTerm}`

3. **Add filters:**
   - Status dropdown: `/api/document-requests?status={selectedStatus}`
   - Document type dropdown: `/api/document-requests?document_type={selectedType}`

4. **Implement view button:**
   - Call `/api/document-requests/{id}`
   - Show details in a modal

5. **Implement status update:**
   - Call `PUT /api/document-requests/{id}` with new status
   - Refresh the table after success

---

## Need Help?

All backend functionality is already implemented and tested. Just integrate with these endpoints!

**Backend is ready for:**
✅ Real-time statistics
✅ Search by name or transaction ID
✅ Filter by status and document type
✅ View full request details
✅ Update request status
✅ Track who processed each request
✅ Auto-set timestamps (processed_at, released_at)
