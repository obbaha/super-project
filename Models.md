This file is a merged representation of a subset of the codebase, containing specifically included files, combined into a single document by Repomix.

# File Summary

## Purpose
This file contains a packed representation of a subset of the repository's contents that is considered the most important context.
It is designed to be easily consumable by AI systems for analysis, code review,
or other automated processes.

## File Format
The content is organized as follows:
1. This summary section
2. Repository information
3. Directory structure
4. Repository files (if enabled)
5. Multiple file entries, each consisting of:
  a. A header with the file path (## File: path/to/file)
  b. The full contents of the file in a code block

## Usage Guidelines
- This file should be treated as read-only. Any changes should be made to the
  original repository files, not this packed version.
- When processing this file, use the file path to distinguish
  between different files in the repository.
- Be aware that this file may contain sensitive information. Handle it with
  the same level of security as you would the original repository.

## Notes
- Some files may have been excluded based on .gitignore rules and Repomix's configuration
- Binary files are not included in this packed representation. Please refer to the Repository Structure section for a complete list of file paths, including binary files
- Only files matching these patterns are included: app/Models/*.php
- Files matching patterns in .gitignore are excluded
- Files matching default ignore patterns are excluded
- Files are sorted by Git change count (files with more changes are at the bottom)

# Directory Structure
```
app/Models/Category.php
app/Models/Coupon.php
app/Models/Customer.php
app/Models/District.php
app/Models/Governorate.php
app/Models/Order.php
app/Models/OrderItem.php
app/Models/Product.php
app/Models/ProductVariation.php
app/Models/ShippingBranch.php
app/Models/User.php
```

# Files

## File: app/Models/Category.php
```php
<?php // app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }
}
```

## File: app/Models/Coupon.php
```php
<?php // app/Models/Coupon.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'value',
        'usage_limit',
        'used_count',
        'expiry_date',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'expiry_date' => 'date',
    ];

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }
}
```

## File: app/Models/Customer.php
```php
<?php // app/Models/Customer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
    ];

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }
}
```

## File: app/Models/District.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class District extends Model
{
    protected $fillable = ['governorate_id', 'name', 'shipping_cost'];

    public function governorate(): BelongsTo {
        return $this->belongsTo(Governorate::class);
    }
}
```

## File: app/Models/Governorate.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Governorate extends Model
{
    protected $fillable = ['name'];

    public function districts(): HasMany {
        return $this->hasMany(District::class);
    }

    public function shippingBranches(): HasMany {
        return $this->hasMany(ShippingBranch::class);
    }
}
```

## File: app/Models/Order.php
```php
<?php // app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'governorate_id',
        'district_id',
        'detailed_address',
        'shipping_branch_id',
        'shipping_cost',
        'total_price',
        'coupon_id',
        'discount_amount',
        'status',
        'inventory_updated',
    ];

    protected $casts = [
        'status' => 'string',
        'governorate' => 'string',
        'shipping_cost' => 'decimal:2',
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'detailed_address' => 'string',
        'inventory_updated' => 'boolean',
    ];

protected $attributes = [
    'status' => 'pending',
    'inventory_updated' => false,
    'discount_amount' => 0.00,
    'detailed_address' => 'No address provided'
];

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function shippingBranch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ShippingBranch::class)->withDefault();
    }

    public function coupon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Coupon::class)->withDefault();
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // app/Models/Order.php

public function governorate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(Governorate::class);
}

public function district(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(District::class);
}
}
```

## File: app/Models/OrderItem.php
```php
<?php // app/Models/OrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_variation_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function variation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'product_variation_id');
    }




public static function boot()
{
    parent::boot();

    // عند إنشاء عنصر جديد، نحجز الكمية في التنوع
    static::created(function ($item) {
        $item->variation->increment('reserved_quantity', $item->quantity);
    });
}












}
```

## File: app/Models/Product.php
```php
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
    // نبحث عن أول تنوع يمتلك صورة
    $variationWithImage = $this->variations->whereNotNull('featured_image_id')->first();
    
    return $variationWithImage ? $variationWithImage->featuredImage : null;
}

/**
 * جلب السعر الأدنى للمنتج
 */
public function getMinPriceAttribute()
{
    return $this->variations->min('additional_price') ?: $this->price;
}




}
```

## File: app/Models/ProductVariation.php
```php
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
```

## File: app/Models/ShippingBranch.php
```php
<?php // app/Models/ShippingBranch.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'governorate_id',
        'branch_name',
        'shipping_cost',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'shipping_cost' => 'decimal:2',
    ];

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }
}
```

## File: app/Models/User.php
```php
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => 'string',
    ];
}
```
