<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'amount',
        'min_order_amount',
        'target_type',
        'usage_limit',
        'used_count',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isValidFor(float $orderSubtotal, string $type = 'all'): bool
    {
        if (!$this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        if ($orderSubtotal < $this->min_order_amount) return false;
        if ($this->target_type !== 'all' && $this->target_type !== $type) return false;

        return true;
    }

    public function calculateDiscount(float $orderSubtotal): float
    {
        if ($this->type === 'percentage') {
            return round(($orderSubtotal * $this->amount) / 100, 2);
        }
        return min($orderSubtotal, (float) $this->amount);
    }
}
