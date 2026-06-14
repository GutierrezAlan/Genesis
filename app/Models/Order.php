<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_price',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
        'total_price' => 'float',
    ];

    /**
     * Relación con el usuario que creó la orden
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
