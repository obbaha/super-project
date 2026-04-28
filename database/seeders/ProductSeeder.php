<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();
        $categoryIds = DB::table('categories')->pluck('id')->toArray();
        
        // Sample products
        $products = [
            ['Electronics', 'Laptop', 'High-performance laptop for work and gaming', 1200.00],
            ['Electronics', 'Smartphone', 'Latest model smartphone with advanced features', 800.00],
            ['Clothing', 'T-Shirt', 'Comfortable cotton t-shirt', 25.00],
            ['Clothing', 'Jeans', 'Denim jeans for casual wear', 60.00],
            ['Home & Garden', 'Blender', 'Powerful kitchen blender', 80.00],
            ['Books', 'Novel', 'Best-selling fiction novel', 15.00],
            ['Toys', 'Action Figure', 'Collectible action figure', 30.00],
            ['Sports', 'Yoga Mat', 'Non-slip yoga mat', 25.00],
            ['Beauty', 'Skincare Set', 'Complete skincare routine', 75.00],
            ['Automotive', 'Car Wax', 'Premium car wax', 18.00],
            ['Health', 'Vitamins', 'Daily multivitamin supplement', 20.00],
            ['Jewelry', 'Necklace', 'Elegant gold necklace', 150.00]
        ];

        // Insert base products
        foreach ($products as $product) {
            $categoryId = $faker->randomElement($categoryIds);
            
            $productId = DB::table('products')->insertGetId([
                'category_id' => $categoryId,
                'sku' => 'PROD-' . Str::upper(Str::random(6)),
                'name' => $product[1],
                'description' => $product[2],
                'price' => $product[3],
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create at least 2 variations for each product
            $attributes = ['Color', 'Size', 'Material', 'Style', 'Model'];
            $values = ['Red', 'Blue', 'Large', 'Small', 'Medium', 'XL', 'XXL', 'Black', 'White', 'Green', 'Cotton', 'Leather', 'Plastic', 'Metal', 'Basic', 'Premium', 'Pro', 'Max', 'Lite'];
            
            for ($j = 0; $j < 2; $j++) {
                $attributeName = $faker->randomElement($attributes) . ': ' . $faker->randomElement($values);
                
                DB::table('product_variations')->insert([
                    'product_id' => $productId,
                    'full_sku' => 'VAR-' . Str::upper(Str::random(8)),
                    'attribute_name' => $attributeName,
                    'additional_price' => $faker->randomFloat(2, 0, 50),
                    'stock_quantity' => $faker->numberBetween(10, 100),
                    'reserved_quantity' => $faker->numberBetween(0, 5),
                    'is_manual_available' => $faker->boolean(70),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}