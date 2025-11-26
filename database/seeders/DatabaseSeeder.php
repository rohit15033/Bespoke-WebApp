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
        // Appointments::factory(30)->create();

        // Seed a specific test user
        // User::updateOrCreate(
        //     ['email' => 'test@example.com'],
        //     [
        //         'name' => 'Test User',
        //         'password' => Hash::make('password123'),
        //     ]
        // );

        Occasions::insert([
            ['name' => 'Resepsi'],
            ['name' => 'Akad'],
            ['name' => 'Wisuda'],
            ['name' => 'Kondangan'],
            ['name' => 'Traditional Event'],
            ['name' => 'Lamaran'],
        ]);

        // Ensure base color taxonomy exists
        $this->call(SubColorSeeder::class); 
        $this->call(SubColorSeeder::class);

        // Items with images (requires SubColors)
        $this->call(ItemsSeeder::class);
        // Headwear and item-linked tables
        // $this->call(ItemLinkedSeeder::class);
        // Kebaya::factory(15)->create();
        // KebayaImages::factory(30)->create();
        // Beskap::factory(15)->create();

        // Orders
        $this->call(OrderSeeder::class);
    }
}
