<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'ebike_unit_id',
        'service_type',
        'service_date',
        'next_service_date',
        'cost',
        'technician_name',
        'notes',
        'damage_details',
        'status',
    ];

    protected $casts = [
        'service_date' => 'date',
        'next_service_date' => 'date',
    ];

    public function ebikeUnit()
    {
        return $this->belongsTo(EBikeUnit::class, 'ebike_unit_id');
    }
}
