<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\OrderItem;
use Carbon\Carbon;

class ApiProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'category', 'images'])->where('is_active', true);

        if ($request->filled('type')) {
            if ($request->type === 'rental') {
                $query->where('is_rental_eligible', true);
            } else {
                $query->where('type', $request->type);
            }
        }

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('brand')) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        switch ($request->get('sort', 'latest')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate($request->get('per_page', 12));

        $formattedData = collect($products->items())->map(fn($p) => $this->formatProduct($p));

        return response()->json([
            'success' => true,
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
            'data' => $formattedData,
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::with(['brand', 'category', 'images', 'variants', 'reviews.user'])
            ->where('is_active', true)
            ->where('slug', $slug)
            ->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'type' => $product->type,
                'brand' => [
                    'id' => $product->brand->id ?? null,
                    'name' => $product->brand->name ?? 'Premium',
                ],
                'category' => [
                    'id' => $product->category->id ?? null,
                    'name' => $product->category->name ?? 'General',
                ],
                'short_description' => $product->short_description,
                'description' => $product->description,
                'price' => (float) $product->price,
                'discount_price' => $product->discount_price ? (float) $product->discount_price : null,
                'effective_price' => (float) $product->effective_price,
                'discount_percentage' => $product->discount_percentage,
                'rental_price_daily' => (float) $product->rental_price_daily,
                'is_rental_eligible' => (bool) $product->is_rental_eligible,
                'stock_quantity' => (int) $product->stock_quantity,
                'motor_specs' => $product->motor_specs,
                'battery_specs' => $product->battery_specs,
                'range_specs' => $product->range_specs,
                'warranty_specs' => $product->warranty_specs,
                'average_rating' => (float) $product->average_rating,
                'reviews_count' => (int) $product->reviews_count,
                'primary_image' => $product->primary_image_url,
                'gallery_images' => $product->images->map(fn($img) => asset($img->image_path)),
                'variants' => $product->variants->map(fn($v) => [
                    'id' => $v->id,
                    'name' => $v->name,
                    'sku' => $v->sku,
                    'price_modifier' => (float) $v->price_modifier,
                    'stock_quantity' => (int) $v->stock_quantity,
                ]),
                'reviews' => $product->reviews->where('status', 'approved')->values()->map(fn($r) => [
                    'id' => $r->id,
                    'rating' => $r->rating,
                    'title' => $r->title,
                    'comment' => $r->comment,
                    'user_name' => $r->user->name ?? 'Verified Rider',
                    'user_avatar' => $r->user->avatar ?? null,
                    'created_at' => $r->created_at->format('d M Y'),
                ]),
            ]
        ]);
    }

    public function checkRentalAvailability(Request $request, int $id)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $product = Product::findOrFail($id);
        if (!$product->is_rental_eligible) {
            return response()->json(['success' => false, 'message' => 'This product is not eligible for rental.'], 422);
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = (int) max(1, $startDate->diffInDays($endDate));

        $overlapBookings = OrderItem::where('product_id', $product->id)
            ->where('item_type', 'rental')
            ->whereHas('order', function ($q) {
                $q->whereNotIn('status', ['cancelled', 'returned', 'completed']);
            })
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('rental_start_date', [$startDate, $endDate])
                  ->orWhereBetween('rental_end_date', [$startDate, $endDate]);
            })
            ->count();

        $isAvailable = $overlapBookings < max(1, $product->stock_quantity);

        $dailyRate = (float) $product->rental_price_daily;
        if ($days >= 30) {
            $dailyRate = round($dailyRate * 0.70, 2);
        } elseif ($days >= 7) {
            $dailyRate = round($dailyRate * 0.85, 2);
        }

        $subtotal = round($dailyRate * $days, 2);
        $deposit = (float) ($product->rental_security_deposit ?? 150.00);
        $advancePct = 30;
        $advance30 = round($subtotal * ($advancePct / 100) + $deposit, 2);

        return response()->json([
            'success' => true,
            'is_available' => $isAvailable,
            'rental_days' => $days,
            'daily_rate' => $dailyRate,
            'subtotal' => $subtotal,
            'security_deposit' => $deposit,
            'advance_percentage' => $advancePct,
            'advance_30_percent' => $advance30,
            'message' => $isAvailable ? 'E-Bike is available for selected rental dates!' : 'Vehicle is fully booked for these dates.',
        ]);
    }

    public function categories()
    {
        return response()->json([
            'success' => true,
            'categories' => Category::where('is_active', true)->orderBy('name')->get()
        ]);
    }

    public function brands()
    {
        return response()->json([
            'success' => true,
            'brands' => Brand::where('is_active', true)->orderBy('name')->get()
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
