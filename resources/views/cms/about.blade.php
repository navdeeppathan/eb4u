@extends('layouts.app')

@section('title', 'Terms & Conditions | eb4u')

@section('content')
<!-- Breadcrumb -->
<div class="border-b border-borderLight bg-[#edf1f8] text-xs">
    <div class="max-w-[1320px] mx-auto px-6 py-3 flex items-center gap-2 text-textMuted font-medium">
        <a href="{{ route('home') }}" class="hover:text-darkSlate-900 transition-colors">Home</a>
        <span>/</span>
        <span class="text-darkSlate-900 font-bold">About</span>
    </div>
</div>

<div class="max-w-[1000px] mx-auto px-6 py-12">
    <div class="bg-white rounded-3xl border border-borderLight shadow-xs p-8 md:p-12 space-y-8">
        
        <section class="about-us py-5">
    <div class="container">

        <div class="text-center mb-5">
            <span class="text-uppercase fw-bold text-warning">About eb4u</span>
            <h1 class="fw-bold mt-2">Ride More. Spend Less. Go Further.</h1>
            <p class="text-muted mx-auto" style="max-width: 750px;">
                Welcome to <strong>eb4u</strong> — your trusted destination for quality electric bikes,
                flexible rentals and essential e-bike accessories across the UK.
            </p>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="fw-bold">Making Electric Mobility Simple</h2>

                <p>
                    At eb4u, we believe electric bikes should make everyday travel
                    <strong>simpler, smarter and more affordable</strong>.
                </p>

                <p>
                    Whether you're looking to buy your first e-bike, rent one for a short trip,
                    commute to work or find the right accessories for your ride, eb4u brings
                    everything together in one convenient platform.
                </p>

                <p>
                    We provide carefully selected electric bikes designed for everyday UK riding,
                    commuting, leisure and practical transportation.
                </p>
            </div>

            <div class="col-lg-6">
                <div class="p-4 rounded shadow-sm bg-light">
                    <h3 class="fw-bold mb-3">Built Around Your Ride</h3>

                    <p>
                        Our electric bikes are supplied to meet applicable British
                        <strong>Electrically Assisted Pedal Cycles (EAPC)</strong> requirements,
                        including motor assistance limits of up to
                        <strong>15.5 mph / 25 km/h</strong> and a maximum continuous rated
                        motor power of <strong>250W</strong>.
                    </p>

                    <p class="mb-0">
                        We also offer flexible rental options, making it easier to enjoy an
                        electric bike without committing to a full purchase.
                    </p>
                </div>
            </div>
        </div>

        <div class="mb-5">
            <div class="text-center mb-4">
                <span class="text-uppercase fw-bold text-warning">Why Choose Us</span>
                <h2 class="fw-bold">Why Choose eb4u?</h2>
            </div>

            <div class="row g-4">

                <div class="col-md-6 col-lg-4">
                    <div class="h-100 p-4 border rounded">
                        <h4 class="fw-bold">Quality E-Bikes</h4>
                        <p class="mb-0">
                            Reliable electric bikes selected for everyday UK riding,
                            with a 2-year manufacturer warranty on eligible new bikes.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="h-100 p-4 border rounded">
                        <h4 class="fw-bold">Flexible Rentals</h4>
                        <p class="mb-0">
                            Choose from short-term and monthly rental options with
                            convenient booking and online rental extension options,
                            subject to fleet availability.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="h-100 p-4 border rounded">
                        <h4 class="fw-bold">Transparent Pricing</h4>
                        <p class="mb-0">
                            We aim to keep our pricing clear and simple, with UK VAT
                            included in displayed product prices and transparent rental terms.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="h-100 p-4 border rounded">
                        <h4 class="fw-bold">Safety Checked</h4>
                        <p class="mb-0">
                            Bikes supplied through our UK home delivery service are
                            pre-assembled and safety checked before dispatch.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="h-100 p-4 border rounded">
                        <h4 class="fw-bold">Complete E-Bike Solution</h4>
                        <p class="mb-0">
                            From electric bikes and rentals to chargers, locks and
                            accessories, find everything you need in one place.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="h-100 p-4 border rounded">
                        <h4 class="fw-bold">Customer Focused</h4>
                        <p class="mb-0">
                            From your first enquiry to your next ride, our goal is to
                            provide an easy, transparent and hassle-free experience.
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="p-4 rounded bg-dark text-white">
                    <span class="text-warning text-uppercase fw-bold">Our Mission</span>

                    <h2 class="fw-bold mt-2">
                        Making Electric Mobility More Accessible
                    </h2>

                    <p>
                        Our mission is to make electric mobility accessible,
                        practical and convenient for more people across the UK.
                    </p>

                    <p class="mb-0">
                        We want to remove the complexity from buying and renting an
                        e-bike by providing quality products, flexible rental choices,
                        clear terms and dependable customer service.
                    </p>
                </div>
            </div>

            <div class="col-lg-6">
                <h2 class="fw-bold">More Than Just an E-Bike Store</h2>

                <p>
                    We don't just sell bikes. We are building a complete
                    <strong>e-bike experience</strong>.
                </p>

                <p>
                    Customers can discover the right bike, rent when they need flexibility,
                    purchase useful accessories and manage their rentals through our
                    convenient online platform.
                </p>

                <p>
                    Whether you're commuting to work, exploring the city, running everyday
                    errands or simply looking for a smarter way to travel, we're here to
                    help you ride electric.
                </p>
            </div>
        </div>

        <div class="text-center p-5 rounded bg-light">
            <span class="text-uppercase fw-bold text-warning">Ride With Confidence</span>

            <h2 class="fw-bold mt-2">Ready to Ride?</h2>

            <p class="mx-auto mb-4" style="max-width: 750px;">
                Whether you're buying, renting or simply exploring your options,
                <strong>eb4u</strong> is here to help you find the right electric bike
                for your journey.
            </p>

            <h4 class="fw-bold mb-4">
                Buy an E-Bike. Rent an E-Bike. Ride Your Way.
            </h4>

            <a href="/shop" class="btn btn-warning me-2">
                Shop E-Bikes
            </a>

            <a href="/rentals" class="btn btn-dark">
                Rent an E-Bike
            </a>
        </div>

    </div>
</section>

    </div>
</div>
@endsection
