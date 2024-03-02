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
        Product::create([
            'vendor_id' => 1, // Replace with an existing vendor_id
            'product_name' => 'Sample Product 1',
            'price' => 19.99,
            'discount' => 5.00,
            'stock' => 50,
            'is_on_sale' => true,
        ]);
    }
}
