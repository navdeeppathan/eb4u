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
        'gps_unit_id',
        'gps_ident',
        'gps_hw_id',
        'battery_level',
        'last_latitude',
        'last_longitude',
        'last_gps_sync',
    ];

    protected $casts = [
        'last_gps_sync' => 'datetime',
        'last_latitude' => 'float',
        'last_longitude' => 'float',
        'battery_level' => 'integer',
    ];

    public function hasGpsTracker(): bool
    {
        return !empty($this->gps_unit_id) || !empty($this->gps_ident);
    }

    public function getMapLocationUrlAttribute(): ?string
    {
        if ($this->last_latitude && $this->last_longitude) {
            return "https://www.google.com/maps?q={$this->last_latitude},{$this->last_longitude}";
        }
        return null;
    }

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

    public function damageLogs()
    {
        return $this->hasMany(ProductDamageLog::class, 'ebike_unit_id');
    }
}
