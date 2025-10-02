<?php

namespace Database\Seeders;

use App\Models\Appointments;
use App\Models\User;
use App\Models\Occasions;
use App\Models\Colors;
use App\Models\SubColors;
use App\Models\Kebaya;
use App\Models\KebayaImages;
use App\Models\KebayaOccasion;
use App\Models\Beskap;
use App\Models\BeskapImages;


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


        Occasions::insert([
            ['name' => 'Wedding'],
            ['name' => 'Engagement'],
            ['name' => 'Graduation'],
            ['name' => 'Party'],
            ['name' => 'Traditional Ceremony'],
        ]);


        // Colors::factory(5)->create();
        SubColors::factory(15)->create();
        // Kebaya::factory(15)->create();
        // KebayaImages::factory(30)->create();
        // Beskap::factory(15)->create();

    }
}
