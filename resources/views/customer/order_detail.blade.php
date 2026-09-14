@extends('layouts.app')

@section('title', 'Order #' . $order->order_number . ' Details | E-Bike 4 U')

@section('content')
<div class="container mx-auto px-4 py-10 max-w-4xl">
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xl p-8 space-y-8">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-100 pb-6 gap-4">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase">UK Official Invoice</span>
                <h1 class="text-2xl font-black text-slate-900">Order #{{ $order->order_number }}</h1>
                <p class="text-xs text-slate-500">Placed on {{ $order->created_at->format('d M Y \a\t H:i') }}</p>
            </div>
            <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase border {{ $order->status_badge_class }}">
                {{ str_replace('_', ' ', $order->status) }}
            </span>
        </div>

        <!-- Verification Documents Section -->
        @if($order->proof_of_id_path || $order->proof_of_address_path)
            <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200/80 space-y-4">
                <h3 class="text-xs font-black uppercase text-slate-900 tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-brand-600"></i> Customer Identity & Verification Documents
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if($order->proof_of_id_path)
                        <div class="p-4 bg-white rounded-2xl border border-slate-200 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-id-card text-brand-600 text-lg"></i>
                                <div>
                                    <span class="font-bold text-xs text-slate-900 block">Proof of Identity (ID)</span>
                                    <span class="text-[10px] text-slate-500">Passport / Visa / License</span>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $order->proof_of_id_path) }}" target="_blank" class="px-3.5 py-1.5 bg-slate-900 hover:bg-black text-white font-bold text-xs rounded-xl flex items-center gap-1 transition-colors">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> View Doc
                            </a>
                        </div>
                    @endif

                    @if($order->proof_of_address_path)
                        <div class="p-4 bg-white rounded-2xl border border-slate-200 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-house-user text-brand-600 text-lg"></i>
                                <div>
                                    <span class="font-bold text-xs text-slate-900 block">UK Proof of Address</span>
                                    <span class="text-[10px] text-slate-500">Utility / Bank Statement</span>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $order->proof_of_address_path) }}" target="_blank" class="px-3.5 py-1.5 bg-slate-900 hover:bg-black text-white font-bold text-xs rounded-xl flex items-center gap-1 transition-colors">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> View Doc
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Items Table -->
        <div>
            <h3 class="text-xs font-black uppercase text-slate-900 tracking-wider mb-4">Purchased / Rented Items</h3>
            <div class="space-y-3">
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center bg-slate-50 p-4 rounded-2xl border border-slate-100 text-xs">
                        <div>
                            <span class="font-bold text-slate-900 text-sm">{{ $item->product_name }}</span>
                            <span class="ml-2 text-[10px] uppercase font-bold px-2 py-0.5 rounded-full {{ $item->item_type === 'rental' ? 'bg-purple-100 text-purple-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $item->item_type === 'rental' ? 'Rental' : 'Sales' }}
                            </span>
                            @if($item->ebikeUnit)
                                <p class="text-xs text-emerald-800 font-semibold mt-1">
                                    <i class="fa-solid fa-qrcode mr-1"></i> Assigned Unit Code: <strong>{{ $item->ebikeUnit->ebike_code }}</strong> (Serial: {{ $item->ebikeUnit->serial_number }})
                                </p>
                            @endif
                        </div>
                        <span class="font-black text-slate-900 text-sm">£{{ number_format($item->subtotal, 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-900 text-white p-6 rounded-3xl text-xs">
            <div class="space-y-2">
                <h4 class="font-black text-slate-300 uppercase tracking-wider text-[10px]">Fulfillment Address</h4>
                <p class="font-bold text-white">{{ $order->shipping_address['name'] ?? auth()->user()->name }}</p>
                <p class="text-slate-400">{{ $order->shipping_address['address_line_1'] ?? '' }}, {{ $order->shipping_address['city'] ?? '' }} {{ $order->shipping_address['postcode'] ?? '' }}</p>
            </div>
            <div class="space-y-2 text-right">
                <div class="flex justify-between"><span>Subtotal:</span> <span class="font-bold">£{{ number_format($order->subtotal, 2) }}</span></div>
                <div class="flex justify-between"><span>VAT (20%):</span> <span>£{{ number_format($order->tax_amount, 2) }}</span></div>
                @if($order->security_deposit_total > 0)
                    <div class="flex justify-between text-purple-300"><span>Deposit:</span> <span>£{{ number_format($order->security_deposit_total, 2) }}</span></div>
                @endif
                <div class="flex justify-between font-black text-sm border-t border-slate-800 pt-2 text-emerald-400"><span>Total:</span> <span>£{{ number_format($order->total_amount, 2) }}</span></div>
                <div class="flex justify-between text-emerald-300 font-bold"><span>Paid Online:</span> <span>£{{ number_format($order->advance_amount, 2) }}</span></div>
                @if($order->remaining_amount > 0)
                    <div class="flex justify-between text-amber-300 font-bold"><span>Balance Due:</span> <span>£{{ number_format($order->remaining_amount, 2) }}</span></div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
