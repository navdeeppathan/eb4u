@extends('layouts.admin')

@section('title', 'Add New Product (E-Bike or Accessory)')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-3xl border border-slate-200 shadow-xs space-y-6">
    <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-6 text-xs">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-700 mb-1">Product Title</label>
                <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">SKU</label>
                <input type="text" name="sku" required placeholder="EB-UK-101" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-mono font-bold uppercase">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Product Type</label>
                <select name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold">
                    <option value="ebike">E-Bike</option>
                    <option value="accessory">Accessory</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Category</label>
                <select name="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold">
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ strtoupper($c->type) }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Brand</label>
                <select name="brand_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold">
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Retail Price (£)</label>
                <input type="number" step="0.01" name="price" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-black">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Discount Price (£)</label>
                <input type="number" step="0.01" name="discount_price" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Total Retail Stock</label>
                <input type="number" name="stock_quantity" value="10" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Primary Image URL</label>
                <input type="url" name="image_url" placeholder="https://..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3">
            </div>
        </div>

        <!-- Rental Pricing Options -->
        <div class="bg-purple-50/50 p-6 rounded-2xl border border-purple-100 space-y-4">
            <label class="flex items-center space-x-2 font-black text-purple-900 uppercase">
                <input type="checkbox" name="is_rental_eligible" value="1" checked class="text-purple-600 rounded">
                <span>Enable E-Bike Rental for this Product</span>
            </label>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-purple-900 mb-1">Daily Rate (£)</label>
                    <input type="number" step="0.01" name="rental_price_daily" value="35.00" class="w-full bg-white border border-purple-200 rounded-xl p-2.5 font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-purple-900 mb-1">Weekly Rate (£)</label>
                    <input type="number" step="0.01" name="rental_price_weekly" value="180.00" class="w-full bg-white border border-purple-200 rounded-xl p-2.5 font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-purple-900 mb-1">Monthly Rate (£)</label>
                    <input type="number" step="0.01" name="rental_price_monthly" value="550.00" class="w-full bg-white border border-purple-200 rounded-xl p-2.5 font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-purple-900 mb-1">Security Deposit (£)</label>
                    <input type="number" step="0.01" name="rental_security_deposit" value="150.00" class="w-full bg-white border border-purple-200 rounded-xl p-2.5 font-bold">
                </div>
            </div>
        </div>

        <!-- E-Bike Technical Specs -->
        <div class="space-y-4">
            <h4 class="font-bold text-slate-900 uppercase">E-Bike Specifications</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input type="text" name="motor_specs" placeholder="Motor (e.g. Bosch Performance Line 75Nm)" class="bg-slate-50 border border-slate-200 rounded-xl p-3">
                <input type="text" name="battery_specs" placeholder="Battery (e.g. Bosch PowerTube 625Wh)" class="bg-slate-50 border border-slate-200 rounded-xl p-3">
                <input type="text" name="range_specs" placeholder="Range (e.g. 75 Miles / 120 km)" class="bg-slate-50 border border-slate-200 rounded-xl p-3">
                <input type="text" name="warranty_specs" placeholder="Warranty (e.g. 5 Years Frame, 2 Years Motor)" class="bg-slate-50 border border-slate-200 rounded-xl p-3">
            </div>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">Description</label>
            <textarea name="description" rows="4" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-medium"></textarea>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.products.index') }}" class="py-3 px-5 bg-slate-100 text-slate-700 font-bold rounded-xl">Cancel</a>
            <button type="submit" class="py-3 px-6 bg-brand-600 hover:bg-brand-700 text-white font-black rounded-xl shadow-md uppercase">Save Product</button>
        </div>
    </form>
</div>
@endsection
