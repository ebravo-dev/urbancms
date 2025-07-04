<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the application's database with only admin user for client testing.
     */
    public function run(): void
    {
        // Create admin user for client testing
        User::updateOrCreate(
            ['email' => 'admin@admin.com'], // Check if user exists by email
            [
                'name' => 'Administrator',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@admin.com');
        $this->command->info('Password: admin123');
    }
}
