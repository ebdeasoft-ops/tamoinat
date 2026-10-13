<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // First ensure there is a user and a branch
        $user_id = DB::table('users')->first()->id ?? 1;
        $branch_id = DB::table('branchs')->first()->id ?? 1;

        $products = [
            [
                'product_name' => 'لابتوب ديل',
                'name_en' => 'Dell Laptop',
                'purchasing_price' => 1500.00,
                'sale_price' => 1800.00,
                'numberofpice' => 50,
                'Status' => 'active',
                'user_id' => $user_id,
                'branchs_id' => $branch_id,
                'Product_Location' => 'A1',
                'Product_Code' => 'PRD-001',
                'Added_Value' => 15,
                'unit' => 'piece',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'شاشة سامسونج',
                'name_en' => 'Samsung Monitor',
                'purchasing_price' => 500.00,
                'sale_price' => 650.00,
                'numberofpice' => 100,
                'Status' => 'active',
                'user_id' => $user_id,
                'branchs_id' => $branch_id,
                'Product_Location' => 'A2',
                'Product_Code' => 'PRD-002',
                'Added_Value' => 15,
                'unit' => 'piece',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('products')->insert($products);
    }
}
