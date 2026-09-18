@extends('layouts.app')

@section('title', 'Terms & Conditions | eb4u E-Bike Rental & Sales UK')

@section('content')
<!-- Breadcrumb -->
<div class="border-b border-borderLight bg-[#edf1f8] text-xs">
    <div class="max-w-[1320px] mx-auto px-6 py-3 flex items-center gap-2 text-textMuted font-medium">
        <a href="{{ route('home') }}" class="hover:text-darkSlate-900 transition-colors">Home</a>
        <span>/</span>
        <span class="text-darkSlate-900 font-bold">Terms & Conditions</span>
    </div>
</div>

<div class="max-w-[1050px] mx-auto px-6 py-12">
    <div class="bg-white rounded-3xl border border-borderLight shadow-xs p-8 md:p-12 space-y-10">
        
        <!-- Page Header -->
        <div class="border-b border-borderLight pb-8">
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="bg-brandOrange-50 text-brandOrange-600 text-xs font-bold uppercase px-3.5 py-1.5 rounded-full border border-brandOrange-500/20">
                    Official UK Rental & Platform Agreement
                </span>
                <span class="bg-slate-100 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-full border border-slate-200">
                    Governing Law: England & Wales
                </span>
            </div>
            <h1 class="font-grotesk text-3xl md:text-5xl font-extrabold text-darkSlate-900 tracking-tight">Terms & Conditions</h1>
            <p class="text-xs md:text-sm text-textMuted font-medium mt-2">
                Last updated: September 2026 | eb4u Ltd (Company Reg: 12849201) | Operating in the United Kingdom
            </p>

            <!-- Quick Highlight Banner -->
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="p-3.5 bg-[#f8fafc] rounded-2xl border border-slate-200 text-center">
                    <div class="text-xs text-textMuted font-medium uppercase tracking-wider">Min. Rental</div>
                    <div class="text-sm font-extrabold text-darkSlate-900 mt-0.5">2 Weeks Minimum</div>
                </div>
                <div class="p-3.5 bg-[#f8fafc] rounded-2xl border border-slate-200 text-center">
                    <div class="text-xs text-textMuted font-medium uppercase tracking-wider">Security Deposit</div>
                    <div class="text-sm font-extrabold text-darkSlate-900 mt-0.5">£250 Refundable</div>
                </div>
                <div class="p-3.5 bg-[#f8fafc] rounded-2xl border border-slate-200 text-center">
                    <div class="text-xs text-textMuted font-medium uppercase tracking-wider">Rental Week</div>
                    <div class="text-sm font-extrabold text-brandOrange-600 mt-0.5">Monday to Monday</div>
                </div>
                <div class="p-3.5 bg-[#f8fafc] rounded-2xl border border-slate-200 text-center">
                    <div class="text-xs text-textMuted font-medium uppercase tracking-wider">Weekly Rates</div>
                    <div class="text-sm font-extrabold text-darkSlate-900 mt-0.5">£50 (Single) / £60 (Double)</div>
                </div>
            </div>
        </div>

        <!-- Section 1: Agreement & Applicability -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">1</span>
                Agreement to Terms & General Applicability
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                By creating an account, making an online booking, executing a rental contract, or purchasing products on <strong>eb4u.co.uk</strong> (operated by <strong>eb4u Ltd</strong>), you ("the Customer", "Renter", or "User") agree to be legally bound by these Terms & Conditions.
            </p>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                If you do not accept these terms in full, you must not hire or purchase electric vehicles or accessories from eb4u. These terms apply to all hire agreements, platform bookings, and vehicle usage across the UK.
            </p>
        </div>

        <!-- Section 2: Customer Identity Verification & Documents Required -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">2</span>
                Customer Identity Verification & Required Documents
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                To comply with UK regulations, prevent identity fraud, and validate rental contracts, every renter must submit valid UK proof of identity and proof of address prior to vehicle handover or dispatch:
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-5 bg-[#f8fafc] rounded-2xl border border-slate-200 space-y-2">
                    <div class="flex items-center gap-2 text-darkSlate-900 font-bold text-sm">
                        <i class="fa-solid fa-id-card text-brandOrange-500"></i>
                        <span>1. Proof of Identity (Photo ID)</span>
                    </div>
                    <p class="text-xs text-textSec leading-relaxed">
                        Renters must provide a valid, unexpired government-issued photo ID. Acceptable identity documents:
                    </p>
                    <ul class="list-disc list-inside text-xs text-textSec space-y-1 font-medium pl-1">
                        <li><strong>Valid Passport</strong> (UK or International)</li>
                        <li><strong>UK Visa / Biometric Residence Permit (BRP)</strong></li>
                        <li><strong>Valid UK Driving License</strong> (Full or Provisional)</li>
                    </ul>
                </div>

                <div class="p-5 bg-[#f8fafc] rounded-2xl border border-slate-200 space-y-2">
                    <div class="flex items-center gap-2 text-darkSlate-900 font-bold text-sm">
                        <i class="fa-solid fa-house-user text-brandOrange-500"></i>
                        <span>2. Proof of Address in the UK</span>
                    </div>
                    <p class="text-xs text-textSec leading-relaxed">
                        Renters must provide a valid UK proof of address document dated within the <strong>last 3 months</strong>:
                    </p>
                    <ul class="list-disc list-inside text-xs text-textSec space-y-1 font-medium pl-1">
                        <li><strong>UK Utility Bill</strong> (Gas, Electricity, Water, or Landline)</li>
                        <li><strong>UK Bank or Building Society Statement</strong></li>
                        <li><strong>Council Tax Bill</strong> or Official Tenancy Agreement</li>
                        <li>Official government letter (HMRC, DWP, or NHS)</li>
                    </ul>
                </div>
            </div>

            <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200 flex items-start gap-3">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-1"></i>
                <p class="text-xs text-amber-900 leading-relaxed font-medium">
                    <strong>Mandatory Verification Policy:</strong> E-bike collection in store will be strictly withheld until valid Proof of ID (Passport, Visa/BRP, or UK License) and Proof of Address are verified by eb4u.
                </p>
            </div>
        </div>

        <!-- Section 3: Rental Pricing & Security Deposit -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">3</span>
                Rental Rates & Security Deposit
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                eb4u provides commercial and personal electric bike rentals billed on a weekly rate based on battery configuration:
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-5 bg-emerald-50/50 rounded-2xl border border-emerald-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-grotesk font-extrabold text-slate-900 text-base">Single Battery E-Bike</span>
                        <span class="bg-emerald-600 text-white font-extrabold text-xs px-3 py-1 rounded-full">£50 / Week</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Standard e-bike equipped with 1 removable lithium battery pack. Rent is billed at <strong>£50 per week</strong>, payable strictly in advance.
                    </p>
                </div>

                <div class="p-5 bg-blue-50/50 rounded-2xl border border-blue-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-grotesk font-extrabold text-slate-900 text-base">Double Battery E-Bike</span>
                        <span class="bg-blue-600 text-white font-extrabold text-xs px-3 py-1 rounded-full">£60 / Week</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Extended-range dual battery e-bike equipped with 2 lithium battery packs. Rent is billed at <strong>£60 per week</strong>, payable strictly in advance.
                    </p>
                </div>
            </div>

            <!-- Deposit Box -->
            <div class="p-5 bg-[#f8fafc] rounded-2xl border border-slate-200 space-y-2">
                <h4 class="font-grotesk font-bold text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-vault text-brandOrange-500"></i> £250 Refundable Security Deposit
                </h4>
                <p class="text-xs md:text-sm text-textSec leading-relaxed">
                    A refundable security deposit of <strong>£250</strong> is mandatory for every e-bike hire. The deposit is held prior to vehicle release and will be refunded in full upon return of the e-bike in undamaged condition, alongside all original accessories (keys, charger, battery unit, lock). Deductions will be made for unpaid rent, missing accessories, or accidental damage.
                </p>
            </div>
        </div>

        <!-- Section 4: Minimum Rental Duration & Monday-to-Monday Billing Cycle -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">4</span>
                Minimum Rental Duration & "Monday-to-Monday" Billing Policy
            </h2>
            
            <div class="space-y-3">
                <div class="flex items-start gap-3 p-4 bg-amber-50 rounded-2xl border border-amber-200">
                    <i class="fa-solid fa-clock text-amber-600 mt-1"></i>
                    <div>
                        <h4 class="font-bold text-xs md:text-sm text-amber-900 mb-1">Minimum 2 Weeks Rental Requirement</h4>
                        <p class="text-xs md:text-sm text-amber-800 leading-relaxed">
                            The minimum contract commitment for any e-bike hire is <strong>two (2) consecutive weeks</strong>. Rental periods under 14 days are not available.
                        </p>
                    </div>
                </div>

                <div class="p-6 bg-slate-900 text-white rounded-2xl space-y-4 shadow-md">
                    <div class="flex items-center gap-2 text-brandOrange-400 font-bold text-xs uppercase tracking-wider">
                        <i class="fa-solid fa-calendar-days"></i> Strict Rental Week Cycle Rules
                    </div>
                    <h3 class="font-grotesk text-lg font-bold text-white">Rental Week is Defined strictly as Monday to Monday</h3>
                    <p class="text-xs md:text-sm text-slate-300 leading-relaxed">
                        Our rental billing week operates on a strict <strong>Monday to Monday schedule</strong>. Rental charges are calculated on full weekly blocks from Monday to Monday, regardless of the day of pickup.
                    </p>
                    
                    <div class="p-4 bg-slate-800/90 rounded-xl border border-slate-700 space-y-2">
                        <div class="font-bold text-xs text-brandOrange-400 uppercase tracking-wide">
                            <i class="fa-solid fa-circle-info mr-1"></i> Mid-Week Pickup Example & Billing Rule:
                        </div>
                        <p class="text-xs md:text-sm text-slate-200 leading-relaxed">
                            If a customer collects a bike on a <strong>Tuesday</strong>, the rent paid covers the initial period up to the following <strong>Monday</strong>.
                        </p>
                        <p class="text-xs md:text-sm text-slate-200 leading-relaxed">
                            If the bike is kept into or returned on the subsequent <strong>Tuesday</strong> (entering a new Monday-to-Monday cycle), the customer is required to pay for <strong>another full week's rent</strong>. Rent is not pro-rated on a daily basis.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 5: Care of Vehicle & Customer Responsibilities -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">5</span>
                Care of E-Bike & Rider Responsibilities
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                The Customer has total custody of the vehicle during the rental period and is responsible for treating the bike with high care and diligence:
            </p>
            <ul class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                <li class="p-4 bg-[#f8fafc] rounded-2xl border border-slate-200 flex items-start gap-3">
                    <i class="fa-solid fa-shield-halved text-brandOrange-500 mt-1"></i>
                    <div>
                        <strong class="text-xs md:text-sm text-slate-900 block mb-0.5">Secure Locking Duty</strong>
                        <span class="text-xs text-slate-600">Must lock the e-bike through the frame to an immovable object using an eb4u-approved Gold-rated lock whenever left unattended.</span>
                    </div>
                </li>
                <li class="p-4 bg-[#f8fafc] rounded-2xl border border-slate-200 flex items-start gap-3">
                    <i class="fa-solid fa-battery-charging text-brandOrange-500 mt-1"></i>
                    <div>
                        <strong class="text-xs md:text-sm text-slate-900 block mb-0.5">Battery & Charging Care</strong>
                        <span class="text-xs text-slate-600">Only use the official eb4u charger provided. Protect battery packs from extreme moisture, submersion, or direct impact.</span>
                    </div>
                </li>
                <li class="p-4 bg-[#f8fafc] rounded-2xl border border-slate-200 flex items-start gap-3">
                    <i class="fa-solid fa-ban text-brandOrange-500 mt-1"></i>
                    <div>
                        <strong class="text-xs md:text-sm text-slate-900 block mb-0.5">Prohibited Modifications</strong>
                        <span class="text-xs text-slate-600">Tampering with speed limiters, controllers, motors, or adding unauthorized electronic accessories is strictly illegal and prohibited.</span>
                    </div>
                </li>
                <li class="p-4 bg-[#f8fafc] rounded-2xl border border-slate-200 flex items-start gap-3">
                    <i class="fa-solid fa-user-shield text-brandOrange-500 mt-1"></i>
                    <div>
                        <strong class="text-xs md:text-sm text-slate-900 block mb-0.5">Authorized Riding Only</strong>
                        <span class="text-xs text-slate-600">Only the registered customer who signed the hire agreement and submitted ID is permitted to operate the e-bike. Sub-letting is strictly forbidden.</span>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Section 6: Technical Problems vs. Accidents & Damage -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">6</span>
                Maintenance, Technical Faults vs. Accidental Damage
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                Our policy clearly separates manufacturing/mechanical faults from rider-caused damage:
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- eb4u Responsibility -->
                <div class="p-5 bg-emerald-50/70 rounded-2xl border border-emerald-200 space-y-3">
                    <div class="flex items-center gap-2 text-emerald-800 font-bold text-sm">
                        <i class="fa-solid fa-screwdriver-wrench text-emerald-600"></i>
                        <span>Inherent Technical Problems (Covered by eb4u)</span>
                    </div>
                    <p class="text-xs text-emerald-950 leading-relaxed">
                        If the bike develops a technical breakdown under normal riding conditions—such as motor failure, controller defects, electrical system malfunction, or internal mechanical failure not caused by external impact:
                    </p>
                    <ul class="list-disc list-inside text-xs text-emerald-900 space-y-1 font-medium">
                        <li><strong>eb4u is responsible</strong> for repairing the fault or providing a replacement vehicle.</li>
                        <li>Standard maintenance and routine wear-and-tear repairs are free of charge.</li>
                        <li>Renter must notify eb4u immediately upon discovering any technical issue.</li>
                    </ul>
                </div>

                <!-- Customer Responsibility -->
                <div class="p-5 bg-rose-50/70 rounded-2xl border border-rose-200 space-y-3">
                    <div class="flex items-center gap-2 text-rose-800 font-bold text-sm">
                        <i class="fa-solid fa-car-burst text-rose-600"></i>
                        <span>Accidents & User Damage (Customer Responsibility)</span>
                    </div>
                    <p class="text-xs text-rose-950 leading-relaxed">
                        If the bike suffers damage due to an accident, crash, fall, collision, water immersion, bad storage, or user misuse/negligence:
                    </p>
                    <ul class="list-disc list-inside text-xs text-rose-900 space-y-1 font-medium">
                        <li><strong>Customer is fully responsible</strong> to pay the complete cost of repairs and replacement parts.</li>
                        <li>Repair costs will be deducted from the £250 deposit or billed directly.</li>
                        <li>Includes damage to wheels, frame, brakes, battery casing, display screens, or lights.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Section 7: Stolen Bike & Police Custody Policy -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">7</span>
                Stolen E-Bikes & Police Custody / Seizure Policy
            </h2>
            
            <div class="p-6 bg-red-950 text-white rounded-3xl border border-red-800 space-y-4 shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center font-black text-lg">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h3 class="font-grotesk text-lg font-bold text-white">Full Financial Liability for Stolen or Confiscated Bikes</h3>
                        <p class="text-xs text-red-200">Customer Responsibility Clause under UK Hire Agreement</p>
                    </div>
                </div>

                <div class="space-y-3 text-xs md:text-sm text-red-100 leading-relaxed">
                    <p>
                        <strong>1. Stolen E-Bike Liability:</strong> If the e-bike is stolen during your rental period, <strong>the Customer has the sole legal and financial responsibility to pay the full value / full market cost of the e-bike to eb4u Ltd</strong>.
                    </p>
                    <p>
                        <strong>2. Police Custody / Impoundment / Seizure:</strong> If the e-bike is stopped, impounded, seized, or taken into police custody for any reason whatsoever (including traffic stops, rider inspection, lack of helmet/license where applicable, or police investigations), <strong>the Customer remains fully responsible to pay the full value of the bike to eb4u Ltd</strong> alongside any impound recovery fees.
                    </p>
                    <p>
                        <strong>3. Mandatory Theft Reporting Procedure:</strong> In the event of theft, the customer must report the incident to the UK Police immediately within 24 hours, obtain an official Crime Reference Number (CRN), and inform eb4u Support. Obtaining a CRN does not exempt the customer from paying the full value of the bike.
                    </p>
                    <p>
                        <strong>4. Deposit & Direct Invoicing:</strong> The £250 security deposit will be forfeited immediately and applied toward the balance of the bike cost. The customer will be invoiced for the remaining balance of the full bike value, payable within 7 business days.
                    </p>
                </div>
            </div>
        </div>

        <!-- Section 8: Overdue Payments & Default -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">8</span>
                Rental Payments, Late Fees & Default
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                Rent is due strictly every Monday in advance for the upcoming week. If rental payments fail or become overdue:
            </p>
            <ul class="list-disc list-inside text-xs md:text-sm text-textSec space-y-2 pl-2 font-medium">
                <li>A late payment administrative fee of £15 will be applied for payments delayed beyond 24 hours.</li>
                <li>eb4u reserves the right to remotely track, immobilize, or repossess the e-bike if rent remains unpaid for over 3 days.</li>
                <li>Unpaid balances will be referred to debt collection agencies and legal courts in England & Wales.</li>
            </ul>
        </div>

        <!-- Section 9: E-Bike Purchases & Sales Terms -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">9</span>
                E-Bike Purchase Terms & Consumer Rights
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                For customers purchasing new or refurbished e-bikes directly from eb4u:
            </p>
            <ul class="list-disc list-inside text-xs md:text-sm text-textSec space-y-2 pl-2 font-medium">
                <li><strong>UK EAPC Compliance:</strong> All sold e-bikes comply with British Electrically Assisted Pedal Cycles regulations (250W rating, maximum assisted speed of 15.5 mph / 25 km/h).</li>
                <li><strong>Manufacturer Warranty:</strong> New e-bikes include a 2-year warranty covering frame, motor, and battery manufacturing defects.</li>
                <li><strong>14-Day Consumer Right to Return:</strong> Under Consumer Contracts Regulations 2013, online sales can be returned within 14 days of receipt, provided the vehicle is unused and in original packaging.</li>
            </ul>
        </div>

        <!-- Section 10: Governing Law -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">10</span>
                Governing Law & Jurisdiction
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                These Terms & Conditions are governed by and construed in accordance with the laws of <strong>England & Wales</strong>. Any legal dispute or claim arising under or in connection with these Terms shall be subject to the exclusive jurisdiction of the Courts of England and Wales.
            </p>
        </div>

        <!-- Section 11: Customer Support Contact -->
        <div class="p-6 bg-[#f5f7fb] rounded-2xl border border-borderLight flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="font-grotesk text-sm font-bold text-darkSlate-900">Questions about our Terms or Rental Agreement?</h3>
                <p class="text-xs text-textSec mt-0.5">Our UK customer care team is available to assist you 7 days a week.</p>
            </div>
            <a href="{{ route('cms.contact') }}" class="px-6 py-3 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-bold text-xs rounded-xl shadow-sm whitespace-nowrap transition-colors flex items-center gap-2">
                <i class="fa-solid fa-headset"></i> Contact Customer Support
            </a>
        </div>

    </div>
</div>
@endsection
