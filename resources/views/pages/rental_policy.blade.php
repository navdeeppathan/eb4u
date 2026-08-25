@extends('layouts.app')

@section('title', 'E-Bike Rental Policy | eb4u')

@section('content')
<!-- Breadcrumb -->
<div class="border-b border-borderLight bg-[#edf1f8] text-xs">
    <div class="max-w-[1320px] mx-auto px-6 py-3 flex items-center gap-2 text-textMuted font-medium">
        <a href="{{ route('home') }}" class="hover:text-darkSlate-900 transition-colors">Home</a>
        <span>/</span>
        <span class="text-darkSlate-900 font-bold">E-Bike Rental Policy</span>
    </div>
</div>

<div class="max-w-[1000px] mx-auto px-6 py-12">
    <div class="bg-white rounded-3xl border border-borderLight shadow-xs p-8 md:p-12 space-y-8">
        <div class="border-b border-borderLight pb-6">
            <span class="bg-brandOrange-50 text-brandOrange-600 text-xs font-bold uppercase px-3.5 py-1.5 rounded-full border border-brandOrange-500/20">
                Official UK Fleet Rental Rules & Guidelines
            </span>
            <h1 class="font-grotesk text-3xl md:text-4xl font-extrabold text-darkSlate-900 mt-3 mb-2">E-Bike Rental Policy</h1>
            <p class="text-xs text-textMuted font-medium">Comprehensive guidelines for daily, weekly, and monthly electric bike rentals at eb4u UK.</p>
        </div>

        <div class="space-y-4">
            <h2 class="font-grotesk text-lg font-bold text-darkSlate-900">1. Rental Duration & Rates</h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                eb4u offers flexible electric bike rentals across three tier options: <strong>Daily</strong>, <strong>Weekly</strong>, and <strong>Monthly</strong>. Tiered discounts are automatically applied to longer rental durations.
            </p>
        </div>

        <div class="space-y-4">
            <h2 class="font-grotesk text-lg font-bold text-darkSlate-900">2. Security Deposit Protection</h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                A refundable security deposit (£150 to £300) is authorized upon vehicle pickup. Deposits are released in full within 24 hours of returning the e-bike in clean, undamaged condition with battery and charger included.
            </p>
        </div>

        <div class="space-y-4">
            <h2 class="font-grotesk text-lg font-bold text-darkSlate-900">3. Rider Responsibilities & Safety</h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                Riders must adhere to all UK Highway Code provisions. Wearing an approved cycling helmet is strongly advised. E-bikes must be locked using the provided Sold Secure Gold rated lock whenever left unattended.
            </p>
        </div>
    </div>
</div>
@endsection
