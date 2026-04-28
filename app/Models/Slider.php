<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slider extends Model
{
    protected $fillable = ['title', 'link', 'media_id', 'order', 'is_active'];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
}
