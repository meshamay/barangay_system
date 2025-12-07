# Testing Document Request System

## 🔍 How to Check Backend for User Documents

### **Method 1: Using Browser Developer Tools (Easiest)**

1. **Open your browser and go to the document request page**:
   ```
   http://localhost:8001/resident/documents
   ```

2. **Open Developer Tools** (F12 or Right-click → Inspect)

3. **Go to Network tab**

4. **Submit a document request** by:
   - Click "New Document Request" dropdown
   - Select any document type
   - Fill out the form
   - Click "Submit Request"

5. **Check the Network requests**:
   - Look for `document-request` POST request
   - Check the **Request payload** (what was sent)
   - Check the **Response** (what the backend returned)

6. **To view all requests**:
   - Look for `document-requests` GET request
   - Check the Response tab to see all user documents

---

### **Method 2: Using Laravel Tinker (Command Line)**

Run these commands in your terminal:

```bash
# Enter Laravel Tinker
php artisan tinker

# Check all document requests
App\Models\DocumentRequest::all();

# Check document requests for a specific user (replace 1 with user ID)
App\Models\DocumentRequest::where('user_id', 1)->get();

# Get latest document request
App\Models\DocumentRequest::latest()->first();

# Get document request with user information
App\Models\DocumentRequest::with('user')->get();

# Count documents by status
App\Models\DocumentRequest::where('status', 'Pending')->count();

# Exit Tinker
exit
```

---

### **Method 3: Using Database Directly**

```bash
# Access SQLite database
php artisan db

# Or use SQL commands
sqlite3 database/database.sqlite

# View all document requests
SELECT * FROM document_requests;

# View with formatted output
SELECT 
    id,
    transaction_id,
    document_type,
    status,
    created_at
FROM document_requests
ORDER BY created_at DESC;

# Exit
.exit
```

---

### **Method 4: Create a Test Route**

Add this to `routes/web.php`:

```php
Route::get('/test-documents', function () {
    $requests = \App\Models\DocumentRequest::with('user')->latest()->get();
    return response()->json($requests);
})->middleware('auth');
```

Then visit: `http://localhost:8001/test-documents`

---

### **Method 5: Using API Testing Tools (Postman/cURL)**

#### **Get All User Documents**
```bash
curl -X GET http://localhost:8001/api/user/document-requests \
  -H "Accept: application/json" \
  -H "X-Requested-With: XMLHttpRequest" \
  --cookie-jar cookies.txt \
  --cookie cookies.txt
```

#### **Submit a Document Request**
```bash
curl -X POST http://localhost:8001/api/user/document-request \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-Requested-With: XMLHttpRequest" \
  -H "X-CSRF-TOKEN: YOUR_CSRF_TOKEN" \
  --cookie cookies.txt \
  -d '{
    "document_type": "Barangay Clearance",
    "first_name": "Juan",
    "last_name": "Dela Cruz",
    "birthdate": "1990-01-01",
    "birthplace": "Manila",
    "civil_status": "Single",
    "length_of_residency": "5 years",
    "valid_id_number": "123456789",
    "registered_voter": "yes",
    "purpose": "Employment"
  }'
```

---

### **Method 6: Check Laravel Logs**

```bash
# View real-time logs
tail -f storage/logs/laravel.log

# Or check the log file directly
cat storage/logs/laravel.log
```

---

### **Method 7: Create a Seeder for Test Data**

Create a seeder to populate test documents:

```bash
php artisan make:seeder DocumentRequestSeeder
```

Edit `database/seeders/DocumentRequestSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentRequest;
use App\Models\User;

class DocumentRequestSeeder extends Seeder
{
    public function run()
    {
        $user = User::first(); // Get first user
        
        if ($user) {
            DocumentRequest::create([
                'transaction_id' => 'DOC-BC-10001',
                'user_id' => $user->id,
                'document_type' => 'Barangay Clearance',
                'purpose' => 'Employment requirement',
                'valid_id_type' => 'Driver\'s License',
                'valid_id_number' => '123456789',
                'registered_voter' => true,
                'length_of_residency' => '5 years',
                'civil_status' => 'Single',
                'status' => 'Pending',
            ]);

            DocumentRequest::create([
                'transaction_id' => 'DOC-BCERT-10001',
                'user_id' => $user->id,
                'document_type' => 'Barangay Certificate',
                'purpose' => 'Bank requirement',
                'valid_id_type' => 'PhilHealth ID',
                'valid_id_number' => '987654321',
                'registered_voter' => true,
                'length_of_residency' => '10 years',
                'civil_status' => 'Married',
                'status' => 'In Progress',
            ]);
        }
    }
}
```

Run the seeder:
```bash
php artisan db:seed --class=DocumentRequestSeeder
```

---

### **Method 8: Check Uploaded Files**

```bash
# List uploaded requirement files
ls -la storage/app/public/requirements/

# Or
ls -lh storage/app/public/requirements/
```

---

## 📊 Quick Database Queries

### Check if table exists and has data:
```bash
php artisan tinker

# Check table exists
Schema::hasTable('document_requests');

# Count all records
App\Models\DocumentRequest::count();

# Get latest 5 requests
App\Models\DocumentRequest::latest()->take(5)->get();
```

### Check specific fields:
```bash
php artisan tinker

# Get all transaction IDs
App\Models\DocumentRequest::pluck('transaction_id');

# Get all document types
App\Models\DocumentRequest::pluck('document_type')->unique();

# Get requests grouped by status
App\Models\DocumentRequest::groupBy('status')->select('status', DB::raw('count(*) as total'))->get();
```

---

## 🐛 Common Issues and Solutions

### Issue 1: "Unauthenticated" Error
**Solution**: Make sure you're logged in. The API routes require authentication.

### Issue 2: "CSRF Token Mismatch"
**Solution**: Ensure the CSRF token is included in the meta tag and JavaScript.

### Issue 3: Empty Response
**Solution**: 
```bash
# Check if you have any documents
php artisan tinker
App\Models\DocumentRequest::all();
```

### Issue 4: File Upload Not Working
**Solution**:
```bash
# Check storage link
ls -la public/storage

# Recreate if needed
php artisan storage:link

# Check permissions
chmod -R 775 storage/app/public
```

---

## 🎯 Recommended Testing Flow

1. **First, check if you can access the page**:
   - Visit: `http://localhost:8001/resident/documents`

2. **Create a test user** (if needed):
   ```bash
   php artisan tinker
   User::create([
       'name' => 'Test User',
       'email' => 'test@example.com',
       'password' => bcrypt('password'),
       'role' => 'resident'
   ]);
   ```

3. **Login with the test user**

4. **Submit a document request** through the UI

5. **Check the database**:
   ```bash
   php artisan tinker
   App\Models\DocumentRequest::latest()->first();
   ```

6. **Verify the data appears in the table** (refresh the page)

---

## 📝 Expected Database Structure

Your `document_requests` table should have these columns:
- `id` - Primary key
- `transaction_id` - Unique (e.g., DOC-BC-10001)
- `user_id` - Foreign key to users
- `document_type` - Type of document requested
- `purpose` - Why they need the document
- `valid_id_type` - Type of ID
- `valid_id_number` - ID number
- `registered_voter` - Boolean
- `length_of_residency` - How long they've lived there
- `civil_status` - Marital status
- `requirement_file_path` - File path (for Indigency)
- `status` - Current status (Pending/In Progress/Completed)
- `created_at`, `updated_at` - Timestamps

---

## 🚀 Quick Test Script

Create a file `test-documents.sh`:

```bash
#!/bin/bash

echo "🔍 Checking Document Requests System..."
echo ""

echo "1️⃣ Checking database..."
php artisan tinker --execute="echo App\Models\DocumentRequest::count() . ' documents found';"

echo ""
echo "2️⃣ Latest document:"
php artisan tinker --execute="dump(App\Models\DocumentRequest::latest()->first());"

echo ""
echo "3️⃣ Documents by status:"
php artisan tinker --execute="dump(App\Models\DocumentRequest::select('status', DB::raw('count(*) as total'))->groupBy('status')->get());"

echo ""
echo "✅ Check complete!"
```

Make it executable and run:
```bash
chmod +x test-documents.sh
./test-documents.sh
```

---

## 📞 Need More Help?

If you encounter any issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Enable debug mode: Set `APP_DEBUG=true` in `.env`
3. Clear cache: `php artisan cache:clear && php artisan config:clear`
