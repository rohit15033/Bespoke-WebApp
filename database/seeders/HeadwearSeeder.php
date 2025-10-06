<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Items;
use App\Models\Headwear;

class HeadwearSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $now = now();

        // Ensure some headwear attributes and values exist
        $attributes = [
            'pattern' => ['batik', 'polos', 'songket'],
            'material' => ['katun', 'sutra', 'beludru'],
            'size' => ['S', 'M', 'L', 'XL'],
        ];

        $attributeIdByName = [];
        foreach ($attributes as $attrName => $values) {
            $attributeId = DB::table('headwear_attributes')->insertGetId([
                'name' => $attrName,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $attributeIdByName[$attrName] = $attributeId;
            $rows = [];
            foreach ($values as $val) {
                $rows[] = [
                    'value' => $val,
                    'headwear_attribute_id' => $attributeId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('headwear_attribute_values')->insert($rows);
        }

        // Map attribute name to its values ids
        $valuesByAttr = [];
        foreach ($attributeIdByName as $attrName => $attrId) {
            $valuesByAttr[$attrName] = DB::table('headwear_attribute_values')
                ->where('headwear_attribute_id', $attrId)
                ->pluck('id')
                ->all();
        }

        // Pick some existing items of type headwear (or create fallback)
        $itemIds = Items::query()->where('type', 'headwear')->pluck('id')->all();
        if (empty($itemIds)) {
            // create a few placeholder Items if none available
            $subcolorIds = DB::table('subcolors')->pluck('id')->all();
            if (!empty($subcolorIds)) {
                $placeholders = [];
                for ($i = 0; $i < 20; $i++) {
                    $placeholders[] = [
                        'name' => 'Headwear ' . $i,
                        'code' => 'HDW' . strtoupper(\Illuminate\Support\Str::random(6)),
                        'type' => 'headwear',
                        'production_month' => rand(1, 12),
                        'production_year' => rand(2018, (int) date('Y')),
                        'subcolor_id' => $subcolorIds[array_rand($subcolorIds)],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                DB::table('items')->insert($placeholders);
                $itemIds = Items::query()->where('type', 'headwear')->pluck('id')->all();
            }
        }

        $types = ['blangkon', 'peci', 'tanjak'];
        $headwearRows = [];
        foreach ($itemIds as $itemId) {
            $headwearRows[] = [
                'item_id' => $itemId,
                'type' => $types[array_rand($types)],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        foreach (array_chunk($headwearRows, 500) as $chunk) {
            DB::table('headwears')->insert($chunk);
        }

        $headwearIds = Headwear::query()->pluck('id')->all();
        $junctionRows = [];
        foreach ($headwearIds as $hid) {
            // assign 2-3 random attributes values across attributes
            foreach ($valuesByAttr as $ids) {
                $pick = (array) array_rand($ids, rand(1, min(2, count($ids))));
                foreach ((array) $pick as $k) {
                    $junctionRows[] = [
                        'headwear_id' => $hid,
                        'headwear_attribute_value_id' => $ids[$k],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }
        foreach (array_chunk($junctionRows, 1000) as $chunk) {
            DB::table('headwear_attributes_junctions')->insert($chunk);
        }
    }
}


