<?php

namespace Database\Seeders;

use App\Models\ProductsAttributes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsAttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productsAttributesRecords = [
            ['id' => 1, 'product_id' => '1', 'size' => 'Small', 'sku' => 'BT001S', 'price' => '1000', 'stock' => '100', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'product_id' => '1', 'size' => 'Medium', 'sku' => 'BT001M', 'price' => '1100', 'stock' => '70', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'product_id' => '1', 'size' => 'Large', 'sku' => 'BT001L', 'price' => '1200', 'stock' => '25', 'created_at' => now(), 'updated_at' => now()],
        ];

        ProductsAttributes::insert($productsAttributesRecords);
    }
}
