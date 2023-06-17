<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->noActionOnDelete();
            $table->text('address');
            $table->json('items');
            $table->enum('status', ['pending', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->double('subtotal')->default(0);
            $table->double('shipping_fee')->default(0);
            $table->enum('payment_method', ['cod', 'bank'])->default('cod');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
