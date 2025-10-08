<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brandsRecords = [
            [
                'id' => 1,
                'brand_name' => 'Arrow',
                'brand_image' => '',
                'brand_logo' => '',
                'brand_discount' => 0,
                'description' => 'About Arrow',
                'url' => 'arrow',
                'meta_title' => 'Arrow Title',
                'meta_description' => 'Arrow Description',
                'meta_keywords' => 'Arrow Keywords',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'brand_name' => 'Gap',
                'brand_image' => '',
                'brand_logo' => '',
                'brand_discount' => 0,
                'description' => 'About Gap',
                'url' => 'gap',
                'meta_title' => 'Gap Title',
                'meta_description' => 'Gap Description',
                'meta_keywords' => 'Gap Keywords',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'brand_name' => 'Nike',
                'brand_image' => '',
                'brand_logo' => '',
                'brand_discount' => 0,
                'description' => 'About Nike',
                'url' => 'nike',
                'meta_title' => 'Nike Title',
                'meta_description' => 'Nike Description',
                'meta_keywords' => 'Nike Keywords',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'brand_name' => 'Puma',
                'brand_image' => '',
                'brand_logo' => '',
                'brand_discount' => 0,
                'description' => 'About Puma',
                'url' => 'puma',
                'meta_title' => 'Puma Title',
                'meta_description' => 'Puma Description',
                'meta_keywords' => 'Puma Keywords',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'brand_name' => 'Adidas',
                'brand_image' => '',
                'brand_logo' => '',
                'brand_discount' => 0,
                'description' => 'About Adidas',
                'url' => 'adidas',
                'meta_title' => 'Adidas Title',
                'meta_description' => 'Adidas Description',
                'meta_keywords' => 'Adidas Keywords',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'brand_name' => 'Monte Carlo',
                'brand_image' => '',
                'brand_logo' => '',
                'brand_discount' => 0,
                'description' => 'About Monte Carlo',
                'url' => 'monte-carlo',
                'meta_title' => 'Monte Carlo Title',
                'meta_description' => 'Monte Carlo Description',
                'meta_keywords' => 'Monte Carlo Keywords',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Brand::insert($brandsRecords);
    }
}
