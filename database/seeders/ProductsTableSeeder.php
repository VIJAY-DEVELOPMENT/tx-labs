<?php

namespace Database\Seeders;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'sku'=>'PRO001',
                'product_name'=>'James HTC',
                'slug' => "",
                'price' => "122.50",
                'product_image' => 'uploads/2024/01/james-hce_1.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'sku'=>'PRO002',
                'product_name'=>'James TC',
                'slug' => "",
                'price' => "176.86",
                'product_image' => 'uploads/2024/01/james-tc.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        Product::insert($data);
    }
}
