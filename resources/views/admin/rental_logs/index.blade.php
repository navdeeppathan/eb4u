@extends('layouts.admin')

@section('title', 'Rental Products Logs & Reports')

@section('content')
<div class="space-y-6">

    <!-- Page Title & Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Rental Products Logs & Reports</h1>
            <p class="text-xs text-slate-500 mt-1">Manage rental product histories, operational statuses, revenue earnings, shop repair expenses, and customer damage liabilities.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.fleet.index') }}" class="px-4 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                <i class="fa-solid fa-barcode mr-1.5"></i> Manage Fleet Serials
            </a>
            <a href="{{ route('admin.orders.index', ['type' => 'rental']) }}" class="px-4 py-2.5 rounded-2xl bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-bold text-xs shadow-md transition-colors">
                <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> Rental Orders
            </a>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

        <!-- Total Rental Products -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Rental Products</span>
                <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $totalRentalProducts }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-brandOrange-500 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-bicycle"></i>
            </div>
        </div>

        <!-- Total Rentals Count -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Times Rented</span>
                <span class="text-2xl font-black text-emerald-600 mt-1 block">{{ $totalRentalOrders }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-arrows-rotate"></i>
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Revenue</span>
                <span class="text-2xl font-black text-slate-900 mt-1 block">£{{ number_format($totalRentalEarnings, 2) }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-sterling-sign"></i>
            </div>
        </div>

        <!-- Repair & Maintenance Cost -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Repair & Maintenance</span>
                <span class="text-2xl font-black text-rose-600 mt-1 block">£{{ number_format($totalDamageRepairCost, 2) }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-wrench"></i>
            </div>
        </div>

        <!-- User Liability Pending -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">User Liability (Pending)</span>
                <span class="text-2xl font-black text-amber-600 mt-1 block">£{{ number_format($totalUserChargesPending, 2) }}</span>
                <span class="text-[10px] text-slate-400 font-semibold block">Paid: £{{ number_format($totalUserChargesPaid, 2) }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
        </div>

    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.rental_logs.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 items-center justify-between">
            <div class="relative w-full md:w-96">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search product name..." class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-slate-200 text-xs font-semibold focus:outline-none focus:border-brandOrange-500 transition-colors">
            </div>
            <div class="flex items-center space-x-2 w-full md:w-auto">
                <button type="submit" class="px-5 py-2.5 rounded-2xl bg-darkBlack-900 text-white font-bold text-xs hover:bg-black transition-colors">
                    Filter Results
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.rental_logs.index') }}" class="px-4 py-2.5 rounded-2xl bg-slate-100 text-slate-600 font-bold text-xs hover:bg-slate-200 transition-colors">
                        Clear Filter
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Rental Products Logs Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-base font-black text-slate-900">Rental Fleet Products & Performance Logs</h3>
            <span class="text-xs font-semibold text-slate-400">Showing {{ $products->total() }} Rental Models</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <th class="p-4">Product Info</th>
                        <th class="p-4">Weekly Rate / Deposit</th>
                        <th class="p-4">Fleet Units Status</th>
                        <th class="p-4">Times Rented</th>
                        <th class="p-4">Total Revenue</th>
                        <th class="p-4">Repair & Maintenance</th>
                        <th class="p-4">User Liability</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-2xl object-cover border border-slate-200">
                                    <div>
                                        <a href="{{ route('admin.rental_logs.show', $product->id) }}" class="font-bold text-slate-900 hover:text-brandOrange-500 transition-colors line-clamp-1">
                                            {{ $product->name }}
                                        </a>
                                        <span class="text-[10px] text-slate-400 font-semibold block">SKU: PROD-{{ $product->id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="font-extrabold text-slate-900 block">£{{ number_format($product->rental_price_weekly ?? 0, 2) }} / wk</span>
                                <span class="text-[10px] text-slate-500 block">Deposit: £{{ number_format($product->rental_security_deposit ?? 250, 2) }}</span>
                            </td>
                            <td class="p-4">
                                <div class="flex flex-wrap gap-1.5 max-w-[200px]">
                                    <span class="px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-100" title="Available">
                                        {{ $product->available_units_count }} Avail
                                    </span>
                                    <span class="px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 text-[10px] font-bold border border-blue-100" title="Rented">
                                        {{ $product->rented_units_count }} Rented
                                    </span>
                                    @if($product->maintenance_units_count > 0)
                                        <span class="px-2 py-0.5 rounded-lg bg-rose-50 text-rose-700 text-[10px] font-bold border border-rose-100" title="Maintenance / Damaged">
                                            {{ $product->maintenance_units_count }} Maint/Dmg
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[10px] text-slate-400 font-semibold block mt-1">Total Fleet: {{ $product->total_units_count }} Units</span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-800 font-black text-xs">
                                    {{ $product->rental_history_count }} Rents
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="font-extrabold text-emerald-600 block">£{{ number_format($product->total_earnings, 2) }}</span>
                            </td>
                            <td class="p-4">
                                <span class="font-extrabold text-rose-600 block">£{{ number_format($product->total_repair_cost, 2) }}</span>
                            </td>
                            <td class="p-4">
                                @if($product->user_pending_charges > 0)
                                    <span class="font-extrabold text-amber-600 block">£{{ number_format($product->user_pending_charges, 2) }} Pending</span>
                                @else
                                    <span class="text-slate-400 text-[11px] block">No Pending</span>
                                @endif
                                @if($product->user_paid_charges > 0)
                                    <span class="text-[10px] text-emerald-600 font-bold block">£{{ number_format($product->user_paid_charges, 2) }} Paid</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <a href="{{ route('admin.rental_logs.show', $product->id) }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-darkBlack-900 hover:bg-brandOrange-500 text-white font-bold text-xs transition-colors">
                                    <i class="fa-solid fa-chart-pie text-[10px]"></i>
                                    <span>Logs & Report</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400 font-semibold">
                                No rental products found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
