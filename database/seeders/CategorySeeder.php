<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Electronics',
            'Clothing',
            'Home & Garden',
            'Books',
            'Toys',
            'Sports',
            'Beauty',
            'Automotive',
            'Health',
            'Jewelry',
            'Furniture',
            'Office Supplies'
        ];

        foreach ($categories as $categoryName) {
            DB::table('categories')->insert([
                'name' => $categoryName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}