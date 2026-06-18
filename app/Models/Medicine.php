<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'barcode', 'name', 'category_id', 'supplier_id',
        'dosage_form', 'strength', 'stock', 'min_stock',
        'purchase_price', 'selling_price', 'expiry_date',
        'image', 'description', 'requires_prescription'
    ];
    
    protected $casts = [
        'expiry_date' => 'date'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}