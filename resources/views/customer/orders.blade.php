@extends('layouts.app')

@section('title', 'My Orders | E-Bike 4 U')

@section('content')
<div class="bg-slate-900 text-white py-10 border-b border-slate-800">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-black text-white"><i class="fa-solid fa-box text-emerald-400 mr-2"></i> My Order History</h1>
    </div>
</div>

<div class="container mx-auto px-4 py-10">
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 uppercase text-[10px] border-b border-slate-100">
                        <th class="pb-3">Order Number</th>
                        <th class="pb-3">Date</th>
                        <th class="pb-3">Order Type</th>
                        <th class="pb-3">Payment</th>
                        <th class="pb-3">Total Amount</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Invoice</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $ord)
                        <tr>
                            <td class="py-4 font-bold text-slate-900">{{ $ord->order_number }}</td>
                            <td class="py-4 text-slate-500">{{ $ord->created_at->format('d M Y') }}</td>
                            <td class="py-4 font-semibold uppercase text-slate-700">{{ $ord->type }}</td>
                            <td class="py-4 uppercase font-bold text-emerald-700">{{ $ord->payment_status }}</td>
                            <td class="py-4 font-black text-slate-900">£{{ number_format($ord->total_amount, 2) }}</td>
                            <td class="py-4"><span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border {{ $ord->status_badge_class }}">{{ str_replace('_', ' ', $ord->status) }}</span></td>
                            <td class="py-4 text-right">
                                <a href="{{ route('customer.order_detail', $ord->order_number) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-900 font-bold rounded-xl text-[11px] transition-colors">
                                    View Receipt &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-slate-400">No sales orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
