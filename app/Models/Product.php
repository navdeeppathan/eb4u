<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'type',
        'category_id',
        'brand_id',
        'price',
        'discount_price',
        'stock_quantity',
        'is_rental_eligible',
        'rental_price_daily',
        'rental_price_weekly',
        'rental_price_monthly',
        'rental_security_deposit',
        'motor_specs',
        'battery_specs',
        'range_specs',
        'charging_time',
        'warranty_specs',
        'short_description',
        'description',
        'specifications',
        'is_featured',
        'is_best_seller',
        'is_most_rented',
        'is_new_arrival',
        'is_active',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'specifications' => 'array',
        'is_rental_eligible' => 'boolean',
        'is_featured' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_most_rented' => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function ebikeUnits()
    {
        return $this->hasMany(EBikeUnit::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $primary = $this->images()->where('is_primary', true)->first() ?? $this->images()->first();
        if ($primary) {
            return asset($primary->image_path);
        }
        return 'https://images.unsplash.com/photo-1571068316344-75bc76f77890?w=600&auto=format&fit=crop&q=80';
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->discount_price && $this->discount_price > 0 ? (float) $this->discount_price : (float) $this->price;
    }

    public function getDiscountPercentageAttribute(): int
    {
        if ($this->discount_price && $this->discount_price < $this->price) {
            return (int) round((($this->price - $this->discount_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getAverageRatingAttribute(): float
    {
        return (float) ($this->reviews()->avg('rating') ?? 4.8);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    // Availability for rental check given start date and end date
    public function getAvailableRentalUnitsCount($startDate, $endDate): int
    {
        if (!$this->is_rental_eligible) return 0;

        // Get all units for this product that are NOT under maintenance or retired
        $totalOperationalUnits = $this->ebikeUnits()
            ->whereIn('status', ['available', 'rented'])
            ->get();

        if ($totalOperationalUnits->isEmpty()) {
            return 0;
        }

        $availableCount = 0;

        foreach ($totalOperationalUnits as $unit) {
            // Check if unit has any overlapping active/confirmed rental order item
            $hasOverlap = OrderItem::where('ebike_unit_id', $unit->id)
                ->where('item_type', 'rental')
                ->whereHas('order', function ($query) {
                    $query->whereNotIn('status', ['cancelled', 'completed', 'returned']);
                })
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('rental_start_date', [$startDate, $endDate])
                        ->orWhereBetween('rental_end_date', [$startDate, $endDate])
                        ->orWhere(function ($q) use ($startDate, $endDate) {
                            $q->where('rental_start_date', '<=', $startDate)
                              ->where('rental_end_date', '>=', $endDate);
                        });
                })->exists();

            if (!$hasOverlap) {
                $availableCount++;
            }
        }

        return $availableCount;
    }
}
