<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->unique();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('dosage_form');
            $table->string('strength');
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(10);
            $table->decimal('purchase_price', 12, 2);
            $table->decimal('selling_price', 12, 2);
            $table->date('expiry_date');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('requires_prescription')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};