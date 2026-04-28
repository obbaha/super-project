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
    Schema::create('sliders', function (Blueprint $table) {
        $table->id();
        $table->string('title')->nullable(); // عنوان الإعلان
        $table->string('link')->nullable();  // الرابط الذي يوجه إليه عند الضغط
        $table->foreignId('media_id')->constrained('media')->cascadeOnDelete(); // صورة الإعلان من Curator
        $table->integer('order')->default(0); // لترتيب الإعلانات
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
