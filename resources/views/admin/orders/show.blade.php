@extends('layouts.admin')

@section('title', 'Manage Order #' . $order->order_number)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    
    <!-- Order Header Bar -->
    <div class="bg-white p-6 rounded-3xl border border-borderLight shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="text-xs text-textMuted font-bold uppercase">Order Reference</span>
            <h1 class="font-grotesk text-2xl font-extrabold text-darkSlate-900">{{ $order->order_number }}</h1>
            <p class="text-xs text-textSec">Customer: <strong>{{ $order->user->name ?? 'Guest' }}</strong> ({{ $order->user->email ?? 'N/A' }})</p>
        </div>

        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex items-center space-x-2">
            @csrf
            <select name="status" class="bg-[#f5f7fb] border border-borderLight rounded-xl p-2.5 text-xs font-bold text-darkSlate-900 focus:ring-2 focus:ring-brandOrange-500">
                @if($order->type === 'rental')
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="ready_for_pickup" {{ $order->status == 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                    <option value="picked_up" {{ $order->status == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                    <option value="active" {{ $order->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="extension_requested" {{ $order->status == 'extension_requested' ? 'selected' : '' }}>Extension Requested</option>
                    <option value="return_requested" {{ $order->status == 'return_requested' ? 'selected' : '' }}>Return Requested</option>
                    <option value="returned" {{ $order->status == 'returned' ? 'selected' : '' }}>Returned (Frees Bike)</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed (Frees Bike)</option>
                    <option value="overdue" {{ $order->status == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                @else
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="packed" {{ $order->status == 'packed' ? 'selected' : '' }}>Packed</option>
                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                @endif
            </select>
            <button type="submit" class="py-2.5 px-4 bg-brandOrange-500 hover:bg-brandOrange-600 text-white text-xs font-bold rounded-xl transition-colors">Update Status</button>
        </form>
    </div>

    <!-- Rental Lifecycle & Bike Return Management Card -->
    @if($order->type === 'rental')
        @php
            $isReturned = in_array($order->status, ['returned', 'completed', 'cancelled']);
            $isExpired = !$isReturned && $order->rental_end_date && $order->rental_end_date->isPast();
            $isExpiringSoon = !$isReturned && $order->rental_end_date && !$isExpired && $order->rental_end_date->diffInDays(now()) <= 3;
        @endphp
        <div class="p-6 rounded-3xl border shadow-xs space-y-4 {{ $isExpired ? 'bg-rose-50 border-rose-300' : ($isExpiringSoon ? 'bg-amber-50 border-amber-300' : ($isReturned ? 'bg-emerald-50 border-emerald-300' : 'bg-white border-borderLight')) }}">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-4 border-b border-black/10 gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-lg shadow-sm {{ $isExpired ? 'bg-rose-600 text-white' : ($isReturned ? 'bg-emerald-600 text-white' : 'bg-brandOrange-500 text-white') }}">
                        <i class="fa-solid fa-bicycle"></i>
                    </div>
                    <div>
                        <h3 class="font-grotesk text-sm font-extrabold uppercase text-darkSlate-900">E-Bike Return & Rental Management Status</h3>
                        <p class="text-xs text-textMuted">Track rental schedule, bike return status, and physical vehicle return verification.</p>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    @if($isReturned)
                        <span class="px-3 py-1.5 rounded-full text-xs font-black uppercase bg-emerald-100 text-emerald-800 border border-emerald-300">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i> Bike Returned ({{ $order->actual_return_date ? $order->actual_return_date->format('d M Y') : 'Completed' }})
                        </span>
                    @else
                        <form action="{{ route('admin.orders.mark_returned', $order->id) }}" method="POST" onsubmit="return confirm('Mark bike as RETURNED for Order #{{ $order->order_number }}? This will set physical E-Bike unit to Available.');">
                            @csrf
                            <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs rounded-xl shadow-md transition-colors flex items-center gap-1.5">
                                <i class="fa-solid fa-rotate-left"></i> Mark Bike as Returned
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-semibold">
                <div class="bg-white/80 p-3.5 rounded-2xl border border-slate-200">
                    <span class="text-[10px] text-textMuted uppercase font-bold block">Rental Start Date</span>
                    <span class="text-sm font-extrabold text-darkSlate-900">{{ $order->rental_start_date ? $order->rental_start_date->format('d M Y') : 'N/A' }}</span>
                </div>
                <div class="bg-white/80 p-3.5 rounded-2xl border border-slate-200">
                    <span class="text-[10px] text-textMuted uppercase font-bold block">Rental Expiration Date</span>
                    <span class="text-sm font-extrabold {{ $isExpired ? 'text-rose-700 underline' : 'text-darkSlate-900' }}">
                        {{ $order->rental_end_date ? $order->rental_end_date->format('d M Y') : 'N/A' }}
                    </span>
                </div>
                <div class="bg-white/80 p-3.5 rounded-2xl border border-slate-200">
                    <span class="text-[10px] text-textMuted uppercase font-bold block">Security Deposit Held</span>
                    <span class="text-sm font-extrabold text-emerald-700">£{{ number_format($order->security_deposit_total, 2) }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Verification Documents (Admin Inspection) -->
    @if($order->proof_of_id_path || $order->proof_of_address_path)
        <div class="bg-white p-6 rounded-3xl border border-borderLight shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-borderLight">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h3 class="font-grotesk text-sm font-bold text-darkSlate-900 uppercase">Customer Verification Documents</h3>
                        <p class="text-xs text-textMuted">Uploaded at checkout for identity and address verification.</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                    <i class="fa-solid fa-check-double"></i> Verified at Checkout
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($order->proof_of_id_path)
                    <div class="p-4 bg-[#f5f7fb] rounded-2xl border border-borderLight flex items-center justify-between text-xs">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-id-card text-brandOrange-500 text-xl"></i>
                            <div>
                                <span class="font-bold text-darkSlate-900 block">Proof of Identity (Photo ID)</span>
                                <span class="text-[10px] text-textMuted">Passport / Visa / BRP / License</span>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $order->proof_of_id_path) }}" target="_blank" class="px-3.5 py-2 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-bold rounded-xl text-xs flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-download"></i> View / Download
                        </a>
                    </div>
                @endif

                @if($order->proof_of_address_path)
                    <div class="p-4 bg-[#f5f7fb] rounded-2xl border border-borderLight flex items-center justify-between text-xs">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-house-user text-brandOrange-500 text-xl"></i>
                            <div>
                                <span class="font-bold text-darkSlate-900 block">UK Proof of Address</span>
                                <span class="text-[10px] text-textMuted">Utility Bill / Bank Statement</span>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $order->proof_of_address_path) }}" target="_blank" class="px-3.5 py-2 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-bold rounded-xl text-xs flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-download"></i> View / Download
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Rental Expiration Reminder Admin Action Panel -->
    @if($order->type === 'rental' || $order->type === 'mixed')
        <div class="bg-white p-6 rounded-3xl border border-brandOrange-500/30 shadow-xs space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-borderLight">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <div>
                        <h3 class="font-grotesk text-sm font-bold text-darkSlate-900 uppercase">Send Rental Expiration Reminder</h3>
                        <p class="text-xs text-textMuted">Dispatches an instant In-App Notification and HTML Email to <strong>{{ $order->user->email ?? 'Renter' }}</strong>.</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-brandOrange-600 bg-brandOrange-50 px-3 py-1 rounded-full border border-brandOrange-500/20">
                    Email + In-App Sync
                </span>
            </div>

            <form action="{{ route('admin.orders.send_expiration_reminder', $order->id) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-textSec mb-1">Custom Note / Store Message (Optional)</label>
                    <input type="text" name="custom_note" placeholder="e.g. Please bring charger when returning, or extend online to avoid late fee." class="w-full text-xs bg-[#f5f7fb] border border-borderLight rounded-xl p-3 font-semibold text-darkSlate-900 focus:ring-2 focus:ring-brandOrange-500">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="py-3 px-6 bg-brandOrange-500 hover:bg-brandOrange-600 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Send Reminder Notice (Email & In-App)
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Order Items & Physical Unit Assignment -->
    <div class="bg-white p-6 rounded-3xl border border-borderLight shadow-xs space-y-4">
        <h3 class="font-grotesk text-xs font-extrabold uppercase text-darkSlate-900 tracking-wider pb-3 border-b border-borderLight">Order Items & Physical E-Bike Allocation</h3>

        <div class="space-y-4">
            @foreach($order->items as $item)
                <div class="p-4 rounded-2xl bg-[#f5f7fb] border border-borderLight flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-xs">
                    <div>
                        <span class="font-grotesk font-bold text-darkSlate-900 text-sm">{{ $item->product_name }}</span>
                        @if($item->item_type === 'rental')
                            <p class="text-brandOrange-600 font-semibold mt-0.5">
                                <i class="fa-regular fa-calendar-check mr-1"></i> Rental Period: {{ $item->rental_start_date ? $item->rental_start_date->format('d M Y') : 'N/A' }} - {{ $item->rental_end_date ? $item->rental_end_date->format('d M Y') : 'N/A' }}
                            </p>
                        @endif
                    </div>

                    <!-- Assign Unit Form for Rental Items -->
                    @if($item->item_type === 'rental')
                        <div class="flex items-center space-x-2">
                            @if($item->ebikeUnit)
                                <span class="bg-emerald-50 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-xl border border-emerald-200">
                                    <i class="fa-solid fa-qrcode mr-1"></i> {{ $item->ebikeUnit->ebike_code }} (SN: {{ $item->ebikeUnit->serial_number }})
                                </span>
                            @else
                                <form action="{{ route('admin.orders.assign_unit', $item->id) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    <select name="ebike_unit_id" required class="bg-white border border-borderLight rounded-xl p-2 text-xs font-bold text-darkSlate-900">
                                        <option value="">-- Assign Physical E-Bike Unit --</option>
                                        @foreach($availableUnits as $unit)
                                            @if($unit->product_id === $item->product_id)
                                                <option value="{{ $unit->id }}">{{ $unit->ebike_code }} (SN: {{ $unit->serial_number }})</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <button type="submit" class="py-2 px-3 bg-darkSlate-900 hover:bg-black text-white font-bold rounded-xl text-xs">Assign</button>
                                </form>
                            @endif
                        </div>
                    @endif

                    <span class="font-grotesk font-extrabold text-darkSlate-900 text-sm">£{{ number_format($item->subtotal, 2) }}</span>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
