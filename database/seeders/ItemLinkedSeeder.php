<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Items;

class ItemLinkedSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $itemIds = Items::query()->pluck('id')->all();
        if (empty($itemIds)) {
            return;
        }

        $faker = \Faker\Factory::create();

        // Celana
        $celanaRows = [];
        foreach ($itemIds as $id) {
            $celanaRows[] = ['item_id' => $id, 'created_at' => $now, 'updated_at' => $now];
        }
        foreach (array_chunk($celanaRows, 1000) as $chunk) {
            DB::table('celana')->insert($chunk);
        }

        // Hijabs (with qty)
        $hijabRows = [];
        foreach ($itemIds as $id) {
            $hijabRows[] = ['item_id' => $id, 'qty' => rand(1, 10), 'created_at' => $now, 'updated_at' => $now];
        }
        foreach (array_chunk($hijabRows, 1000) as $chunk) {
            DB::table('hijabs')->insert($chunk);
        }

        // Veils
        $veilRows = [];
        foreach ($itemIds as $id) {
            $veilRows[] = ['item_id' => $id, 'created_at' => $now, 'updated_at' => $now];
        }
        foreach (array_chunk($veilRows, 1000) as $chunk) {
            DB::table('veils')->insert($chunk);
        }

        // Ekors
        $ekorRows = [];
        foreach ($itemIds as $id) {
            $ekorRows[] = ['item_id' => $id, 'created_at' => $now, 'updated_at' => $now];
        }
        foreach (array_chunk($ekorRows, 1000) as $chunk) {
            DB::table('ekors')->insert($chunk);
        }

        // Vests
        $vestRows = [];
        foreach ($itemIds as $id) {
            $vestRows[] = ['item_id' => $id, 'created_at' => $now, 'updated_at' => $now];
        }
        foreach (array_chunk($vestRows, 1000) as $chunk) {
            DB::table('vests')->insert($chunk);
        }

        // Dasi with type
        $types = ['bowtie', 'necktie', 'ascot'];
        $dasiRows = [];
        foreach ($itemIds as $id) {
            $dasiRows[] = ['item_id' => $id, 'type' => $types[array_rand($types)], 'created_at' => $now, 'updated_at' => $now];
        }
        foreach (array_chunk($dasiRows, 1000) as $chunk) {
            DB::table('dasis')->insert($chunk);
        }

        // Kemeja with type
        $kemejaTypes = ['short', 'long'];
        $kemejaRows = [];
        foreach ($itemIds as $id) {
            $kemejaRows[] = ['item_id' => $id, 'type' => $kemejaTypes[array_rand($kemejaTypes)], 'created_at' => $now, 'updated_at' => $now];
        }
        foreach (array_chunk($kemejaRows, 1000) as $chunk) {
            DB::table('kemejas')->insert($chunk);
        }

        // Bustiers with qty
        $bustierRows = [];
        foreach ($itemIds as $id) {
            $bustierRows[] = ['item_id' => $id, 'qty' => rand(1, 10), 'created_at' => $now, 'updated_at' => $now];
        }
        foreach (array_chunk($bustierRows, 1000) as $chunk) {
            DB::table('bustiers')->insert($chunk);
        }

        // Mansets with qty
        $mansetRows = [];
        foreach ($itemIds as $id) {
            $mansetRows[] = ['item_id' => $id, 'qty' => rand(1, 10), 'created_at' => $now, 'updated_at' => $now];
        }
        foreach (array_chunk($mansetRows, 1000) as $chunk) {
            DB::table('mansets')->insert($chunk);
        }

        // Selops with size
        $selopRows = [];
        foreach ($itemIds as $id) {
            $selopRows[] = ['item_id' => $id, 'size' => rand(35, 45), 'created_at' => $now, 'updated_at' => $now];
        }
        foreach (array_chunk($selopRows, 1000) as $chunk) {
            DB::table('selops')->insert($chunk);
        }
    }
}


