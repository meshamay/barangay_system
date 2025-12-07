<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique(); // e.g., CMP-10001
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Incident Details
            $table->date('incident_date');
            $table->time('incident_time');
            $table->string('incident_location')->nullable();
            
            // Defendant Information
            $table->string('defendant_name');
            $table->text('defendant_address');
            
            // Complaint Details
            $table->enum('complaint_type', [
                'Noise Complaint',
                'Property Dispute',
                'Harassment',
                'Vandalism',
                'Theft',
                'Physical Assault',
                'Verbal Abuse',
                'Environmental',
                'Other'
            ]);
            $table->enum('urgency_level', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');
            $table->text('complaint_statement'); // Detailed description
            
            // Status Management
            $table->enum('status', ['Open', 'In Progress', 'Resolved', 'Closed'])->default('Open');
            $table->text('admin_remarks')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
