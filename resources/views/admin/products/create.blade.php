@extends('layouts.admin')

@section('title', 'Add New Product (E-Bike or Accessory)')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-3xl border border-slate-200 shadow-xs space-y-6">
    <div class="border-b border-slate-100 pb-4 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-black text-slate-900">Add New Product</h2>
            <p class="text-xs text-slate-500">Create a new E-Bike or Accessory product with image uploads.</p>
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

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-xs">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-700 mb-1">Product Title <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Gazelle Ultimate C380 HMB E-Bike" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">SKU <span class="text-rose-500">*</span></label>
                <input type="text" name="sku" value="{{ old('sku') }}" required placeholder="EB-UK-101" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-mono font-bold uppercase text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Product Type <span class="text-rose-500">*</span></label>
                <select name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
                    <option value="ebike" {{ old('type') == 'ebike' ? 'selected' : '' }}>E-Bike</option>
                    <option value="accessory" {{ old('type') == 'accessory' ? 'selected' : '' }}>Accessory</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Category <span class="text-rose-500">*</span></label>
                <select name="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ strtoupper($c->type) }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Brand</label>
                <select name="brand_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
                    <option value="">-- Select Brand --</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}" {{ old('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Retail Price (£) <span class="text-rose-500">*</span></label>
                <input type="number" step="0.01" name="price" value="{{ old('price') }}" required placeholder="3299.00" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-black text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Discount Price (£) (Optional)</label>
                <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price') }}" placeholder="2999.00" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Total Stock Quantity <span class="text-rose-500">*</span></label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 10) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
            </div>
        </div>

        <!-- Image Upload Section (Strict Max 2MB Validation) -->
        <div class="bg-brandOrange-50/40 p-6 rounded-2xl border border-brandOrange-500/20 space-y-4">
            <h4 class="font-bold text-brandOrange-600 uppercase flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up text-base"></i> Product Image Upload (Max File Size: 2MB)
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-900 mb-1">
                        Primary Cover Image File 
                        <span class="text-[10px] text-brandOrange-600 font-bold block">(JPG, PNG, WEBP — Strictly Max 2MB)</span>
                    </label>
                    <input type="file" name="primary_image" accept="image/jpeg,image/png,image/jpg,image/webp" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-900 mb-1">
                        Gallery Images (Multiple Files)
                        <span class="text-[10px] text-brandOrange-600 font-bold block">(Multiple files allowed — Max 2MB each)</span>
                    </label>
                    <input type="file" name="gallery_images[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-semibold text-slate-900">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Or Primary Image URL Fallback (Optional)</label>
                    <input type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://..." class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-semibold">
                </div>
            </div>
        </div>

        <!-- Rental Pricing Options -->
        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-4">
            <label class="flex items-center space-x-2 font-bold text-slate-900 uppercase">
                <input type="checkbox" name="is_rental_eligible" value="1" {{ old('is_rental_eligible', 1) ? 'checked' : '' }} class="text-brandOrange-500 rounded focus:ring-brandOrange-500">
                <span>Enable E-Bike Rental Option for this Product</span>
            </label>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-700 mb-1">Daily Rate (£)</label>
                    <input type="number" step="0.01" name="rental_price_daily" value="{{ old('rental_price_daily', '35.00') }}" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-bold text-slate-900">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-700 mb-1">Weekly Rate (£)</label>
                    <input type="number" step="0.01" name="rental_price_weekly" value="{{ old('rental_price_weekly', '180.00') }}" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-bold text-slate-900">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-700 mb-1">Monthly Rate (£)</label>
                    <input type="number" step="0.01" name="rental_price_monthly" value="{{ old('rental_price_monthly', '550.00') }}" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-bold text-slate-900">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-700 mb-1">Security Deposit (£)</label>
                    <input type="number" step="0.01" name="rental_security_deposit" value="{{ old('rental_security_deposit', '150.00') }}" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-bold text-slate-900">
                </div>
            </div>
        </div>

        <!-- E-Bike Technical Specs -->
        <div class="space-y-4">
            <h4 class="font-bold text-slate-900 uppercase">E-Bike Specifications (Optional)</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input type="text" name="motor_specs" value="{{ old('motor_specs') }}" placeholder="Motor (e.g. Bosch Performance Line 75Nm)" class="bg-slate-50 border border-slate-200 rounded-xl p-3 font-medium">
                <input type="text" name="battery_specs" value="{{ old('battery_specs') }}" placeholder="Battery (e.g. Bosch PowerTube 625Wh)" class="bg-slate-50 border border-slate-200 rounded-xl p-3 font-medium">
                <input type="text" name="range_specs" value="{{ old('range_specs') }}" placeholder="Range (e.g. 75 Miles / 120 km)" class="bg-slate-50 border border-slate-200 rounded-xl p-3 font-medium">
                <input type="text" name="warranty_specs" value="{{ old('warranty_specs') }}" placeholder="Warranty (e.g. 5 Years Frame, 2 Years Motor)" class="bg-slate-50 border border-slate-200 rounded-xl p-3 font-medium">
            </div>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">Description</label>
            <textarea name="description" rows="4" placeholder="Full product description..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-medium">{{ old('description') }}</textarea>
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.products.index') }}" class="py-3 px-5 bg-slate-100 text-slate-700 font-bold rounded-xl">Cancel</a>
            <button type="submit" class="py-3.5 px-7 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-black rounded-xl shadow-md uppercase">Save & Publish Product</button>
        </div>
    </form>
</div>
@endsection
