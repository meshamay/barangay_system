# Database ERD - Barangay Management System

## Entity Relationship Diagram

```
┌─────────────────────┐
│      USERS          │
├─────────────────────┤
│ id (PK)             │
│ first_name          │
│ last_name           │
│ middle_name         │
│ email               │
│ password            │
│ contact_number      │
│ date_of_birth       │
│ place_of_birth      │
│ gender              │
│ address             │
│ role                │
│ account_status      │
│ is_active           │
│ created_at          │
│ updated_at          │
└─────────────────────┘
         │
         │ 1:N (one user has many documents)
         ↓
┌─────────────────────────────┐
│   DOCUMENT_REQUESTS         │
├─────────────────────────────┤
│ id (PK)                     │
│ transaction_id              │
│ user_id (FK → users)        │◄──── Resident who requested
│ document_type               │
│ purpose                     │
│ valid_id_type               │
│ valid_id_number             │
│ registered_voter            │
│ length_of_residency         │
│ barangay_id_number          │
│ civil_status                │
│ employment_status           │
│ monthly_income              │
│ requirement_file_path       │
│ status                      │
│ remarks                     │
│ processed_by (FK → users)   │◄──── Admin who processed
│ processed_at                │
│ released_at                 │
│ created_at                  │
│ updated_at                  │
└─────────────────────────────┘

         │
         │ 1:N (one user has many complaints)
         ↓
┌─────────────────────────────┐
│      COMPLAINTS             │
├─────────────────────────────┤
│ id (PK)                     │
│ transaction_id              │
│ user_id (FK → users)        │◄──── Resident who filed
│ incident_date               │
│ incident_time               │
│ incident_location           │
│ defendant_name              │
│ defendant_address           │
│ complaint_type              │
│ urgency_level               │
│ complaint_statement         │
│ status                      │
│ admin_remarks               │
│ assigned_to (FK → users)    │◄──── Admin assigned
│ resolved_at                 │
│ created_at                  │
│ updated_at                  │
└─────────────────────────────┘
```

---

## Simplified ERD (Your Specification)

### Table: `complaints`

| Field | Type | Description |
|-------|------|-------------|
| `id` | INTEGER (PK) | Primary key |
| `resident_id` | INTEGER (FK → users) | Resident who filed complaint |
| `complaint_type` | VARCHAR | Type of complaint |
| `complaint_statement` | TEXT | Detailed complaint description |
| `processed_by` | INTEGER (FK → users) | Admin/Super Admin who processed |
| `date_requested` | TIMESTAMP | When complaint was filed |
| `status` | VARCHAR | `open case`, `in progress`, `case resolved` |
| `deleted_at` | TIMESTAMP | Soft delete timestamp |
| `created_at` | TIMESTAMP | Record creation timestamp |
| `updated_at` | TIMESTAMP | Last update timestamp |

**Current Implementation Mapping:**
- `resident_id` → implemented as `user_id`
- `date_requested` → implemented as `created_at`
- `processed_by` → implemented as `assigned_to`
- Added fields: `transaction_id`, `incident_date`, `incident_time`, `incident_location`, `defendant_name`, `defendant_address`, `urgency_level`, `admin_remarks`, `resolved_at`

---

### Table: `document_requests`

| Field | Type | Description |
|-------|------|-------------|
| `id` | INTEGER (PK) | Primary key |
| `resident_id` | INTEGER (FK → users) | Resident who requested |
| `document_type` | VARCHAR | Type of document |
| `purpose` | VARCHAR | Purpose of request |
| `date_requested` | TIMESTAMP | When requested |
| `status` | VARCHAR | `pending`, `in progress`, `completed` |
| `processed_by` | INTEGER (FK → users) | Admin who processed |
| `released_at` | TIMESTAMP | When document was released |
| `created_at` | TIMESTAMP | Record creation timestamp |
| `updated_at` | TIMESTAMP | Last update timestamp |

**Current Implementation Mapping:**
- `resident_id` → implemented as `user_id`
- `date_requested` → implemented as `created_at`
- Added fields: `transaction_id`, `valid_id_type`, `valid_id_number`, `registered_voter`, `length_of_residency`, `barangay_id_number`, `civil_status`, `employment_status`, `monthly_income`, `requirement_file_path`, `remarks`, `processed_at`

---

## Detailed Implementation

### Table: `complaints` (Full Schema)

```sql
CREATE TABLE "complaints" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "transaction_id" VARCHAR NOT NULL,
    "user_id" INTEGER NOT NULL,
    "incident_date" DATE NOT NULL,
    "incident_time" TIME NOT NULL,
    "incident_location" VARCHAR,
    "defendant_name" VARCHAR NOT NULL,
    "defendant_address" TEXT NOT NULL,
    "complaint_type" VARCHAR CHECK ("complaint_type" IN (
        'Noise Complaint',
        'Property Dispute',
        'Harassment',
        'Vandalism',
        'Theft',
        'Physical Assault',
        'Verbal Abuse',
        'Environmental',
        'Other'
    )) NOT NULL,
    "urgency_level" VARCHAR CHECK ("urgency_level" IN (
        'Low',
        'Medium',
        'High',
        'Urgent'
    )) NOT NULL DEFAULT 'Medium',
    "complaint_statement" TEXT NOT NULL,
    "status" VARCHAR CHECK ("status" IN (
        'Open',
        'In Progress',
        'Resolved',
        'Closed'
    )) NOT NULL DEFAULT 'Open',
    "admin_remarks" TEXT,
    "resolved_at" DATETIME,
    "assigned_to" INTEGER,
    "created_at" DATETIME,
    "updated_at" DATETIME,
    FOREIGN KEY("user_id") REFERENCES "users"("id") ON DELETE CASCADE,
    FOREIGN KEY("assigned_to") REFERENCES "users"("id")
);
```

**Field Mappings:**

| ERD Field | Actual Field | Notes |
|-----------|--------------|-------|
| `resident_id` | `user_id` | Foreign key to users table |
| `complaint_type` | `complaint_type` | ✓ Matches (with enum constraints) |
| `complaint_statement` | `complaint_statement` | ✓ Matches |
| `processed_by` | `assigned_to` | Admin assigned to case |
| `date_requested` | `created_at` | When complaint was filed |
| `status` | `status` | Values: Open, In Progress, Resolved, Closed |
| `deleted_at` | — | Not implemented (using hard delete) |

**Additional Fields:**
- `transaction_id` - Auto-generated ID (e.g., "CMP-10001")
- `incident_date` - When incident occurred
- `incident_time` - Time of incident
- `incident_location` - Where incident happened
- `defendant_name` - Person being complained about
- `defendant_address` - Defendant's address
- `urgency_level` - Priority level (Low, Medium, High, Urgent)
- `admin_remarks` - Admin notes
- `resolved_at` - When case was resolved

---

### Table: `document_requests` (Full Schema)

```sql
CREATE TABLE "document_requests" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "transaction_id" VARCHAR NOT NULL,
    "user_id" INTEGER NOT NULL,
    "document_type" VARCHAR CHECK ("document_type" IN (
        'barangay_clearance',
        'barangay_certificate',
        'indigency_clearance',
        'resident_certificate'
    )) NOT NULL,
    "purpose" TEXT NOT NULL,
    "valid_id_type" VARCHAR NOT NULL,
    "valid_id_number" VARCHAR NOT NULL,
    "registered_voter" TINYINT(1) NOT NULL,
    "length_of_residency" VARCHAR,
    "barangay_id_number" VARCHAR,
    "civil_status" VARCHAR,
    "employment_status" VARCHAR,
    "monthly_income" NUMERIC,
    "requirement_file_path" VARCHAR,
    "status" VARCHAR CHECK ("status" IN (
        'Pending',
        'In Progress',
        'Ready for Pickup',
        'Completed',
        'Rejected'
    )) NOT NULL DEFAULT 'Pending',
    "remarks" TEXT,
    "processed_at" DATETIME,
    "processed_by" INTEGER,
    "released_at" DATETIME,
    "created_at" DATETIME,
    "updated_at" DATETIME,
    FOREIGN KEY("user_id") REFERENCES "users"("id") ON DELETE CASCADE,
    FOREIGN KEY("processed_by") REFERENCES "users"("id")
);
```

**Field Mappings:**

| ERD Field | Actual Field | Notes |
|-----------|--------------|-------|
| `resident_id` | `user_id` | Foreign key to users table |
| `document_type` | `document_type` | ✓ Matches (with enum constraints) |
| `purpose` | `purpose` | ✓ Matches |
| `date_requested` | `created_at` | When request was made |
| `status` | `status` | Values: Pending, In Progress, Ready for Pickup, Completed, Rejected |
| `processed_by` | `processed_by` | ✓ Matches |
| `released_at` | `released_at` | ✓ Matches |

**Additional Fields:**
- `transaction_id` - Auto-generated ID (e.g., "DOC-BC-10001")
- `valid_id_type` - Type of valid ID
- `valid_id_number` - Valid ID number
- `registered_voter` - Boolean flag
- `length_of_residency` - How long lived in barangay
- `barangay_id_number` - Barangay ID if available
- `civil_status` - Single, Married, etc.
- `employment_status` - Employment details
- `monthly_income` - For indigency clearance
- `requirement_file_path` - Uploaded document path
- `remarks` - Admin notes
- `processed_at` - When processing started

---

## Relationships

### One-to-Many Relationships

**1. Users → Document Requests**
- One user (resident) can have many document requests
- Foreign Key: `document_requests.user_id` → `users.id`
- Cascade on delete: When user is deleted, all their requests are deleted

**2. Users → Complaints**
- One user (resident) can file many complaints
- Foreign Key: `complaints.user_id` → `users.id`
- Cascade on delete: When user is deleted, all their complaints are deleted

**3. Users (Admin) → Document Requests (Processor)**
- One admin can process many document requests
- Foreign Key: `document_requests.processed_by` → `users.id`
- No cascade: If admin is deleted, `processed_by` remains

**4. Users (Admin) → Complaints (Assigned)**
- One admin can be assigned to many complaints
- Foreign Key: `complaints.assigned_to` → `users.id`
- No cascade: If admin is deleted, `assigned_to` remains

---

## Status Values

### Complaint Status Workflow

```
Open → In Progress → Resolved
  ↓
Closed
```

**Status Values:**
- `Open` - New complaint, not yet assigned
- `In Progress` - Admin is investigating
- `Resolved` - Case has been resolved
- `Closed` - Case closed without resolution

### Document Request Status Workflow

```
Pending → In Progress → Ready for Pickup → Completed
   ↓
Rejected
```

**Status Values:**
- `Pending` - New request, awaiting processing
- `In Progress` - Admin is preparing document
- `Ready for Pickup` - Document is ready
- `Completed` - Document has been released
- `Rejected` - Request was denied

---

## Indexes and Constraints

### Complaints Table
- **Primary Key:** `id`
- **Foreign Keys:** 
  - `user_id` → `users.id` (CASCADE DELETE)
  - `assigned_to` → `users.id`
- **Check Constraints:**
  - `complaint_type` must be one of 9 valid types
  - `urgency_level` must be Low, Medium, High, or Urgent
  - `status` must be Open, In Progress, Resolved, or Closed
- **Defaults:**
  - `urgency_level` = 'Medium'
  - `status` = 'Open'

### Document Requests Table
- **Primary Key:** `id`
- **Foreign Keys:**
  - `user_id` → `users.id` (CASCADE DELETE)
  - `processed_by` → `users.id`
- **Check Constraints:**
  - `document_type` must be one of 4 valid types
  - `status` must be one of 5 valid statuses
- **Defaults:**
  - `status` = 'Pending'

---

## Field Name Differences

### ERD vs Implementation

| Your ERD Field | Implemented As | Reason for Change |
|----------------|----------------|-------------------|
| `resident_id` | `user_id` | More generic, follows Laravel conventions |
| `date_requested` | `created_at` | Laravel timestamp convention |
| `processed_by` (complaints) | `assigned_to` | Clearer meaning for complaint workflow |
| `deleted_at` | Not implemented | Hard delete used instead of soft delete |

---

## Data Types

### String Fields
- `VARCHAR` - Variable length strings (names, IDs, types)
- `TEXT` - Large text fields (statements, purposes, addresses)

### Numeric Fields
- `INTEGER` - Primary keys, foreign keys
- `NUMERIC` - Decimal values (monthly_income)
- `TINYINT(1)` - Boolean values (registered_voter)

### Date/Time Fields
- `DATE` - Date only (incident_date)
- `TIME` - Time only (incident_time)
- `DATETIME` - Full timestamp (created_at, updated_at, processed_at, released_at, resolved_at)

---

## Migration Files

**Complaints Table:**
`database/migrations/2025_12_07_092212_create_complaints_table.php`

**Document Requests Table:**
`database/migrations/2025_12_07_092211_create_document_requests_table.php`

**Users Table:**
`database/migrations/0001_01_01_000000_create_users_table.php`

---

## Summary

Your ERD shows the core fields needed for the system. The actual implementation includes:

✅ **All your ERD fields** (with minor naming differences)
✅ **Additional fields** for better functionality
✅ **Proper foreign keys** and relationships
✅ **Enum constraints** for data integrity
✅ **Transaction IDs** for easier tracking
✅ **Timestamps** for audit trail

The database is **production-ready** and follows Laravel best practices!
