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
        'coupon_code',
        'discount_amount',
        'status',
        'inventory_updated',
    ];

    protected $casts = [
        'status' => 'string',
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


// احذف أي نسخة قديمة لهذه الدالة وأبقي على هذه فقط
    public function coupon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        // نربط الطلب بالكوبون عن طريق الكود النصي بدلاً من الـ ID
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code')->withDefault();
    }

}