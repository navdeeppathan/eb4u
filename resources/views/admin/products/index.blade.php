@extends('layouts.admin')

@section('title', 'E-Bikes & Accessories Catalog Management')

@section('content')
<div class="space-y-6">
    
    <div class="flex justify-between items-center bg-white p-4 rounded-3xl border border-slate-200 shadow-xs">
        <div class="flex items-center space-x-3 text-xs">
            <a href="{{ route('admin.products.index') }}" class="px-3 py-1.5 rounded-xl font-bold {{ request('type') == '' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }}">All Products</a>
            <a href="{{ route('admin.products.index', ['type' => 'ebike']) }}" class="px-3 py-1.5 rounded-xl font-bold {{ request('type') == 'ebike' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }}">E-Bikes</a>
            <a href="{{ route('admin.products.index', ['type' => 'accessory']) }}" class="px-3 py-1.5 rounded-xl font-bold {{ request('type') == 'accessory' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }}">Accessories</a>
        </div>

        <a href="{{ route('admin.products.create') }}" class="py-2.5 px-5 bg-brand-600 hover:bg-brand-700 text-white font-black text-xs rounded-xl shadow-sm transition-colors">
            <i class="fa-solid fa-plus mr-1"></i> Add New Product
        </a>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                <tr>
                    <th class="p-4">Product</th>
                    <th class="p-4">Type</th>
                    <th class="p-4">Price</th>
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
                            <img src="{{ $p->primary_image_url }}" class="w-12 h-12 object-cover rounded-xl border border-slate-200">
                            <div>
                                <span class="font-bold text-slate-900 block leading-tight">{{ $p->name }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">SKU: {{ $p->sku }}</span>
                            </div>
                        </td>
                        <td class="p-4 font-bold uppercase text-[10px]">
                            <span class="px-2 py-0.5 rounded-full {{ $p->type === 'ebike' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $p->type }}
                            </span>
                        </td>
                        <td class="p-4 font-black text-slate-900">£{{ number_format($p->effective_price, 2) }}</td>
                        <td class="p-4">
                            @if($p->is_rental_eligible)
                                <span class="text-[11px] font-bold text-purple-900 bg-purple-50 px-2 py-1 rounded-lg border border-purple-100">
                                    £{{ number_format($p->rental_price_daily, 0) }}/day (£{{ number_format($p->rental_price_weekly, 0) }}/wk)
                                </span>
                            @else
                                <span class="text-slate-400 text-[10px]">N/A</span>
                            @endif
                        </td>
                        <td class="p-4 font-bold text-slate-800">{{ $p->stock_quantity }} units</td>
                        <td class="p-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $p->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $p->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="p-4 text-right space-x-2">
                            <a href="{{ route('admin.products.edit', $p->id) }}" class="text-slate-600 hover:text-emerald-700 font-bold"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
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
