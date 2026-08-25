<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images'])->where('is_active', true);

        // Filter by Type (ebike, accessory, or rental)
        if ($request->filled('type')) {
            if ($request->type === 'rental') {
                $query->where('is_rental_eligible', true);
            } else {
                $query->where('type', $request->type);
            }
        }

        // Filter by Category
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Filter by Brand
        if ($request->filled('brand')) {
            $brand = Brand::where('slug', $request->brand)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        // Search query
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('motor_specs', 'like', "%{$search}%");
            });
        }

        // Price filtering
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // Flags filtering
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }
        if ($request->boolean('best_seller')) {
            $query->where('is_best_seller', true);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                // Subquery or fallback
                $query->latest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $brands = Brand::where('is_active', true)->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('catalog.partials.product_grid', compact('products'))->render(),
                'pagination' => view('catalog.partials.pagination', compact('products'))->render(),
                'count' => $products->total(),
            ]);
        }

        return view('catalog.index', compact('products', 'categories', 'brands'));
    }
}
