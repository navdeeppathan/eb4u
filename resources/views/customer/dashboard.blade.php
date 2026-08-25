@extends('layouts.app')

@section('title', 'Customer Dashboard | E-Bike 4 U')

@section('content')
<div class="bg-slate-900 text-white py-10 border-b border-slate-800">
    <div class="container mx-auto px-4 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-white">Welcome back, {{ $user->name }}</h1>
            <p class="text-xs text-slate-400 mt-1">Manage your active E-Bike rentals, sales orders, wishlist & profile.</p>
        </div>
        <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 text-xs font-bold px-3 py-1 rounded-full">
            UK Customer Account
        </span>
    </div>
</div>

<div class="container mx-auto px-4 py-10">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <a href="{{ route('customer.rentals') }}" class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-lg mb-3">
                <i class="fa-solid fa-bicycle"></i>
            </div>
            <span class="block text-2xl font-black text-slate-900">{{ $activeRentals->count() }}</span>
            <span class="text-xs text-slate-500 font-semibold">Active E-Bike Rentals</span>
        </a>

        <a href="{{ route('customer.orders') }}" class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-lg mb-3">
                <i class="fa-solid fa-box"></i>
            </div>
            <span class="block text-2xl font-black text-slate-900">{{ $totalOrdersCount }}</span>
            <span class="text-xs text-slate-500 font-semibold">Total Orders Placed</span>
        </a>

        <a href="{{ route('customer.wishlist') }}" class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-lg mb-3">
                <i class="fa-solid fa-heart"></i>
            </div>
            <span class="block text-2xl font-black text-slate-900">{{ $wishlistCount }}</span>
            <span class="text-xs text-slate-500 font-semibold">Wishlist Items</span>
        </a>

        <a href="{{ route('customer.profile') }}" class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg mb-3">
                <i class="fa-solid fa-user"></i>
            </div>
            <span class="block text-xs font-bold text-slate-900 mt-2">Manage Profile</span>
            <span class="text-[10px] text-slate-400">Addresses & Password</span>
        </a>
    </div>

    <!-- Active Rentals Section -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm mb-8">
        <div class="flex justify-between items-center mb-6 pb-3 border-b border-slate-100">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider"><i class="fa-solid fa-bicycle text-purple-600 mr-2"></i> Active & Upcoming E-Bike Rentals</h3>
            <a href="{{ route('customer.rentals') }}" class="text-xs font-bold text-purple-700 hover:underline">View All Rentals &rarr;</a>
        </div>

        @if($activeRentals->isEmpty() && $upcomingRentals->isEmpty())
            <p class="text-xs text-slate-400 text-center py-6">You currently have no active or upcoming E-Bike rentals.</p>
        @else
            <div class="space-y-4">
                @foreach($activeRentals as $rental)
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-purple-50/50 p-4 rounded-2xl border border-purple-100 gap-4">
                        <div>
                            <span class="text-xs font-bold text-purple-900">Order #{{ $rental->order_number }}</span>
                            <span class="ml-2 text-[10px] font-bold uppercase px-2 py-0.5 rounded-full {{ $rental->status_badge_class }}">{{ str_replace('_', ' ', $rental->status) }}</span>
                            <p class="text-xs text-slate-600 mt-1">
                                Rental Period: <strong>{{ $rental->rental_start_date ? $rental->rental_start_date->format('d M Y') : 'N/A' }}</strong> to <strong>{{ $rental->rental_end_date ? $rental->rental_end_date->format('d M Y') : 'N/A' }}</strong>
                            </p>
                        </div>
                        <a href="{{ route('customer.rentals') }}" class="px-4 py-2 bg-purple-700 text-white rounded-xl text-xs font-bold hover:bg-purple-800 transition-colors">
                            Manage Rental / Extend / Return
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Recent Sales Orders -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
        <div class="flex justify-between items-center mb-6 pb-3 border-b border-slate-100">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider"><i class="fa-solid fa-box text-brand-600 mr-2"></i> Recent Order History</h3>
            <a href="{{ route('customer.orders') }}" class="text-xs font-bold text-brand-700 hover:underline">View Full History &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 uppercase text-[10px] border-b border-slate-100">
                        <th class="pb-3">Order Number</th>
                        <th class="pb-3">Date</th>
                        <th class="pb-3">Type</th>
                        <th class="pb-3">Total Amount</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recentOrders as $ord)
                        <tr>
                            <td class="py-3 font-bold text-slate-900">{{ $ord->order_number }}</td>
                            <td class="py-3 text-slate-500">{{ $ord->created_at->format('d M Y') }}</td>
                            <td class="py-3 uppercase font-semibold text-slate-600">{{ $ord->type }}</td>
                            <td class="py-3 font-black text-slate-900">£{{ number_format($ord->total_amount, 2) }}</td>
                            <td class="py-3"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $ord->status_badge_class }}">{{ str_replace('_', ' ', $ord->status) }}</span></td>
                            <td class="py-3 text-right">
                                <a href="{{ route('customer.order_detail', $ord->order_number) }}" class="text-brand-600 font-bold hover:underline">View Invoice &rarr;</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
