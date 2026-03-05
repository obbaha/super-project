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