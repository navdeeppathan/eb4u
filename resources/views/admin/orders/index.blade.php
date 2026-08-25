@extends('layouts.admin')

@section('title', 'Sales & Rental Orders Lifecycle Manager')

@section('content')
<div class="space-y-6">
    
    <!-- Filter Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-center bg-white p-4 rounded-3xl border border-slate-200 shadow-xs gap-4">
        <div class="flex items-center space-x-2 text-xs">
            <a href="{{ route('admin.orders.index') }}" class="px-3 py-1.5 rounded-xl font-bold {{ request('type') == '' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }}">All Orders</a>
            <a href="{{ route('admin.orders.index', ['type' => 'rental']) }}" class="px-3 py-1.5 rounded-xl font-bold {{ request('type') == 'rental' ? 'bg-purple-700 text-white' : 'bg-purple-50 text-purple-700' }}">Rental Orders</a>
            <a href="{{ route('admin.orders.index', ['type' => 'purchase']) }}" class="px-3 py-1.5 rounded-xl font-bold {{ request('type') == 'purchase' ? 'bg-emerald-700 text-white' : 'bg-emerald-50 text-emerald-700' }}">Sales Orders</a>
        </div>

        <form action="{{ route('admin.orders.index') }}" method="GET" class="w-full sm:w-64">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Order #..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                <tr>
                    <th class="p-4">Order #</th>
                    <th class="p-4">Customer</th>
                    <th class="p-4">Type</th>
                    <th class="p-4">Total Amount</th>
                    <th class="p-4">Paid / Balance</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($orders as $ord)
                    <tr class="hover:bg-slate-50/50">
                        <td class="p-4 font-black font-mono text-slate-900">{{ $ord->order_number }}</td>
                        <td class="p-4 font-bold text-slate-800">{{ $ord->user->name ?? 'Guest Customer' }}</td>
                        <td class="p-4 font-bold uppercase text-[10px]">
                            <span class="px-2 py-0.5 rounded-full {{ $ord->type === 'rental' ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800' }}">
                                {{ $ord->type }}
                            </span>
                        </td>
                        <td class="p-4 font-black text-slate-900">£{{ number_format($ord->total_amount, 2) }}</td>
                        <td class="p-4">
                            <span class="text-[11px] text-emerald-700 font-bold block">Paid: £{{ number_format($ord->advance_amount, 2) }}</span>
                            @if($ord->remaining_amount > 0)
                                <span class="text-[10px] text-amber-700 font-bold block">Due: £{{ number_format($ord->remaining_amount, 2) }}</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase border {{ $ord->status_badge_class }}">
                                {{ str_replace('_', ' ', $ord->status) }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-[11px] font-bold transition-colors">
                                Manage Order &rarr;
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
