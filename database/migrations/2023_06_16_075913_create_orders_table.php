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
            $table->string('status', 20)->default('pending');
            $table->double('subtotal')->default(0);
            $table->double('shipping_fee')->default(0);
            $table->string('payment_method', 30)->default('cod');
            $table->timestamps();
            $table->softDeletes();
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
