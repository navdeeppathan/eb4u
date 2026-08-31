<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CmsBanner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Faq;

class HomeController extends Controller
{
    public function index()
    {
        $banners = CmsBanner::where('is_active', true)->orderBy('sort_order')->get();
        $ebikeCategories = Category::where('type', 'ebike')->where('is_active', true)->orderBy('sort_order')->take(8)->get();
        $accessoryCategories = Category::where('type', 'accessory')->where('is_active', true)->orderBy('sort_order')->take(8)->get();
        
        // Buy Products (Retail Sales)
        $buyProducts = Product::with(['brand', 'category', 'images'])
            ->where('is_active', true)
            ->where(function($q) {
                $q->where('product_tag', 'sell')->orWhere('is_rental_eligible', false);
            })
            ->latest()
            ->take(4)
            ->get();

        // Rent Products (Rental Fleet)
        $rentalProducts = Product::with(['brand', 'category', 'images'])
            ->where('is_active', true)
            ->where(function($q) {
                $q->where('product_tag', 'rent')->orWhere('is_rental_eligible', true);
            })
            ->latest()
            ->take(4)
            ->get();

        // Fallback All Featured Products
        $featuredEBikes = Product::with(['brand', 'category', 'images'])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $popularAccessories = Product::with(['brand', 'category', 'images'])
            ->where('type', 'accessory')
            ->where('is_active', true)
            ->take(6)
            ->get();
        
        $reviews = Review::with('user', 'product')->where('status', 'approved')->where('is_featured', true)->latest()->take(6)->get();
        $faqs = Faq::where('is_active', true)->orderBy('sort_order')->take(5)->get();

        return view('home', compact(
            'banners',
            'ebikeCategories',
            'accessoryCategories',
            'buyProducts',
            'rentalProducts',
            'featuredEBikes',
            'popularAccessories',
            'reviews',
            'faqs'
        ));
    }

    public function subscribeNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for subscribing to eb4u newsletter!'
        ]);
    }
}
