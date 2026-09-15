@extends('layouts.app')

@section('title', 'My Orders | E-Bike 4 U')

@section('content')
<div class="bg-slate-900 text-white py-10 border-b border-slate-800">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-black text-white"><i class="fa-solid fa-box text-emerald-400 mr-2"></i> My Order History</h1>
    </div>
</div>

<div class="container mx-auto px-4 py-10 space-y-3">
    <div class="md:hidden flex items-center justify-between text-[10px] text-brandOrange-700 bg-brandOrange-50 px-3.5 py-2 rounded-2xl border border-brandOrange-200 font-bold shadow-xs">
        <span class="flex items-center gap-1.5"><i class="fa-solid fa-arrows-left-right text-brandOrange-500"></i> Scroll table horizontally to view full details</span>
        <i class="fa-solid fa-hand-pointer text-brandOrange-500 animate-pulse"></i>
    </div>
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs whitespace-nowrap min-w-[750px]">
                <thead>
                    <tr class="text-slate-400 uppercase text-[10px] border-b border-slate-100">
                        <th class="pb-3 whitespace-nowrap">Order Number</th>
                        <th class="pb-3 whitespace-nowrap">Date</th>
                        <th class="pb-3 whitespace-nowrap">Order Type</th>
                        <th class="pb-3 whitespace-nowrap">Payment</th>
                        <th class="pb-3 whitespace-nowrap">Total Amount</th>
                        <th class="pb-3 whitespace-nowrap">Status</th>
                        <th class="pb-3 text-right whitespace-nowrap">Invoice</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $ord)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 font-bold text-slate-900 whitespace-nowrap">{{ $ord->order_number }}</td>
                            <td class="py-4 text-slate-500 whitespace-nowrap">{{ $ord->created_at->format('d M Y') }}</td>
                            <td class="py-4 font-semibold uppercase text-slate-700 whitespace-nowrap">{{ $ord->type }}</td>
                            <td class="py-4 uppercase font-bold text-emerald-700 whitespace-nowrap">{{ $ord->payment_status }}</td>
                            <td class="py-4 font-black text-slate-900 whitespace-nowrap">£{{ number_format($ord->total_amount, 2) }}</td>
                            <td class="py-4 whitespace-nowrap"><span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border {{ $ord->status_badge_class }}">{{ str_replace('_', ' ', $ord->status) }}</span></td>
                            <td class="py-4 text-right whitespace-nowrap">
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

        <!-- Pagination Footer -->
        <div class="mt-6 pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
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
