<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Use Carbon for timestamps

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $colors = [
            'Blue',
            'Red',
            'Gold',
            'Brown',
            'Green',
            'Purple',
            'Black',
            'White',
            'Silver',
            'Pink',
        ];
        $dataToInsert = [];
        $now = Carbon::now();

        foreach ($colors as $colorName) {
            $dataToInsert[] = [
                'name' => $colorName,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Efficient bulk insert into the 'colors' table
        DB::table('colors')->insert($dataToInsert);

    }
}
