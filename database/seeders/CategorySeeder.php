<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Antibiotik', 'description' => 'Obat antibiotik', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vitamin', 'description' => 'Suplemen vitamin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Herbal', 'description' => 'Obat herbal', 'created_at' => now(), 'updated_at' => now()],
        ];
        
        DB::table('categories')->insert($categories);
    }
}