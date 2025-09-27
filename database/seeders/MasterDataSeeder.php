<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemBlueprint;
use App\Models\ItemType;
use App\Models\PackageBlueprint;
use App\Models\SetBlueprint;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create item types first
        $itemTypes = [
            ['name' => 'Kebaya'],
            ['name' => 'Beskap'],
            ['name' => 'Jas'],
            ['name' => 'Celana'],
            ['name' => 'Kain'],
            ['name' => 'Headwear'],
            ['name' => 'Gaun'],
            ['name' => 'Bustier'],
            ['name' => 'Rok'],
            ['name' => 'Manset'],
            ['name' => 'Selop Pria'],
            ['name' => 'Selop Wanita'],
            ['name' => 'Aksesoris'],
            ['name' => 'Tail'],
            ['name' => 'Kemeja'],
            ['name' => 'Accesories'],
        ];

        foreach ($itemTypes as $itemTypeData) {
            ItemType::create($itemTypeData);
        }

        // Create sample items
        $items = [
            ['sku' => 'KBY-BRU-PDK', 'name' => 'Kebaya Biru Pendek Full Payet', 'type' => 'Kebaya', 'color' => 'Biru', 'image_url' => 'https://example.com/images/kebaya-biru-pdk.jpg'],
            ['sku' => 'KBY-BRU-SMT', 'name' => 'Kebaya Biru Semata Kaki Full Payet', 'type' => 'Kebaya', 'color' => 'Biru', 'image_url' => 'https://example.com/images/kebaya-biru-smt.jpg'],
            ['sku' => 'KBY-BRU-MLT', 'name' => 'Kebaya Biru Melantai Full Payet', 'type' => 'Kebaya', 'color' => 'Biru', 'image_url' => 'https://example.com/images/kebaya-biru-mlt.jpg'],

            ['sku' => 'KBY-MRH-SMT', 'name' => 'Kebaya Merah Semata Kaki Full Payet', 'type' => 'Kebaya', 'color' => 'Merah', 'image_url' => 'https://example.com/images/kebaya-merah-smt.jpg'],
            ['sku' => 'KBY-MRH-MLT', 'name' => 'Kebaya Merah Melantai Full Payet', 'type' => 'Kebaya', 'color' => 'Merah', 'image_url' => 'https://example.com/images/kebaya-merah-mlt.jpg'],
            ['sku' => 'KBY-MRH-PDK', 'name' => 'Kebaya Merah Pendek Full Payet', 'type' => 'Kebaya', 'color' => 'Merah', 'image_url' => 'https://example.com/images/kebaya-merah-pdk.jpg'],

            ['sku' => 'KBY-GLD-SMT', 'name' => 'Kebaya Gold Semata Kaki Full Payet', 'type' => 'Kebaya', 'color' => 'Gold', 'image_url' => 'https://example.com/images/kebaya-gold-smt.jpg'],
            ['sku' => 'KBY-GLD-MLT', 'name' => 'Kebaya Gold Melantai Full Payet', 'type' => 'Kebaya', 'color' => 'Gold', 'image_url' => 'https://example.com/images/kebaya-gold-mlt.jpg'],
            ['sku' => 'KBY-GLD-PDK', 'name' => 'Kebaya Gold Pendek Full Payet', 'type' => 'Kebaya', 'color' => 'Gold', 'image_url' => 'https://example.com/images/kebaya-gold-pdk.jpg'],

            ['sku' => 'KBY-CKLT-SMT', 'name' => 'Kebaya Coklat Semata Kaki Full Payet', 'type' => 'Kebaya', 'color' => 'Coklat', 'image_url' => 'https://example.com/images/kebaya-coklat-smt.jpg'],
            ['sku' => 'KBY-CKLT-MLT', 'name' => 'Kebaya Coklat Melantai Full Payet', 'type' => 'Kebaya', 'color' => 'Coklat', 'image_url' => 'https://example.com/images/kebaya-coklat-mlt.jpg'],
            ['sku' => 'KBY-CKLT-PDK', 'name' => 'Kebaya Coklat Pendek Full Payet', 'type' => 'Kebaya', 'color' => 'Coklat', 'image_url' => 'https://example.com/images/kebaya-coklat-pdk.jpg'],

            ['sku' => 'KBY-HJU-SMT', 'name' => 'Kebaya Hijau Semata Kaki Full Payet', 'type' => 'Kebaya', 'color' => 'Hijau', 'image_url' => 'https://example.com/images/kebaya-hijau-smt.jpg'],
            ['sku' => 'KBY-HJU-MLT', 'name' => 'Kebaya Hijau Melantai Full Payet', 'type' => 'Kebaya', 'color' => 'Hijau', 'image_url' => 'https://example.com/images/kebaya-hijau-mlt.jpg'],
            ['sku' => 'KBY-HJU-PDK', 'name' => 'Kebaya Hijau Pendek Full Payet', 'type' => 'Kebaya', 'color' => 'Hijau', 'image_url' => 'https://example.com/images/kebaya-hijau-pdk.jpg'],

            ['sku' => 'KBY-UNGU-SMT', 'name' => 'Kebaya Ungu Semata Kaki Full Payet', 'type' => 'Kebaya', 'color' => 'Ungu', 'image_url' => 'https://example.com/images/kebaya-ungu-smt.jpg'],
            ['sku' => 'KBY-UNGU-MLT', 'name' => 'Kebaya Ungu Melantai Full Payet', 'type' => 'Kebaya', 'color' => 'Ungu', 'image_url' => 'https://example.com/images/kebaya-ungu-mlt.jpg'],
            ['sku' => 'KBY-UNGU-PDK', 'name' => 'Kebaya Ungu Pendek Full Payet', 'type' => 'Kebaya', 'color' => 'Ungu', 'image_url' => 'https://example.com/images/kebaya-ungu-pdk.jpg'],

            ['sku' => 'KBY-HTM-SMT', 'name' => 'Kebaya Hitam Semata Kaki Full Payet', 'type' => 'Kebaya', 'color' => 'Hitam', 'image_url' => 'https://example.com/images/kebaya-hitam-smt.jpg'],
            ['sku' => 'KBY-HTM-MLT', 'name' => 'Kebaya Hitam Melantai Full Payet', 'type' => 'Kebaya', 'color' => 'Hitam', 'image_url' => 'https://example.com/images/kebaya-hitam-mlt.jpg'],
            ['sku' => 'KBY-HTM-PDK', 'name' => 'Kebaya Hitam Pendek Full Payet', 'type' => 'Kebaya', 'color' => 'Hitam', 'image_url' => 'https://example.com/images/kebaya-hitam-pdk.jpg'],

            ['sku' => 'KBY-PTH-SMT', 'name' => 'Kebaya Putih Semata Kaki Full Payet', 'type' => 'Kebaya', 'color' => 'Putih', 'image_url' => 'https://example.com/images/kebaya-putih-smt.jpg'],
            ['sku' => 'KBY-PTH-MLT', 'name' => 'Kebaya Putih Melantai Full Payet', 'type' => 'Kebaya', 'color' => 'Putih', 'image_url' => 'https://example.com/images/kebaya-putih-mlt.jpg'],
            ['sku' => 'KBY-PTH-PDK', 'name' => 'Kebaya Putih Pendek Full Payet', 'type' => 'Kebaya', 'color' => 'Putih', 'image_url' => 'https://example.com/images/kebaya-putih-pdk.jpg'],

            ['sku' => 'KBY-SLVR-SMT', 'name' => 'Kebaya Silver Semata Kaki Full Payet', 'type' => 'Kebaya', 'color' => 'Silver', 'image_url' => 'https://example.com/images/kebaya-silver-smt.jpg'],
            ['sku' => 'KBY-SLVR-MLT', 'name' => 'Kebaya Silver Melantai Full Payet', 'type' => 'Kebaya', 'color' => 'Silver', 'image_url' => 'https://example.com/images/kebaya-silver-mlt.jpg'],
            ['sku' => 'KBY-SLVR-PDK', 'name' => 'Kebaya Silver Pendek Full Payet', 'type' => 'Kebaya', 'color' => 'Silver', 'image_url' => 'https://example.com/images/kebaya-silver-pdk.jpg'],

            ['sku' => 'KBY-PNK-SMT', 'name' => 'Kebaya Taupe Semata Kaki Full Payet', 'type' => 'Kebaya', 'color' => 'Taupe', 'image_url' => 'https://example.com/images/kebaya-taupe-smt.jpg'],
            ['sku' => 'KBY-PNK-MLT', 'name' => 'Kebaya Taupe Melantai Full Payet', 'type' => 'Kebaya', 'color' => 'Taupe', 'image_url' => 'https://example.com/images/kebaya-taupe-mlt.jpg'],
            ['sku' => 'KBY-PNK-PDK', 'name' => 'Kebaya Taupe Pendek Full Payet', 'type' => 'Kebaya', 'color' => 'Taupe', 'image_url' => 'https://example.com/images/kebaya-taupe-pdk.jpg'],

            ['sku' => 'BSK-MRH-COAK', 'name' => 'Beskap Merah Coak', 'type' => 'Beskap', 'color' => 'Merah', 'image_url' => 'https://example.com/images/beskap-merah-coak-m.jpg'],
            ['sku' => 'BSK-MRH-PNJG', 'name' => 'Beskap Merah Panjang', 'type' => 'Beskap', 'color' => 'Merah', 'image_url' => 'https://example.com/images/beskap-merah-panjang-m.jpg'],

            ['sku' => 'BSK-GLD-COAK', 'name' => 'Beskap Gold Coak', 'type' => 'Beskap', 'color' => 'Gold', 'image_url' => 'https://example.com/images/beskap-gold-coak-m.jpg'],
            ['sku' => 'BSK-GLD-PNJG', 'name' => 'Beskap Gold Panjang', 'type' => 'Beskap', 'color' => 'Gold', 'image_url' => 'https://example.com/images/beskap-gold-panjang-m.jpg'],

            ['sku' => 'BSK-CKLT-COAK', 'name' => 'Beskap Coklat Coak', 'type' => 'Beskap', 'color' => 'Coklat', 'image_url' => 'https://example.com/images/beskap-coklat-coak-m.jpg'],
            ['sku' => 'BSK-CKLT-PNJG', 'name' => 'Beskap Coklat Panjang', 'type' => 'Beskap', 'color' => 'Coklat', 'image_url' => 'https://example.com/images/beskap-coklat-panjang-m.jpg'],

            ['sku' => 'BSK-HJU-COAK', 'name' => 'Beskap Hijau Coak', 'type' => 'Beskap', 'color' => 'Hijau', 'image_url' => 'https://example.com/images/beskap-hijau-coak-m.jpg'],
            ['sku' => 'BSK-HJU-PNJG', 'name' => 'Beskap Hijau Panjang', 'type' => 'Beskap', 'color' => 'Hijau', 'image_url' => 'https://example.com/images/beskap-hijau-panjang-m.jpg'],

            ['sku' => 'BSK-UNGU-COAK', 'name' => 'Beskap Ungu Coak', 'type' => 'Beskap', 'color' => 'Ungu', 'image_url' => 'https://example.com/images/beskap-ungu-coak-m.jpg'],
            ['sku' => 'BSK-UNGU-PNJG', 'name' => 'Beskap Ungu Panjang', 'type' => 'Beskap', 'color' => 'Ungu', 'image_url' => 'https://example.com/images/beskap-ungu-panjang-m.jpg'],

            ['sku' => 'BSK-HTM-COAK', 'name' => 'Beskap Hitam Coak', 'type' => 'Beskap', 'color' => 'Hitam', 'image_url' => 'https://example.com/images/beskap-hitam-coak-m.jpg'],
            ['sku' => 'BSK-HTM-PNJG', 'name' => 'Beskap Hitam Panjang', 'type' => 'Beskap', 'color' => 'Hitam', 'image_url' => 'https://example.com/images/beskap-hitam-panjang-m.jpg'],

            ['sku' => 'BSK-PTH-COAK', 'name' => 'Beskap Putih Coak', 'type' => 'Beskap', 'color' => 'Putih', 'image_url' => 'https://example.com/images/beskap-putih-coak-m.jpg'],
            ['sku' => 'BSK-PTH-PNJG', 'name' => 'Beskap Putih Panjang', 'type' => 'Beskap', 'color' => 'Putih', 'image_url' => 'https://example.com/images/beskap-putih-panjang-m.jpg'],

            ['sku' => 'BSK-SLVR-COAK', 'name' => 'Beskap Silver Coak', 'type' => 'Beskap', 'color' => 'Silver', 'image_url' => 'https://example.com/images/beskap-silver-coak-m.jpg'],
            ['sku' => 'BSK-SLVR-PNJG', 'name' => 'Beskap Silver Panjang', 'type' => 'Beskap', 'color' => 'Silver', 'image_url' => 'https://example.com/images/beskap-silver-panjang-m.jpg'],

            ['sku' => 'BSK-PNK-COAK', 'name' => 'Beskap Pink Coak', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-coak-m.jpg'],
            ['sku' => 'BSK-PNK-PNJG', 'name' => 'Beskap Pink Panjang', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-panjang-m.jpg'],

            ['sku' => 'JAS-HTM', 'name' => 'Beskap Pink Panjang', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-panjang-m.jpg'],
            ['sku' => 'JAS-PTH', 'name' => 'Beskap Pink Panjang', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-panjang-m.jpg'],
            ['sku' => 'JAS-SLVR', 'name' => 'Beskap Pink Panjang', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-panjang-m.jpg'],
            ['sku' => 'JAS-CKLT', 'name' => 'Beskap Pink Panjang', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-panjang-m.jpg'],
            ['sku' => 'JAS-HJU', 'name' => 'Beskap Pink Panjang', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-panjang-m.jpg'],

            ['sku' => 'VST-PTH', 'name' => 'Beskap Pink Panjang', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-panjang-m.jpg'],
            ['sku' => 'VST-SLVR', 'name' => 'Beskap Pink Panjang', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-panjang-m.jpg'],
            ['sku' => 'VST-HTM', 'name' => 'Beskap Pink Panjang', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-panjang-m.jpg'],

            ['sku' => 'DASI-GLD', 'name' => 'Beskap Pink Panjang', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-panjang-m.jpg'],
            ['sku' => 'DASI-MRH', 'name' => 'Beskap Pink Panjang', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-panjang-m.jpg'],
            ['sku' => 'DASI-BRU', 'name' => 'Beskap Pink Panjang', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-panjang-m.jpg'],
            ['sku' => 'DASI-HJU', 'name' => 'Beskap Pink Panjang', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-panjang-m.jpg'],
            ['sku' => 'DASI-SLVR', 'name' => 'Beskap Pink Panjang', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-panjang-m.jpg'],

            ['sku' => 'KMJ-PTH-PLS', 'name' => 'Beskap Pink Panjang', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-panjang-m.jpg'],
            ['sku' => 'KMJ-PTH-MTF', 'name' => 'Beskap Pink Panjang', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-panjang-m.jpg'],
            ['sku' => 'KMJ-HTM-MTF', 'name' => 'Beskap Pink Panjang', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-panjang-m.jpg'],

            ['sku' => 'KMJ-BTK', 'name' => 'Beskap Pink Panjang', 'type' => 'Beskap', 'color' => 'Pink', 'image_url' => 'https://example.com/images/beskap-pink-panjang-m.jpg'],

            ['sku' => 'CLNA-MRH', 'name' => 'Celana Merah', 'type' => 'Celana', 'color' => 'Merah', 'image_url' => 'https://example.com/images/celana-merah.jpg'],
            ['sku' => 'CLNA-BRU', 'name' => 'Celana Biru', 'type' => 'Celana', 'color' => 'Biru', 'image_url' => 'https://example.com/images/celana-blue.jpg'],
            ['sku' => 'CLNA-PNK', 'name' => 'Celana Pink', 'type' => 'Celana', 'color' => 'Pink', 'image_url' => 'https://example.com/images/celana-pink.jpg'],
            ['sku' => 'CLNA-HJU', 'name' => 'Celana Hijau', 'type' => 'Celana', 'color' => 'Hijau', 'image_url' => 'https://example.com/images/celana-hijau.jpg'],
            ['sku' => 'CLNA-UNGU', 'name' => 'Celana Ungu', 'type' => 'Celana', 'color' => 'Ungu', 'image_url' => 'https://example.com/images/celana-ungu.jpg'],
            ['sku' => 'CLNA-HITM', 'name' => 'Celana Hitam', 'type' => 'Celana', 'color' => 'Hitam', 'image_url' => 'https://example.com/images/celana-hitam.jpg'],
            ['sku' => 'CLNA-PTH', 'name' => 'Celana Putih', 'type' => 'Celana', 'color' => 'Putih', 'image_url' => 'https://example.com/images/celana-putih.jpg'],
            ['sku' => 'CLNA-GLD', 'name' => 'Celana Gold', 'type' => 'Celana', 'color' => 'Gold', 'image_url' => 'https://example.com/images/celana-gold.jpg'],
            ['sku' => 'CLNA-SLVR', 'name' => 'Celana Silver', 'type' => 'Celana', 'color' => 'Silver', 'image_url' => 'https://example.com/images/celana-silver.jpg'],

            ['sku' => 'ROK-BTK-JAVA', 'name' => 'Rok Batik Jogja', 'type' => 'Rok', 'variant' => 'Batik','adat'=>'Jogja', 'image_url' => 'https://example.com/images/beskap-pink-coak-m.jpg'],
            ['sku' => 'ROK-BTK-SOLO', 'name' => 'Rok Batik Solo', 'type' => 'Rok', 'variant' => 'Batik','adat'=>'Solo', 'image_url' => 'https://example.com/images/beskap-pink-coak-m.jpg'],
            ['sku' => 'ROK-BTK-SUNDA', 'name' => 'Rok Batik Sunda', 'type' => 'Rok', 'variant' => 'Batik','adat'=>'Sunda', 'image_url' => 'https://example.com/images/beskap-pink-coak-m.jpg'],
            ['sku' => 'ROK-SNGKT', 'name' => 'Rok Songket', 'type' => 'Rok', 'variant' => 'Songket', 'image_url' => 'https://example.com/images/beskap-pink-coak-m.jpg'],
            
            ['sku' => 'KNSTG-BTK-SUNDA', 'name' => 'Kain Setengah Batik Sunda', 'type' => 'Kain Setengah', 'variant' => 'Batik','adat' => 'Sunda', 'image_url' => 'https://example.com/images/beskap-pink-coak-m.jpg'],
            ['sku' => 'KNSTG-BTK-SOLO', 'name' => 'Kain Setengah Batik Solo', 'type' => 'Kain Setengah', 'variant' => 'Batik','adat' => 'Solo', 'image_url' => 'https://example.com/images/beskap-pink-coak-m.jpg'],
            ['sku' => 'KNSTG-BTK-JOGJA', 'name' => 'Kain Setengah Batik Jogja', 'type' => 'Kain Setengah', 'variant' => 'Batik', 'adat' => 'Jogja', 'image_url' => 'https://example.com/images/beskap-pink-coak-m.jpg'],

            ['sku' => 'KNSTG-SNGKT', 'name' => 'Kain Segengah Songket', 'type' => 'Beskap', 'variant' => 'Songket', 'image_url' => 'https://example.com/images/beskap-pink-coak-m.jpg'],

            // Hitam
            ['sku' => 'SLP-CWK-HTM-UK35', 'name' => 'Selop Wanita Hitam Ukuran 35', 'type' => 'Selop Wanita', 'color' => 'Hitam', 'size' => '35', 'image_url' => 'https://example.com/images/selop-wanita-Hitam-35.jpg'],
            ['sku' => 'SLP-CWK-HTM-UK36', 'name' => 'Selop Wanita Hitam Ukuran 36', 'type' => 'Selop Wanita', 'color' => 'Hitam', 'size' => '36', 'image_url' => 'https://example.com/images/selop-wanita-Hitam-36.jpg'],
            ['sku' => 'SLP-CWK-HTM-UK37', 'name' => 'Selop Wanita Hitam Ukuran 37', 'type' => 'Selop Wanita', 'color' => 'Hitam', 'size' => '37', 'image_url' => 'https://example.com/images/selop-wanita-Hitam-37.jpg'],
            ['sku' => 'SLP-CWK-HTM-UK38', 'name' => 'Selop Wanita Hitam Ukuran 38', 'type' => 'Selop Wanita', 'color' => 'Hitam', 'size' => '38', 'image_url' => 'https://example.com/images/selop-wanita-Hitam-38.jpg'],
            ['sku' => 'SLP-CWK-HTM-UK39', 'name' => 'Selop Wanita Hitam Ukuran 39', 'type' => 'Selop Wanita', 'color' => 'Hitam', 'size' => '39', 'image_url' => 'https://example.com/images/selop-wanita-Hitam-39.jpg'],
            ['sku' => 'SLP-CWK-HTM-UK40', 'name' => 'Selop Wanita Hitam Ukuran 40', 'type' => 'Selop Wanita', 'color' => 'Hitam', 'size' => '40', 'image_url' => 'https://example.com/images/selop-wanita-Hitam-40.jpg'],
            ['sku' => 'SLP-CWK-HTM-UK41', 'name' => 'Selop Wanita Hitam Ukuran 41', 'type' => 'Selop Wanita', 'color' => 'Hitam', 'size' => '41', 'image_url' => 'https://example.com/images/selop-wanita-Hitam-41.jpg'],
            ['sku' => 'SLP-CWK-HTM-UK42', 'name' => 'Selop Wanita Hitam Ukuran 42', 'type' => 'Selop Wanita', 'color' => 'Hitam', 'size' => '42', 'image_url' => 'https://example.com/images/selop-wanita-Hitam-42.jpg'],

            // Putih
            ['sku' => 'SLP-CWK-PTH-UK35', 'name' => 'Selop Wanita Putih Ukuran 35', 'type' => 'Selop Wanita', 'color' => 'Putih', 'size' => '35', 'image_url' => 'https://example.com/images/selop-wanita-Putih-35.jpg'],
            ['sku' => 'SLP-CWK-PTH-UK36', 'name' => 'Selop Wanita Putih Ukuran 36', 'type' => 'Selop Wanita', 'color' => 'Putih', 'size' => '36', 'image_url' => 'https://example.com/images/selop-wanita-Putih-36.jpg'],
            ['sku' => 'SLP-CWK-PTH-UK37', 'name' => 'Selop Wanita Putih Ukuran 37', 'type' => 'Selop Wanita', 'color' => 'Putih', 'size' => '37', 'image_url' => 'https://example.com/images/selop-wanita-Putih-37.jpg'],
            ['sku' => 'SLP-CWK-PTH-UK38', 'name' => 'Selop Wanita Putih Ukuran 38', 'type' => 'Selop Wanita', 'color' => 'Putih', 'size' => '38', 'image_url' => 'https://example.com/images/selop-wanita-Putih-38.jpg'],
            ['sku' => 'SLP-CWK-PTH-UK39', 'name' => 'Selop Wanita Putih Ukuran 39', 'type' => 'Selop Wanita', 'color' => 'Putih', 'size' => '39', 'image_url' => 'https://example.com/images/selop-wanita-Putih-39.jpg'],
            ['sku' => 'SLP-CWK-PTH-UK40', 'name' => 'Selop Wanita Putih Ukuran 40', 'type' => 'Selop Wanita', 'color' => 'Putih', 'size' => '40', 'image_url' => 'https://example.com/images/selop-wanita-Putih-40.jpg'],
            ['sku' => 'SLP-CWK-PTH-UK41', 'name' => 'Selop Wanita Putih Ukuran 41', 'type' => 'Selop Wanita', 'color' => 'Putih', 'size' => '41', 'image_url' => 'https://example.com/images/selop-wanita-Putih-41.jpg'],
            ['sku' => 'SLP-CWK-PTH-UK42', 'name' => 'Selop Wanita Putih Ukuran 42', 'type' => 'Selop Wanita', 'color' => 'Putih', 'size' => '42', 'image_url' => 'https://example.com/images/selop-wanita-Putih-42.jpg'],

            // Merah
            ['sku' => 'SLP-CWK-MRH-UK35', 'name' => 'Selop Wanita Merah Ukuran 35', 'type' => 'Selop Wanita', 'color' => 'Merah', 'size' => '35', 'image_url' => 'https://example.com/images/selop-wanita-red-35.jpg'],
            ['sku' => 'SLP-CWK-MRH-UK36', 'name' => 'Selop Wanita Merah Ukuran 36', 'type' => 'Selop Wanita', 'color' => 'Merah', 'size' => '36', 'image_url' => 'https://example.com/images/selop-wanita-red-36.jpg'],
            ['sku' => 'SLP-CWK-MRH-UK37', 'name' => 'Selop Wanita Merah Ukuran 37', 'type' => 'Selop Wanita', 'color' => 'Merah', 'size' => '37', 'image_url' => 'https://example.com/images/selop-wanita-red-37.jpg'],
            ['sku' => 'SLP-CWK-MRH-UK38', 'name' => 'Selop Wanita Merah Ukuran 38', 'type' => 'Selop Wanita', 'color' => 'Merah', 'size' => '38', 'image_url' => 'https://example.com/images/selop-wanita-red-38.jpg'],
            ['sku' => 'SLP-CWK-MRH-UK39', 'name' => 'Selop Wanita Merah Ukuran 39', 'type' => 'Selop Wanita', 'color' => 'Merah', 'size' => '39', 'image_url' => 'https://example.com/images/selop-wanita-red-39.jpg'],
            ['sku' => 'SLP-CWK-MRH-UK40', 'name' => 'Selop Wanita Merah Ukuran 40', 'type' => 'Selop Wanita', 'color' => 'Merah', 'size' => '40', 'image_url' => 'https://example.com/images/selop-wanita-red-40.jpg'],
            ['sku' => 'SLP-CWK-MRH-UK41', 'name' => 'Selop Wanita Merah Ukuran 41', 'type' => 'Selop Wanita', 'color' => 'Merah', 'size' => '41', 'image_url' => 'https://example.com/images/selop-wanita-red-41.jpg'],
            ['sku' => 'SLP-CWK-MRH-UK42', 'name' => 'Selop Wanita Merah Ukuran 42', 'type' => 'Selop Wanita', 'color' => 'Merah', 'size' => '42', 'image_url' => 'https://example.com/images/selop-wanita-red-42.jpg'],

            // Biru
            ['sku' => 'SLP-CWK-BRU-UK35', 'name' => 'Selop Wanita Biru Ukuran 35', 'type' => 'Selop Wanita', 'color' => 'Biru', 'size' => '35', 'image_url' => 'https://example.com/images/selop-wanita-blue-35.jpg'],
            ['sku' => 'SLP-CWK-BRU-UK36', 'name' => 'Selop Wanita Biru Ukuran 36', 'type' => 'Selop Wanita', 'color' => 'Biru', 'size' => '36', 'image_url' => 'https://example.com/images/selop-wanita-blue-36.jpg'],
            ['sku' => 'SLP-CWK-BRU-UK37', 'name' => 'Selop Wanita Biru Ukuran 37', 'type' => 'Selop Wanita', 'color' => 'Biru', 'size' => '37', 'image_url' => 'https://example.com/images/selop-wanita-blue-37.jpg'],
            ['sku' => 'SLP-CWK-BRU-UK38', 'name' => 'Selop Wanita Biru Ukuran 38', 'type' => 'Selop Wanita', 'color' => 'Biru', 'size' => '38', 'image_url' => 'https://example.com/images/selop-wanita-blue-38.jpg'],
            ['sku' => 'SLP-CWK-BRU-UK39', 'name' => 'Selop Wanita Biru Ukuran 39', 'type' => 'Selop Wanita', 'color' => 'Biru', 'size' => '39', 'image_url' => 'https://example.com/images/selop-wanita-blue-39.jpg'],
            ['sku' => 'SLP-CWK-BRU-UK40', 'name' => 'Selop Wanita Biru Ukuran 40', 'type' => 'Selop Wanita', 'color' => 'Biru', 'size' => '40', 'image_url' => 'https://example.com/images/selop-wanita-blue-40.jpg'],
            ['sku' => 'SLP-CWK-BRU-UK41', 'name' => 'Selop Wanita Biru Ukuran 41', 'type' => 'Selop Wanita', 'color' => 'Biru', 'size' => '41', 'image_url' => 'https://example.com/images/selop-wanita-blue-41.jpg'],
            ['sku' => 'SLP-CWK-BRU-UK42', 'name' => 'Selop Wanita Biru Ukuran 42', 'type' => 'Selop Wanita', 'color' => 'Biru', 'size' => '42', 'image_url' => 'https://example.com/images/selop-wanita-blue-42.jpg'],

            // Hijau
            ['sku' => 'SLP-CWK-HJU-UK35', 'name' => 'Selop Wanita Hijau Ukuran 35', 'type' => 'Selop Wanita', 'color' => 'Hijau', 'size' => '35', 'image_url' => 'https://example.com/images/selop-wanita-green-35.jpg'],
            ['sku' => 'SLP-CWK-HJU-UK36', 'name' => 'Selop Wanita Hijau Ukuran 36', 'type' => 'Selop Wanita', 'color' => 'Hijau', 'size' => '36', 'image_url' => 'https://example.com/images/selop-wanita-green-36.jpg'],
            ['sku' => 'SLP-CWK-HJU-UK37', 'name' => 'Selop Wanita Hijau Ukuran 37', 'type' => 'Selop Wanita', 'color' => 'Hijau', 'size' => '37', 'image_url' => 'https://example.com/images/selop-wanita-green-37.jpg'],
            ['sku' => 'SLP-CWK-HJU-UK38', 'name' => 'Selop Wanita Hijau Ukuran 38', 'type' => 'Selop Wanita', 'color' => 'Hijau', 'size' => '38', 'image_url' => 'https://example.com/images/selop-wanita-green-38.jpg'],
            ['sku' => 'SLP-CWK-HJU-UK39', 'name' => 'Selop Wanita Hijau Ukuran 39', 'type' => 'Selop Wanita', 'color' => 'Hijau', 'size' => '39', 'image_url' => 'https://example.com/images/selop-wanita-green-39.jpg'],
            ['sku' => 'SLP-CWK-HJU-UK40', 'name' => 'Selop Wanita Hijau Ukuran 40', 'type' => 'Selop Wanita', 'color' => 'Hijau', 'size' => '40', 'image_url' => 'https://example.com/images/selop-wanita-green-40.jpg'],
            ['sku' => 'SLP-CWK-HJU-UK41', 'name' => 'Selop Wanita Hijau Ukuran 41', 'type' => 'Selop Wanita', 'color' => 'Hijau', 'size' => '41', 'image_url' => 'https://example.com/images/selop-wanita-green-41.jpg'],
            ['sku' => 'SLP-CWK-HJU-UK42', 'name' => 'Selop Wanita Hijau Ukuran 42', 'type' => 'Selop Wanita', 'color' => 'Hijau', 'size' => '42', 'image_url' => 'https://example.com/images/selop-wanita-green-42.jpg'],

            // Pink
            ['sku' => 'SLP-CWK-PNK-UK35', 'name' => 'Selop Wanita Pink Ukuran 35', 'type' => 'Selop Wanita', 'color' => 'Pink', 'size' => '35', 'image_url' => 'https://example.com/images/selop-wanita-pink-35.jpg'],
            ['sku' => 'SLP-CWK-PNK-UK36', 'name' => 'Selop Wanita Pink Ukuran 36', 'type' => 'Selop Wanita', 'color' => 'Pink', 'size' => '36', 'image_url' => 'https://example.com/images/selop-wanita-pink-36.jpg'],
            ['sku' => 'SLP-CWK-PNK-UK37', 'name' => 'Selop Wanita Pink Ukuran 37', 'type' => 'Selop Wanita', 'color' => 'Pink', 'size' => '37', 'image_url' => 'https://example.com/images/selop-wanita-pink-37.jpg'],
            ['sku' => 'SLP-CWK-PNK-UK38', 'name' => 'Selop Wanita Pink Ukuran 38', 'type' => 'Selop Wanita', 'color' => 'Pink', 'size' => '38', 'image_url' => 'https://example.com/images/selop-wanita-pink-38.jpg'],
            ['sku' => 'SLP-CWK-PNK-UK39', 'name' => 'Selop Wanita Pink Ukuran 39', 'type' => 'Selop Wanita', 'color' => 'Pink', 'size' => '39', 'image_url' => 'https://example.com/images/selop-wanita-pink-39.jpg'],
            ['sku' => 'SLP-CWK-PNK-UK40', 'name' => 'Selop Wanita Pink Ukuran 40', 'type' => 'Selop Wanita', 'color' => 'Pink', 'size' => '40', 'image_url' => 'https://example.com/images/selop-wanita-pink-40.jpg'],
            ['sku' => 'SLP-CWK-PNK-UK41', 'name' => 'Selop Wanita Pink Ukuran 41', 'type' => 'Selop Wanita', 'color' => 'Pink', 'size' => '41', 'image_url' => 'https://example.com/images/selop-wanita-pink-41.jpg'],
            ['sku' => 'SLP-CWK-PNK-UK42', 'name' => 'Selop Wanita Pink Ukuran 42', 'type' => 'Selop Wanita', 'color' => 'Pink', 'size' => '42', 'image_url' => 'https://example.com/images/selop-wanita-pink-42.jpg'],

            // Ungu
            ['sku' => 'SLP-CWK-UNGU-UK35', 'name' => 'Selop Wanita Ungu Ukuran 35', 'type' => 'Selop Wanita', 'color' => 'Ungu', 'size' => '35', 'image_url' => 'https://example.com/images/selop-wanita-purple-35.jpg'],
            ['sku' => 'SLP-CWK-UNGU-UK36', 'name' => 'Selop Wanita Ungu Ukuran 36', 'type' => 'Selop Wanita', 'color' => 'Ungu', 'size' => '36', 'image_url' => 'https://example.com/images/selop-wanita-purple-36.jpg'],
            ['sku' => 'SLP-CWK-UNGU-UK37', 'name' => 'Selop Wanita Ungu Ukuran 37', 'type' => 'Selop Wanita', 'color' => 'Ungu', 'size' => '37', 'image_url' => 'https://example.com/images/selop-wanita-purple-37.jpg'],
            ['sku' => 'SLP-CWK-UNGU-UK38', 'name' => 'Selop Wanita Ungu Ukuran 38', 'type' => 'Selop Wanita', 'color' => 'Ungu', 'size' => '38', 'image_url' => 'https://example.com/images/selop-wanita-purple-38.jpg'],
            ['sku' => 'SLP-CWK-UNGU-UK39', 'name' => 'Selop Wanita Ungu Ukuran 39', 'type' => 'Selop Wanita', 'color' => 'Ungu', 'size' => '39', 'image_url' => 'https://example.com/images/selop-wanita-purple-39.jpg'],
            ['sku' => 'SLP-CWK-UNGU-UK40', 'name' => 'Selop Wanita Ungu Ukuran 40', 'type' => 'Selop Wanita', 'color' => 'Ungu', 'size' => '40', 'image_url' => 'https://example.com/images/selop-wanita-purple-40.jpg'],
            ['sku' => 'SLP-CWK-UNGU-UK41', 'name' => 'Selop Wanita Ungu Ukuran 41', 'type' => 'Selop Wanita', 'color' => 'Ungu', 'size' => '41', 'image_url' => 'https://example.com/images/selop-wanita-purple-41.jpg'],
            ['sku' => 'SLP-CWK-UNGU-UK42', 'name' => 'Selop Wanita Ungu Ukuran 42', 'type' => 'Selop Wanita', 'color' => 'Ungu', 'size' => '42', 'image_url' => 'https://example.com/images/selop-wanita-purple-42.jpg'],
            ['sku' => 'SLP-CWK-UNGU-UK43', 'name' => 'Selop Wanita Ungu Ukuran 43', 'type' => 'Selop Wanita', 'color' => 'Ungu', 'size' => '43', 'image_url' => 'https://example.com/images/selop-wanita-purple-43.jpg'],
            ['sku' => 'SLP-CWK-UNGU-UK44', 'name' => 'Selop Wanita Ungu Ukuran 44', 'type' => 'Selop Wanita', 'color' => 'Ungu', 'size' => '44', 'image_url' => 'https://example.com/images/selop-wanita-purple-44.jpg'],
            ['sku' => 'SLP-CWK-UNGU-UK45', 'name' => 'Selop Wanita Ungu Ukuran 45', 'type' => 'Selop Wanita', 'color' => 'Ungu', 'size' => '45', 'image_url' => 'https://example.com/images/selop-wanita-purple-45.jpg'],
            ['sku' => 'SLP-CWK-UNGU-UK46', 'name' => 'Selop Wanita Ungu Ukuran 46', 'type' => 'Selop Wanita', 'color' => 'Ungu', 'size' => '46', 'image_url' => 'https://example.com/images/selop-wanita-purple-46.jpg'],

            // Gold
            ['sku' => 'SLP-CWK-GLD-UK35', 'name' => 'Selop Wanita Gold Ukuran 35', 'type' => 'Selop Wanita', 'color' => 'Gold', 'size' => '35', 'image_url' => 'https://example.com/images/selop-wanita-gold-35.jpg'],
            ['sku' => 'SLP-CWK-GLD-UK36', 'name' => 'Selop Wanita Gold Ukuran 36', 'type' => 'Selop Wanita', 'color' => 'Gold', 'size' => '36', 'image_url' => 'https://example.com/images/selop-wanita-gold-36.jpg'],
            ['sku' => 'SLP-CWK-GLD-UK37', 'name' => 'Selop Wanita Gold Ukuran 37', 'type' => 'Selop Wanita', 'color' => 'Gold', 'size' => '37', 'image_url' => 'https://example.com/images/selop-wanita-gold-37.jpg'],
            ['sku' => 'SLP-CWK-GLD-UK38', 'name' => 'Selop Wanita Gold Ukuran 38', 'type' => 'Selop Wanita', 'color' => 'Gold', 'size' => '38', 'image_url' => 'https://example.com/images/selop-wanita-gold-38.jpg'],
            ['sku' => 'SLP-CWK-GLD-UK39', 'name' => 'Selop Wanita Gold Ukuran 39', 'type' => 'Selop Wanita', 'color' => 'Gold', 'size' => '39', 'image_url' => 'https://example.com/images/selop-wanita-gold-39.jpg'],
            ['sku' => 'SLP-CWK-GLD-UK40', 'name' => 'Selop Wanita Gold Ukuran 40', 'type' => 'Selop Wanita', 'color' => 'Gold', 'size' => '40', 'image_url' => 'https://example.com/images/selop-wanita-gold-40.jpg'],
            ['sku' => 'SLP-CWK-GLD-UK41', 'name' => 'Selop Wanita Gold Ukuran 41', 'type' => 'Selop Wanita', 'color' => 'Gold', 'size' => '41', 'image_url' => 'https://example.com/images/selop-wanita-gold-41.jpg'],
            ['sku' => 'SLP-CWK-GLD-UK42', 'name' => 'Selop Wanita Gold Ukuran 42', 'type' => 'Selop Wanita', 'color' => 'Gold', 'size' => '42', 'image_url' => 'https://example.com/images/selop-wanita-gold-42.jpg'],

            // Silver
            ['sku' => 'SLP-CWK-SLVR-UK35', 'name' => 'Selop Wanita Silver Ukuran 35', 'type' => 'Selop Wanita', 'color' => 'Silver', 'size' => '35', 'image_url' => 'https://example.com/images/selop-wanita-silver-35.jpg'],
            ['sku' => 'SLP-CWK-SLVR-UK36', 'name' => 'Selop Wanita Silver Ukuran 36', 'type' => 'Selop Wanita', 'color' => 'Silver', 'size' => '36', 'image_url' => 'https://example.com/images/selop-wanita-silver-36.jpg'],
            ['sku' => 'SLP-CWK-SLVR-UK37', 'name' => 'Selop Wanita Silver Ukuran 37', 'type' => 'Selop Wanita', 'color' => 'Silver', 'size' => '37', 'image_url' => 'https://example.com/images/selop-wanita-silver-37.jpg'],
            ['sku' => 'SLP-CWK-SLVR-UK38', 'name' => 'Selop Wanita Silver Ukuran 38', 'type' => 'Selop Wanita', 'color' => 'Silver', 'size' => '38', 'image_url' => 'https://example.com/images/selop-wanita-silver-38.jpg'],
            ['sku' => 'SLP-CWK-SLVR-UK39', 'name' => 'Selop Wanita Silver Ukuran 39', 'type' => 'Selop Wanita', 'color' => 'Silver', 'size' => '39', 'image_url' => 'https://example.com/images/selop-wanita-silver-39.jpg'],
            ['sku' => 'SLP-CWK-SLVR-UK40', 'name' => 'Selop Wanita Silver Ukuran 40', 'type' => 'Selop Wanita', 'color' => 'Silver', 'size' => '40', 'image_url' => 'https://example.com/images/selop-wanita-silver-40.jpg'],
            ['sku' => 'SLP-CWK-SLVR-UK41', 'name' => 'Selop Wanita Silver Ukuran 41', 'type' => 'Selop Wanita', 'color' => 'Silver', 'size' => '41', 'image_url' => 'https://example.com/images/selop-wanita-silver-41.jpg'],
            ['sku' => 'SLP-CWK-SLVR-UK42', 'name' => 'Selop Wanita Silver Ukuran 42', 'type' => 'Selop Wanita', 'color' => 'Silver', 'size' => '42', 'image_url' => 'https://example.com/images/selop-wanita-silver-42.jpg'],


            // Merah
            ['sku' => 'SLP-CWO-MRH-UK40', 'name' => 'Selop Pria Merah Ukuran 40', 'type' => 'Selop Pria', 'color' => 'Merah', 'size' => '40', 'image_url' => 'https://example.com/images/selop-pria-merah-40.jpg'],
            ['sku' => 'SLP-CWO-MRH-UK41', 'name' => 'Selop Pria Merah Ukuran 41', 'type' => 'Selop Pria', 'color' => 'Merah', 'size' => '41', 'image_url' => 'https://example.com/images/selop-pria-merah-41.jpg'],
            ['sku' => 'SLP-CWO-MRH-UK42', 'name' => 'Selop Pria Merah Ukuran 42', 'type' => 'Selop Pria', 'color' => 'Merah', 'size' => '42', 'image_url' => 'https://example.com/images/selop-pria-merah-42.jpg'],
            ['sku' => 'SLP-CWO-MRH-UK43', 'name' => 'Selop Pria Merah Ukuran 43', 'type' => 'Selop Pria', 'color' => 'Merah', 'size' => '43', 'image_url' => 'https://example.com/images/selop-pria-merah-43.jpg'],
            ['sku' => 'SLP-CWO-MRH-UK44', 'name' => 'Selop Pria Merah Ukuran 44', 'type' => 'Selop Pria', 'color' => 'Merah', 'size' => '44', 'image_url' => 'https://example.com/images/selop-pria-merah-44.jpg'],
            ['sku' => 'SLP-CWO-MRH-UK45', 'name' => 'Selop Pria Merah Ukuran 45', 'type' => 'Selop Pria', 'color' => 'Merah', 'size' => '45', 'image_url' => 'https://example.com/images/selop-pria-merah-45.jpg'],
            ['sku' => 'SLP-CWO-MRH-UK46', 'name' => 'Selop Pria Merah Ukuran 46', 'type' => 'Selop Pria', 'color' => 'Merah', 'size' => '46', 'image_url' => 'https://example.com/images/selop-pria-merah-46.jpg'],

            // Biru
            ['sku' => 'SLP-CWO-BRU-UK40', 'name' => 'Selop Pria Biru Ukuran 40', 'type' => 'Selop Pria', 'color' => 'Biru', 'size' => '40', 'image_url' => 'https://example.com/images/selop-pria-biru-40.jpg'],
            ['sku' => 'SLP-CWO-BRU-UK41', 'name' => 'Selop Pria Biru Ukuran 41', 'type' => 'Selop Pria', 'color' => 'Biru', 'size' => '41', 'image_url' => 'https://example.com/images/selop-pria-biru-41.jpg'],
            ['sku' => 'SLP-CWO-BRU-UK42', 'name' => 'Selop Pria Biru Ukuran 42', 'type' => 'Selop Pria', 'color' => 'Biru', 'size' => '42', 'image_url' => 'https://example.com/images/selop-pria-biru-42.jpg'],
            ['sku' => 'SLP-CWO-BRU-UK43', 'name' => 'Selop Pria Biru Ukuran 43', 'type' => 'Selop Pria', 'color' => 'Biru', 'size' => '43', 'image_url' => 'https://example.com/images/selop-pria-biru-43.jpg'],
            ['sku' => 'SLP-CWO-BRU-UK44', 'name' => 'Selop Pria Biru Ukuran 44', 'type' => 'Selop Pria', 'color' => 'Biru', 'size' => '44', 'image_url' => 'https://example.com/images/selop-pria-biru-44.jpg'],
            ['sku' => 'SLP-CWO-BRU-UK45', 'name' => 'Selop Pria Biru Ukuran 45', 'type' => 'Selop Pria', 'color' => 'Biru', 'size' => '45', 'image_url' => 'https://example.com/images/selop-pria-biru-45.jpg'],
            ['sku' => 'SLP-CWO-BRU-UK46', 'name' => 'Selop Pria Biru Ukuran 46', 'type' => 'Selop Pria', 'color' => 'Biru', 'size' => '46', 'image_url' => 'https://example.com/images/selop-pria-biru-46.jpg'],

            // Hijau
            ['sku' => 'SLP-CWO-HJA-UK40', 'name' => 'Selop Pria Hijau Ukuran 40', 'type' => 'Selop Pria', 'color' => 'Hijau', 'size' => '40', 'image_url' => 'https://example.com/images/selop-pria-hijau-40.jpg'],
            ['sku' => 'SLP-CWO-HJA-UK41', 'name' => 'Selop Pria Hijau Ukuran 41', 'type' => 'Selop Pria', 'color' => 'Hijau', 'size' => '41', 'image_url' => 'https://example.com/images/selop-pria-hijau-41.jpg'],
            ['sku' => 'SLP-CWO-HJA-UK42', 'name' => 'Selop Pria Hijau Ukuran 42', 'type' => 'Selop Pria', 'color' => 'Hijau', 'size' => '42', 'image_url' => 'https://example.com/images/selop-pria-hijau-42.jpg'],
            ['sku' => 'SLP-CWO-HJA-UK43', 'name' => 'Selop Pria Hijau Ukuran 43', 'type' => 'Selop Pria', 'color' => 'Hijau', 'size' => '43', 'image_url' => 'https://example.com/images/selop-pria-hijau-43.jpg'],
            ['sku' => 'SLP-CWO-HJA-UK44', 'name' => 'Selop Pria Hijau Ukuran 44', 'type' => 'Selop Pria', 'color' => 'Hijau', 'size' => '44', 'image_url' => 'https://example.com/images/selop-pria-hijau-44.jpg'],
            ['sku' => 'SLP-CWO-HJA-UK45', 'name' => 'Selop Pria Hijau Ukuran 45', 'type' => 'Selop Pria', 'color' => 'Hijau', 'size' => '45', 'image_url' => 'https://example.com/images/selop-pria-hijau-45.jpg'],
            ['sku' => 'SLP-CWO-HJA-UK46', 'name' => 'Selop Pria Hijau Ukuran 46', 'type' => 'Selop Pria', 'color' => 'Hijau', 'size' => '46', 'image_url' => 'https://example.com/images/selop-pria-hijau-46.jpg'],

            // Pink
            ['sku' => 'SLP-CWO-PNK-UK40', 'name' => 'Selop Pria Pink Ukuran 40', 'type' => 'Selop Pria', 'color' => 'Pink', 'size' => '40', 'image_url' => 'https://example.com/images/selop-pria-pink-40.jpg'],
            ['sku' => 'SLP-CWO-PNK-UK41', 'name' => 'Selop Pria Pink Ukuran 41', 'type' => 'Selop Pria', 'color' => 'Pink', 'size' => '41', 'image_url' => 'https://example.com/images/selop-pria-pink-41.jpg'],
            ['sku' => 'SLP-CWO-PNK-UK42', 'name' => 'Selop Pria Pink Ukuran 42', 'type' => 'Selop Pria', 'color' => 'Pink', 'size' => '42', 'image_url' => 'https://example.com/images/selop-pria-pink-42.jpg'],
            ['sku' => 'SLP-CWO-PNK-UK43', 'name' => 'Selop Pria Pink Ukuran 43', 'type' => 'Selop Pria', 'color' => 'Pink', 'size' => '43', 'image_url' => 'https://example.com/images/selop-pria-pink-43.jpg'],
            ['sku' => 'SLP-CWO-PNK-UK44', 'name' => 'Selop Pria Pink Ukuran 44', 'type' => 'Selop Pria', 'color' => 'Pink', 'size' => '44', 'image_url' => 'https://example.com/images/selop-pria-pink-44.jpg'],
            ['sku' => 'SLP-CWO-PNK-UK45', 'name' => 'Selop Pria Pink Ukuran 45', 'type' => 'Selop Pria', 'color' => 'Pink', 'size' => '45', 'image_url' => 'https://example.com/images/selop-pria-pink-45.jpg'],
            ['sku' => 'SLP-CWO-PNK-UK46', 'name' => 'Selop Pria Pink Ukuran 46', 'type' => 'Selop Pria', 'color' => 'Pink', 'size' => '46', 'image_url' => 'https://example.com/images/selop-pria-pink-46.jpg'],

            // Ungu
            ['sku' => 'SLP-CWO-UNG-UK40', 'name' => 'Selop Pria Ungu Ukuran 40', 'type' => 'Selop Pria', 'color' => 'Ungu', 'size' => '40', 'image_url' => 'https://example.com/images/selop-pria-ungu-40.jpg'],
            ['sku' => 'SLP-CWO-UNG-UK41', 'name' => 'Selop Pria Ungu Ukuran 41', 'type' => 'Selop Pria', 'color' => 'Ungu', 'size' => '41', 'image_url' => 'https://example.com/images/selop-pria-ungu-41.jpg'],
            ['sku' => 'SLP-CWO-UNG-UK42', 'name' => 'Selop Pria Ungu Ukuran 42', 'type' => 'Selop Pria', 'color' => 'Ungu', 'size' => '42', 'image_url' => 'https://example.com/images/selop-pria-ungu-42.jpg'],
            ['sku' => 'SLP-CWO-UNG-UK43', 'name' => 'Selop Pria Ungu Ukuran 43', 'type' => 'Selop Pria', 'color' => 'Ungu', 'size' => '43', 'image_url' => 'https://example.com/images/selop-pria-ungu-43.jpg'],
            ['sku' => 'SLP-CWO-UNG-UK44', 'name' => 'Selop Pria Ungu Ukuran 44', 'type' => 'Selop Pria', 'color' => 'Ungu', 'size' => '44', 'image_url' => 'https://example.com/images/selop-pria-ungu-44.jpg'],
            ['sku' => 'SLP-CWO-UNG-UK45', 'name' => 'Selop Pria Ungu Ukuran 45', 'type' => 'Selop Pria', 'color' => 'Ungu', 'size' => '45', 'image_url' => 'https://example.com/images/selop-pria-ungu-45.jpg'],
            ['sku' => 'SLP-CWO-UNG-UK46', 'name' => 'Selop Pria Ungu Ukuran 46', 'type' => 'Selop Pria', 'color' => 'Ungu', 'size' => '46', 'image_url' => 'https://example.com/images/selop-pria-ungu-46.jpg'],

            // Putih
            ['sku' => 'SLP-CWO-PTH-UK40', 'name' => 'Selop Pria Putih Ukuran 40', 'type' => 'Selop Pria', 'color' => 'Putih', 'size' => '40', 'image_url' => 'https://example.com/images/selop-pria-putih-40.jpg'],
            ['sku' => 'SLP-CWO-PTH-UK41', 'name' => 'Selop Pria Putih Ukuran 41', 'type' => 'Selop Pria', 'color' => 'Putih', 'size' => '41', 'image_url' => 'https://example.com/images/selop-pria-putih-41.jpg'],
            ['sku' => 'SLP-CWO-PTH-UK42', 'name' => 'Selop Pria Putih Ukuran 42', 'type' => 'Selop Pria', 'color' => 'Putih', 'size' => '42', 'image_url' => 'https://example.com/images/selop-pria-putih-42.jpg'],
            ['sku' => 'SLP-CWO-PTH-UK43', 'name' => 'Selop Pria Putih Ukuran 43', 'type' => 'Selop Pria', 'color' => 'Putih', 'size' => '43', 'image_url' => 'https://example.com/images/selop-pria-putih-43.jpg'],
            ['sku' => 'SLP-CWO-PTH-UK44', 'name' => 'Selop Pria Putih Ukuran 44', 'type' => 'Selop Pria', 'color' => 'Putih', 'size' => '44', 'image_url' => 'https://example.com/images/selop-pria-putih-44.jpg'],
            ['sku' => 'SLP-CWO-PTH-UK45', 'name' => 'Selop Pria Putih Ukuran 45', 'type' => 'Selop Pria', 'color' => 'Putih', 'size' => '45', 'image_url' => 'https://example.com/images/selop-pria-putih-45.jpg'],
            ['sku' => 'SLP-CWO-PTH-UK46', 'name' => 'Selop Pria Putih Ukuran 46', 'type' => 'Selop Pria', 'color' => 'Putih', 'size' => '46', 'image_url' => 'https://example.com/images/selop-pria-putih-46.jpg'],

            // Hitam
            ['sku' => 'SLP-CWO-HTM-UK40', 'name' => 'Selop Pria Hitam Ukuran 40', 'type' => 'Selop Pria', 'color' => 'Hitam', 'size' => '40', 'image_url' => 'https://example.com/images/selop-pria-hitam-40.jpg'],
            ['sku' => 'SLP-CWO-HTM-UK41', 'name' => 'Selop Pria Hitam Ukuran 41', 'type' => 'Selop Pria', 'color' => 'Hitam', 'size' => '41', 'image_url' => 'https://example.com/images/selop-pria-hitam-41.jpg'],
            ['sku' => 'SLP-CWO-HTM-UK42', 'name' => 'Selop Pria Hitam Ukuran 42', 'type' => 'Selop Pria', 'color' => 'Hitam', 'size' => '42', 'image_url' => 'https://example.com/images/selop-pria-hitam-42.jpg'],
            ['sku' => 'SLP-CWO-HTM-UK43', 'name' => 'Selop Pria Hitam Ukuran 43', 'type' => 'Selop Pria', 'color' => 'Hitam', 'size' => '43', 'image_url' => 'https://example.com/images/selop-pria-hitam-43.jpg'],
            ['sku' => 'SLP-CWO-HTM-UK44', 'name' => 'Selop Pria Hitam Ukuran 44', 'type' => 'Selop Pria', 'color' => 'Hitam', 'size' => '44', 'image_url' => 'https://example.com/images/selop-pria-hitam-44.jpg'],
            ['sku' => 'SLP-CWO-HTM-UK45', 'name' => 'Selop Pria Hitam Ukuran 45', 'type' => 'Selop Pria', 'color' => 'Hitam', 'size' => '45', 'image_url' => 'https://example.com/images/selop-pria-hitam-45.jpg'],
            ['sku' => 'SLP-CWO-HTM-UK46', 'name' => 'Selop Pria Hitam Ukuran 46', 'type' => 'Selop Pria', 'color' => 'Hitam', 'size' => '46', 'image_url' => 'https://example.com/images/selop-pria-hitam-46.jpg'],

            // Gold
            ['sku' => 'SLP-CWO-GLD-UK40', 'name' => 'Selop Pria Gold Ukuran 40', 'type' => 'Selop ', 'color' => 'Gold', 'size' => '40', 'image_url' => 'https://example.com/images/selop-pria-gold-40.jpg'],
            ['sku' => 'SLP-CWO-GLD-UK41', 'name' => 'Selop Pria Gold Ukuran 41', 'type' => 'Selop Pria', 'color' => 'Gold', 'size' => '41', 'image_url' => 'https://example.com/images/selop-pria-gold-41.jpg'],
            ['sku' => 'SLP-CWO-GLD-UK42', 'name' => 'Selop Pria Gold Ukuran 42', 'type' => 'Selop Pria', 'color' => 'Gold', 'size' => '42', 'image_url' => 'https://example.com/images/selop-pria-gold-42.jpg'],
            ['sku' => 'SLP-CWO-GLD-UK43', 'name' => 'Selop Pria Gold Ukuran 43', 'type' => 'Selop Pria', 'color' => 'Gold', 'size' => '43', 'image_url' => 'https://example.com/images/selop-pria-gold-43.jpg'],
            ['sku' => 'SLP-CWO-GLD-UK44', 'name' => 'Selop Pria Gold Ukuran 44', 'type' => 'Selop Pria', 'color' => 'Gold', 'size' => '44', 'image_url' => 'https://example.com/images/selop-pria-gold-44.jpg'],
            ['sku' => 'SLP-CWO-GLD-UK45', 'name' => 'Selop Pria Gold Ukuran 45', 'type' => 'Selop Pria', 'color' => 'Gold', 'size' => '45', 'image_url' => 'https://example.com/images/selop-pria-gold-45.jpg'],
            ['sku' => 'SLP-CWO-GLD-UK46', 'name' => 'Selop Pria Gold Ukuran 46', 'type' => 'Selop Pria', 'color' => 'Gold', 'size' => '46', 'image_url' => 'https://example.com/images/selop-pria-gold-46.jpg'],

            // Silver
            ['sku' => 'SLP-CWO-SLVR-UK40', 'name' => 'Selop Pria Silver Ukuran 40', 'type' => 'Selop Pria', 'color' => 'Silver', 'size' => '40', 'image_url' => 'https://example.com/images/selop-pria-silver-40.jpg'],
            ['sku' => 'SLP-CWO-SLVR-UK41', 'name' => 'Selop Pria Silver Ukuran 41', 'type' => 'Selop Pria', 'color' => 'Silver', 'size' => '41', 'image_url' => 'https://example.com/images/selop-pria-silver-41.jpg'],
            ['sku' => 'SLP-CWO-SLVR-UK42', 'name' => 'Selop Pria Silver Ukuran 42', 'type' => 'Selop Pria', 'color' => 'Silver', 'size' => '42', 'image_url' => 'https://example.com/images/selop-pria-silver-42.jpg'],
            ['sku' => 'SLP-CWO-SLVR-UK43', 'name' => 'Selop Pria Silver Ukuran 43', 'type' => 'Selop Pria', 'color' => 'Silver', 'size' => '43', 'image_url' => 'https://example.com/images/selop-pria-silver-43.jpg'],
            ['sku' => 'SLP-CWO-SLVR-UK44', 'name' => 'Selop Pria Silver Ukuran 44', 'type' => 'Selop Pria', 'color' => 'Silver', 'size' => '44', 'image_url' => 'https://example.com/images/selop-pria-silver-44.jpg'],
            ['sku' => 'SLP-CWO-SLVR-UK45', 'name' => 'Selop Pria Silver Ukuran 45', 'type' => 'Selop Pria', 'color' => 'Silver', 'size' => '45', 'image_url' => 'https://example.com/images/selop-pria-silver-45.jpg'],
            ['sku' => 'SLP-CWO-SLVR-UK46', 'name' => 'Selop Pria Silver Ukuran 46', 'type' => 'Selop Pria', 'color' => 'Silver', 'size' => '46', 'image_url' => 'https://example.com/images/selop-pria-silver-46.jpg'],

            ['sku' => 'HW-BLNGK-JW', 'name' => 'Blangkon Jawa', 'type' => 'Headwear', 'item' => 'Blangkon', 'adat' => 'Jawa', 'image_url' => 'https://example.com/images/beskap-pink-coak-m.jpg'],
            ['sku' => 'HW-BLNGK-SUNDA', 'name' => 'Blangkon Sunda', 'type' => 'Headwear', 'item' => 'Blangkon', 'adat' => 'Sunda', 'image_url' => 'https://example.com/images/beskap-pink-coak-m.jpg'],
            ['sku' => 'HW-BLNGK-SOLO', 'name' => 'Blangkon Solo', 'type' => 'Headwear', 'item' => 'Blangkon', 'adat' => 'Solo', 'image_url' => 'https://example.com/images/beskap-pink-coak-m.jpg'],

            ['sku' => 'HW-TJK', 'name' => 'Tanjak', 'type' => 'Headwear', 'item' => 'Tanjak', 'image_url' => 'https://example.com/images/beskap-pink-coak-m.jpg'],
            ['sku' => 'HW-SLK', 'name' => 'Saluak', 'type' => 'Headwear', 'item' => 'Saluak', 'image_url' => 'https://example.com/images/beskap-pink-coak-m.jpg'],

            ['sku' => 'HW-PCI-MRH', 'name' => 'Peci Merah', 'type' => 'Peci', 'color' => 'Merah', 'image_url' => 'https://example.com/images/peci-merah.jpg'],
            ['sku' => 'HW-PCI-BRU', 'name' => 'Peci Biru', 'type' => 'Peci', 'color' => 'Biru', 'image_url' => 'https://example.com/images/peci-blue.jpg'],
            ['sku' => 'HW-PCI-PNK', 'name' => 'Peci Pink', 'type' => 'Peci', 'color' => 'Pink', 'image_url' => 'https://example.com/images/peci-pink.jpg'],
            ['sku' => 'HW-PCI-HJU', 'name' => 'Peci Hijau', 'type' => 'Peci', 'color' => 'Hijau', 'image_url' => 'https://example.com/images/peci-hijau.jpg'],
            ['sku' => 'HW-PCI-UNGU', 'name' => 'Peci Ungu', 'type' => 'Peci', 'color' => 'Ungu', 'image_url' => 'https://example.com/images/peci-purple.jpg'],
            ['sku' => 'HW-PCI-HITM', 'name' => 'Peci Hitam', 'type' => 'Peci', 'color' => 'Hitam', 'image_url' => 'https://example.com/images/peci-hitam.jpg'],
            ['sku' => 'HW-PCI-PTH', 'name' => 'Peci Putih', 'type' => 'Peci', 'color' => 'Putih', 'image_url' => 'https://example.com/images/peci-putih.jpg'],
            ['sku' => 'HW-PCI-GLD', 'name' => 'Peci Gold', 'type' => 'Peci', 'color' => 'Gold', 'image_url' => 'https://example.com/images/peci-gold.jpg'],
            ['sku' => 'HW-PCI-SLVR', 'name' => 'Peci Silver', 'type' => 'Peci', 'color' => 'Silver', 'image_url' => 'https://example.com/images/peci-silver.jpg'],

            ['sku' => 'AKSR-CROWN-SLVR', 'name' => 'Crown Silver', 'type' => 'Aksesoris', 'item' => 'Crown', 'color' => 'Silver', 'image_url' => 'https://example.com/images/beskap-pink-coak-m.jpg'],
            ['sku' => 'AKSR-CROWN-GLD', 'name' => 'Crown Silver', 'type' => 'Aksesoris', 'item' => 'Crown', 'color' => 'Gold', 'image_url' => 'https://example.com/images/beskap-pink-coak-m.jpg'],


            // Bros Kebaya
            ['sku' => 'AKSR-BROSKBY-SLVR', 'name' => 'Bros Kebaya Silver', 'type' => 'Aksesoris', 'item' => 'Bros Kebaya', 'color' => 'Silver', 'image_url' => 'https://example.com/images/bros-kebaya-silver.jpg'],
            ['sku' => 'AKSR-BROSKBY-GLD', 'name' => 'Bros Kebaya Gold', 'type' => 'Aksesoris', 'item' => 'Bros Kebaya', 'color' => 'Gold', 'image_url' => 'https://example.com/images/bros-kebaya-gold.jpg'],

            // Bros Blangkon
            ['sku' => 'AKSR-BROSBLNGK-SLVR', 'name' => 'Bros Blangkon Silver', 'type' => 'Aksesoris', 'item' => 'Bros Blangkon', 'color' => 'Silver', 'image_url' => 'https://example.com/images/bros-blangkon-silver.jpg'],
            ['sku' => 'AKSR-BROSBLNGK-GLD', 'name' => 'Bros Blangkon Gold', 'type' => 'Aksesoris', 'item' => 'Bros Blangkon', 'color' => 'Gold', 'image_url' => 'https://example.com/images/bros-blangkon-gold.jpg'],

            // Kembang Goyang
            ['sku' => 'AKSR-GYNG-SLVR', 'name' => 'Kembang Goyang Silver', 'type' => 'Aksesoris', 'item' => 'Kembang Goyang', 'color' => 'Silver', 'image_url' => 'https://example.com/images/kembang-goyang-silver.jpg'],
            ['sku' => 'AKSR-GYNG-GLD', 'name' => 'Kembang Goyang Gold', 'type' => 'Aksesoris', 'item' => 'Kembang Goyang', 'color' => 'Gold', 'image_url' => 'https://example.com/images/kembang-goyang-gold.jpg'],

            // Karset
            ['sku' => 'AKSR-KRST-SLVR', 'name' => 'Karset Silver', 'type' => 'Aksesoris', 'item' => 'Karset', 'color' => 'Silver', 'image_url' => 'https://example.com/images/karset-silver.jpg'],
            ['sku' => 'AKSR-KRST-GLD', 'name' => 'Karset Gold', 'type' => 'Aksesoris', 'item' => 'Karset', 'color' => 'Gold', 'image_url' => 'https://example.com/images/karset-gold.jpg'],

            // Pin
            ['sku' => 'AKSR-PIN-SLVR', 'name' => 'Pin Silver', 'type' => 'Aksesoris', 'item' => 'Pin', 'color' => 'Silver', 'image_url' => 'https://example.com/images/pin-silver.jpg'],
            ['sku' => 'AKSR-PIN-GLD', 'name' => 'Pin Gold', 'type' => 'Aksesoris', 'item' => 'Pin', 'color' => 'Gold', 'image_url' => 'https://example.com/images/pin-gold.jpg'],

            // Stagen
            ['sku' => 'AKSR-STGN-MRH', 'name' => 'Stagen Merah', 'type' => 'Aksesoris', 'item' => 'Stagen', 'color' => 'Merah', 'image_url' => 'https://example.com/images/stagen-merah.jpg'],
            ['sku' => 'AKSR-STGN-HJM', 'name' => 'Stagen Hijau', 'type' => 'Aksesoris', 'item' => 'Stagen', 'color' => 'Hijau', 'image_url' => 'https://example.com/images/stagen-hijau.jpg'],
            ['sku' => 'AKSR-STGN-HTM', 'name' => 'Stagen Hitam', 'type' => 'Aksesoris', 'item' => 'Stagen', 'color' => 'Hitam', 'image_url' => 'https://example.com/images/stagen-hitam.jpg'],

            // Keris
            ['sku' => 'AKSR-KRIS-SLVR', 'name' => 'Keris Silver', 'type' => 'Aksesoris', 'item' => 'Keris', 'color' => 'Silver', 'image_url' => 'https://example.com/images/keris-silver.jpg'],
            ['sku' => 'AKSR-KRIS-GLD', 'name' => 'Keris Gold', 'type' => 'Aksesoris', 'item' => 'Keris', 'color' => 'Gold', 'image_url' => 'https://example.com/images/keris-gold.jpg'],

            // Sabuk
            ['sku' => 'AKSR-SABUK-MRH', 'name' => 'Sabuk Merah', 'type' => 'Aksesoris', 'color' => 'Merah', 'image_url' => 'https://example.com/images/sabuk-merah-m.jpg'],
            ['sku' => 'AKSR-SABUK-GLD', 'name' => 'Sabuk Gold', 'type' => 'Aksesoris', 'color' => 'Gold', 'image_url' => 'https://example.com/images/sabuk-gold-m.jpg'],
            ['sku' => 'AKSR-SABUK-CKLT', 'name' => 'Sabuk Coklat', 'type' => 'Aksesoris', 'color' => 'Coklat', 'image_url' => 'https://example.com/images/sabuk-coklat-m.jpg'],
            ['sku' => 'AKSR-SABUK-HJU', 'name' => 'Sabuk Hijau', 'type' => 'Aksesoris', 'color' => 'Hijau', 'image_url' => 'https://example.com/images/sabuk-hijau-m.jpg'],
            ['sku' => 'AKSR-SABUK-UNGU', 'name' => 'Sabuk Ungu', 'type' => 'Aksesoris', 'color' => 'Ungu', 'image_url' => 'https://example.com/images/sabuk-ungu-m.jpg'],
            ['sku' => 'AKSR-SABUK-HTM', 'name' => 'Sabuk Hitam', 'type' => 'Aksesoris', 'color' => 'Hitam', 'image_url' => 'https://example.com/images/sabuk-hitam-m.jpg'],
            ['sku' => 'AKSR-SABUK-PTH', 'name' => 'Sabuk Putih', 'type' => 'Aksesoris', 'color' => 'Putih', 'image_url' => 'https://example.com/images/sabuk-putih-m.jpg'],
            ['sku' => 'AKSR-SABUK-SLVR', 'name' => 'Sabuk Silver', 'type' => 'Aksesoris', 'color' => 'Silver', 'image_url' => 'https://example.com/images/sabuk-silver-m.jpg'],
            ['sku' => 'AKSR-SABUK-PNK', 'name' => 'Sabuk Pink', 'type' => 'Aksesoris', 'color' => 'Pink', 'image_url' => 'https://example.com/images/sabuk-pink-m.jpg'],

            // Bustier
            ['sku' => 'AKSR-BSTER-MRH', 'name' => 'Bustier Merah', 'type' => 'Aksesoris', 'color' => 'Merah', 'image_url' => 'https://example.com/images/bustier-merah-m.jpg'],
            ['sku' => 'AKSR-BSTER-GLD', 'name' => 'Bustier Gold', 'type' => 'Aksesoris', 'color' => 'Gold', 'image_url' => 'https://example.com/images/bustier-gold-m.jpg'],
            ['sku' => 'AKSR-BSTER-CKLT', 'name' => 'Bustier Coklat', 'type' => 'Aksesoris', 'color' => 'Coklat', 'image_url' => 'https://example.com/images/bustier-coklat-m.jpg'],
            ['sku' => 'AKSR-BSTER-HJU', 'name' => 'Bustier Hijau', 'type' => 'Aksesoris', 'color' => 'Hijau', 'image_url' => 'https://example.com/images/bustier-hijau-m.jpg'],
            ['sku' => 'AKSR-BSTER-UNGU', 'name' => 'Bustier Ungu', 'type' => 'Aksesoris', 'color' => 'Ungu', 'image_url' => 'https://example.com/images/bustier-ungu-m.jpg'],
            ['sku' => 'AKSR-BSTER-HTM', 'name' => 'Bustier Hitam', 'type' => 'Aksesoris', 'color' => 'Hitam', 'image_url' => 'https://example.com/images/bustier-hitam-m.jpg'],
            ['sku' => 'AKSR-BSTER-PTH', 'name' => 'Bustier Putih', 'type' => 'Aksesoris', 'color' => 'Putih', 'image_url' => 'https://example.com/images/bustier-putih-m.jpg'],
            ['sku' => 'AKSR-BSTER-SLVR', 'name' => 'Bustier Silver', 'type' => 'Aksesoris', 'color' => 'Silver', 'image_url' => 'https://example.com/images/bustier-silver-m.jpg'],
            ['sku' => 'AKSR-BSTER-PNK', 'name' => 'Bustier Pink', 'type' => 'Aksesoris', 'color' => 'Pink', 'image_url' => 'https://example.com/images/bustier-pink-m.jpg'],

            // Manset
            ['sku' => 'AKSR-MANST-MRH', 'name' => 'Manset Merah', 'type' => 'Aksesoris', 'color' => 'Merah', 'image_url' => 'https://example.com/images/manset-merah-m.jpg'],
            ['sku' => 'AKSR-MANST-GLD', 'name' => 'Manset Gold', 'type' => 'Aksesoris', 'color' => 'Gold', 'image_url' => 'https://example.com/images/manset-gold-m.jpg'],
            ['sku' => 'AKSR-MANST-CKLT', 'name' => 'Manset Coklat', 'type' => 'Aksesoris', 'color' => 'Coklat', 'image_url' => 'https://example.com/images/manset-coklat-m.jpg'],
            ['sku' => 'AKSR-MANST-HJU', 'name' => 'Manset Hijau', 'type' => 'Aksesoris', 'color' => 'Hijau', 'image_url' => 'https://example.com/images/manset-hijau-m.jpg'],
            ['sku' => 'AKSR-MANST-UNGU', 'name' => 'Manset Ungu', 'type' => 'Aksesoris', 'color' => 'Ungu', 'image_url' => 'https://example.com/images/manset-ungu-m.jpg'],
            ['sku' => 'AKSR-MANST-HTM', 'name' => 'Manset Hitam', 'type' => 'Aksesoris', 'color' => 'Hitam', 'image_url' => 'https://example.com/images/manset-hitam-m.jpg'],
            ['sku' => 'AKSR-MANST-PTH', 'name' => 'Manset Putih', 'type' => 'Aksesoris', 'color' => 'Putih', 'image_url' => 'https://example.com/images/manset-putih-m.jpg'],
            ['sku' => 'AKSR-MANST-SLVR', 'name' => 'Manset Silver', 'type' => 'Aksesoris', 'color' => 'Silver', 'image_url' => 'https://example.com/images/manset-silver-m.jpg'],
            ['sku' => 'AKSR-MANST-PNK', 'name' => 'Manset Pink', 'type' => 'Aksesoris', 'color' => 'Pink', 'image_url' => 'https://example.com/images/manset-pink-m.jpg'],

            // Hijab
            ['sku' => 'AKSR-HJB-MRH', 'name' => 'Hijab Merah', 'type' => 'Aksesoris', 'color' => 'Merah', 'image_url' => 'https://example.com/images/hijab-merah-m.jpg'],
            ['sku' => 'AKSR-HJB-GLD', 'name' => 'Hijab Gold', 'type' => 'Aksesoris', 'color' => 'Gold', 'image_url' => 'https://example.com/images/hijab-gold-m.jpg'],
            ['sku' => 'AKSR-HJB-CKLT', 'name' => 'Hijab Coklat', 'type' => 'Aksesoris', 'color' => 'Coklat', 'image_url' => 'https://example.com/images/hijab-coklat-m.jpg'],
            ['sku' => 'AKSR-HJB-HJU', 'name' => 'Hijab Hijau', 'type' => 'Aksesoris', 'color' => 'Hijau', 'image_url' => 'https://example.com/images/hijab-hijau-m.jpg'],
            ['sku' => 'AKSR-HJB-UNGU', 'name' => 'Hijab Ungu', 'type' => 'Aksesoris', 'color' => 'Ungu', 'image_url' => 'https://example.com/images/hijab-ungu-m.jpg'],
            ['sku' => 'AKSR-HJB-HTM', 'name' => 'Hijab Hitam', 'type' => 'Aksesoris', 'color' => 'Hitam', 'image_url' => 'https://example.com/images/hijab-hitam-m.jpg'],
            ['sku' => 'AKSR-HJB-PTH', 'name' => 'Hijab Putih', 'type' => 'Aksesoris', 'color' => 'Putih', 'image_url' => 'https://example.com/images/hijab-putih-m.jpg'],
            ['sku' => 'AKSR-HJB-SLVR', 'name' => 'Hijab Silver', 'type' => 'Aksesoris', 'color' => 'Silver', 'image_url' => 'https://example.com/images/hijab-silver-m.jpg'],
            ['sku' => 'AKSR-HJB-PNK', 'name' => 'Hijab Pink', 'type' => 'Aksesoris', 'color' => 'Pink', 'image_url' => 'https://example.com/images/hijab-pink-m.jpg'],


            // Veil
            ['sku' => 'AKSR-VEIL-MRH', 'name' => 'Veil Merah', 'type' => 'Aksesoris', 'color' => 'Merah', 'image_url' => 'https://example.com/images/veil-merah-m.jpg'],
            ['sku' => 'AKSR-VEIL-GLD', 'name' => 'Veil Gold', 'type' => 'Aksesoris', 'color' => 'Gold', 'image_url' => 'https://example.com/images/veil-gold-m.jpg'],
            ['sku' => 'AKSR-VEIL-CKLT', 'name' => 'Veil Coklat', 'type' => 'Aksesoris', 'color' => 'Coklat', 'image_url' => 'https://example.com/images/veil-coklat-m.jpg'],
            ['sku' => 'AKSR-VEIL-HJU', 'name' => 'Veil Hijau', 'type' => 'Aksesoris', 'color' => 'Hijau', 'image_url' => 'https://example.com/images/veil-hijau-m.jpg'],
            ['sku' => 'AKSR-VEIL-UNGU', 'name' => 'Veil Ungu', 'type' => 'Aksesoris', 'color' => 'Ungu', 'image_url' => 'https://example.com/images/veil-ungu-m.jpg'],
            ['sku' => 'AKSR-VEIL-HTM', 'name' => 'Veil Hitam', 'type' => 'Aksesoris', 'color' => 'Hitam', 'image_url' => 'https://example.com/images/veil-hitam-m.jpg'],
            ['sku' => 'AKSR-VEIL-PTH', 'name' => 'Veil Putih', 'type' => 'Aksesoris', 'color' => 'Putih', 'image_url' => 'https://example.com/images/veil-putih-m.jpg'],
            ['sku' => 'AKSR-VEIL-SLVR', 'name' => 'Veil Silver', 'type' => 'Aksesoris', 'color' => 'Silver', 'image_url' => 'https://example.com/images/veil-silver-m.jpg'],
            ['sku' => 'AKSR-VEIL-PNK', 'name' => 'Veil Pink', 'type' => 'Aksesoris', 'color' => 'Pink', 'image_url' => 'https://example.com/images/veil-pink-m.jpg'],

            // Ekor
            ['sku' => 'AKSR-EKOR-MRH', 'name' => 'Ekor Merah', 'type' => 'Aksesoris', 'color' => 'Merah', 'image_url' => 'https://example.com/images/ekor-merah-m.jpg'],
            ['sku' => 'AKSR-EKOR-GLD', 'name' => 'Ekor Gold', 'type' => 'Aksesoris', 'color' => 'Gold', 'image_url' => 'https://example.com/images/ekor-gold-m.jpg'],
            ['sku' => 'AKSR-EKOR-CKLT', 'name' => 'Ekor Coklat', 'type' => 'Aksesoris', 'color' => 'Coklat', 'image_url' => 'https://example.com/images/ekor-coklat-m.jpg'],
            ['sku' => 'AKSR-EKOR-HJU', 'name' => 'Ekor Hijau', 'type' => 'Aksesoris', 'color' => 'Hijau', 'image_url' => 'https://example.com/images/ekor-hijau-m.jpg'],
            ['sku' => 'AKSR-EKOR-UNGU', 'name' => 'Ekor Ungu', 'type' => 'Aksesoris', 'color' => 'Ungu', 'image_url' => 'https://example.com/images/ekor-ungu-m.jpg'],
            ['sku' => 'AKSR-EKOR-HTM', 'name' => 'Ekor Hitam', 'type' => 'Aksesoris', 'color' => 'Hitam', 'image_url' => 'https://example.com/images/ekor-hitam-m.jpg'],
            ['sku' => 'AKSR-EKOR-PTH', 'name' => 'Ekor Putih', 'type' => 'Aksesoris', 'color' => 'Putih', 'image_url' => 'https://example.com/images/ekor-putih-m.jpg'],
            ['sku' => 'AKSR-EKOR-SLVR', 'name' => 'Ekor Silver', 'type' => 'Aksesoris', 'color' => 'Silver', 'image_url' => 'https://example.com/images/ekor-silver-m.jpg'],
            ['sku' => 'AKSR-EKOR-PNK', 'name' => 'Ekor Pink', 'type' => 'Aksesoris', 'color' => 'Pink', 'image_url' => 'https://example.com/images/ekor-pink-m.jpg'],

            // Obi
            ['sku' => 'AKSR-OBI-MRH', 'name' => 'Obi Merah', 'type' => 'Aksesoris', 'color' => 'Merah', 'image_url' => 'https://example.com/images/obi-merah-m.jpg'],
            ['sku' => 'AKSR-OBI-GLD', 'name' => 'Obi Gold', 'type' => 'Aksesoris', 'color' => 'Gold', 'image_url' => 'https://example.com/images/obi-gold-m.jpg'],
            ['sku' => 'AKSR-OBI-CKLT', 'name' => 'Obi Coklat', 'type' => 'Aksesoris', 'color' => 'Coklat', 'image_url' => 'https://example.com/images/obi-coklat-m.jpg'],
            ['sku' => 'AKSR-OBI-HJU', 'name' => 'Obi Hijau', 'type' => 'Aksesoris', 'color' => 'Hijau', 'image_url' => 'https://example.com/images/obi-hijau-m.jpg'],
            ['sku' => 'AKSR-OBI-UNGU', 'name' => 'Obi Ungu', 'type' => 'Aksesoris', 'color' => 'Ungu', 'image_url' => 'https://example.com/images/obi-ungu-m.jpg'],
            ['sku' => 'AKSR-OBI-HTM', 'name' => 'Obi Hitam', 'type' => 'Aksesoris', 'color' => 'Hitam', 'image_url' => 'https://example.com/images/obi-hitam-m.jpg'],
            ['sku' => 'AKSR-OBI-PTH', 'name' => 'Obi Putih', 'type' => 'Aksesoris', 'color' => 'Putih', 'image_url' => 'https://example.com/images/obi-putih-m.jpg'],
            ['sku' => 'AKSR-OBI-SLVR', 'name' => 'Obi Silver', 'type' => 'Aksesoris', 'color' => 'Silver', 'image_url' => 'https://example.com/images/obi-silver-m.jpg'],
            ['sku' => 'AKSR-OBI-PNK', 'name' => 'Obi Pink', 'type' => 'Aksesoris', 'color' => 'Pink', 'image_url' => 'https://example.com/images/obi-pink-m.jpg'],

            // Selendang
            ['sku' => 'AKSR-SLDNG-MRH', 'name' => 'Selendang Merah', 'type' => 'Aksesoris', 'color' => 'Merah', 'image_url' => 'https://example.com/images/selendang-merah-m.jpg'],
            ['sku' => 'AKSR-SLDNG-GLD', 'name' => 'Selendang Gold', 'type' => 'Aksesoris', 'color' => 'Gold', 'image_url' => 'https://example.com/images/selendang-gold-m.jpg'],
            ['sku' => 'AKSR-SLDNG-CKLT', 'name' => 'Selendang Coklat', 'type' => 'Aksesoris', 'color' => 'Coklat', 'image_url' => 'https://example.com/images/selendang-coklat-m.jpg'],
            ['sku' => 'AKSR-SLDNG-HJU', 'name' => 'Selendang Hijau', 'type' => 'Aksesoris', 'color' => 'Hijau', 'image_url' => 'https://example.com/images/selendang-hijau-m.jpg'],
            ['sku' => 'AKSR-SLDNG-UNGU', 'name' => 'Selendang Ungu', 'type' => 'Aksesoris', 'color' => 'Ungu', 'image_url' => 'https://example.com/images/selendang-ungu-m.jpg'],
            ['sku' => 'AKSR-SLDNG-HTM', 'name' => 'Selendang Hitam', 'type' => 'Aksesoris', 'color' => 'Hitam', 'image_url' => 'https://example.com/images/selendang-hitam-m.jpg'],
            ['sku' => 'AKSR-SLDNG-PTH', 'name' => 'Selendang Putih', 'type' => 'Aksesoris', 'color' => 'Putih', 'image_url' => 'https://example.com/images/selendang-putih-m.jpg'],
            ['sku' => 'AKSR-SLDNG-SLVR', 'name' => 'Selendang Silver', 'type' => 'Aksesoris', 'color' => 'Silver', 'image_url' => 'https://example.com/images/selendang-silver-m.jpg'],
            ['sku' => 'AKSR-SLDNG-PNK', 'name' => 'Selendang Pink', 'type' => 'Aksesoris', 'color' => 'Pink', 'image_url' => 'https://example.com/images/selendang-pink-m.jpg'],

        ];

        foreach ($items as $itemData) {
            Item::create($itemData);
        }

        // Create sample package blueprints
        $packageBlueprints = [
            [
                'name' => 'Berkat Silver',
                'default_price' => 10000000,
                'default_discount' => 0,
                'note' => '',
            ],
            [
                'name' => 'Berkat Elegant',
                'default_price' => 17500000,
                'default_discount' => 0,
                'note' => '',
            ],
            [
                'name' => 'Berkat Diamond',
                'default_price' => 25000000,
                'default_discount' => 0,
                'note' => '',
            ],
            [
                'name' => 'Berkat Solitaire',
                'default_price' => 37500000,
                'default_discount' => 0,
                'note' => '',
            ],
            [
                'name' => 'Parents Anggun',
                'default_price' => 15000000,
                'default_discount' => 0,
                'note' => '',
            ],
            [
                'name' => 'Parents Glamour',
                'default_price' => 27500000,
                'default_discount' => 0,
                'note' => '',
            ],
            [
                'name' => 'Graduation',
                'default_price' => 5000000,
                'default_discount' => 0,
                'note' => '',
            ],
            [
                'name' => 'Engagement',
                'default_price' => 10000000,
                'default_discount' => 0,
                'note' => '',
            ]
        ];

        foreach ($packageBlueprints as $packageData) {
            PackageBlueprint::create($packageData);
        }

        // Create sample set blueprints for each package
        $setBlueprints = [
            // For Berkat Silver (ID: 1)
            ['package_blueprint_id' => 1, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 1, 'name' => "Woman's Set", 'sort_order' => 1],
            // For Berkat Elegant (ID: 2)
            ['package_blueprint_id' => 2, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 2, 'name' => "Woman's Set", 'sort_order' => 1],
            // For Berkat Diamond (ID: 3)
            ['package_blueprint_id' => 3, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 3, 'name' => "Woman's Set", 'sort_order' => 1],
            // For Berkat Solitaire (ID: 4)
            ['package_blueprint_id' => 4, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 4, 'name' => "Woman's Set", 'sort_order' => 1],
            // For Parents Anggun (ID: 5)
            ['package_blueprint_id' => 5, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 5, 'name' => "Woman's Set", 'sort_order' => 1],
            // For Parents Glamour (ID: 6)
            ['package_blueprint_id' => 6, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 6, 'name' => "Woman's Set", 'sort_order' => 1],
            // For Graduation (ID: 7)
            // ['package_blueprint_id' => 7, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 7, 'name' => "Woman's Set", 'sort_order' => 0],
            // For Engagement (ID: 8)
            ['package_blueprint_id' => 8, 'name' => "Man's Set", 'sort_order' => 0],
            ['package_blueprint_id' => 8, 'name' => "Woman's Set", 'sort_order' => 1],
        ];
        foreach ($setBlueprints as $setData) {
            SetBlueprint::create($setData);
        }

        // Create sample item blueprints for each set
        $itemBlueprints = [
            // Berkat Silver - Man's set (ID: 1)
            ['set_blueprint_id' => 1, 'sort_order' => 0, 'item_types' => ['Beskap']],
            ['set_blueprint_id' => 1, 'sort_order' => 1, 'item_types' => ['Jas']],
            ['set_blueprint_id' => 1, 'sort_order' => 2, 'item_types' => ['Celana']],
            ['set_blueprint_id' => 1, 'sort_order' => 3, 'item_types' => ['Kain']],
            ['set_blueprint_id' => 1, 'sort_order' => 4, 'item_types' => ['Headwear']],
            // Berkat Silver - Woman's Set (Set ID: 2)
            ['set_blueprint_id' => 2, 'sort_order' => 0, 'item_types' => ['Kebaya']],
            ['set_blueprint_id' => 2, 'sort_order' => 1, 'item_types' => ['Gaun']],
            ['set_blueprint_id' => 2, 'sort_order' => 2, 'item_types' => ['Bustier']],
            ['set_blueprint_id' => 2, 'sort_order' => 3, 'item_types' => ['Rok']],
            ['set_blueprint_id' => 2, 'sort_order' => 4, 'item_types' => ['Manset']],
            // Berkat Elegant - Mens Set (Set ID: 3)
            ['set_blueprint_id' => 3, 'sort_order' => 0, 'item_types' => ['Beskap']],
            ['set_blueprint_id' => 3, 'sort_order' => 1, 'item_types' => ['Jas']],
            ['set_blueprint_id' => 3, 'sort_order' => 2, 'item_types' => ['Celana']],
            ['set_blueprint_id' => 3, 'sort_order' => 3, 'item_types' => ['Kain']],
            ['set_blueprint_id' => 3, 'sort_order' => 4, 'item_types' => ['Headwear']],
            ['set_blueprint_id' => 3, 'sort_order' => 5, 'item_types' => ['Selop Pria']],
            // Berkat Elegant - Woman's Set (Set ID: 4)
            ['set_blueprint_id' => 4, 'sort_order' => 0, 'item_types' => ['Kebaya']],
            ['set_blueprint_id' => 4, 'sort_order' => 1, 'item_types' => ['Gaun']],
            ['set_blueprint_id' => 4, 'sort_order' => 2, 'item_types' => ['Bustier']],
            ['set_blueprint_id' => 4, 'sort_order' => 3, 'item_types' => ['Rok']],
            ['set_blueprint_id' => 4, 'sort_order' => 4, 'item_types' => ['Manset']],
            ['set_blueprint_id' => 4, 'sort_order' => 5, 'item_types' => ['Selop Wanita']],
            // Berkat Diamond - Man's Set (Set ID: 5)
            ['set_blueprint_id' => 5, 'sort_order' => 0, 'item_types' => ['Beskap']],
            ['set_blueprint_id' => 5, 'sort_order' => 1, 'item_types' => ['Jas']],
            ['set_blueprint_id' => 5, 'sort_order' => 2, 'item_types' => ['Celana']],
            ['set_blueprint_id' => 5, 'sort_order' => 3, 'item_types' => ['Kain']],
            ['set_blueprint_id' => 5, 'sort_order' => 4, 'item_types' => ['Headwear']],
            ['set_blueprint_id' => 5, 'sort_order' => 5, 'item_types' => ['Selop Pria']],
            ['set_blueprint_id' => 5, 'sort_order' => 6, 'item_types' => ['Aksesoris']],
            // Berkat Diamond - Woman's Set (Set ID: 6)
            ['set_blueprint_id' => 6, 'sort_order' => 0, 'item_types' => ['Kebaya']],
            ['set_blueprint_id' => 6, 'sort_order' => 1, 'item_types' => ['Gaun']],
            ['set_blueprint_id' => 6, 'sort_order' => 2, 'item_types' => ['Bustier']],
            ['set_blueprint_id' => 6, 'sort_order' => 3, 'item_types' => ['Rok']],
            ['set_blueprint_id' => 6, 'sort_order' => 4, 'item_types' => ['Manset']],
            ['set_blueprint_id' => 6, 'sort_order' => 5, 'item_types' => ['Selop Wanita']],
            ['set_blueprint_id' => 6, 'sort_order' => 6, 'item_types' => ['Tail']],
            ['set_blueprint_id' => 6, 'sort_order' => 7, 'item_types' => ['Aksesoris']],
            // Berkat Solitaire - Man's Set (Set ID: 7)
            ['set_blueprint_id' => 7, 'sort_order' => 0, 'item_types' => ['Beskap']],
            ['set_blueprint_id' => 7, 'sort_order' => 1, 'item_types' => ['Jas']],
            ['set_blueprint_id' => 7, 'sort_order' => 2, 'item_types' => ['Celana']],
            ['set_blueprint_id' => 7, 'sort_order' => 3, 'item_types' => ['Kain']],
            ['set_blueprint_id' => 7, 'sort_order' => 4, 'item_types' => ['Headwear']],
            ['set_blueprint_id' => 7, 'sort_order' => 5, 'item_types' => ['Aksesoris']],
            ['set_blueprint_id' => 7, 'sort_order' => 6, 'item_types' => ['Selop Pria']],
            // Berkat Solitaire - Woman's Set (Set ID: 8)
            ['set_blueprint_id' => 8, 'sort_order' => 0, 'item_types' => ['Kebaya']],
            ['set_blueprint_id' => 8, 'sort_order' => 1, 'item_types' => ['Gaun']],
            ['set_blueprint_id' => 8, 'sort_order' => 2, 'item_types' => ['Bustier']],
            ['set_blueprint_id' => 8, 'sort_order' => 3, 'item_types' => ['Rok']],
            ['set_blueprint_id' => 8, 'sort_order' => 4, 'item_types' => ['Manset']],
            ['set_blueprint_id' => 8, 'sort_order' => 5, 'item_types' => ['Selop Wanita']],
            ['set_blueprint_id' => 8, 'sort_order' => 6, 'item_types' => ['Tail']],
            ['set_blueprint_id' => 8, 'sort_order' => 7, 'item_types' => ['Aksesoris']],
            ['set_blueprint_id' => 8, 'sort_order' => 8, 'item_types' => [], 'is_custom' => true],
            //Parents Anggun Mens Set (Set ID: 9)
            ['set_blueprint_id' => 9, 'sort_order' => 0, 'item_types' => ['Beskap']],
            ['set_blueprint_id' => 9, 'sort_order' => 1, 'item_types' => ['Beskap']],
            ['set_blueprint_id' => 9, 'sort_order' => 2, 'item_types' => ['Jas']],
            ['set_blueprint_id' => 9, 'sort_order' => 3, 'item_types' => ['Jas']],
            ['set_blueprint_id' => 9, 'sort_order' => 4, 'item_types' => ['Celana']],
            ['set_blueprint_id' => 9, 'sort_order' => 5, 'item_types' => ['Celana']],
            ['set_blueprint_id' => 9, 'sort_order' => 6, 'item_types' => ['Kain']],
            ['set_blueprint_id' => 9, 'sort_order' => 7, 'item_types' => ['Kain']],
            ['set_blueprint_id' => 9, 'sort_order' => 8, 'item_types' => ['Headwear']],
            ['set_blueprint_id' => 9, 'sort_order' => 9, 'item_types' => ['Headwear']],
            ['set_blueprint_id' => 9, 'sort_order' => 10, 'item_types' => ['Selop Pria']],
            ['set_blueprint_id' => 9, 'sort_order' => 11, 'item_types' => ['Selop Pria']],
            //Parents Anggun Womens Set (Set ID: 10)
            ['set_blueprint_id' => 10, 'sort_order' => 0, 'item_types' => ['Kebaya']],
            ['set_blueprint_id' => 10, 'sort_order' => 1, 'item_types' => ['Kebaya']],
            ['set_blueprint_id' => 10, 'sort_order' => 2, 'item_types' => ['Gaun']],
            ['set_blueprint_id' => 10, 'sort_order' => 3, 'item_types' => ['Gaun']],
            ['set_blueprint_id' => 10, 'sort_order' => 4, 'item_types' => ['Bustier']],
            ['set_blueprint_id' => 10, 'sort_order' => 5, 'item_types' => ['Bustier']],
            ['set_blueprint_id' => 10, 'sort_order' => 6, 'item_types' => ['Rok']],
            ['set_blueprint_id' => 10, 'sort_order' => 7, 'item_types' => ['Rok']],
            ['set_blueprint_id' => 10, 'sort_order' => 8, 'item_types' => ['Manset']],
            ['set_blueprint_id' => 10, 'sort_order' => 9, 'item_types' => ['Manset']],
            ['set_blueprint_id' => 10, 'sort_order' => 10, 'item_types' => ['Selop Wanita']],
            ['set_blueprint_id' => 10, 'sort_order' => 11, 'item_types' => ['Selop Wanita']],
            //Parents Glamour Mens Set (Set ID: 11)
            ['set_blueprint_id' => 11, 'sort_order' => 0, 'item_types' => ['Beskap']],
            ['set_blueprint_id' => 11, 'sort_order' => 1, 'item_types' => ['Beskap']],
            ['set_blueprint_id' => 11, 'sort_order' => 2, 'item_types' => ['Jas']],
            ['set_blueprint_id' => 11, 'sort_order' => 3, 'item_types' => ['Jas']],
            ['set_blueprint_id' => 11, 'sort_order' => 4, 'item_types' => ['Celana']],
            ['set_blueprint_id' => 11, 'sort_order' => 5, 'item_types' => ['Celana']],
            ['set_blueprint_id' => 11, 'sort_order' => 6, 'item_types' => ['Kain']],
            ['set_blueprint_id' => 11, 'sort_order' => 7, 'item_types' => ['Kain']],
            ['set_blueprint_id' => 11, 'sort_order' => 8, 'item_types' => ['Headwear']],
            ['set_blueprint_id' => 11, 'sort_order' => 9, 'item_types' => ['Headwear']],
            ['set_blueprint_id' => 11, 'sort_order' => 10, 'item_types' => ['Selop Pria']],
            ['set_blueprint_id' => 11, 'sort_order' => 11, 'item_types' => ['Selop Pria']],
            //Parents Glamour Womens Set (Set ID: 12)

            ['set_blueprint_id' => 12, 'sort_order' => 0, 'item_types' => ['Kebaya']],
            ['set_blueprint_id' => 12, 'sort_order' => 1, 'item_types' => ['Kebaya']],
            ['set_blueprint_id' => 12, 'sort_order' => 2, 'item_types' => ['Gaun']],
            ['set_blueprint_id' => 12, 'sort_order' => 3, 'item_types' => ['Gaun']],
            ['set_blueprint_id' => 12, 'sort_order' => 4, 'item_types' => ['Bustier']],
            ['set_blueprint_id' => 12, 'sort_order' => 5, 'item_types' => ['Bustier']],
            ['set_blueprint_id' => 12, 'sort_order' => 6, 'item_types' => ['Rok']],
            ['set_blueprint_id' => 12, 'sort_order' => 7, 'item_types' => ['Rok']],
            ['set_blueprint_id' => 12, 'sort_order' => 8, 'item_types' => ['Manset']],
            ['set_blueprint_id' => 12, 'sort_order' => 9, 'item_types' => ['Manset']],
            ['set_blueprint_id' => 12, 'sort_order' => 10, 'item_types' => ['Selop Wanita']],
            ['set_blueprint_id' => 12, 'sort_order' => 11, 'item_types' => ['Selop Wanita']],

            // Graduation - Woman's Set (Set ID: 13)
            ['set_blueprint_id' => 13, 'sort_order' => 0, 'item_types' => ['Kebaya']],
            ['set_blueprint_id' => 13, 'sort_order' => 1, 'item_types' => ['Bustier']],
            ['set_blueprint_id' => 13, 'sort_order' => 2, 'item_types' => ['Rok']],
            ['set_blueprint_id' => 13, 'sort_order' => 3, 'item_types' => ['Selop Wanita']],
            ['set_blueprint_id' => 13, 'sort_order' => 4, 'item_types' => ['Manset']],
            // Engagement - Man's Set (Set ID: 14)
            ['set_blueprint_id' => 14, 'sort_order' => 0, 'item_types' => ['Kemeja']],
            // Engagement - Woman's Set (Set ID: 15)
            ['set_blueprint_id' => 15, 'sort_order' => 0, 'item_types' => ['Kebaya']],
            ['set_blueprint_id' => 15, 'sort_order' => 1, 'item_types' => ['Gaun']],
            ['set_blueprint_id' => 15, 'sort_order' => 2, 'item_types' => ['Bustier']],
            ['set_blueprint_id' => 15, 'sort_order' => 3, 'item_types' => ['Rok']],
            ['set_blueprint_id' => 15, 'sort_order' => 4, 'item_types' => ['Selop Wanita']],
            ['set_blueprint_id' => 15, 'sort_order' => 5, 'item_types' => ['Accesories']],
            ['set_blueprint_id' => 15, 'sort_order' => 6, 'item_types' => ['Manset']],
        ];

        foreach ($itemBlueprints as $itemData) {
            $itemTypeNames = $itemData['item_types'];
            unset($itemData['item_types']);
            
            // Create the item blueprint
            $itemBlueprint = ItemBlueprint::create($itemData);
            
            // Attach item types to the blueprint
            foreach ($itemTypeNames as $index => $itemTypeName) {
                $itemType = ItemType::where('name', $itemTypeName)->first();
                if ($itemType) {
                    $itemBlueprint->itemTypes()->attach($itemType->id, [
                        'sort_order' => $index,
                    ]);
                }
            }
        }
    }
}
