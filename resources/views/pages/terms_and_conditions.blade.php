@extends('layouts.app')

@section('title', 'Terms & Conditions | eb4u')

@section('content')
<!-- Breadcrumb -->
<div class="border-b border-borderLight bg-[#edf1f8] text-xs">
    <div class="max-w-[1320px] mx-auto px-6 py-3 flex items-center gap-2 text-textMuted font-medium">
        <a href="{{ route('home') }}" class="hover:text-darkSlate-900 transition-colors">Home</a>
        <span>/</span>
        <span class="text-darkSlate-900 font-bold">Terms & Conditions</span>
    </div>
</div>

<div class="max-w-[1000px] mx-auto px-6 py-12">
    <div class="bg-white rounded-3xl border border-borderLight shadow-xs p-8 md:p-12 space-y-8">
        
        <!-- Page Header -->
        <div class="border-b border-borderLight pb-6">
            <span class="bg-brandOrange-50 text-brandOrange-600 text-xs font-bold uppercase px-3.5 py-1.5 rounded-full border border-brandOrange-500/20">
                Official UK Store & Rental Agreement Terms
            </span>
            <h1 class="font-grotesk text-3xl md:text-4xl font-extrabold text-darkSlate-900 mt-3 mb-2">Terms & Conditions</h1>
            <p class="text-xs text-textMuted font-medium">Last updated: August 25, 2026 | Governing law: England & Wales | eb4u Ltd (Reg: 12849201)</p>
        </div>

        <!-- Section 1: Acceptance -->
        <div class="space-y-3">
            <h2 class="font-grotesk text-lg font-bold text-darkSlate-900 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-xs font-black border border-brandOrange-500/20">1</span>
                Agreement to Terms & Applicability
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                By accessing, browsing, or placing an order through <strong>eb4u.co.uk</strong> (operated by eb4u Ltd), or by entering into a short-term / monthly electric bike rental contract at our London store or online platform, you agree to be legally bound by these Terms & Conditions.
            </p>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                If you do not agree to all provisions of these Terms, you must not purchase products or rent electric vehicles from our platform.
            </p>
        </div>

        <!-- Section 2: E-Bike Sales & Warranty -->
        <div class="space-y-3">
            <h2 class="font-grotesk text-lg font-bold text-darkSlate-900 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-xs font-black border border-brandOrange-500/20">2</span>
                E-Bike Sales, Orders & UK Warranty
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                All electric bikes sold on eb4u are certified under British Electrically Assisted Pedal Cycles (EAPC) standards (250W maximum continuous rated motor power and speed assistance capped at 15.5 mph / 25 km/h).
            </p>
            <ul class="list-disc list-inside text-xs md:text-sm text-textSec space-y-1.5 pl-2 font-medium">
                <li><strong>Pricing & VAT:</strong> All displayed prices include 20% UK VAT. Shipping fees are calculated at checkout. Free UK mainland delivery applies to orders over £500.</li>
                <li><strong>Warranty Coverage:</strong> Every new e-bike carries a 2-year manufacturer warranty covering battery cells, motor drive units, and frame integrity under normal riding conditions.</li>
                <li><strong>Assembly & Inspection:</strong> Bikes shipped via UK Home Delivery are pre-assembled and safety checked by certified bicycle mechanics prior to dispatch.</li>
            </ul>
        </div>

        <!-- Section 3: E-Bike Rental & Booking Terms -->
        <div class="space-y-3">
            <h2 class="font-grotesk text-lg font-bold text-darkSlate-900 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-xs font-black border border-brandOrange-500/20">3</span>
                E-Bike Rental & Fleet Booking Conditions
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                Electric bike rentals are subject to specific hire rules to ensure user safety and physical vehicle protection:
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div class="p-4 bg-[#f5f7fb] rounded-2xl border border-borderLight">
                    <h4 class="font-grotesk text-xs font-bold text-darkSlate-900 mb-1"><i class="fa-solid fa-calendar-check text-brandOrange-500 mr-1.5"></i> 30% Advance Option</h4>
                    <p class="text-xs text-textSec">Renters may choose to pay 30% advance at checkout to lock in their dates. The remaining 70% balance is payable upon vehicle collection or delivery.</p>
                </div>
                <div class="p-4 bg-[#f5f7fb] rounded-2xl border border-borderLight">
                    <h4 class="font-grotesk text-xs font-bold text-darkSlate-900 mb-1"><i class="fa-solid fa-vault text-brandOrange-500 mr-1.5"></i> Refundable Security Deposit</h4>
                    <p class="text-xs text-textSec">A refundable security deposit (£150 - £300 depending on bike class) is held upon collection and returned within 24 hours of undamaged bike return.</p>
                </div>
                <div class="p-4 bg-[#f5f7fb] rounded-2xl border border-borderLight">
                    <h4 class="font-grotesk text-xs font-bold text-darkSlate-900 mb-1"><i class="fa-solid fa-id-card text-brandOrange-500 mr-1.5"></i> Eligibility Requirements</h4>
                    <p class="text-xs text-textSec">Riders must be at least 14 years of age under UK EAPC legislation. A valid government photo ID and proof of address are required at pickup.</p>
                </div>
                <div class="p-4 bg-[#f5f7fb] rounded-2xl border border-borderLight">
                    <h4 class="font-grotesk text-xs font-bold text-darkSlate-900 mb-1"><i class="fa-solid fa-clock-rotate-left text-brandOrange-500 mr-1.5"></i> Online Extensions</h4>
                    <p class="text-xs text-textSec">Active rentals can be extended online via the Customer Dashboard subject to physical fleet availability for subsequent dates.</p>
                </div>
            </div>
        </div>

        <!-- Section 4: Cancellation & Refund Policy -->
        <div class="space-y-3">
            <h2 class="font-grotesk text-lg font-bold text-darkSlate-900 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-xs font-black border border-brandOrange-500/20">4</span>
                Cancellations & 14-Day Consumer Right to Return
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                Under UK Consumer Contracts Regulations 2013, e-bike sales buyers have the right to cancel and return unused products within 14 days of delivery for a full refund.
            </p>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                For rental bookings, cancellations made at least 48 hours prior to the scheduled pickup date receive a 100% refund of any advance payments made.
            </p>
        </div>

        <!-- Section 5: Governing Law -->
        <div class="space-y-3">
            <h2 class="font-grotesk text-lg font-bold text-darkSlate-900 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-xs font-black border border-brandOrange-500/20">5</span>
                Governing Law & Jurisdiction
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                These Terms & Conditions are governed by and construed in accordance with the laws of England & Wales. Any disputes arising in connection with this platform shall be subject to the exclusive jurisdiction of the English Courts.
            </p>
        </div>

        <!-- Section 6: Contact -->
        <div class="p-6 bg-[#f5f7fb] rounded-2xl border border-borderLight flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="font-grotesk text-sm font-bold text-darkSlate-900">Need help understanding our terms?</h3>
                <p class="text-xs text-textSec mt-0.5">Our support team is available 7 days a week at support@eb4u.co.uk or +44 (0) 20 7946 0912.</p>
            </div>
            <a href="{{ route('cms.contact') }}" class="px-6 py-3 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-bold text-xs rounded-xl shadow-sm whitespace-nowrap transition-colors">
                Contact Customer Care
            </a>
        </div>

    </div>
</div>
@endsection
