@extends('layouts.app')

@section('title', 'eb4u | UK Premium E-Bike Rental, Sales & Cycling Accessories')

@section('content')
<!-- HERO SECTION (Exact design from Testing Platform Homepage.dc.html) -->
<section class="min-h-[90vh] bg-[#f5f7fb] relative overflow-hidden flex items-center py-16 md:py-24">
    <!-- Radial Orange Glow Background -->
    <div class="absolute -top-32 -right-20 w-[700px] h-[700px] bg-[radial-gradient(circle,rgba(249,115,22,0.12)_0%,transparent_70%)] pointer-events-none"></div>
    <div class="absolute -bottom-48 -left-16 w-[500px] h-[500px] bg-[radial-gradient(circle,rgba(249,115,22,0.08)_0%,transparent_70%)] pointer-events-none"></div>
    <div class="absolute inset-0 bg-[radial-gradient(rgba(0,0,0,0.04)_1px,transparent_1px)] [background-size:32px_32px] pointer-events-none"></div>

    <div class="max-w-[1320px] mx-auto px-6 w-full flex flex-col lg:flex-row items-center justify-between gap-12 relative z-10">
        
        <!-- Left: Headline & Actions -->
        <div class="max-w-[620px]">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-brandOrange-50 border border-brandOrange-500/25 rounded-full mb-6">
                <div class="w-2 h-2 rounded-full bg-brandOrange-500 animate-pulse"></div>
                <span class="text-xs font-semibold text-brandOrange-600 tracking-wide">UK's #1 E-Bike Platform · Free Delivery</span>
            </div>

            <h1 class="font-grotesk text-5xl sm:text-7xl lg:text-8xl font-extrabold leading-[1.0] tracking-[-3px] text-darkSlate-900 mb-6">
                Ride<br><span class="text-brandOrange-500">Electric.</span><br><span class="text-textSec text-[0.7em] font-bold tracking-[-2px]">Ride Free.</span>
            </h1>

            <p class="text-textSec text-base sm:text-lg leading-relaxed mb-8 max-w-[480px]">
                Buy, rent or try premium e-bikes across the UK. City commuters to mountain explorers — your perfect electric ride awaits.
            </p>

            <div class="flex flex-wrap gap-3 mb-10">
                <a href="{{ route('catalog.index', ['type' => 'ebike']) }}" class="inline-flex items-center gap-2.5 px-7 py-4 bg-brandOrange-500 hover:bg-brandOrange-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-brandOrange-500/20 hover:shadow-brandOrange-500/35 transition-all transform hover:-translate-y-0.5">
                    Shop E-Bikes
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="2" y1="7.5" x2="13" y2="7.5"/><polyline points="8.5,3 13,7.5 8.5,12"/></svg>
                </a>
                <a href="{{ route('catalog.index', ['type' => 'rental']) }}" class="inline-flex items-center gap-2.5 px-7 py-4 bg-transparent text-darkSlate-900 border-2 border-borderMid hover:border-brandOrange-500 hover:text-brandOrange-500 hover:bg-brandOrange-50/50 rounded-xl text-sm font-bold transition-all">
                    Rent an E-Bike
                </a>
                <a href="{{ route('catalog.index', ['type' => 'accessory']) }}" class="inline-flex items-center gap-2.5 px-7 py-4 bg-transparent text-textSec border border-borderLight hover:border-borderMid hover:text-darkSlate-900 rounded-xl text-sm font-semibold transition-all">
                    Accessories
                </a>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 pt-4 border-t border-borderLight/60">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 bg-brandOrange-50 rounded-xl flex items-center justify-center text-brandOrange-500 flex-shrink-0">
                        <i class="fa-solid fa-bicycle"></i>
                    </div>
                    <div>
                        <div class="font-grotesk text-base font-bold text-darkSlate-900">500+</div>
                        <div class="text-[11px] text-textMuted font-medium">E-Bikes</div>
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 bg-brandOrange-50 rounded-xl flex items-center justify-center text-brandOrange-500 flex-shrink-0">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <div>
                        <div class="font-grotesk text-base font-bold text-darkSlate-900">4.9 ★</div>
                        <div class="text-[11px] text-textMuted font-medium">Rating</div>
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 bg-brandOrange-50 rounded-xl flex items-center justify-center text-brandOrange-500 flex-shrink-0">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <div>
                        <div class="font-grotesk text-base font-bold text-darkSlate-900">Free</div>
                        <div class="text-[11px] text-textMuted font-medium">UK Delivery</div>
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 bg-brandOrange-50 rounded-xl flex items-center justify-center text-brandOrange-500 flex-shrink-0">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <div class="font-grotesk text-base font-bold text-darkSlate-900">2-Year</div>
                        <div class="text-[11px] text-textMuted font-medium">Warranty</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Floating Hero Bike Image Graphic -->
        <div class="flex-1 max-w-[520px] relative flex items-center justify-center">
            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[380px] h-[60px] bg-[radial-gradient(ellipse,rgba(249,115,22,0.28)_0%,transparent_70%)] filter blur-lg"></div>
            
            <!-- Bike Graphic Frame -->
            <div class="relative bg-gradient-to-br from-[#1a2d42] to-[#0f172a] rounded-3xl p-6 border border-borderMid shadow-2xl overflow-hidden group">
                <img src="{{ $banners->first()->image ?? 'https://images.unsplash.com/photo-1571068316344-75bc76f77890?w=1200&auto=format&fit=crop&q=80' }}" class="w-full h-[320px] object-cover rounded-2xl group-hover:scale-105 transition-transform duration-700">
                <div class="absolute top-10 right-10 bg-brandOrange-500 text-white text-xs font-black px-3.5 py-1.5 rounded-full shadow-lg">
                    ⚡ Certified 250W German Motor
                </div>
            </div>
        </div>

    </div>
</section>

<!-- CATEGORIES SECTION -->
<section class="py-16 bg-[#edf1f8] border-y border-borderLight">
    <div class="max-w-[1320px] mx-auto px-6">
        <div class="flex justify-between items-end mb-8">
            <div>
                <span class="text-xs font-bold text-brandOrange-500 uppercase tracking-wider">E-Bike Range</span>
                <h2 class="font-grotesk text-2xl md:text-3xl font-extrabold text-darkSlate-900 mt-1">Explore E-Bike Categories</h2>
            </div>
            <a href="{{ route('catalog.index', ['type' => 'ebike']) }}" class="text-xs font-bold text-darkSlate-900 hover:text-brandOrange-500 flex items-center">
                View All Categories <i class="fa-solid fa-arrow-right ml-1.5 text-brandOrange-500"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($ebikeCategories as $cat)
                <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" class="group bg-white p-5 rounded-2xl border border-borderLight shadow-xs hover:border-brandOrange-500 hover:shadow-lg transition-all text-center">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-[#f5f7fb] group-hover:bg-brandOrange-500 text-darkSlate-900 group-hover:text-white flex items-center justify-center text-xl transition-colors">
                        <i class="fa-solid fa-bicycle"></i>
                    </div>
                    <h3 class="font-grotesk text-xs font-bold text-darkSlate-900 group-hover:text-brandOrange-500 transition-colors">{{ $cat->name }}</h3>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- FEATURED PRODUCTS GRID -->
<section class="py-20 bg-[#f5f7fb]">
    <div class="max-w-[1320px] mx-auto px-6">
        <div class="flex justify-between items-end mb-10">
            <div>
                <span class="text-xs font-bold text-brandOrange-500 uppercase tracking-wider">Handpicked Fleet</span>
                <h2 class="font-grotesk text-2xl md:text-3xl font-extrabold text-darkSlate-900 mt-1">Featured E-Bikes & Gear</h2>
            </div>
            <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-darkSlate-900 hover:text-brandOrange-500 flex items-center">
                Explore Full Catalog <i class="fa-solid fa-arrow-right ml-1.5 text-brandOrange-500"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredEBikes as $product)
                <div class="group bg-white rounded-2xl border border-borderLight overflow-hidden shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                    <div class="relative h-44 bg-gradient-to-br from-[#dde8f7] to-[#cddaf2] flex items-center justify-center overflow-hidden">
                        <img src="{{ $product->primary_image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @if($product->discount_percentage > 0)
                            <span class="absolute top-2.5 left-2.5 bg-brandOrange-500 text-white text-[10px] font-black uppercase px-2 py-0.5 rounded-md shadow-xs">
                                SAVE {{ $product->discount_percentage }}%
                            </span>
                        @endif
                        <span class="absolute top-2.5 right-2.5 bg-darkSlate-900 text-white text-[10px] font-bold px-2 py-0.5 rounded-md">
                            Rent £{{ number_format($product->rental_price_daily, 0) }}/day
                        </span>
                    </div>

                    <div class="p-4 flex-grow flex flex-col justify-between">
                        <div>
                            <div class="text-[10px] font-bold text-brandOrange-600 uppercase tracking-wider mb-1">{{ $product->brand->name ?? 'Premium' }}</div>
                            <a href="{{ route('products.show', $product->slug) }}" class="font-grotesk text-sm font-bold text-darkSlate-900 hover:text-brandOrange-500 line-clamp-1 mb-2">
                                {{ $product->name }}
                            </a>

                            <div class="flex items-center gap-1 mb-3">
                                <span class="text-amber-400 text-xs"><i class="fa-solid fa-star"></i></span>
                                <span class="text-xs font-semibold text-darkSlate-900">{{ $product->average_rating }}</span>
                                <span class="text-[11px] text-textMuted">({{ $product->reviews_count }})</span>
                            </div>

                            <div class="flex flex-wrap gap-1.5 mb-4">
                                <span class="px-2 py-0.5 bg-[#edf1f8] rounded-md text-[10px] font-medium text-textSec">⚡ {{ $product->range_specs ?? '75 miles' }}</span>
                                <span class="px-2 py-0.5 bg-[#edf1f8] rounded-md text-[10px] font-medium text-textSec">◎ {{ $product->motor_specs ?? '250W' }}</span>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-baseline justify-between mb-3">
                                <div class="flex items-baseline gap-1.5">
                                    <span class="font-grotesk text-lg font-extrabold text-brandOrange-500">£{{ number_format($product->effective_price, 2) }}</span>
                                    @if($product->discount_price)
                                        <span class="text-xs text-textMuted line-through">£{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <button @click="addToCart({{ $product->id }}, 'purchase')" class="w-8 h-8 rounded-lg bg-brandOrange-50 border border-brandOrange-500/30 text-brandOrange-500 hover:bg-brandOrange-500 hover:text-white flex items-center justify-center transition-colors">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="text-center py-2 bg-darkSlate-900 hover:bg-black text-white rounded-xl text-xs font-bold transition-colors">
                                    Buy Now
                                </a>
                                <a href="{{ route('products.show', $product->slug) }}#rental" class="text-center py-2 bg-brandOrange-500 hover:bg-brandOrange-600 text-white rounded-xl text-xs font-bold transition-colors shadow-xs">
                                    Rent Bike
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- HOW E-BIKE RENTAL WORKS SECTION -->
<section class="py-20 bg-white border-t border-borderLight">
    <div class="max-w-[1320px] mx-auto px-6">
        <div class="text-center max-w-xl mx-auto mb-16">
            <span class="bg-brandOrange-50 text-brandOrange-600 text-xs font-bold uppercase px-3.5 py-1.5 rounded-full border border-brandOrange-500/20">
                Simple & Seamless UK Process
            </span>
            <h2 class="font-grotesk text-3xl md:text-4xl font-extrabold text-darkSlate-900 mt-3 mb-3">How E-Bike Rental Works</h2>
            <p class="text-textSec text-sm">Rent top-rated electric bikes in under 2 minutes with real-time calendar availability and instant deposit protection.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-[#f5f7fb] border border-borderLight p-6 rounded-2xl text-center relative group hover:border-brandOrange-500 transition-all">
                <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-brandOrange-500 text-white font-grotesk font-extrabold text-lg flex items-center justify-center shadow-md">
                    1
                </div>
                <h3 class="font-grotesk text-base font-bold text-darkSlate-900 mb-2">Choose Your E-Bike</h3>
                <p class="text-xs text-textSec leading-relaxed">Select from City, Mountain, Folding, or Long-Range E-Bikes suited to your ride.</p>
            </div>

            <div class="bg-[#f5f7fb] border border-borderLight p-6 rounded-2xl text-center relative group hover:border-brandOrange-500 transition-all">
                <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-brandOrange-50 text-brandOrange-600 font-grotesk font-extrabold text-lg flex items-center justify-center border border-brandOrange-500/30">
                    2
                </div>
                <h3 class="font-grotesk text-base font-bold text-darkSlate-900 mb-2">Pick Rental Dates</h3>
                <p class="text-xs text-textSec leading-relaxed">Select daily, weekly, or monthly dates. Our live system checks physical bike availability.</p>
            </div>

            <div class="bg-[#f5f7fb] border border-borderLight p-6 rounded-2xl text-center relative group hover:border-brandOrange-500 transition-all">
                <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-brandOrange-500 text-white font-grotesk font-extrabold text-lg flex items-center justify-center shadow-md">
                    3
                </div>
                <h3 class="font-grotesk text-base font-bold text-darkSlate-900 mb-2">30% Advance or Full</h3>
                <p class="text-xs text-textSec leading-relaxed">Pay just 30% advance to hold your bike or pay full amount online. Select store pickup or home delivery.</p>
            </div>

            <div class="bg-[#f5f7fb] border border-borderLight p-6 rounded-2xl text-center relative group hover:border-brandOrange-500 transition-all">
                <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-brandOrange-50 text-brandOrange-600 font-grotesk font-extrabold text-lg flex items-center justify-center border border-brandOrange-500/30">
                    4
                </div>
                <h3 class="font-grotesk text-base font-bold text-darkSlate-900 mb-2">Ride & Easily Return</h3>
                <p class="text-xs text-textSec leading-relaxed">Enjoy your ride! Extend online anytime or return to receive instant security deposit refund.</p>
            </div>
        </div>
    </div>
</section>

<!-- POPULAR ACCESSORIES -->
<section class="py-16 bg-[#f5f7fb] border-t border-borderLight">
    <div class="max-w-[1320px] mx-auto px-6">
        <div class="flex justify-between items-end mb-10">
            <div>
                <span class="text-xs font-bold text-brandOrange-500 uppercase tracking-wider">Gear Up</span>
                <h2 class="font-grotesk text-2xl md:text-3xl font-extrabold text-darkSlate-900 mt-1">Popular Cycling Accessories</h2>
            </div>
            <a href="{{ route('catalog.index', ['type' => 'accessory']) }}" class="text-xs font-bold text-darkSlate-900 hover:text-brandOrange-500 flex items-center">
                Shop All Accessories <i class="fa-solid fa-arrow-right ml-1.5 text-brandOrange-500"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($popularAccessories as $acc)
                <div class="bg-white p-4 rounded-2xl border border-borderLight shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="aspect-square bg-[#edf1f8] rounded-xl overflow-hidden mb-3 flex items-center justify-center">
                            <img src="{{ $acc->primary_image_url }}" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-grotesk text-xs font-bold text-darkSlate-900 line-clamp-2 mb-1">{{ $acc->name }}</h4>
                        <p class="text-[10px] text-textMuted font-semibold">{{ $acc->brand->name ?? 'Accessory' }}</p>
                    </div>
                    <div class="mt-3 pt-2 border-t border-slate-100 flex justify-between items-center">
                        <span class="font-grotesk text-xs font-bold text-brandOrange-500">£{{ number_format($acc->effective_price, 2) }}</span>
                        <button @click="addToCart({{ $acc->id }}, 'purchase')" class="w-7 h-7 rounded-lg bg-brandOrange-50 text-brandOrange-500 border border-brandOrange-500/30 flex items-center justify-center hover:bg-brandOrange-500 hover:text-white transition-colors">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- REVIEWS & NEWSLETTER -->
<section class="py-16 bg-white border-t border-borderLight">
    <div class="max-w-[1320px] mx-auto px-6">
        <div class="text-center max-w-xl mx-auto mb-12">
            <span class="text-xs font-bold text-brandOrange-500 uppercase tracking-wider">Verified Feedback</span>
            <h2 class="font-grotesk text-2xl md:text-3xl font-extrabold text-darkSlate-900 mt-1">What British Cyclists Say</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($reviews as $rev)
                <div class="bg-[#f5f7fb] p-6 rounded-2xl border border-borderLight flex flex-col justify-between">
                    <div>
                        <div class="flex text-amber-400 text-xs mb-3">
                            @for($i=1; $i<=5; $i++)
                                <i class="fa-solid fa-star {{ $i <= $rev->rating ? '' : 'text-slate-300' }}"></i>
                            @endfor
                        </div>
                        <h4 class="font-grotesk text-sm font-bold text-darkSlate-900 mb-2">"{{ $rev->title }}"</h4>
                        <p class="text-xs text-textSec leading-relaxed italic mb-4">"{{ $rev->comment }}"</p>
                    </div>

                    <div class="flex items-center space-x-3 pt-3 border-t border-borderLight">
                        <img src="{{ $rev->user->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" class="w-8 h-8 rounded-full object-cover">
                        <div>
                            <span class="block text-xs font-bold text-darkSlate-900">{{ $rev->user->name ?? 'Verified Rider' }}</span>
                            <span class="block text-[10px] text-brandOrange-600 font-bold"><i class="fa-solid fa-circle-check"></i> Verified Rental Customer</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
