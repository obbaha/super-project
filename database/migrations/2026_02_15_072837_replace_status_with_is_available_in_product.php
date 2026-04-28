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
        Schema::table('products', function (Blueprint $table) {
            // حذف حقل status القديم
            if (Schema::hasColumn('products', 'status')) {
                $table->dropColumn('status');
            }

            // إضافة الحقل الجديد الموحد
            $table->boolean('is_available')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_available');
            $table->boolean('status')->default(true)->after('additional_price');
        });
    }
};