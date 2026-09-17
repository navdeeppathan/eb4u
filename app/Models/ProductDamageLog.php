<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDamageLog extends Model
{
    use HasFactory;

    protected $table = 'product_damage_logs';

    protected $fillable = [
        'product_id',
        'ebike_unit_id',
        'user_id',
        'order_id',
        'damage_type',
        'incident_date',
        'damage_description',
        'repair_cost',
        'user_charge_amount',
        'user_payment_status',
        'repair_status',
        'repair_start_date',
        'repair_completion_date',
        'admin_notes',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'repair_start_date' => 'date',
        'repair_completion_date' => 'date',
        'repair_cost' => 'float',
        'user_charge_amount' => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function ebikeUnit()
    {
        return $this->belongsTo(EBikeUnit::class, 'ebike_unit_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
