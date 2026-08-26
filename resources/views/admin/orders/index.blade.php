@extends('layouts.admin')

@section('title', 'Sales & Rental Orders Lifecycle Manager')

@section('content')
<div class="space-y-6">
    
    <!-- Top Action & Filter Bar -->
    <div class="flex flex-col md:flex-row justify-between items-center bg-white p-5 rounded-3xl border border-borderLight shadow-xs gap-4">
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <a href="{{ route('admin.orders.index') }}" class="px-3.5 py-2 rounded-xl font-bold {{ request('type') == '' && request('expiring') == '' ? 'bg-darkSlate-900 text-white' : 'bg-[#f5f7fb] text-darkSlate-900 hover:bg-slate-200' }}">All Orders</a>
            <a href="{{ route('admin.orders.index', ['type' => 'rental']) }}" class="px-3.5 py-2 rounded-xl font-bold {{ request('type') == 'rental' && request('expiring') == '' ? 'bg-brandOrange-500 text-white' : 'bg-brandOrange-50 text-brandOrange-600' }}">Rental Orders</a>
            <a href="{{ route('admin.orders.index', ['expiring' => '1']) }}" class="px-3.5 py-2 rounded-xl font-bold {{ request('expiring') == '1' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-600 border border-rose-200' }}">
                <i class="fa-solid fa-clock mr-1"></i> Expiring / Overdue Rentals ({{ $expiringCount }})
            </a>
            <a href="{{ route('admin.orders.index', ['type' => 'purchase']) }}" class="px-3.5 py-2 rounded-xl font-bold {{ request('type') == 'purchase' ? 'bg-darkSlate-900 text-white' : 'bg-[#f5f7fb] text-darkSlate-900 hover:bg-slate-200' }}">Sales Orders</a>
        </div>

        <div class="flex items-center space-x-3 w-full sm:w-auto">
            @if($expiringCount > 0)
                <form action="{{ route('admin.orders.send_bulk_expiration_reminders') }}" method="POST" onsubmit="return confirm('Send rental expiration reminders (In-App Notification + Email) to all active/expiring renters?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-brandOrange-500 hover:bg-brandOrange-600 text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center gap-1.5 whitespace-nowrap">
                        <i class="fa-solid fa-paper-plane"></i> Send Reminders to All Expiring Renters
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.orders.index') }}" method="GET" class="w-full sm:w-60">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Order #..." class="w-full bg-[#f5f7fb] border border-borderLight rounded-xl px-3 py-2 text-xs font-semibold text-darkSlate-900">
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-borderLight shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-[#f5f7fb] border-b border-borderLight text-textMuted font-bold uppercase text-[10px]">
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
                        <td class="p-4 font-black font-mono text-darkSlate-900">{{ $ord->order_number }}</td>
                        <td class="p-4">
                            <span class="font-bold text-darkSlate-900 block">{{ $ord->user->name ?? 'Guest Customer' }}</span>
                            <span class="text-[11px] text-textMuted block">{{ $ord->user->email ?? '' }}</span>
                        </td>
                        <td class="p-4 font-bold uppercase text-[10px]">
                            <span class="px-2.5 py-1 rounded-full {{ $ord->type === 'rental' ? 'bg-brandOrange-50 text-brandOrange-600 border border-brandOrange-500/20' : 'bg-slate-100 text-slate-800' }}">
                                {{ $ord->type }}
                            </span>
                        </td>
                        <td class="p-4 font-black text-darkSlate-900">£{{ number_format($ord->total_amount, 2) }}</td>
                        <td class="p-4">
                            <span class="text-[11px] text-emerald-700 font-bold block">Paid: £{{ number_format($ord->advance_amount, 2) }}</span>
                            @if($ord->remaining_amount > 0)
                                <span class="text-[10px] text-brandOrange-600 font-bold block">Due: £{{ number_format($ord->remaining_amount, 2) }}</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase border {{ $ord->status_badge_class }}">
                                {{ str_replace('_', ' ', $ord->status) }}
                            </span>
                        </td>
                        <td class="p-4 text-right space-x-1">
                            @if($ord->type === 'rental' && in_array($ord->status, ['active', 'ready_for_pickup', 'extension_requested', 'overdue']))
                                <form action="{{ route('admin.orders.send_expiration_reminder', $ord->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Send Expiration Reminder (In-App Notification + Email) to {{ $ord->user->email ?? 'customer' }}?');">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-600 hover:text-white rounded-xl text-[10px] font-bold transition-colors" title="Send Expiration Reminder (App + Email)">
                                        <i class="fa-solid fa-bell"></i> Send Reminder
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-3 py-1.5 bg-darkSlate-900 hover:bg-black text-white rounded-xl text-[11px] font-bold transition-colors">
                                Manage Order &rarr;
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4 border-t border-borderLight">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
