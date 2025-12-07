<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a default resident account
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'first_name' => 'Test',
                'last_name' => 'Resident',
                'password' => Hash::make('password'),
                'role' => 'resident',
                'account_status' => 'approved',
                'is_active' => true,
            ]
        );

        // Create a default admin account
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Test',
                'last_name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'account_status' => 'approved',
                'is_active' => true,
            ]
        );
    }
}
