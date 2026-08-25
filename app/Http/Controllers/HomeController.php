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
        $banners = CmsBanner::where('position', 'home_hero')->where('is_active', true)->orderBy('sort_order')->get();
        $ebikeCategories = Category::where('type', 'ebike')->where('is_active', true)->orderBy('sort_order')->take(8)->get();
        $accessoryCategories = Category::where('type', 'accessory')->where('is_active', true)->orderBy('sort_order')->take(8)->get();
        
        $featuredEBikes = Product::where('type', 'ebike')->where('is_featured', true)->where('is_active', true)->take(4)->get();
        $bestSellers = Product::where('is_best_seller', true)->where('is_active', true)->take(4)->get();
        $mostRented = Product::where('is_most_rented', true)->where('is_active', true)->take(4)->get();
        $newArrivals = Product::where('is_new_arrival', true)->where('is_active', true)->take(4)->get();
        $popularAccessories = Product::where('type', 'accessory')->where('is_active', true)->take(6)->get();
        
        $reviews = Review::with('user', 'product')->where('status', 'approved')->where('is_featured', true)->latest()->take(6)->get();
        $faqs = Faq::where('is_active', true)->orderBy('sort_order')->take(5)->get();

        return view('home', compact(
            'banners',
            'ebikeCategories',
            'accessoryCategories',
            'featuredEBikes',
            'bestSellers',
            'mostRented',
            'newArrivals',
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
            'message' => 'Thank you for subscribing to E-Bike 4 U newsletter! Check your inbox for your 10% discount voucher code.'
        ]);
    }
}
