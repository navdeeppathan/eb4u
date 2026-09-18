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
        'weekly_rate',
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

    public function getDailyRateAttribute(): float
    {
        if (!empty($this->weekly_rate) && (float)$this->weekly_rate > 0) {
            return (float) round((float)$this->weekly_rate / 7, 2);
        }
        if ($this->product && !empty($this->product->rental_price_weekly) && (float)$this->product->rental_price_weekly > 0) {
            return (float) round((float)$this->product->rental_price_weekly / 7, 2);
        }
        return 35.00;
    }

    public function getSubtotalAttribute(): float
    {
        if ($this->item_type === 'rental') {
            $weeks = max(1, (int) ceil(($this->rental_days ?? 7) / 7));
            return (float) ($this->weekly_rate * $weeks * $this->quantity);
        }

        $price = $this->variant ? ($this->product->effective_price + $this->variant->price_modifier) : $this->product->effective_price;
        return (float) ($price * $this->quantity);
    }
}
