<?php

namespace Database\Seeders;

use App\Models\Appointments;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Appointments::factory(30)->create();

        // Seed a specific test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'), // IMPORTANT: Always hash passwords!
            // 'email_verified_at' => now(), // Optional, uncomment if you want them verified
        ]);
    }
}
