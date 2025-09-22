<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productsRecords = [
            [
                'id' => 1,
                'category_id' => 10,
                'brand_id' => 0,
                'product_name' => 'Blue T-Shirt',
                'product_code' => 'BT001',
                'product_color' => 'Dark Blue',
                'family_color' => 'Blue',
                'group_code' => 'TSHIRT0000',
                'product_price' => 1500,
                'product_discount' => 10,
                'discount_type' => 'product',
                'final_price' => 1350,
                'description' => 'Test Product',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'category_id' => 10,
                'brand_id' => 0,
                'product_name' => 'Red T-Shirt',
                'product_code' => 'RT001',
                'product_color' => 'Red',
                'family_color' => 'Red',
                'group_code' => 'TSHIRT0000',
                'product_price' => 1000,
                'product_discount' => 0,
                'discount_type' => 'product',
                'final_price' => 1000,
                'description' => 'Test Product',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Product::insert($productsRecords);
    }
}
