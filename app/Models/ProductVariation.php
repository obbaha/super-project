<?php // app/Models/ProductVariation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Awcodes\Curator\Models\Media;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'full_sku',
        'attribute_name',
        'additional_price',
        'stock_quantity',
        'reserved_quantity',
        'featured_image_id',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'additional_price' => 'decimal:2',
    ];

    protected $appends = ['stock_available'];

protected function stockAvailable(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->stock_quantity - $this->reserved_quantity,
        );
    }

public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'product_variation_id');
    }

// app/Models/ProductVariation.php [cite: 1]

public function featuredImage(): BelongsTo
{
    // تأكد أن المعامل الثاني هو 'featured_image_id' 
    return $this->belongsTo(\Awcodes\Curator\Models\Media::class, 'featured_image_id');
}

// أضف هذا داخل كلاس ProductVariation للتشخيص
protected static function booted()
{
    static::saving(function ($model) {
        // سيقوم هذا السطر بإيقاف الحفظ وعرض البيانات التي يحاول لارافيل إرسالها لقاعدة البيانات
        // dd($model->featured_image_id); 
    });
}

}