@extends('layouts.app')

@section('title', 'E-Bike Rental Policy | eb4u UK')

@section('content')
<!-- Breadcrumb -->
<div class="border-b border-borderLight bg-[#edf1f8] text-xs">
    <div class="max-w-[1320px] mx-auto px-6 py-3 flex items-center gap-2 text-textMuted font-medium">
        <a href="{{ route('home') }}" class="hover:text-darkSlate-900 transition-colors">Home</a>
        <span>/</span>
        <span class="text-darkSlate-900 font-bold">E-Bike Rental Policy</span>
    </div>
</div>

<div class="max-w-[1050px] mx-auto px-6 py-12">
    <div class="bg-white rounded-3xl border border-borderLight shadow-xs p-8 md:p-12 space-y-10">
        
        <div class="border-b border-borderLight pb-8">
            <span class="bg-brandOrange-50 text-brandOrange-600 text-xs font-bold uppercase px-3.5 py-1.5 rounded-full border border-brandOrange-500/20">
                Official UK Fleet Rental Rules & Policy
            </span>
            <h1 class="font-grotesk text-3xl md:text-5xl font-extrabold text-darkSlate-900 mt-3 mb-2 tracking-tight">E-Bike Rental Policy</h1>
            <p class="text-xs md:text-sm text-textMuted font-medium">Comprehensive guidelines for weekly e-bike rentals at eb4u UK.</p>

            <!-- Quick Summary Badges -->
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="p-3.5 bg-[#f8fafc] rounded-2xl border border-slate-200 text-center">
                    <div class="text-xs text-textMuted font-medium uppercase tracking-wider">Min. Duration</div>
                    <div class="text-sm font-extrabold text-darkSlate-900 mt-0.5">2 Weeks Minimum</div>
                </div>
                <div class="p-3.5 bg-[#f8fafc] rounded-2xl border border-slate-200 text-center">
                    <div class="text-xs text-textMuted font-medium uppercase tracking-wider">Deposit</div>
                    <div class="text-sm font-extrabold text-darkSlate-900 mt-0.5">£250 Refundable</div>
                </div>
                <div class="p-3.5 bg-[#f8fafc] rounded-2xl border border-slate-200 text-center">
                    <div class="text-xs text-textMuted font-medium uppercase tracking-wider">Pay Cycle</div>
                    <div class="text-sm font-extrabold text-brandOrange-600 mt-0.5">Monday to Monday</div>
                </div>
                <div class="p-3.5 bg-[#f8fafc] rounded-2xl border border-slate-200 text-center">
                    <div class="text-xs text-textMuted font-medium uppercase tracking-wider">Rates</div>
                    <div class="text-sm font-extrabold text-darkSlate-900 mt-0.5">£50 Single / £60 Double</div>
                </div>
            </div>
        </div>

        <!-- 1. Customer Verification & Required Documents -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900">1. Required ID Verification & UK Address Proof</h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                Prior to receiving or unlocking an e-bike, all renters must present valid government photo ID and UK proof of address:
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-[#f8fafc] rounded-2xl border border-slate-200 space-y-1">
                    <h4 class="font-bold text-xs md:text-sm text-slate-900"><i class="fa-solid fa-id-card text-brandOrange-500 mr-1.5"></i> Valid Photo ID (Passport / Visa)</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">Valid Passport, UK Visa / Biometric Residence Permit (BRP), or UK Photocard Driving License.</p>
                </div>
                <div class="p-4 bg-[#f8fafc] rounded-2xl border border-slate-200 space-y-1">
                    <h4 class="font-bold text-xs md:text-sm text-slate-900"><i class="fa-solid fa-house-user text-brandOrange-500 mr-1.5"></i> UK Proof of Address</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">UK Utility bill, Bank Statement, Council Tax bill, or Official Tenancy Agreement dated within the last 3 months.</p>
                </div>
            </div>
        </div>

        <!-- 2. Rates & Deposit -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900">2. Rental Duration, Rates & Deposit</h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                eb4u provides commercial and personal electric bike rentals billed on a weekly rate based on battery configuration:
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-5 bg-emerald-50/50 rounded-2xl border border-emerald-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-grotesk font-extrabold text-slate-900 text-base">Single Battery E-Bike</span>
                        <span class="bg-emerald-600 text-white font-extrabold text-xs px-3 py-1 rounded-full">£50 / Week</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">Standard single battery e-bike, billed at <strong>£50 per week</strong> in advance.</p>
                </div>
                <div class="p-5 bg-blue-50/50 rounded-2xl border border-blue-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-grotesk font-extrabold text-slate-900 text-base">Double Battery E-Bike</span>
                        <span class="bg-blue-600 text-white font-extrabold text-xs px-3 py-1 rounded-full">£60 / Week</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">Long-range dual battery e-bike, billed at <strong>£60 per week</strong> in advance.</p>
                </div>
            </div>
            <div class="p-4 bg-[#f8fafc] rounded-2xl border border-slate-200 space-y-1">
                <h4 class="font-bold text-xs md:text-sm text-slate-900"><i class="fa-solid fa-vault text-brandOrange-500 mr-1.5"></i> £250 Security Deposit</h4>
                <p class="text-xs text-slate-600">A refundable security deposit of £250 is required upfront per bike rental. Returned within 24 hours of returning the e-bike undamaged with all accessories.</p>
            </div>
        </div>

        <!-- 3. Monday to Monday Billing -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900">3. Minimum 2 Weeks & "Monday to Monday" Pay Period</h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                The minimum rental period for any e-bike is <strong>two (2) weeks</strong>. Rental weeks run strictly from <strong>Monday to Monday</strong>.
            </p>
            <div class="p-5 bg-slate-900 text-white rounded-2xl space-y-2">
                <h4 class="font-bold text-xs text-brandOrange-400 uppercase tracking-wider"><i class="fa-solid fa-calendar-check mr-1.5"></i> Mid-Week Pickup Rule Example</h4>
                <p class="text-xs text-slate-200 leading-relaxed">
                    If a customer collects a bike on <strong>Tuesday</strong>, the initial rent covers up to the following <strong>Monday</strong>. If the bike is retained into or brought back on the subsequent <strong>Tuesday</strong>, the customer enters a new weekly billing period and <strong>must pay another full week's rent</strong>. Rent is not pro-rated daily.
                </p>
            </div>
        </div>

        <!-- 4. Maintenance vs Damage -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900">4. Technical Problems vs Accidental Damage</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-5 bg-emerald-50/70 rounded-2xl border border-emerald-200 space-y-2">
                    <h4 class="font-bold text-xs md:text-sm text-emerald-900"><i class="fa-solid fa-gear text-emerald-600 mr-1.5"></i> Technical Issues (eb4u's Responsibility)</h4>
                    <p class="text-xs text-emerald-900 leading-relaxed">
                        Internal mechanical or electrical failures (e.g. motor not working, controller fault) under normal operation are covered by eb4u. Repairs or vehicle swap will be provided at no cost.
                    </p>
                </div>
                <div class="p-5 bg-rose-50/70 rounded-2xl border border-rose-200 space-y-2">
                    <h4 class="font-bold text-xs md:text-sm text-rose-900"><i class="fa-solid fa-car-burst text-rose-600 mr-1.5"></i> Accidents & Misuse (Customer's Responsibility)</h4>
                    <p class="text-xs text-rose-900 leading-relaxed">
                        Damage resulting from accidents, falls, collisions, water damage, or negligence is the customer's sole responsibility to pay for repairs and replacement parts.
                    </p>
                </div>
            </div>
        </div>

        <!-- 5. Theft & Police Custody -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900">5. Stolen Bike & Police Custody Responsibility</h2>
            <div class="p-5 bg-red-950 text-white rounded-2xl space-y-2">
                <h4 class="font-bold text-xs text-red-400 uppercase tracking-wider"><i class="fa-solid fa-shield-cat mr-1.5"></i> Full Replacement Value Responsibility</h4>
                <p class="text-xs md:text-sm text-red-100 leading-relaxed">
                    If the bike is <strong>stolen</strong> or <strong>taken into police custody/impounded</strong> for any reason during the hire term, the customer has full responsibility to pay the <strong>full value/amount of the e-bike</strong> to eb4u Ltd. The £250 deposit will be forfeited and applied toward the balance, and the remaining amount will be invoiced to the customer.
                </p>
            </div>
        </div>

        <!-- Support Link -->
        <div class="p-6 bg-[#f5f7fb] rounded-2xl border border-borderLight flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="font-grotesk text-sm font-bold text-darkSlate-900">Need help understanding our rental policy?</h3>
                <p class="text-xs text-textSec mt-0.5">Contact eb4u support for any clarification on weekly rentals.</p>
            </div>
            <a href="{{ route('cms.contact') }}" class="px-6 py-3 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-bold text-xs rounded-xl shadow-sm whitespace-nowrap transition-colors">
                Contact Customer Care
            </a>
        </div>

    </div>
</div>
@endsection
