<?php // app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'description',
        'price',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2',
    ];

    protected $attributes = [
    'is_available' => true,
    ];

public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }




public function getFeaturedImageAttribute()
{
    // 1. الأولوية لأول تنوع "متاح" ويمتلك صورة مثبتة (المثالي)
    $image = $this->variations->where('is_available', true)->whereNotNull('featured_image_id')->first()?->featuredImage;

    // 2. إذا لم يجد، يبحث في أي تنوع يمتلك صورة حتى لو غير متاح
    if (!$image) {
        $image = $this->variations->whereNotNull('featured_image_id')->first()?->featuredImage;
    }

    // 3. الخطة البديلة الأخيرة: أول صورة موجودة في معرض صور أي تنوع (Media)
    if (!$image) {
        $image = $this->variations->flatMap->media->first();
    }

    return $image;
}

/**
 * جلب السعر الأدنى للمنتج
 */
public function getMinPriceAttribute()
{
    return $this->variations->min('additional_price') ?: $this->price;
}




}
