@extends('layouts.admin')

@section('title', 'E-Bikes & Accessories Catalog Management')

@section('content')
<div class="space-y-6">
    
    <!-- Top Action & Search Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-center bg-white p-5 rounded-3xl border border-slate-200 shadow-xs gap-4">
        <div class="flex items-center space-x-2 text-xs">
            <a href="{{ route('admin.products.index') }}" class="px-3.5 py-2 rounded-xl font-bold {{ request('type') == '' ? 'bg-darkBlack-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">All Products</a>
            <a href="{{ route('admin.products.index', ['type' => 'ebike']) }}" class="px-3.5 py-2 rounded-xl font-bold {{ request('type') == 'ebike' ? 'bg-brandOrange-500 text-white' : 'bg-brandOrange-50 text-brandOrange-600' }}">E-Bikes</a>
            <a href="{{ route('admin.products.index', ['type' => 'accessory']) }}" class="px-3.5 py-2 rounded-xl font-bold {{ request('type') == 'accessory' ? 'bg-darkBlack-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Accessories</a>
        </div>

        <div class="flex items-center space-x-3 w-full sm:w-auto">
            <form action="{{ route('admin.products.index') }}" method="GET" class="w-full sm:w-60">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Name or SKU..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-900">
            </form>

            <a href="{{ route('admin.products.create') }}" class="py-2.5 px-5 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-black text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5 whitespace-nowrap">
                <i class="fa-solid fa-plus"></i> Add New Product
            </a>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                <tr>
                    <th class="p-4">Product</th>
                    <th class="p-4">Type</th>
                    <th class="p-4">Retail Price</th>
                    <th class="p-4">Rental Rates</th>
                    <th class="p-4">Stock</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($products as $p)
                    <tr class="hover:bg-slate-50/50">
                        <td class="p-4 flex items-center space-x-3">
                            <img src="{{ $p->primary_image_url }}" class="w-12 h-12 object-cover rounded-xl border border-slate-200 flex-shrink-0">
                            <div>
                                <span class="font-bold text-slate-900 block leading-snug">{{ $p->name }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">SKU: {{ $p->sku }}</span>
                            </div>
                        </td>
                        <td class="p-4 font-bold uppercase text-[10px]">
                            <span class="px-2.5 py-1 rounded-full {{ $p->type === 'ebike' ? 'bg-brandOrange-50 text-brandOrange-600 border border-brandOrange-500/20' : 'bg-slate-100 text-slate-800' }}">
                                {{ $p->type }}
                            </span>
                        </td>
                        <td class="p-4 font-black text-slate-900">
                            £{{ number_format($p->effective_price, 2) }}
                            @if($p->discount_price)
                                <span class="text-[10px] text-slate-400 line-through block">£{{ number_format($p->price, 2) }}</span>
                            @endif
                        </td>
                        <td class="p-4">
                            @if($p->is_rental_eligible)
                                <span class="text-[11px] font-bold text-brandOrange-600 bg-brandOrange-50 px-2.5 py-1 rounded-xl border border-brandOrange-500/20">
                                    £{{ number_format($p->rental_price_daily, 0) }}/day (£{{ number_format($p->rental_price_weekly, 0) }}/wk)
                                </span>
                            @else
                                <span class="text-slate-400 text-[10px]">N/A</span>
                            @endif
                        </td>
                        <td class="p-4 font-bold text-slate-800">{{ $p->stock_quantity }} units</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $p->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $p->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="p-4 text-right space-x-2">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.products.edit', $p->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl text-[11px] font-bold transition-colors">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                            </a>

                            <!-- Delete Form Button -->
                            <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to permanently delete {{ addslashes($p->name) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-xl text-[11px] font-bold transition-colors">
                                    <i class="fa-solid fa-trash mr-1"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4 border-t border-slate-100">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
