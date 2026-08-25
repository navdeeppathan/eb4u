@extends('layouts.admin')

@section('title', 'UK Store & Rental Payment Settings')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-3xl border border-slate-200 shadow-xs space-y-6">
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6 text-xs">
        @csrf

        <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider pb-3 border-b">E-Bike Rental Payment Controls</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Rental Advance Percentage (%)</label>
                <input type="number" step="0.01" name="rental_advance_percentage" value="{{ $settings['rental_advance_percentage'] }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-black">
                <span class="text-[10px] text-slate-400">Default advance amount required online at checkout (e.g. 30%)</span>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Default Security Deposit (£)</label>
                <input type="number" step="0.01" name="default_security_deposit" value="{{ $settings['default_security_deposit'] }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-black">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Standard UK Delivery Charge (£)</label>
                <input type="number" step="0.01" name="default_delivery_charge" value="{{ $settings['default_delivery_charge'] }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Late Fee Charged Per Day (£)</label>
                <input type="number" step="0.01" name="default_late_fee_per_day" value="{{ $settings['default_late_fee_per_day'] }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold text-rose-600">
            </div>
        </div>

        <div class="pt-4 border-t space-y-4">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider pb-2">UK Store Information</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Store Name</label>
                    <input type="text" name="store_name" value="{{ $settings['store_name'] }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Store Phone</label>
                    <input type="text" name="store_phone" value="{{ $settings['store_phone'] }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold">
                </div>
                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Store Address</label>
                    <input type="text" name="store_address" value="{{ $settings['store_address'] }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold">
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="py-3 px-6 bg-brand-600 hover:bg-brand-700 text-white font-black rounded-xl shadow-md uppercase">Save Settings</button>
        </div>
    </form>
</div>
@endsection
