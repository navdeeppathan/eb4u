<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'product_id',
        'variant_id',
        'item_type',
        'quantity',
        'rental_start_date',
        'rental_end_date',
        'rental_days',
        'rental_plan',
        'daily_rate',
        'security_deposit',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
        'rental_start_date' => 'date',
        'rental_end_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSubtotalAttribute(): float
    {
        if ($this->item_type === 'rental') {
            return (float) ($this->daily_rate * $this->rental_days * $this->quantity);
        }

        $price = $this->variant ? ($this->product->effective_price + $this->variant->price_modifier) : $this->product->effective_price;
        return (float) ($price * $this->quantity);
    }
}
