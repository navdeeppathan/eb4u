<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EBikeUnit extends Model
{
    use HasFactory;

    protected $table = 'ebike_units';

    protected $fillable = [
        'product_id',
        'ebike_code',
        'serial_number',
        'frame_size',
        'qr_code_data',
        'status',
        'condition_notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function maintenanceRecords()
    {
        return $this->hasMany(MaintenanceRecord::class, 'ebike_unit_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'ebike_unit_id');
    }
}
