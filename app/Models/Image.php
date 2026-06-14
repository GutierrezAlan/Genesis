<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    protected $fillable = [
        'product_id',
        'filename',
        'path',
        'mime_type',
        'size',
        'url'
    ];

    protected $appends = ['url'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->without('images');
    }
    public function getUrlAttribute()
    {
        return $this->path ? Storage::url($this->path) : null;
    }
}
