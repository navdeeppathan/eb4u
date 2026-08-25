@extends('layouts.app')

@section('title', 'Order Confirmation #' . $order->order_number . ' | E-Bike 4 U')

@section('content')
<div class="container mx-auto px-4 py-16 max-w-3xl">
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xl p-8 space-y-8">
        
        <!-- Header status -->
        <div class="text-center space-y-2 pb-6 border-b border-slate-100">
            <div class="w-16 h-16 mx-auto rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl mb-3">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900">Order Confirmed!</h1>
            <p class="text-xs text-slate-500">Thank you for your order. Your official UK tax invoice and rental record have been generated.</p>
            <span class="inline-block bg-slate-100 text-slate-800 text-xs font-mono font-bold px-3 py-1 rounded-full border border-slate-200 mt-2">
                Order Reference: {{ $order->order_number }}
            </span>
        </div>

        <!-- Order Items -->
        <div>
            <h3 class="text-xs font-black uppercase text-slate-900 tracking-wider mb-4">Itemized Summary</h3>
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center bg-slate-50 p-4 rounded-2xl border border-slate-100 text-xs">
                        <div>
                            <span class="font-bold text-slate-900">{{ $item->product_name }} (x{{ $item->quantity }})</span>
                            @if($item->item_type === 'rental')
                                <p class="text-[11px] text-purple-700 font-semibold mt-0.5">
                                    <i class="fa-regular fa-calendar-check mr-1"></i> Rental Period: {{ $item->rental_start_date->format('d M Y') }} - {{ $item->rental_end_date->format('d M Y') }}
                                </p>
                            @endif
                        </div>
                        <span class="font-black text-slate-900">£{{ number_format($item->subtotal, 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Financial Breakdown -->
        <div class="bg-slate-900 text-white p-6 rounded-2xl space-y-3 text-xs">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span class="font-bold">£{{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span>UK VAT (20%)</span>
                <span>£{{ number_format($order->tax_amount, 2) }}</span>
            </div>
            @if($order->security_deposit_total > 0)
                <div class="flex justify-between text-purple-300">
                    <span>Security Deposit (Refundable)</span>
                    <span>£{{ number_format($order->security_deposit_total, 2) }}</span>
                </div>
            @endif
            <div class="border-t border-slate-800 pt-2 flex justify-between text-sm font-black">
                <span>Total Amount</span>
                <span class="text-emerald-400">£{{ number_format($order->total_amount, 2) }}</span>
            </div>
            <div class="flex justify-between text-emerald-300 pt-1 font-bold">
                <span>Amount Paid Online Today:</span>
                <span>£{{ number_format($order->advance_amount, 2) }}</span>
            </div>
            @if($order->remaining_amount > 0)
                <div class="flex justify-between text-amber-300 font-bold">
                    <span>Remaining Balance:</span>
                    <span>£{{ number_format($order->remaining_amount, 2) }}</span>
                </div>
            @endif
        </div>

        <!-- Customer Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 pt-4">
            <a href="{{ route('customer.dashboard') }}" class="flex-1 text-center py-3 bg-brand-600 hover:bg-brand-700 text-white text-xs font-black rounded-xl shadow-md transition-colors">
                Go to Customer Dashboard
            </a>
            <a href="{{ route('home') }}" class="flex-1 text-center py-3 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition-colors">
                Return to Store
            </a>
        </div>

    </div>
</div>
@endsection
