@extends('layouts.app')

@section('title', 'Privacy Policy | eb4u')

@section('content')
<!-- Breadcrumb -->
<div class="border-b border-borderLight bg-[#edf1f8] text-xs">
    <div class="max-w-[1320px] mx-auto px-6 py-3 flex items-center gap-2 text-textMuted font-medium">
        <a href="{{ route('home') }}" class="hover:text-darkSlate-900 transition-colors">Home</a>
        <span>/</span>
        <span class="text-darkSlate-900 font-bold">Privacy Policy</span>
    </div>
</div>

<div class="max-w-[1000px] mx-auto px-6 py-12">
    <div class="bg-white rounded-3xl border border-borderLight shadow-xs p-8 md:p-12 space-y-8">
        
        <!-- Page Header -->
        <div class="border-b border-borderLight pb-6">
            <span class="bg-brandOrange-50 text-brandOrange-600 text-xs font-bold uppercase px-3.5 py-1.5 rounded-full border border-brandOrange-500/20">
                UK GDPR & Data Protection Act 2018 Compliant
            </span>
            <h1 class="font-grotesk text-3xl md:text-4xl font-extrabold text-darkSlate-900 mt-3 mb-2">Privacy Policy</h1>
            <p class="text-xs text-textMuted font-medium">Last updated: August 25, 2026 | Applies to all eb4u website visitors, buyers, and e-bike rental customers across the United Kingdom.</p>
        </div>

        <!-- Section 1: Introduction -->
        <div class="space-y-3">
            <h2 class="font-grotesk text-lg font-bold text-darkSlate-900 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-xs font-black border border-brandOrange-500/20">1</span>
                Introduction & Data Controller
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                Welcome to <strong>eb4u Ltd</strong> ("eb4u", "we", "our", or "us"). Registered in England & Wales (Company No. 12849201) with registered head offices at 142 Regent Street, London, W1B 5SE, United Kingdom. We are committed to safeguarding the privacy and safety of our customers' personal data.
            </p>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                This Privacy Policy explains how we collect, store, process, share, and protect your personal information when you visit our website (<strong>eb4u.co.uk</strong>), purchase electric bikes or accessories, or book short-term and monthly e-bike rentals.
            </p>
        </div>

        <!-- Section 2: Information We Collect -->
        <div class="space-y-3">
            <h2 class="font-grotesk text-lg font-bold text-darkSlate-900 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-xs font-black border border-brandOrange-500/20">2</span>
                Information We Collect
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                We collect personal information that you voluntarily provide to us when registering an account, placing a sale order, or completing an e-bike rental agreement:
            </p>
            <ul class="list-disc list-inside text-xs md:text-sm text-textSec space-y-1.5 pl-2 font-medium">
                <li><strong>Identity Data:</strong> Full name, date of birth, and proof of identification (driver's licence or passport required for high-value e-bike rentals).</li>
                <li><strong>Contact Data:</strong> UK billing address, delivery address, email address, and mobile phone number for SMS delivery tracking.</li>
                <li><strong>Transaction & Rental Data:</strong> Details of bikes purchased, accessories ordered, rental duration dates, pickup/delivery choices, and security deposit allocations.</li>
                <li><strong>Payment Data:</strong> Payment card details processed securely via PCI-DSS compliant UK payment gateways. We never store raw credit card numbers on our servers.</li>
                <li><strong>Technical Data:</strong> IP address, browser type, device information, and browsing patterns collected via essential cookies.</li>
            </ul>
        </div>

        <!-- Section 3: How We Use Your Information -->
        <div class="space-y-3">
            <h2 class="font-grotesk text-lg font-bold text-darkSlate-900 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-xs font-black border border-brandOrange-500/20">3</span>
                How We Use Your Personal Data
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                We process your personal information strictly under lawful UK GDPR bases for the following purposes:
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div class="p-4 bg-[#f5f7fb] rounded-2xl border border-borderLight">
                    <h4 class="font-grotesk text-xs font-bold text-darkSlate-900 mb-1"><i class="fa-solid fa-truck-fast text-brandOrange-500 mr-1.5"></i> Order & Rental Fulfillment</h4>
                    <p class="text-xs text-textSec">To process transactions, assemble your electric bike, manage physical fleet allocations, and dispatch home delivery packages across the UK.</p>
                </div>
                <div class="p-4 bg-[#f5f7fb] rounded-2xl border border-borderLight">
                    <h4 class="font-grotesk text-xs font-bold text-darkSlate-900 mb-1"><i class="fa-solid fa-rotate-left text-brandOrange-500 mr-1.5"></i> Security Deposit Refunds</h4>
                    <p class="text-xs text-textSec">To inspect returned rental bikes, process security deposit refunds, or extend rental agreements seamlessly upon request.</p>
                </div>
                <div class="p-4 bg-[#f5f7fb] rounded-2xl border border-borderLight">
                    <h4 class="font-grotesk text-xs font-bold text-darkSlate-900 mb-1"><i class="fa-solid fa-shield-halved text-brandOrange-500 mr-1.5"></i> Theft & Risk Mitigation</h4>
                    <p class="text-xs text-textSec">To verify identity for physical fleet protection and handle insurance claims in compliance with UK cycling laws.</p>
                </div>
                <div class="p-4 bg-[#f5f7fb] rounded-2xl border border-borderLight">
                    <h4 class="font-grotesk text-xs font-bold text-darkSlate-900 mb-1"><i class="fa-solid fa-headset text-brandOrange-500 mr-1.5"></i> Customer Support</h4>
                    <p class="text-xs text-textSec">To respond to inquiries, send SMS delivery alerts, issue warranty documentation, and manage customer service interactions.</p>
                </div>
            </div>
        </div>

        <!-- Section 4: Data Security & Storage -->
        <div class="space-y-3">
            <h2 class="font-grotesk text-lg font-bold text-darkSlate-900 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-xs font-black border border-brandOrange-500/20">4</span>
                Data Protection & Security Standard
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                We implement robust SSL 256-bit encryption, strict access control policies, and firewall protections to safeguard your data against unauthorized access, alteration, disclosure, or destruction.
            </p>
        </div>

        <!-- Section 5: Your Rights -->
        <div class="space-y-3">
            <h2 class="font-grotesk text-lg font-bold text-darkSlate-900 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-xs font-black border border-brandOrange-500/20">5</span>
                Your Legal Rights Under UK Law
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                Under UK data protection legislation, you have the right to request access to your personal data, request correction of inaccurate data, request erasure ("right to be forgotten"), object to processing, and request data portability. To exercise any of these rights, contact our Data Protection Team at <strong>privacy@eb4u.co.uk</strong>.
            </p>
        </div>

        <!-- Section 6: Contact -->
        <div class="p-6 bg-[#f5f7fb] rounded-2xl border border-borderLight flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="font-grotesk text-sm font-bold text-darkSlate-900">Questions about your privacy?</h3>
                <p class="text-xs text-textSec mt-0.5">Contact our UK Data Protection Officer at privacy@eb4u.co.uk or visit our Regent Street Store in London.</p>
            </div>
            <a href="{{ route('cms.contact') }}" class="px-6 py-3 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-bold text-xs rounded-xl shadow-sm whitespace-nowrap transition-colors">
                Contact Data Team
            </a>
        </div>

    </div>
</div>
@endsection
