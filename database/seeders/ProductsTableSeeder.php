<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert
        ([
            [
                'name' => 'Producto A',
                'price' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Producto B',
                'price' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Producto C',
                'price' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
