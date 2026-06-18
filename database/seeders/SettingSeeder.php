<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('shop_name', 'Apotek Super');
        Setting::set('shop_address', 'Jl. Laragon No. 1, Kota Laragon');
        Setting::set('shop_phone', '0812-3456-7890');
        Setting::set('shop_email', 'info@apoteksuper.com');
    }
}
