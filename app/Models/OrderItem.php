<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'ebike_unit_id',
        'item_type',
        'product_name',
        'variant_name',
        'unit_price',
        'quantity',
        'subtotal',
        'rental_start_date',
        'rental_end_date',
        'rental_days',
        'rental_rate',
        'security_deposit',
    ];

    protected $casts = [
        'rental_start_date' => 'date',
        'rental_end_date' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function ebikeUnit()
    {
        return $this->belongsTo(EBikeUnit::class, 'ebike_unit_id');
    }
}
