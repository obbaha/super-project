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
        // إضافة عمود يربطنا بجدول ميديا الخاص بـ Curator
        $table->foreignId('featured_image_id')
            ->nullable() // نجعله اختياري لكي لا تتعطل البيانات القديمة
            ->constrained('media') // يربطه بجدول media
            ->nullOnDelete(); // إذا حذفت الصورة، يترك الحقل فارغاً ولا يحذف المنتج
    });
}

public function down(): void
{
    Schema::table('product_variations', function (Blueprint $table) {
        $table->dropForeign(['featured_image_id']);
        $table->dropColumn('featured_image_id');
    });
}
};
