<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'category_name' => 'Food',
                'product_name' => 'Pizza',
                'qty' => 10,
                'rate' => 299.00,
                'gst' => '5'
            ],
            [
                'category_name' => 'Food',
                'product_name' => 'Burger',
                'qty' => 10,
                'rate' => 399.00,
                'gst' => '5'
            ],
            [
                'category_name' => 'Electronics',
                'product_name' => 'Laptop',
                'qty' => 5,
                'rate' => 54999.00,
                'gst' => '18'
            ],
            [
                'category_name' => 'Clothing',
                'product_name' => 'T-Shirt',
                'qty' => 50,
                'rate' => 999.00,
                'gst' => '12'
            ],
            [
                'category_name' => 'Electronics',
                'product_name' => 'Keyboard',
                'qty' => 20,
                'rate' => 999.00,
                'gst' => '18'
            ],
            [
                'category_name' => 'Clothing',
                'product_name' => 'Sleeveless T-Shirt',
                'qty' => 100,
                'rate' => 599.00,
                'gst' => '12'
            ]
        ];

        foreach ($products as $productData) {

            $category = Category::firstOrCreate(['category_name' => $productData['category_name']]);

            Product::updateOrCreate(
                ['product_name' => $productData['product_name']],
                [
                    'category_id' => $category->id,
                    'qty' => $productData['qty'],
                    'rate' => $productData['rate'],
                    'gst' => $productData['gst']
                ]
            );
        }
    }
}