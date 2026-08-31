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
use Illuminate\Support\Facades\File;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'ebikeUnits']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
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
        // Strict Validation: Images must be max 2048 KB (2MB)
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'type' => 'required|in:ebike,accessory',
            'product_tag' => 'required|in:sell,rent',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'rental_price_daily' => 'nullable|numeric|min:0',
            'rental_price_weekly' => 'nullable|numeric|min:0',
            'rental_price_monthly' => 'nullable|numeric|min:0',
            'rental_security_deposit' => 'nullable|numeric|min:0',
            'primary_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url' => 'nullable|url',
        ], [
            'primary_image.max' => 'Primary image file size must not exceed 2MB.',
            'gallery_images.*.max' => 'Each gallery image file size must not exceed 2MB.',
            'primary_image.mimes' => 'Primary image must be a valid file of type: jpeg, png, jpg, webp.',
            'gallery_images.*.mimes' => 'Gallery images must be valid files of type: jpeg, png, jpg, webp.',
        ]);

        $productTag = $request->input('product_tag', 'sell');
        $isRental = ($productTag === 'rent');

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(5),
            'sku' => strtoupper($request->sku),
            'type' => $request->type,
            'product_tag' => $productTag,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'stock_quantity' => $request->stock_quantity,
            'is_rental_eligible' => $isRental,
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

        // Process Primary Image Upload (Max 2MB validated)
        $primaryPath = null;
        if ($request->hasFile('primary_image')) {
            $file = $request->file('primary_image');
            $uploadDir = public_path('uploads/products');
            if (!File::exists($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $filename = time() . '_primary_' . Str::slug($product->name) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $primaryPath = 'uploads/products/' . $filename;
        } elseif ($request->filled('image_url')) {
            $primaryPath = $request->image_url;
        } else {
            $primaryPath = 'https://images.unsplash.com/photo-1571068316344-75bc76f77890?w=800&auto=format&fit=crop&q=80';
        }

        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => $primaryPath,
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        // Process Gallery Images Upload (Max 2MB validated per file)
        if ($request->hasFile('gallery_images')) {
            $sortOrder = 2;
            foreach ($request->file('gallery_images') as $file) {
                if ($file->isValid()) {
                    $uploadDir = public_path('uploads/products');
                    if (!File::exists($uploadDir)) {
                        File::makeDirectory($uploadDir, 0755, true);
                    }
                    $filename = time() . '_' . $sortOrder . '_' . Str::slug($product->name) . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadDir, $filename);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'uploads/products/' . $filename,
                        'is_primary' => false,
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully with image uploads!');
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
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'product_tag' => 'required|in:sell,rent',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'primary_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'primary_image.max' => 'Primary image file size must not exceed 2MB.',
            'gallery_images.*.max' => 'Each gallery image file size must not exceed 2MB.',
        ]);

        $productTag = $request->input('product_tag', 'sell');
        $isRental = ($productTag === 'rent');

        $product->update([
            'name' => $request->name,
            'sku' => strtoupper($request->sku),
            'product_tag' => $productTag,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'stock_quantity' => $request->stock_quantity,
            'is_rental_eligible' => $isRental,
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

        // Process Primary Image Replacement if uploaded
        if ($request->hasFile('primary_image')) {
            $file = $request->file('primary_image');
            $uploadDir = public_path('uploads/products');
            if (!File::exists($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $filename = time() . '_primary_' . Str::slug($product->name) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $newPath = 'uploads/products/' . $filename;

            $primaryImg = ProductImage::where('product_id', $product->id)->where('is_primary', true)->first();
            if ($primaryImg) {
                $primaryImg->update(['image_path' => $newPath]);
            } else {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $newPath,
                    'is_primary' => true,
                    'sort_order' => 1,
                ]);
            }
        } elseif ($request->filled('image_url')) {
            $primaryImg = ProductImage::where('product_id', $product->id)->where('is_primary', true)->first();
            if ($primaryImg) {
                $primaryImg->update(['image_path' => $request->image_url]);
            }
        }

        // Process Additional Gallery Images
        if ($request->hasFile('gallery_images')) {
            $lastSort = ProductImage::where('product_id', $product->id)->max('sort_order') ?? 1;
            foreach ($request->file('gallery_images') as $file) {
                if ($file->isValid()) {
                    $lastSort++;
                    $uploadDir = public_path('uploads/products');
                    if (!File::exists($uploadDir)) {
                        File::makeDirectory($uploadDir, 0755, true);
                    }
                    $filename = time() . '_' . $lastSort . '_' . Str::slug($product->name) . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadDir, $filename);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'uploads/products/' . $filename,
                        'is_primary' => false,
                        'sort_order' => $lastSort,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(int $id)
    {
        $product = Product::with('images')->findOrFail($id);
        
        // Unlink image files from disk if stored locally
        foreach ($product->images as $img) {
            if ($img->image_path && Str::startsWith($img->image_path, 'uploads/products/')) {
                $fullPath = public_path($img->image_path);
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                }
            }
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
