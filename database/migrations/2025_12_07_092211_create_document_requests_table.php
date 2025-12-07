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
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('document_type', ['barangay_clearance', 'barangay_certificate', 'indigency_clearance', 'resident_certificate']);
            $table->text('purpose');
            $table->string('valid_id_type');
            $table->string('valid_id_number');
            $table->boolean('registered_voter');
            $table->string('length_of_residency')->nullable();
            $table->string('barangay_id_number')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('employment_status')->nullable();
            $table->decimal('monthly_income', 10, 2)->nullable();
            $table->string('requirement_file_path')->nullable();
            $table->enum('status', ['Pending', 'In Progress', 'Ready for Pickup', 'Completed', 'Rejected'])->default('Pending');
            $table->text('remarks')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_requests');
    }
};
