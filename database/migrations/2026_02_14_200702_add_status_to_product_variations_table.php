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
    Schema::table('product_variations', function (Blueprint $table) {
        // إضافة حقل الحالة، افتراضياً يكون متاحاً
        $table->boolean('is_available')->default(true)->after('additional_price');
    });
}

public function down(): void
{
    Schema::table('product_variations', function (Blueprint $table) {
        $table->dropColumn('is_available');
    });
}
};
