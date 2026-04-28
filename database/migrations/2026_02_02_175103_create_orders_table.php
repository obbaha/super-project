<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('customer_id')->constrained()->onDelete('cascade');

        // الربط مع الجداول الجديدة بدلاً من الحقل النصي
        $table->foreignId('governorate_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('district_id')->nullable()->constrained()->nullOnDelete();
        
        $table->text('detailed_address');
        $table->foreignId('shipping_branch_id')->nullable()->constrained()->restrictOnDelete();
        $table->decimal('shipping_cost', 10, 2);
        $table->decimal('total_price', 10, 2);
        $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
        $table->decimal('discount_amount', 10, 2)->default(0.00);
        $table->string('status')->default('pending');
        $table->boolean('inventory_updated')->default(false);
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};