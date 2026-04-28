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
        // حذف الحقل
        $table->dropColumn('is_manual_available');
    });
}

public function down(): void
{
    Schema::table('product_variations', function (Blueprint $table) {
        // في حال أردت التراجع عن الحذف (Rollback)
        $table->boolean('is_manual_available')->default(true);
    });
}
};
