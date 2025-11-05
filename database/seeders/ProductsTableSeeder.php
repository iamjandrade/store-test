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
                'price' => 1999,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Producto B',
                'price' => 2950,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Producto C',
                'price' => 999,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
