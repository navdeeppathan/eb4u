@extends('layouts.admin')

@section('title', 'Manage Coupons & Promotions')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Add Coupon Form -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
        <h3 class="text-xs font-black uppercase text-slate-900">Create New Coupon Promo Code</h3>
        <form action="{{ route('admin.promotions.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Coupon Code</label>
                    <input type="text" name="code" placeholder="SAVE20" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-mono font-bold uppercase">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Discount Type</label>
                    <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold">
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount (£)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Discount Amount</label>
                    <input type="number" step="0.01" name="amount" value="10" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-black">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Eligibility Target</label>
                    <select name="target_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold">
                        <option value="all">All Products & Rentals</option>
                        <option value="ebikes">E-Bikes Only</option>
                        <option value="rentals">Rentals Only</option>
                        <option value="accessories">Accessories Only</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="py-2.5 px-6 bg-brand-600 hover:bg-brand-700 text-white font-black rounded-xl uppercase">Create Promo Code</button>
        </form>
    </div>

    <!-- Active Coupons Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                <tr>
                    <th class="p-4">Code</th>
                    <th class="p-4">Discount</th>
                    <th class="p-4">Target</th>
                    <th class="p-4">Used Count</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Toggle</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($coupons as $c)
                    <tr class="hover:bg-slate-50/50">
                        <td class="p-4 font-black font-mono text-slate-900 text-sm">{{ $c->code }}</td>
                        <td class="p-4 font-extrabold text-brand-700">{{ $c->type === 'percentage' ? $c->amount . '%' : '£' . number_format($c->amount, 2) }}</td>
                        <td class="p-4 font-bold uppercase text-[10px] text-slate-600">{{ $c->target_type }}</td>
                        <td class="p-4 font-bold text-slate-800">{{ $c->used_count }} / {{ $c->usage_limit ?? '∞' }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $c->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $c->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <form action="{{ route('admin.promotions.toggle', $c->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs font-bold text-slate-700 hover:text-brand-600">Toggle Active</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
