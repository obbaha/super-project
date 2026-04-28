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