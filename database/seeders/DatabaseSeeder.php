<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CategorySeeder::class,      // Independent
            CustomerSeeder::class,      // Independent 
            ShippingBranchSeeder::class, // Independent
            CouponSeeder::class,        // Independent
            ProductSeeder::class,       // Depends on categories
            OrderSeeder::class,         // Depends on customers, shipping_branches, coupons, product_variations
        ]);
    }
}