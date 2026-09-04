@extends('layouts.app')

@section('title', 'About Us | eb4u')

@section('content')

<!-- Breadcrumb -->
<div class="border-b border-borderLight bg-[#edf1f8] text-xs">
    <div class="max-w-[1320px] mx-auto px-6 py-3 flex items-center gap-2 text-textMuted font-medium">
        <a href="{{ route('home') }}" class="hover:text-darkSlate-900 transition-colors">
            Home
        </a>

        <span>/</span>

        <span class="text-darkSlate-900 font-bold">
            About Us
        </span>
    </div>
</div>

<div class="max-w-[1100px] mx-auto px-6 py-12">

    <div class="bg-white rounded-3xl border border-borderLight shadow-xs overflow-hidden">

        <!-- Hero / Page Header -->
        <div class="px-8 md:px-12 pt-10 pb-10 border-b border-borderLight">

            <div class="max-w-3xl">

                <span class="bg-brandOrange-50 text-brandOrange-600 text-xs font-bold uppercase px-3.5 py-1.5 rounded-full border border-brandOrange-500/20">
                    About eb4u
                </span>

                <h1 class="font-grotesk text-3xl md:text-5xl font-extrabold text-darkSlate-900 mt-4 mb-4 leading-tight">
                    Ride More.
                    <span class="text-brandOrange-500">Spend Less.</span>
                    Go Further.
                </h1>

                <p class="text-sm md:text-base text-textSec leading-relaxed max-w-2xl">
                    Welcome to <strong>eb4u</strong> — your trusted destination for
                    quality electric bikes, flexible rentals and essential e-bike
                    accessories across the UK.
                </p>

            </div>

        </div>


        <!-- Introduction -->
        <div class="px-8 md:px-12 py-10">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">

                <div class="space-y-4">

                    <span class="text-brandOrange-500 text-xs font-bold uppercase">
                        Making Electric Mobility Simple
                    </span>

                    <h2 class="font-grotesk text-2xl md:text-3xl font-extrabold text-darkSlate-900">
                        Electric mobility made simple.
                    </h2>

                    <p class="text-xs md:text-sm text-textSec leading-relaxed">
                        At eb4u, we believe electric bikes should make everyday travel
                        <strong>simpler, smarter and more affordable.</strong>
                    </p>

                    <p class="text-xs md:text-sm text-textSec leading-relaxed">
                        Whether you're looking to buy your first e-bike, rent one for
                        a short trip, commute to work, explore the city or find the
                        right accessories for your ride, eb4u brings everything
                        together in one convenient platform.
                    </p>

                    <p class="text-xs md:text-sm text-textSec leading-relaxed">
                        Our focus is to provide customers with quality electric bikes,
                        flexible rental options and a straightforward online experience.
                    </p>

                </div>


                <!-- Highlight Card -->
                <div class="bg-[#f5f7fb] rounded-3xl border border-borderLight p-7">

                    <div class="w-12 h-12 rounded-2xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center mb-5">
                        <i class="fa-solid fa-bicycle text-xl"></i>
                    </div>

                    <h3 class="font-grotesk text-xl font-bold text-darkSlate-900 mb-3">
                        Built Around Your Ride
                    </h3>

                    <p class="text-xs md:text-sm text-textSec leading-relaxed mb-4">
                        We offer carefully selected electric bikes designed for
                        everyday UK riding, commuting, leisure and practical
                        transportation.
                    </p>

                    <p class="text-xs md:text-sm text-textSec leading-relaxed">
                        Our electric bikes are supplied to meet applicable British
                        <strong>Electrically Assisted Pedal Cycles (EAPC)</strong>
                        requirements, including assistance capped at
                        <strong>15.5 mph / 25 km/h</strong> and applicable
                        <strong>250W</strong> continuous rated motor limits.
                    </p>

                </div>

            </div>

        </div>


        <!-- Why Choose Us -->
        <div class="bg-[#f8fafc] border-y border-borderLight px-8 md:px-12 py-10">

            <div class="text-center mb-8">

                <span class="text-brandOrange-500 text-xs font-bold uppercase">
                    Why Choose Us
                </span>

                <h2 class="font-grotesk text-2xl md:text-3xl font-extrabold text-darkSlate-900 mt-2">
                    Why Choose eb4u?
                </h2>

                <p class="text-xs md:text-sm text-textMuted mt-2 max-w-2xl mx-auto">
                    We focus on quality products, flexible options and a simple
                    customer experience from start to finish.
                </p>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                <!-- Card 1 -->
                <div class="bg-white p-5 rounded-2xl border border-borderLight">

                    <div class="w-10 h-10 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-bicycle"></i>
                    </div>

                    <h3 class="font-grotesk text-sm font-bold text-darkSlate-900 mb-2">
                        Quality E-Bikes
                    </h3>

                    <p class="text-xs text-textSec leading-relaxed">
                        Reliable electric bikes selected for everyday UK riding,
                        with a 2-year manufacturer warranty on eligible new bikes.
                    </p>

                </div>


                <!-- Card 2 -->
                <div class="bg-white p-5 rounded-2xl border border-borderLight">

                    <div class="w-10 h-10 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>

                    <h3 class="font-grotesk text-sm font-bold text-darkSlate-900 mb-2">
                        Flexible Rentals
                    </h3>

                    <p class="text-xs text-textSec leading-relaxed">
                        Choose from short-term and monthly rental options with
                        convenient booking and online rental extensions,
                        subject to fleet availability.
                    </p>

                </div>


                <!-- Card 3 -->
                <div class="bg-white p-5 rounded-2xl border border-borderLight">

                    <div class="w-10 h-10 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-receipt"></i>
                    </div>

                    <h3 class="font-grotesk text-sm font-bold text-darkSlate-900 mb-2">
                        Transparent Pricing
                    </h3>

                    <p class="text-xs text-textSec leading-relaxed">
                        We aim to keep our pricing clear and straightforward,
                        with UK VAT included in displayed product prices.
                    </p>

                </div>


                <!-- Card 4 -->
                <div class="bg-white p-5 rounded-2xl border border-borderLight">

                    <div class="w-10 h-10 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>

                    <h3 class="font-grotesk text-sm font-bold text-darkSlate-900 mb-2">
                        Safety Checked
                    </h3>

                    <p class="text-xs text-textSec leading-relaxed">
                        Bikes supplied through our UK home delivery service are
                        pre-assembled and safety checked before dispatch.
                    </p>

                </div>


                <!-- Card 5 -->
                <div class="bg-white p-5 rounded-2xl border border-borderLight">

                    <div class="w-10 h-10 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-toolbox"></i>
                    </div>

                    <h3 class="font-grotesk text-sm font-bold text-darkSlate-900 mb-2">
                        Complete E-Bike Solution
                    </h3>

                    <p class="text-xs text-textSec leading-relaxed">
                        From electric bikes and rentals to chargers, locks and
                        accessories, find everything you need in one place.
                    </p>

                </div>


                <!-- Card 6 -->
                <div class="bg-white p-5 rounded-2xl border border-borderLight">

                    <div class="w-10 h-10 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-headset"></i>
                    </div>

                    <h3 class="font-grotesk text-sm font-bold text-darkSlate-900 mb-2">
                        Customer Focused
                    </h3>

                    <p class="text-xs text-textSec leading-relaxed">
                        From your first enquiry to your next ride, our goal is
                        to provide an easy, transparent and hassle-free experience.
                    </p>

                </div>

            </div>

        </div>


        <!-- Our Mission -->
        <div class="px-8 md:px-12 py-10">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Mission -->
                <div class="bg-darkSlate-900 rounded-3xl p-7 md:p-8 text-white">

                    <span class="text-brandOrange-500 text-xs font-bold uppercase">
                        Our Mission
                    </span>

                    <h2 class="font-grotesk text-2xl md:text-3xl font-extrabold mt-3 mb-4">
                        Making Electric Mobility More Accessible
                    </h2>

                    <p class="text-xs md:text-sm text-gray-300 leading-relaxed mb-4">
                        Our mission is to make electric mobility
                        <strong class="text-white">
                            accessible, practical and convenient
                        </strong>
                        for more people across the UK.
                    </p>

                    <p class="text-xs md:text-sm text-gray-300 leading-relaxed">
                        We want to remove the complexity from buying and renting
                        an e-bike by providing quality products, flexible rental
                        choices, clear terms and dependable customer service.
                    </p>

                </div>


                <!-- More Than Store -->
                <div class="p-7 md:p-8 border border-borderLight rounded-3xl">

                    <span class="text-brandOrange-500 text-xs font-bold uppercase">
                        Our Approach
                    </span>

                    <h2 class="font-grotesk text-2xl font-extrabold text-darkSlate-900 mt-3 mb-4">
                        More Than Just an E-Bike Store
                    </h2>

                    <p class="text-xs md:text-sm text-textSec leading-relaxed mb-4">
                        We don't just sell bikes. We are building a complete
                        <strong>e-bike experience</strong>.
                    </p>

                    <p class="text-xs md:text-sm text-textSec leading-relaxed">
                        Customers can discover the right bike, rent when they
                        need flexibility, purchase useful accessories and manage
                        their rentals through our convenient online platform.
                    </p>

                </div>

            </div>

        </div>


        <!-- Ride With Confidence -->
        <div class="px-8 md:px-12 pb-10">

            <div class="relative overflow-hidden bg-[#f5f7fb] rounded-3xl border border-borderLight p-8 md:p-10 text-center">

                <div class="relative z-10">

                    <span class="bg-brandOrange-50 text-brandOrange-600 text-xs font-bold uppercase px-3.5 py-1.5 rounded-full border border-brandOrange-500/20">
                        Ride With Confidence
                    </span>

                    <h2 class="font-grotesk text-2xl md:text-3xl font-extrabold text-darkSlate-900 mt-4 mb-3">
                        Ready to Ride?
                    </h2>

                    <p class="text-xs md:text-sm text-textSec leading-relaxed max-w-2xl mx-auto mb-6">
                        Whether you're buying, renting or simply exploring your
                        options, <strong>eb4u</strong> is here to help you find
                        the right electric bike for your journey.
                    </p>

                    <div class="flex flex-col sm:flex-row justify-center gap-3">

                        <a href="{{ route('home') }}"
                           class="px-6 py-3 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                            Shop E-Bikes
                        </a>

                        <a href="{{ route('home') }}"
                           class="px-6 py-3 bg-darkSlate-900 hover:bg-darkSlate-800 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                            Rent an E-Bike
                        </a>

                    </div>

                    <p class="font-grotesk font-bold text-darkSlate-900 text-sm mt-6">
                        Buy an E-Bike. Rent an E-Bike. Ride Your Way.
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection