<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'product_id',
        'product_name',
        'product_slug',
        'product_image',
        'currency_code',
        'quantity',
        'unit_price',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'unit_price' => 'decimal:2',
    ];

    public function getTotalAttribute(): float
    {
        return (float) $this->unit_price * $this->quantity;
    }
}
