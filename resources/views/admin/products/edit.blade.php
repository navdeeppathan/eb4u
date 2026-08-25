@extends('layouts.admin')

@section('title', 'Edit Product #' . $product->id . ' - ' . $product->name)

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-3xl border border-slate-200 shadow-xs space-y-6">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" class="space-y-6 text-xs">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-700 mb-1">Product Title</label>
                <input type="text" name="name" value="{{ $product->name }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Retail Price (£)</label>
                <input type="number" step="0.01" name="price" value="{{ $product->price }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-black">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Discount Price (£)</label>
                <input type="number" step="0.01" name="discount_price" value="{{ $product->discount_price }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Stock Quantity</label>
                <input type="number" name="stock_quantity" value="{{ $product->stock_quantity }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold">
            </div>
        </div>

        <div class="bg-purple-50/50 p-6 rounded-2xl border border-purple-100 space-y-4">
            <label class="flex items-center space-x-2 font-black text-purple-900 uppercase">
                <input type="checkbox" name="is_rental_eligible" value="1" {{ $product->is_rental_eligible ? 'checked' : '' }} class="text-purple-600 rounded">
                <span>Rental Eligible</span>
            </label>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-purple-900 mb-1">Daily (£)</label>
                    <input type="number" step="0.01" name="rental_price_daily" value="{{ $product->rental_price_daily }}" class="w-full bg-white border border-purple-200 rounded-xl p-2.5 font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-purple-900 mb-1">Weekly (£)</label>
                    <input type="number" step="0.01" name="rental_price_weekly" value="{{ $product->rental_price_weekly }}" class="w-full bg-white border border-purple-200 rounded-xl p-2.5 font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-purple-900 mb-1">Monthly (£)</label>
                    <input type="number" step="0.01" name="rental_price_monthly" value="{{ $product->rental_price_monthly }}" class="w-full bg-white border border-purple-200 rounded-xl p-2.5 font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-purple-900 mb-1">Deposit (£)</label>
                    <input type="number" step="0.01" name="rental_security_deposit" value="{{ $product->rental_security_deposit }}" class="w-full bg-white border border-purple-200 rounded-xl p-2.5 font-bold">
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.products.index') }}" class="py-3 px-5 bg-slate-100 text-slate-700 font-bold rounded-xl">Cancel</a>
            <button type="submit" class="py-3 px-6 bg-brand-600 hover:bg-brand-700 text-white font-black rounded-xl shadow-md uppercase">Update Product</button>
        </div>
    </form>
</div>
@endsection
