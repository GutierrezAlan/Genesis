<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Image;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'description',
        'category',
        'price',
        'stock'
    ];

    protected $appends = ['image_url'];

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

public function getImageUrlAttribute(): ?string
{
    $image = $this->images()->first();

    return $image ? asset('storage/' . $image->path) : null;
}
}
