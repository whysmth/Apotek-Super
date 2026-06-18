<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['name' => 'PT Farmasi Jaya', 'phone' => '08123456789', 'email' => 'info@farmasijaya.com', 'address' => 'Jakarta', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'CV Sehat Abadi', 'phone' => '08198765432', 'email' => 'cs@sehatabadi.com', 'address' => 'Bandung', 'created_at' => now(), 'updated_at' => now()],
        ];
        
        DB::table('suppliers')->insert($suppliers);
    }
}