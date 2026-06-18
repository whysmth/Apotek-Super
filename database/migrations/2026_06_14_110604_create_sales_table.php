<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();
            $table->decimal('total_amount', 12, 2);
            $table->string('payment_method')->default('cash');
            $table->decimal('payment_amount', 12, 2);
            $table->decimal('change_amount', 12, 2)->default(0);
            $table->string('status')->default('completed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};