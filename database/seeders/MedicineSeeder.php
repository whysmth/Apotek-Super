<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $catAntibiotik = Category::where('name', 'Antibiotik')->first()->id ?? 1;
        $catVitamin = Category::where('name', 'Vitamin')->first()->id ?? 2;
        $catHerbal = Category::where('name', 'Herbal')->first()->id ?? 3;

        $supJaya = Supplier::where('name', 'PT Farmasi Jaya')->first()->id ?? 1;
        $supSehat = Supplier::where('name', 'CV Sehat Abadi')->first()->id ?? 2;

        $medicines = [
            [
                'name' => 'Paracetamol 500mg',
                'barcode' => '8990001112221',
                'category_id' => $catVitamin,
                'supplier_id' => $supJaya,
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'stock' => 120,
                'min_stock' => 15,
                'purchase_price' => 800.00,
                'selling_price' => 1500.00,
                'expiry_date' => '2027-12-31',
                'description' => 'Obat pereda demam dan sakit kepala ringan.'
            ],
            [
                'name' => 'Amoxicillin 500mg',
                'barcode' => '8990001112222',
                'category_id' => $catAntibiotik,
                'supplier_id' => $supJaya,
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'stock' => 45,
                'min_stock' => 10,
                'purchase_price' => 1200.00,
                'selling_price' => 2500.00,
                'expiry_date' => '2027-08-15',
                'description' => 'Antibiotik untuk mengobati infeksi bakteri.'
            ],
            [
                'name' => 'Sangobion Kapsul',
                'barcode' => '8990001112223',
                'category_id' => $catVitamin,
                'supplier_id' => $supJaya,
                'dosage_form' => 'Kapsul',
                'strength' => 'Capsule',
                'stock' => 75,
                'min_stock' => 10,
                'purchase_price' => 3800.00,
                'selling_price' => 5500.00,
                'expiry_date' => '2027-10-20',
                'description' => 'Suplemen penambah darah untuk mencegah anemia.'
            ],
            [
                'name' => 'Tolak Angin Cair',
                'barcode' => '8990001112224',
                'category_id' => $catHerbal,
                'supplier_id' => $supSehat,
                'dosage_form' => 'Sirup',
                'strength' => '15ml',
                'stock' => 200,
                'min_stock' => 20,
                'purchase_price' => 2800.00,
                'selling_price' => 4000.00,
                'expiry_date' => '2028-03-10',
                'description' => 'Obat herbal untuk meredakan masuk angin.'
            ],
            [
                'name' => 'Vitamin C 1000mg',
                'barcode' => '8990001112225',
                'category_id' => $catVitamin,
                'supplier_id' => $supSehat,
                'dosage_form' => 'Tablet',
                'strength' => '1000mg',
                'stock' => 150,
                'min_stock' => 15,
                'purchase_price' => 1800.00,
                'selling_price' => 3000.00,
                'expiry_date' => '2028-05-01',
                'description' => 'Suplemen untuk menjaga daya tahan tubuh.'
            ],
            [
                'name' => 'Betadine Antiseptic',
                'barcode' => '8990001112226',
                'category_id' => $catHerbal,
                'supplier_id' => $supSehat,
                'dosage_form' => 'Salep',
                'strength' => '15ml',
                'stock' => 30,
                'min_stock' => 5,
                'purchase_price' => 7500.00,
                'selling_price' => 11000.00,
                'expiry_date' => '2028-11-20',
                'description' => 'Cairan antiseptic pembersih luka luar.'
            ],
            [
                'name' => 'Decolgen Tablet',
                'barcode' => '8990001112227',
                'category_id' => $catVitamin,
                'supplier_id' => $supJaya,
                'dosage_form' => 'Tablet',
                'strength' => '4 Tablet',
                'stock' => 8, // Stock < min_stock (10) for testing low-stock alerts
                'min_stock' => 10,
                'purchase_price' => 1000.00,
                'selling_price' => 2000.00,
                'expiry_date' => '2027-05-15',
                'description' => 'Obat flu, batuk, dan sakit kepala.'
            ]
        ];

        foreach ($medicines as $medicineData) {
            Medicine::updateOrCreate(
                ['barcode' => $medicineData['barcode']],
                $medicineData
            );
        }
    }
}
