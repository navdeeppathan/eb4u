<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsBanner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;

class ApiHomeController extends Controller
{
    public function index()
    {
        $banners = CmsBanner::where('is_active', true)->orderBy('sort_order')->get();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        
        $featuredEBikes = Product::with(['brand', 'images'])
            ->where('is_active', true)
            ->where('type', 'ebike')
            ->where('is_featured', true)
            ->take(8)
            ->get()
            ->map(fn($p) => $this->formatProduct($p));

        $popularAccessories = Product::with(['brand', 'images'])
            ->where('is_active', true)
            ->where('type', 'accessory')
            ->take(6)
            ->get()
            ->map(fn($p) => $this->formatProduct($p));

        $reviews = Review::with('user')
            ->where('status', 'approved')
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->id,
                    'rating' => $r->rating,
                    'title' => $r->title,
                    'comment' => $r->comment,
                    'user_name' => $r->user->name ?? 'Verified Rider',
                    'user_avatar' => $r->user->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80',
                    'is_verified' => true,
                ];
            });

        return response()->json([
            'success' => true,
            'banners' => $banners,
            'categories' => $categories,
            'featured_ebikes' => $featuredEBikes,
            'popular_accessories' => $popularAccessories,
            'reviews' => $reviews,
            'stats' => [
                'ebikes_count' => '500+',
                'rating' => '4.9 ★',
                'delivery' => 'Free UK Delivery > £500',
                'warranty' => '2-Year Official UK Warranty'
            ]
        ]);
    }

    private function formatProduct($p)
    {
        return [
            'id' => $p->id,
            'name' => $p->name,
            'slug' => $p->slug,
            'type' => $p->type,
            'brand_name' => $p->brand->name ?? 'Premium',
            'price' => (float) $p->price,
            'discount_price' => $p->discount_price ? (float) $p->discount_price : null,
            'effective_price' => (float) $p->effective_price,
            'discount_percentage' => $p->discount_percentage,
            'rental_price_daily' => (float) $p->rental_price_daily,
            'is_rental_eligible' => (bool) $p->is_rental_eligible,
            'primary_image' => $p->primary_image_url,
            'motor_specs' => $p->motor_specs ?? '250W',
            'battery_specs' => $p->battery_specs ?? '625Wh',
            'range_specs' => $p->range_specs ?? '75 Miles',
            'average_rating' => (float) $p->average_rating,
            'reviews_count' => (int) $p->reviews_count,
            'stock_quantity' => (int) $p->stock_quantity,
        ];
    }
}
