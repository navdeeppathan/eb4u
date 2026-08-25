<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with(['category', 'brand', 'images', 'variants', 'reviews.user'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function checkRentalAvailability(Request $request, int $id)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $product = Product::findOrFail($id);

        if (!$product->is_rental_eligible) {
            return response()->json([
                'success' => false,
                'message' => 'This product is not available for rental.'
            ], 422);
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = (int) ceil($startDate->diffInDays($endDate));

        if ($days <= 0) {
            $days = 1;
        }

        // Calculate Pricing Plan (Daily, Weekly, Monthly)
        $dailyRate = (float) $product->rental_price_daily;
        if ($days >= 30 && $product->rental_price_monthly) {
            $monthlyRate = (float) $product->rental_price_monthly / 30;
            $dailyRate = min($dailyRate, $monthlyRate);
        } elseif ($days >= 7 && $product->rental_price_weekly) {
            $weeklyRate = (float) $product->rental_price_weekly / 7;
            $dailyRate = min($dailyRate, $weeklyRate);
        }

        $rentalSubtotal = round($dailyRate * $days, 2);
        $deposit = (float) ($product->rental_security_deposit ?? 150.00);

        // Check Available physical units
        $availableUnitsCount = $product->getAvailableRentalUnitsCount($startDate->toDateString(), $endDate->toDateString());
        $isAvailable = ($availableUnitsCount > 0);

        return response()->json([
            'success' => true,
            'is_available' => $isAvailable,
            'available_units_count' => $availableUnitsCount,
            'rental_days' => $days,
            'daily_rate' => number_format($dailyRate, 2),
            'subtotal' => number_format($rentalSubtotal, 2),
            'security_deposit' => number_format($deposit, 2),
            'advance_30_percent' => number_format($rentalSubtotal * 0.3, 2),
            'remaining_balance' => number_format($rentalSubtotal * 0.7, 2),
            'message' => $isAvailable
                ? "{$availableUnitsCount} physical E-Bike(s) available for these dates!"
                : "Sorry, all units of this E-Bike are reserved or under maintenance for the selected dates."
        ]);
    }

    public function submitReview(Request $request, int $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in to leave a review.'], 401);
        }

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $id,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'status' => 'approved', // Auto-approved for fast feedback or configurable
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your review has been submitted successfully.'
        ]);
    }
}
