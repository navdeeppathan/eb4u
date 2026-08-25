<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'type',
        'status',
        'payment_status',
        'payment_type',
        'advance_percentage',
        'advance_amount',
        'remaining_amount',
        'subtotal',
        'tax_amount',
        'delivery_fee',
        'security_deposit_total',
        'discount_amount',
        'total_amount',
        'coupon_code',
        'fulfillment_type',
        'shipping_address',
        'billing_address',
        'pickup_location',
        'rental_start_date',
        'rental_end_date',
        'actual_return_date',
        'late_fee_charged',
        'damage_fee_charged',
        'customer_notes',
        'admin_notes',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'rental_start_date' => 'date',
        'rental_end_date' => 'date',
        'actual_return_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800 border-amber-300',
            'confirmed', 'ready_for_pickup' => 'bg-blue-100 text-blue-800 border-blue-300',
            'processing', 'packed', 'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'active', 'picked_up' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'delivered', 'completed', 'returned' => 'bg-green-100 text-green-800 border-green-300',
            'extension_requested', 'return_requested' => 'bg-purple-100 text-purple-800 border-purple-300',
            'overdue' => 'bg-rose-100 text-rose-800 border-rose-300',
            'cancelled', 'refunded' => 'bg-slate-100 text-slate-800 border-slate-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }
}
