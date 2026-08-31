@extends('layouts.admin')

@section('title', 'Edit Product: ' . $product->name)

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-3xl border border-slate-200 shadow-xs space-y-6">
    <div class="border-b border-slate-100 pb-4 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-black text-slate-900">Edit Product</h2>
            <p class="text-xs text-slate-500">Update product specifications, prices, stock, and images.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-xs rounded-xl">
            &larr; Back to Catalog
        </a>
    </div>

    <!-- Error Validation Messages -->
    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-2xl text-xs space-y-1 font-semibold">
            <p class="font-bold text-rose-800"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Please fix the following validation errors:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-xs">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-700 mb-1">Product Title <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">SKU <span class="text-rose-500">*</span></label>
                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-mono font-bold uppercase text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
            </div>

            <!-- Product Tag: Sell (Buy Only) vs Rent (Rent Only) -->
            <div class="sm:col-span-2 bg-amber-50/60 border border-amber-200/80 p-4 rounded-2xl">
                <label class="block font-extrabold text-slate-800 text-xs mb-2">
                    <i class="fa-solid fa-tags text-brandOrange-500 mr-1"></i> Product Tag / Purpose <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="flex items-center space-x-3 bg-white p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-brandOrange-500 transition-all">
                        <input type="radio" name="product_tag" value="sell" {{ old('product_tag', $product->product_tag) == 'sell' ? 'checked' : '' }} class="text-brandOrange-500 focus:ring-brandOrange-500">
                        <div>
                            <span class="font-extrabold text-slate-900 block text-xs">🛒 Buy Only (Sell Tag)</span>
                            <span class="text-[10px] text-slate-500 font-medium">Customer can ONLY purchase outright. Cannot be rented.</span>
                        </div>
                    </label>

                    <label class="flex items-center space-x-3 bg-white p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-brandOrange-500 transition-all">
                        <input type="radio" name="product_tag" value="rent" {{ old('product_tag', $product->product_tag) == 'rent' ? 'checked' : '' }} class="text-brandOrange-500 focus:ring-brandOrange-500">
                        <div>
                            <span class="font-extrabold text-slate-900 block text-xs">⚡ Rent Only (Rent Tag)</span>
                            <span class="text-[10px] text-slate-500 font-medium">Customer can ONLY rent. Cannot be bought outright.</span>
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Retail Price (£) <span class="text-rose-500">*</span></label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-black text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Discount Price (£) (Optional)</label>
                <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Total Stock Quantity <span class="text-rose-500">*</span></label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Status</label>
                <select name="is_active" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold text-slate-900">
                    <option value="1" {{ $product->is_active ? 'selected' : '' }}>Active (Visible in Store)</option>
                    <option value="0" {{ !$product->is_active ? 'selected' : '' }}>Inactive (Hidden)</option>
                </select>
            </div>
        </div>

        <!-- Image Upload & Preview Section -->
        <div class="bg-brandOrange-50/40 p-6 rounded-2xl border border-brandOrange-500/20 space-y-4">
            <h4 class="font-bold text-brandOrange-600 uppercase flex items-center gap-2">
                <i class="fa-solid fa-image text-base"></i> Product Images (Max File Size: 2MB per Image)
            </h4>

            <div class="flex items-center space-x-4 mb-4">
                <img src="{{ $product->primary_image_url }}" class="w-20 h-20 object-cover rounded-2xl border border-slate-200 shadow-sm flex-shrink-0">
                <div>
                    <span class="text-xs font-bold text-slate-900 block">Current Primary Cover Image</span>
                    <span class="text-[11px] text-slate-500 block truncate max-w-sm">{{ $product->primary_image_url }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-900 mb-1">
                        Replace Primary Cover Image File 
                        <span class="text-[10px] text-brandOrange-600 font-bold block">(JPG, PNG, WEBP — Max 2MB)</span>
                    </label>
                    <input type="file" name="primary_image" accept="image/jpeg,image/png,image/jpg,image/webp" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-900 mb-1">
                        Add Gallery Images (Multiple Files)
                        <span class="text-[10px] text-brandOrange-600 font-bold block">(Multiple files — Max 2MB each)</span>
                    </label>
                    <input type="file" name="gallery_images[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-semibold text-slate-900">
                </div>
            </div>
        </div>

        <!-- Rental Pricing Options -->
        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-4">
            <label class="flex items-center space-x-2 font-bold text-slate-900 uppercase">
                <input type="checkbox" name="is_rental_eligible" value="1" {{ old('is_rental_eligible', $product->is_rental_eligible) ? 'checked' : '' }} class="text-brandOrange-500 rounded focus:ring-brandOrange-500">
                <span>Enable E-Bike Rental Option for this Product</span>
            </label>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-700 mb-1">Daily Rate (£)</label>
                    <input type="number" step="0.01" name="rental_price_daily" value="{{ old('rental_price_daily', $product->rental_price_daily) }}" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-bold text-slate-900">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-700 mb-1">Weekly Rate (£)</label>
                    <input type="number" step="0.01" name="rental_price_weekly" value="{{ old('rental_price_weekly', $product->rental_price_weekly) }}" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-bold text-slate-900">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-700 mb-1">Monthly Rate (£)</label>
                    <input type="number" step="0.01" name="rental_price_monthly" value="{{ old('rental_price_monthly', $product->rental_price_monthly) }}" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-bold text-slate-900">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-700 mb-1">Security Deposit (£)</label>
                    <input type="number" step="0.01" name="rental_security_deposit" value="{{ old('rental_security_deposit', $product->rental_security_deposit) }}" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-bold text-slate-900">
                </div>
            </div>
        </div>

        <!-- Specs & Descriptions -->
        <div class="space-y-4">
            <h4 class="font-bold text-slate-900 uppercase">E-Bike Specifications</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input type="text" name="motor_specs" value="{{ old('motor_specs', $product->motor_specs) }}" placeholder="Motor specs" class="bg-slate-50 border border-slate-200 rounded-xl p-3 font-medium">
                <input type="text" name="battery_specs" value="{{ old('battery_specs', $product->battery_specs) }}" placeholder="Battery specs" class="bg-slate-50 border border-slate-200 rounded-xl p-3 font-medium">
                <input type="text" name="range_specs" value="{{ old('range_specs', $product->range_specs) }}" placeholder="Range specs" class="bg-slate-50 border border-slate-200 rounded-xl p-3 font-medium">
                <input type="text" name="warranty_specs" value="{{ old('warranty_specs', $product->warranty_specs) }}" placeholder="Warranty specs" class="bg-slate-50 border border-slate-200 rounded-xl p-3 font-medium">
            </div>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">Description</label>
            <textarea name="description" rows="4" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-medium">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.products.index') }}" class="py-3 px-5 bg-slate-100 text-slate-700 font-bold rounded-xl">Cancel</a>
            <button type="submit" class="py-3.5 px-7 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-black rounded-xl shadow-md uppercase">Update Product</button>
        </div>
    </form>
</div>
@endsection
