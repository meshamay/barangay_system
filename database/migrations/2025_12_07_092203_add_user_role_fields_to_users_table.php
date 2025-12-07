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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['resident', 'admin', 'super_admin'])->default('resident')->after('email');
            $table->enum('account_status', ['pending', 'approved', 'rejected'])->default('pending')->after('role');
            $table->boolean('is_active')->default(true)->after('account_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'account_status', 'is_active']);
        });
    }
};
