<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'ebikeUnits']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'type' => 'required|in:ebike,accessory',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'is_rental_eligible' => 'nullable|boolean',
            'rental_price_daily' => 'nullable|numeric|min:0',
            'rental_price_weekly' => 'nullable|numeric|min:0',
            'rental_price_monthly' => 'nullable|numeric|min:0',
            'rental_security_deposit' => 'nullable|numeric|min:0',
            'image_url' => 'nullable|url',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'sku' => strtoupper($request->sku),
            'type' => $request->type,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'stock_quantity' => $request->stock_quantity,
            'is_rental_eligible' => $request->boolean('is_rental_eligible'),
            'rental_price_daily' => $request->rental_price_daily,
            'rental_price_weekly' => $request->rental_price_weekly,
            'rental_price_monthly' => $request->rental_price_monthly,
            'rental_security_deposit' => $request->rental_security_deposit ?? 150.00,
            'motor_specs' => $request->motor_specs,
            'battery_specs' => $request->battery_specs,
            'range_specs' => $request->range_specs,
            'charging_time' => $request->charging_time,
            'warranty_specs' => $request->warranty_specs,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'is_featured' => $request->boolean('is_featured'),
            'is_best_seller' => $request->boolean('is_best_seller'),
            'is_most_rented' => $request->boolean('is_most_rented'),
            'is_new_arrival' => $request->boolean('is_new_arrival'),
            'is_active' => true,
        ]);

        $imgUrl = $request->image_url ?? 'https://images.unsplash.com/photo-1571068316344-75bc76f77890?w=800&auto=format&fit=crop&q=80';
        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => $imgUrl,
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function edit(int $id)
    {
        $product = Product::with(['images', 'variants', 'ebikeUnits'])->findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, int $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'stock_quantity' => $request->stock_quantity,
            'is_rental_eligible' => $request->boolean('is_rental_eligible'),
            'rental_price_daily' => $request->rental_price_daily,
            'rental_price_weekly' => $request->rental_price_weekly,
            'rental_price_monthly' => $request->rental_price_monthly,
            'rental_security_deposit' => $request->rental_security_deposit,
            'motor_specs' => $request->motor_specs,
            'battery_specs' => $request->battery_specs,
            'range_specs' => $request->range_specs,
            'charging_time' => $request->charging_time,
            'warranty_specs' => $request->warranty_specs,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'is_featured' => $request->boolean('is_featured'),
            'is_best_seller' => $request->boolean('is_best_seller'),
            'is_most_rented' => $request->boolean('is_most_rented'),
            'is_new_arrival' => $request->boolean('is_new_arrival'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(int $id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }
}
