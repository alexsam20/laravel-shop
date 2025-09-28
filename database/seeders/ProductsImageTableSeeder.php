<?php

namespace Database\Seeders;

use App\Models\ProductsImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsImageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productImagesRecords = [
            ['id' => 1, 'product_id' => 1, 'image' => '1.jpg', 'image_sort' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'product_id' => 1, 'image' => '2.jpg', 'image_sort' => 2, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'product_id' => 1, 'image' => '3.jpg', 'image_sort' => 3, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'product_id' => 2, 'image' => '4.jpg', 'image_sort' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'product_id' => 2, 'image' => '5.jpg', 'image_sort' => 2, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'product_id' => 2, 'image' => '6.jpg', 'image_sort' => 3, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        ProductsImage::insert($productImagesRecords);
    }
}
