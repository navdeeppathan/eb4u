@extends('layouts.admin')

@section('title', $activeTab === 'purchase' ? 'Selling Orders Management' : 'Rental Orders & Return Management System')

@section('content')
<div class="space-y-6">
    
    <!-- Top Action & Filter Navigation Bar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center bg-white p-5 rounded-3xl border border-borderLight shadow-xs gap-4">
        
        <!-- Navigation Tabs -->
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <a href="{{ route('admin.orders.index', ['type' => 'rental']) }}" class="px-4 py-2.5 rounded-2xl font-black transition-all flex items-center gap-2 {{ $activeTab === 'rental' && request('expiring') == '' ? 'bg-brandOrange-500 text-white shadow-md' : 'bg-brandOrange-50 text-brandOrange-700 hover:bg-brandOrange-100' }}">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Rental Orders</span>
                @if($expiredCount > 0 || $expiringSoonCount > 0)
                    <span class="px-2 py-0.5 text-[10px] font-extrabold rounded-full {{ $expiredCount > 0 ? 'bg-rose-600 text-white' : 'bg-amber-500 text-white' }}">
                        {{ $expiredCount + $expiringSoonCount }} Action Req.
                    </span>
                @endif
            </a>

            <a href="{{ route('admin.orders.index', ['type' => 'purchase']) }}" class="px-4 py-2.5 rounded-2xl font-black transition-all flex items-center gap-2 {{ $activeTab === 'purchase' ? 'bg-darkSlate-900 text-white shadow-md' : 'bg-[#f5f7fb] text-darkSlate-900 hover:bg-slate-200' }}">
                <i class="fa-solid fa-cart-shopping"></i>
                <span>Selling Orders</span>
            </a>

            <a href="{{ route('admin.orders.index', ['type' => 'all']) }}" class="px-4 py-2.5 rounded-2xl font-black transition-all flex items-center gap-2 {{ $activeTab === 'all' ? 'bg-darkSlate-900 text-white shadow-md' : 'bg-[#f5f7fb] text-darkSlate-900 hover:bg-slate-200' }}">
                <i class="fa-solid fa-list-check"></i>
                <span>All Orders</span>
            </a>
        </div>

        <!-- Search & Bulk Action -->
        <div class="flex flex-wrap items-center space-x-3 w-full lg:w-auto">
            @if($activeTab === 'rental' && $expiringCount > 0)
                <form action="{{ route('admin.orders.send_bulk_expiration_reminders') }}" method="POST" onsubmit="return confirm('Send rental expiration reminders (In-App Notification + Email) to all active and expiring renters?');">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-2xl shadow-xs transition-all flex items-center gap-1.5 whitespace-nowrap">
                        <i class="fa-solid fa-paper-plane"></i> Send Bulk Expiration Reminders ({{ $expiringCount }})
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.orders.index') }}" method="GET" class="flex-1 lg:w-60">
                <input type="hidden" name="type" value="{{ $activeTab }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Order #..." class="w-full bg-[#f5f7fb] border border-borderLight rounded-2xl px-3.5 py-2.5 text-xs font-semibold text-darkSlate-900 focus:ring-2 focus:ring-brandOrange-500">
            </form>
        </div>
    </div>

    <!-- Rental Orders Priority Legend & Summary Bar -->
    @if($activeTab === 'rental')
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Expired Rentals Card -->
            <div class="bg-rose-50 border-2 border-rose-200 rounded-3xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase text-rose-700 tracking-wider block">🚨 Expired & Overdue Rentals</span>
                    <span class="text-2xl font-black text-rose-900">{{ $expiredCount }}</span>
                    <span class="text-[11px] text-rose-700 block font-medium">Require Urgent Return / Extension</span>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-rose-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-calendar-xmark"></i>
                </div>
            </div>

            <!-- Expiring Soon Card -->
            <div class="bg-amber-50 border-2 border-amber-200 rounded-3xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase text-amber-700 tracking-wider block">⏰ Expiring Soon (3 Days)</span>
                    <span class="text-2xl font-black text-amber-900">{{ $expiringSoonCount }}</span>
                    <span class="text-[11px] text-amber-700 block font-medium">Reminder Recommended</span>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>

            <!-- Priority Notice -->
            <div class="bg-darkBlack-950 text-white rounded-3xl p-4 flex items-center justify-between border border-darkBlack-800">
                <div>
                    <span class="text-[10px] font-black uppercase text-brandOrange-500 tracking-wider block">⚡ Smart Sorting Active</span>
                    <span class="text-xs font-bold text-slate-200 block mt-1">Expired (RED) & Expiring (ORANGE) orders automatically pinned to the TOP!</span>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-brandOrange-500 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-arrow-down-short-wide"></i>
                </div>
            </div>
        </div>
    @endif

    <!-- Mobile Swipe Scroll Hint Badge -->
    <div class="xl:hidden flex items-center justify-between text-[11px] text-brandOrange-700 bg-brandOrange-50 px-4 py-2.5 rounded-2xl border border-brandOrange-200 font-bold shadow-xs">
        <span class="flex items-center gap-2"><i class="fa-solid fa-arrows-left-right text-brandOrange-500 text-sm"></i> Swipe table horizontally to view all columns & return actions</span>
        <i class="fa-solid fa-hand-pointer text-brandOrange-500 animate-pulse"></i>
    </div>

    <!-- Orders Table Container -->
    <div class="bg-white rounded-3xl border border-borderLight shadow-xs overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs whitespace-nowrap min-w-[1150px]">
                <thead class="bg-[#f5f7fb] border-b border-borderLight text-textMuted font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-4 whitespace-nowrap">Order #</th>
                        <th class="p-4 whitespace-nowrap">Customer</th>
                        @if($activeTab === 'rental')
                            <th class="p-4 whitespace-nowrap">Assigned Unit</th>
                            <th class="p-4 whitespace-nowrap">Start Date</th>
                            <th class="p-4 whitespace-nowrap">Expire Date</th>
                            <th class="p-4 whitespace-nowrap">Bike Return Status</th>
                        @else
                            <th class="p-4 whitespace-nowrap">Type</th>
                            <th class="p-4 whitespace-nowrap">Fulfillment</th>
                        @endif
                        <th class="p-4 whitespace-nowrap">Total & Balance</th>
                        <th class="p-4 whitespace-nowrap">Order Status</th>
                        <th class="p-4 text-right whitespace-nowrap">Return & Order Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $ord)
                        @php
                            $isReturned = in_array($ord->status, ['returned', 'completed', 'cancelled']);
                            $isExpired = $ord->type === 'rental' && !$isReturned && $ord->rental_end_date && $ord->rental_end_date->isPast();
                            $isExpiringSoon = $ord->type === 'rental' && !$isReturned && $ord->rental_end_date && !$isExpired && $ord->rental_end_date->diffInDays(now()) <= 3;
                            
                            $rowClass = 'hover:bg-slate-50/50';
                            if ($ord->type === 'rental') {
                                if ($isExpired) {
                                    $rowClass = 'bg-rose-50/80 hover:bg-rose-100/80 border-l-4 border-rose-600';
                                } elseif ($isExpiringSoon) {
                                    $rowClass = 'bg-amber-50/80 hover:bg-amber-100/80 border-l-4 border-amber-500';
                                } elseif ($isReturned) {
                                    $rowClass = 'bg-emerald-50/40 hover:bg-emerald-50/70 border-l-4 border-emerald-500';
                                }
                            }
                        @endphp
                        <tr class="{{ $rowClass }} transition-colors">
                            <!-- Order Number -->
                            <td class="p-4 font-black font-mono text-darkSlate-900 whitespace-nowrap">
                                <span class="block text-sm font-bold">{{ $ord->order_number }}</span>
                                <span class="text-[10px] text-textMuted font-normal block mt-0.5">{{ $ord->created_at ? $ord->created_at->format('d M Y, H:i') : '' }}</span>
                            </td>

                            <!-- Customer Info -->
                            <td class="p-4 whitespace-nowrap">
                                <span class="font-bold text-darkSlate-900 block text-xs">{{ $ord->user->name ?? 'Guest Customer' }}</span>
                                <span class="text-[11px] text-textMuted block mb-1">{{ $ord->user->email ?? 'N/A' }}</span>
                                @if($ord->proof_of_id_path || $ord->proof_of_address_path)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md border border-emerald-200" title="Proof of ID & Address verified">
                                        <i class="fa-solid fa-file-shield text-emerald-600"></i> Docs Verified
                                    </span>
                                @elseif($ord->type === 'rental')
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded-md border border-amber-200">
                                        <i class="fa-solid fa-circle-exclamation text-amber-600"></i> Pending Docs
                                    </span>
                                @endif
                            </td>

                            @if($activeTab === 'rental')
                                <!-- Assigned Unit -->
                                <td class="p-4 whitespace-nowrap">
                                    @php $rentalItem = $ord->items->where('item_type', 'rental')->first(); @endphp
                                    @if($rentalItem && $rentalItem->ebikeUnit)
                                        <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-800 text-[11px] font-bold px-2.5 py-1 rounded-xl border border-emerald-200">
                                            <i class="fa-solid fa-qrcode text-emerald-600"></i> {{ $rentalItem->ebikeUnit->ebike_code }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-800 text-[10px] font-bold px-2.5 py-1 rounded-xl border border-amber-200">
                                            <i class="fa-solid fa-circle-question"></i> Unassigned
                                        </span>
                                    @endif
                                </td>

                                <!-- Start Date -->
                                <td class="p-4 font-bold text-darkSlate-900 whitespace-nowrap">
                                    {{ $ord->rental_start_date ? $ord->rental_start_date->format('d M Y') : 'N/A' }}
                                </td>

                                <!-- Expire Date -->
                                <td class="p-4 font-bold whitespace-nowrap">
                                    @if($ord->rental_end_date)
                                        <span class="{{ $isExpired ? 'text-rose-700 font-black underline' : ($isExpiringSoon ? 'text-amber-700 font-black' : 'text-darkSlate-900') }}">
                                            {{ $ord->rental_end_date->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">N/A</span>
                                    @endif
                                </td>

                                <!-- Bike Return Status -->
                                <td class="p-4 whitespace-nowrap">
                                    @if($isReturned)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase text-emerald-800 bg-emerald-100 border border-emerald-300">
                                            <i class="fa-solid fa-circle-check text-emerald-600"></i> Bike Returned
                                        </span>
                                    @elseif($isExpired)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase text-rose-800 bg-rose-100 border border-rose-300 animate-pulse">
                                            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> Expired - Bike Outstanding
                                        </span>
                                    @elseif($isExpiringSoon)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase text-amber-800 bg-amber-100 border border-amber-300">
                                            <i class="fa-solid fa-clock text-amber-600"></i> Expiring Soon - Not Returned
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase text-blue-800 bg-blue-100 border border-blue-300">
                                            <i class="fa-solid fa-bicycle text-blue-600"></i> Active Rental - Bike Outstanding
                                        </span>
                                    @endif
                                </td>
                            @else
                                <td class="p-4 font-bold uppercase text-[10px] whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-800 border border-slate-300">
                                        {{ $ord->type }}
                                    </span>
                                </td>
                                <td class="p-4 font-semibold text-slate-600 uppercase text-[10px] whitespace-nowrap">
                                    {{ str_replace('_', ' ', $ord->fulfillment_type ?? 'delivery') }}
                                </td>
                            @endif

                            <!-- Total & Balance -->
                            <td class="p-4 whitespace-nowrap">
                                <span class="font-black text-darkSlate-900 block text-xs">£{{ number_format($ord->total_amount, 2) }}</span>
                                <div class="flex items-center gap-1 mt-0.5">
                                    @if($ord->payment_status === 'paid')
                                        <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[9px] font-black uppercase">PAID</span>
                                    @elseif($ord->payment_status === 'partially_paid')
                                        <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-[9px] font-black uppercase">PARTIAL (£{{ number_format($ord->advance_amount, 2) }})</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-800 text-[9px] font-black uppercase">UNPAID</span>
                                    @endif

                                    @php
                                        $firstPm = $ord->payments ? $ord->payments->first() : null;
                                        $methodName = $firstPm ? $firstPm->payment_method : 'card';
                                    @endphp
                                    @if(in_array($methodName, ['cash_on_pickup', 'cash']))
                                        <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 text-[9px] font-bold uppercase"><i class="fa-solid fa-money-bill-wave"></i> Pay at Store</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200 text-[9px] font-bold uppercase"><i class="fa-solid fa-credit-card"></i> Card</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Order Status Badge -->
                            <td class="p-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase border {{ $ord->status_badge_class }}">
                                    {{ str_replace('_', ' ', $ord->status) }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="p-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end space-x-1.5">
                                    <!-- Mark Bike Returned Quick Action Button -->
                                    @if($ord->type === 'rental' && !$isReturned)
                                        <form action="{{ route('admin.orders.mark_returned', $ord->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Confirm vehicle return for Order #{{ $ord->order_number }}? This will release the physical E-Bike unit to Available.');">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[10px] font-black shadow-xs transition-colors flex items-center gap-1" title="Mark Bike as Returned & Free Unit">
                                                <i class="fa-solid fa-rotate-left"></i> Mark Returned
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Expiration Reminder Button -->
                                    @if($ord->type === 'rental' && !$isReturned)
                                        <form action="{{ route('admin.orders.send_expiration_reminder', $ord->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Send Expiration Reminder (In-App Notification + Email) to {{ $ord->user->email ?? 'customer' }}?');">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 bg-amber-50 text-amber-700 border border-amber-300 hover:bg-amber-500 hover:text-white rounded-xl text-[10px] font-bold transition-colors" title="Send Expiration Reminder Notice">
                                                <i class="fa-solid fa-bell"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Manage Link -->
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-3 py-1.5 bg-darkSlate-900 hover:bg-black text-white rounded-xl text-[11px] font-bold transition-colors">
                                        Manage &rarr;
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-400 font-medium text-xs">
                                No orders found matching the selected filter criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Enhanced Responsive Pagination Footer -->
        <div class="p-4 bg-[#fafbfc] border-t border-borderLight flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs text-slate-600 font-medium">
                Showing <strong class="text-slate-900 font-black">{{ $orders->firstItem() ?? 0 }}</strong> to <strong class="text-slate-900 font-black">{{ $orders->lastItem() ?? 0 }}</strong> of <strong class="text-slate-900 font-black">{{ $orders->total() }}</strong> total orders
                @if($orders->lastPage() > 1)
                    <span class="ml-1 text-[11px] text-brandOrange-600 font-bold">(Page {{ $orders->currentPage() }} of {{ $orders->lastPage() }})</span>
                @endif
            </div>

            <div>
                @if($orders->hasPages())
                    {{ $orders->appends(request()->query())->links() }}
                @else
                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs font-extrabold bg-slate-100 text-slate-600 border border-slate-200">
                        Page 1 of 1
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
