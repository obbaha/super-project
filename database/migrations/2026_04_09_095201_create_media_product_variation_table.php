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
    Schema::create('media_product_variation', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_variation_id')->constrained()->cascadeOnDelete();
        $table->foreignId('media_id')->constrained('media')->cascadeOnDelete(); // تأكد أن اسم جدول الميديا هو media
        $table->integer('order')->default(0); // مهم جداً لحفظ ترتيب الصور في Curator
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_product_variation');
    }
};
