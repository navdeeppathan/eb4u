@extends('layouts.app')

@section('title', 'E-Bike 4 U | UK Premium E-Bike Rental, Sales & Cycling Accessories')

@section('content')
<!-- Hero Banner Section matching design image styling -->
<section class="relative bg-forest-900 text-white overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center opacity-30 transform scale-105"
         style="background-image: url('{{ $banners->first()->image ?? 'https://images.unsplash.com/photo-1571068316344-75bc76f77890?w=1600&auto=format&fit=crop&q=80' }}');">
    </div>
    <div class="absolute inset-0 bg-gradient-to-r from-forest-950 via-forest-900/90 to-transparent"></div>

    <div class="relative container mx-auto px-4 py-20 md:py-32 flex flex-col justify-center max-w-4xl">
        <span class="inline-block bg-amberAcc-500 text-forest-950 text-xs font-black uppercase tracking-widest px-3.5 py-1.5 rounded-full mb-4 w-max shadow-md">
            <i class="fa-solid fa-bolt mr-1.5"></i> {{ $banners->first()->badge ?? 'UK #1 E-BIKE PLATFORM' }}
        </span>
        <h1 class="text-4xl md:text-6xl font-black tracking-tight leading-none text-white mb-6">
            {{ $banners->first()->title ?? 'Experience the Future of British Cycling' }}
        </h1>
        <p class="text-cream-100/90 text-base md:text-lg max-w-2xl mb-8 font-normal leading-relaxed">
            {{ $banners->first()->subtitle ?? 'Explore premium German and UK electric bikes for immediate purchase or flexible daily, weekly & monthly rental with full UK warranty & support.' }}
        </p>

        <!-- CTA Buttons with Warm Orange & Forest Green styling -->
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('catalog.index', ['type' => 'ebike']) }}" class="bg-amberAcc-500 hover:bg-amberAcc-600 text-forest-950 font-black text-sm px-8 py-4 rounded-2xl shadow-xl hover:shadow-amberAcc-500/25 transition-all flex items-center">
                <i class="fa-solid fa-bicycle mr-2"></i> Buy E-Bike
            </a>
            <a href="{{ route('catalog.index', ['type' => 'rental']) }}" class="bg-forest-950 hover:bg-forest-800 text-amberAcc-400 border border-amberAcc-500/40 font-black text-sm px-8 py-4 rounded-2xl shadow-xl transition-all flex items-center">
                <i class="fa-solid fa-calendar-check mr-2"></i> Rent E-Bike (from £25/day)
            </a>
            <a href="{{ route('catalog.index', ['type' => 'accessory']) }}" class="bg-white/10 hover:bg-white/20 text-cream-100 font-bold text-sm px-6 py-4 rounded-2xl backdrop-blur-md transition-all flex items-center border border-cream-100/20">
                <i class="fa-solid fa-helmet-safety mr-2"></i> Shop Accessories
            </a>
        </div>
    </div>
</section>

<!-- Value Propositions Bar -->
<section class="bg-cream-200/60 border-b border-cream-300/50 py-8 shadow-xs">
    <div class="container mx-auto px-4 grid grid-cols-2 lg:grid-cols-4 gap-6 text-center md:text-left">
        <div class="flex items-center space-x-4 p-2">
            <div class="w-12 h-12 rounded-2xl bg-amberAcc-500 text-forest-950 flex items-center justify-center text-xl flex-shrink-0 shadow-sm">
                <i class="fa-solid fa-truck-ramp-box"></i>
            </div>
            <div>
                <h4 class="text-xs font-black uppercase text-forest-900">Free UK Delivery</h4>
                <p class="text-[11px] text-forest-800/80 font-medium">Fast mainland dispatch over £500</p>
            </div>
        </div>
        <div class="flex items-center space-x-4 p-2">
            <div class="w-12 h-12 rounded-2xl bg-forest-900 text-cream-100 flex items-center justify-center text-xl flex-shrink-0 shadow-sm">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div>
                <h4 class="text-xs font-black uppercase text-forest-900">Flexible Rental Plans</h4>
                <p class="text-[11px] text-forest-800/80 font-medium">Daily, weekly & monthly options</p>
            </div>
        </div>
        <div class="flex items-center space-x-4 p-2">
            <div class="w-12 h-12 rounded-2xl bg-emeraldAcc-600 text-white flex items-center justify-center text-xl flex-shrink-0 shadow-sm">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <h4 class="text-xs font-black uppercase text-forest-900">Certified Battery Safety</h4>
                <p class="text-[11px] text-forest-800/80 font-medium">Bosch, Yamaha & Shimano power</p>
            </div>
        </div>
        <div class="flex items-center space-x-4 p-2">
            <div class="w-12 h-12 rounded-2xl bg-amberAcc-500 text-forest-950 flex items-center justify-center text-xl flex-shrink-0 shadow-sm">
                <i class="fa-solid fa-rotate-left"></i>
            </div>
            <div>
                <h4 class="text-xs font-black uppercase text-forest-900">Deposit & Refund Safe</h4>
                <p class="text-[11px] text-forest-800/80 font-medium">Instant deposit return on bike return</p>
            </div>
        </div>
    </div>
</section>

<!-- E-Bike Categories Section -->
<section class="py-16 bg-[#f4f0e6]">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-end mb-10">
            <div>
                <span class="text-xs font-black text-amberAcc-600 uppercase tracking-widest">E-Bike Range</span>
                <h2 class="text-2xl md:text-3xl font-black text-forest-900 mt-1">Explore E-Bike Categories</h2>
            </div>
            <a href="{{ route('catalog.index', ['type' => 'ebike']) }}" class="text-xs font-black text-forest-900 hover:text-amberAcc-600 flex items-center">
                View All Categories <i class="fa-solid fa-arrow-right ml-1 text-amberAcc-500"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach($ebikeCategories as $cat)
                <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" class="group bg-white p-5 rounded-3xl border border-cream-200 shadow-sm hover:border-amberAcc-500 hover:shadow-xl transition-all text-center">
                    <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-cream-100 group-hover:bg-amberAcc-500 text-forest-900 group-hover:text-forest-950 flex items-center justify-center text-2xl transition-colors shadow-xs">
                        <i class="fa-solid fa-bicycle"></i>
                    </div>
                    <h3 class="text-xs font-black text-forest-900 group-hover:text-amberAcc-600 transition-colors">{{ $cat->name }}</h3>
                    <p class="text-[10px] text-slate-500 mt-1 line-clamp-1 font-medium">{{ $cat->description }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured E-Bikes Section -->
<section class="py-16 bg-white border-t border-cream-200">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-end mb-10">
            <div>
                <span class="text-xs font-black text-amberAcc-600 uppercase tracking-widest">Handpicked Fleet</span>
                <h2 class="text-2xl md:text-3xl font-black text-forest-900 mt-1">Featured E-Bikes</h2>
            </div>
            <a href="{{ route('catalog.index', ['type' => 'ebike']) }}" class="text-xs font-black text-forest-900 hover:text-amberAcc-600 flex items-center">
                Explore Full Catalog <i class="fa-solid fa-arrow-right ml-1 text-amberAcc-500"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredEBikes as $product)
                <div class="group bg-[#fcfbfa] rounded-3xl border border-cream-200 overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 flex flex-col justify-between">
                    <div class="relative bg-cream-100 aspect-video overflow-hidden">
                        <img src="{{ $product->primary_image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @if($product->discount_percentage > 0)
                            <span class="absolute top-3 left-3 bg-amberAcc-500 text-forest-950 text-[10px] font-black uppercase px-2.5 py-1 rounded-full shadow-md">
                                SAVE {{ $product->discount_percentage }}%
                            </span>
                        @endif
                        <span class="absolute top-3 right-3 bg-forest-900/90 text-cream-100 text-[10px] font-bold px-2.5 py-1 rounded-full border border-cream-200/20">
                            Rent £{{ number_format($product->rental_price_daily, 0) }}/day
                        </span>
                    </div>

                    <div class="p-5 flex-grow flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-[11px] text-slate-500 font-semibold mb-1">
                                <span class="uppercase font-bold text-amberAcc-700">{{ $product->brand->name ?? 'Premium' }}</span>
                                <span class="text-amber-500"><i class="fa-solid fa-star"></i> {{ $product->average_rating }}</span>
                            </div>
                            <a href="{{ route('products.show', $product->slug) }}" class="text-sm font-black text-forest-900 hover:text-amberAcc-600 line-clamp-2 mb-2">
                                {{ $product->name }}
                            </a>

                            <!-- Specs Pill -->
                            <div class="bg-cream-100/80 p-2.5 rounded-2xl text-[11px] text-forest-900 space-y-1 mb-4 border border-cream-200">
                                <div><i class="fa-solid fa-microchip text-amberAcc-600 mr-1.5"></i> {{ $product->motor_specs }}</div>
                                <div><i class="fa-solid fa-battery-three-quarters text-emeraldAcc-600 mr-1.5"></i> {{ $product->battery_specs }}</div>
                                <div><i class="fa-solid fa-route text-forest-700 mr-1.5"></i> {{ $product->range_specs }}</div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-baseline justify-between mb-4">
                                <div>
                                    <span class="text-xl font-black text-forest-900">£{{ number_format($product->effective_price, 2) }}</span>
                                    @if($product->discount_price)
                                        <span class="text-xs text-slate-400 line-through ml-1">£{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <span class="text-[10px] font-bold text-slate-400">Inc. UK VAT</span>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="text-center py-2.5 bg-forest-900 hover:bg-forest-800 text-white rounded-xl text-xs font-black transition-colors">
                                    Buy Now
                                </a>
                                <a href="{{ route('products.show', $product->slug) }}#rental" class="text-center py-2.5 bg-amberAcc-500 hover:bg-amberAcc-400 text-forest-950 rounded-xl text-xs font-black transition-colors shadow-sm">
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

<!-- How E-Bike Rental Works Section with Emerald Accent Block -->
<section class="py-20 bg-forest-900 text-white relative overflow-hidden">
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="bg-amberAcc-500 text-forest-950 text-xs font-black uppercase px-3.5 py-1 rounded-full shadow-md">
                Simple & Seamless UK Process
            </span>
            <h2 class="text-3xl md:text-4xl font-black text-white mt-3 mb-4">How E-Bike Rental Works</h2>
            <p class="text-cream-200/80 text-sm">Rent top-rated electric bikes in under 2 minutes with real-time calendar availability and instant deposit protection.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="bg-forest-950/80 border border-cream-200/20 p-6 rounded-3xl text-center relative group hover:border-amberAcc-500 transition-all">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-amberAcc-500 text-forest-950 font-black text-xl flex items-center justify-center shadow-lg">
                    1
                </div>
                <h3 class="text-base font-bold text-white mb-2">Choose Your E-Bike</h3>
                <p class="text-xs text-cream-200/80 leading-relaxed">Select from City, Mountain, Folding, or Long-Range E-Bikes suited to your ride.</p>
            </div>

            <div class="bg-forest-950/80 border border-cream-200/20 p-6 rounded-3xl text-center relative group hover:border-amberAcc-500 transition-all">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-emeraldAcc-600 text-white font-black text-xl flex items-center justify-center shadow-lg">
                    2
                </div>
                <h3 class="text-base font-bold text-white mb-2">Pick Rental Dates</h3>
                <p class="text-xs text-cream-200/80 leading-relaxed">Select daily, weekly, or monthly dates. Our live system checks physical bike availability.</p>
            </div>

            <div class="bg-forest-950/80 border border-cream-200/20 p-6 rounded-3xl text-center relative group hover:border-amberAcc-500 transition-all">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-amberAcc-500 text-forest-950 font-black text-xl flex items-center justify-center shadow-lg">
                    3
                </div>
                <h3 class="text-base font-bold text-white mb-2">30% Advance or Full</h3>
                <p class="text-xs text-cream-200/80 leading-relaxed">Pay just 30% advance to hold your bike or pay full amount online. Select store pickup or home delivery.</p>
            </div>

            <div class="bg-forest-950/80 border border-cream-200/20 p-6 rounded-3xl text-center relative group hover:border-amberAcc-500 transition-all">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-emeraldAcc-600 text-white font-black text-xl flex items-center justify-center shadow-lg">
                    4
                </div>
                <h3 class="text-base font-bold text-white mb-2">Ride & Easily Return</h3>
                <p class="text-xs text-cream-200/80 leading-relaxed">Enjoy your ride! Extend online anytime or return to receive instant security deposit refund.</p>
            </div>
        </div>
    </div>
</section>

<!-- Popular Accessories Section -->
<section class="py-16 bg-[#f4f0e6]">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-end mb-10">
            <div>
                <span class="text-xs font-black text-amberAcc-600 uppercase tracking-widest">Gear Up</span>
                <h2 class="text-2xl md:text-3xl font-black text-forest-900 mt-1">Popular Cycling Accessories</h2>
            </div>
            <a href="{{ route('catalog.index', ['type' => 'accessory']) }}" class="text-xs font-black text-forest-900 hover:text-amberAcc-600 flex items-center">
                Shop All Accessories <i class="fa-solid fa-arrow-right ml-1 text-amberAcc-500"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($popularAccessories as $acc)
                <div class="bg-white p-4 rounded-3xl border border-cream-200 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between">
                    <div>
                        <div class="aspect-square bg-cream-100 rounded-2xl overflow-hidden mb-3">
                            <img src="{{ $acc->primary_image_url }}" class="w-full h-full object-cover">
                        </div>
                        <h4 class="text-xs font-black text-forest-900 line-clamp-2 mb-1">{{ $acc->name }}</h4>
                        <p class="text-[10px] text-slate-400 font-semibold">{{ $acc->brand->name ?? 'Accessory' }}</p>
                    </div>
                    <div class="mt-3 pt-2 border-t border-cream-100 flex justify-between items-center">
                        <span class="text-xs font-black text-forest-900">£{{ number_format($acc->effective_price, 2) }}</span>
                        <button @click="addToCart({{ $acc->id }}, 'purchase')" class="w-7 h-7 rounded-full bg-amberAcc-500 text-forest-950 flex items-center justify-center hover:bg-amberAcc-400 transition-colors">
                            <i class="fa-solid fa-cart-plus text-xs"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Customer Reviews Section -->
<section class="py-16 bg-white border-t border-cream-200">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-xl mx-auto mb-12">
            <span class="text-xs font-black text-amberAcc-600 uppercase tracking-widest">Verified Feedback</span>
            <h2 class="text-2xl md:text-3xl font-black text-forest-900 mt-1">What British Cyclists Say</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($reviews as $rev)
                <div class="bg-[#fcfbfa] p-6 rounded-3xl border border-cream-200 flex flex-col justify-between shadow-xs">
                    <div>
                        <div class="flex text-amber-500 text-xs mb-3">
                            @for($i=1; $i<=5; $i++)
                                <i class="fa-solid fa-star {{ $i <= $rev->rating ? '' : 'text-slate-300' }}"></i>
                            @endfor
                        </div>
                        <h4 class="text-sm font-black text-forest-900 mb-2">"{{ $rev->title }}"</h4>
                        <p class="text-xs text-slate-600 leading-relaxed italic mb-4">"{{ $rev->comment }}"</p>
                    </div>

                    <div class="flex items-center space-x-3 pt-3 border-t border-cream-200">
                        <img src="{{ $rev->user->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" class="w-8 h-8 rounded-full object-cover">
                        <div>
                            <span class="block text-xs font-bold text-forest-900">{{ $rev->user->name ?? 'Verified Rider' }}</span>
                            <span class="block text-[10px] text-emeraldAcc-600 font-bold"><i class="fa-solid fa-circle-check"></i> Verified Rental Customer</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-16 bg-forest-950 text-white border-t border-forest-800">
    <div class="container mx-auto px-4 text-center max-w-xl" x-data="{ email: '', message: '', success: false }">
        <i class="fa-solid fa-paper-plane text-3xl text-amberAcc-500 mb-3"></i>
        <h2 class="text-2xl font-black text-white mb-2">Join the E-Bike 4 U Club</h2>
        <p class="text-xs text-cream-200/80 mb-6">Subscribe for exclusive UK rental discounts, new E-Bike arrival alerts, and £10 off your first accessory order!</p>

        <form @submit.prevent="axios.post('{{ route('newsletter.subscribe') }}', { email: email }).then(r => { message = r.data.message; success = true; email = ''; })" class="flex flex-col sm:flex-row gap-2">
            <input type="email" x-model="email" required placeholder="Enter your email address..." class="flex-grow bg-forest-900 text-cream-100 text-xs px-4 py-3 rounded-xl border border-cream-200/20 focus:outline-none focus:ring-2 focus:ring-amberAcc-500">
            <button type="submit" class="bg-amberAcc-500 hover:bg-amberAcc-400 text-forest-950 font-black text-xs px-6 py-3 rounded-xl shadow-lg transition-colors">
                Subscribe
            </button>
        </form>

        <div x-show="message" x-cloak class="mt-3 text-xs font-bold text-amberAcc-400" x-text="message"></div>
    </div>
</section>
@endsection
