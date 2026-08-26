@extends('layouts.app')

@section('title', 'eb4u | UK Premium E-Bike Rental, Sales & Cycling Accessories')

@section('content')
<!-- Hero Section (Black & Orange Theme) -->
<section class="relative bg-darkBlack-950 text-white overflow-hidden py-16 md:py-24 border-b border-darkBlack-800">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(242,78,0,0.18)_0%,transparent_60%)] pointer-events-none"></div>

    <div class="container mx-auto px-6 md:px-12 flex flex-col lg:flex-row items-center justify-between gap-12 relative z-10">
        
        <!-- Hero Headline & Actions -->
        <div class="max-w-2xl space-y-6">
            <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full bg-brandOrange-500/10 border border-brandOrange-500/30 text-brandOrange-400 text-xs font-bold uppercase tracking-wider">
                <i class="fa-solid fa-bolt text-brandOrange-500"></i>
                <span>UK's #1 Premium E-Bike Platform</span>
            </div>

            <h1 class="text-4xl sm:text-6xl font-black text-white leading-tight tracking-tight">
                Ride Electric. <br>
                <span class="text-brandOrange-500">Ride Free.</span>
            </h1>

            <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-xl font-normal">
                Buy, rent, or try premium electric bikes across the UK. Flexible daily, weekly & monthly rentals with 30% advance booking and instant security deposit protection.
            </p>

            <div class="flex flex-wrap gap-4 pt-2">
                <a href="{{ route('catalog.index', ['type' => 'ebike']) }}" class="px-8 py-3.5 bg-brandOrange-500 hover:bg-brandOrange-600 text-white rounded-full font-black text-xs uppercase tracking-wider shadow-lg shadow-brandOrange-500/25 transition-all transform hover:-translate-y-0.5">
                    Shop E-Bikes <i class="fa-solid fa-arrow-right ml-2"></i>
                </a>
                <a href="{{ route('catalog.index', ['type' => 'rental']) }}" class="px-8 py-3.5 bg-darkBlack-800 hover:bg-black text-white border border-brandOrange-500/40 rounded-full font-bold text-xs uppercase tracking-wider transition-all">
                    ⚡ Rent an E-Bike
                </a>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 pt-8 border-t border-darkBlack-800">
                <div>
                    <span class="block text-xl font-black text-brandOrange-500">500+</span>
                    <span class="text-xs text-slate-400 font-medium">E-Bikes Available</span>
                </div>
                <div>
                    <span class="block text-xl font-black text-white">4.9 ★</span>
                    <span class="text-xs text-slate-400 font-medium">Rider Rating</span>
                </div>
                <div>
                    <span class="block text-xl font-black text-brandOrange-500">Free</span>
                    <span class="text-xs text-slate-400 font-medium">UK Delivery > £500</span>
                </div>
                <div>
                    <span class="block text-xl font-black text-white">2-Year</span>
                    <span class="text-xs text-slate-400 font-medium">UK Warranty</span>
                </div>
            </div>
        </div>

        <!-- Right Hero Banner Card (Ultra-Smooth Crossfade Slider) -->
        <div class="w-full lg:w-1/2 max-w-lg relative" x-data="heroBannerSlider({{ $banners->count() }})">
            <div class="relative h-[380px] sm:h-[420px] w-full rounded-3xl overflow-hidden border border-white/15 shadow-2xl bg-darkBlack-950">
                
                @forelse($banners as $index => $b)
                    <div :class="activeSlide === {{ $index }} ? 'opacity-100 z-10 pointer-events-auto scale-100' : 'opacity-0 z-0 pointer-events-none scale-105'" 
                         class="absolute inset-0 transition-all duration-700 ease-in-out">
                        
                        <!-- Banner Background Image -->
                        <img src="{{ Str::startsWith($b->image, 'http') ? $b->image : asset($b->image) }}" 
                             alt="{{ $b->title }}" 
                             class="w-full h-full object-cover">
                        
                        <!-- Dark Overlay Gradient for Readable Text -->
                        <div class="absolute inset-0 bg-gradient-to-t from-darkBlack-950 via-darkBlack-950/50 to-transparent"></div>
                        
                        <!-- Content Overlay -->
                        <div class="absolute bottom-6 left-6 right-6 space-y-2.5 z-10">
                            @if($b->badge)
                                <span class="bg-brandOrange-500 text-white text-[10px] font-black uppercase px-3 py-1 rounded-md inline-block shadow-md tracking-wider">
                                    {{ $b->badge }}
                                </span>
                            @endif
                            
                            <h3 class="text-xl sm:text-2xl font-black text-white leading-tight drop-shadow-md">
                                {{ $b->title }}
                            </h3>
                            
                            @if($b->subtitle)
                                <p class="text-xs text-slate-300 font-normal line-clamp-2 leading-relaxed max-w-md">
                                    {{ $b->subtitle }}
                                </p>
                            @endif
                            
                            @if($b->button_text)
                                <div class="pt-1">
                                    <a href="{{ $b->button_url ?? route('catalog.index') }}" class="inline-flex items-center space-x-2 text-xs font-black text-white bg-brandOrange-500 hover:bg-brandOrange-600 px-5 py-2.5 rounded-full shadow-lg transition-all transform hover:-translate-y-0.5">
                                        <span>{{ $b->button_text }}</span>
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="absolute inset-0">
                        <img src="https://images.unsplash.com/photo-1571068316344-75bc76f77890?w=1200&auto=format&fit=crop&q=80" alt="Featured E-Bike" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-darkBlack-950 via-transparent to-transparent"></div>
                        <div class="absolute bottom-6 left-6 right-6">
                            <span class="bg-brandOrange-500 text-white text-[10px] font-black uppercase px-2.5 py-1 rounded-md mb-2 inline-block">
                                ⚡ Certified 250W German Motor
                            </span>
                            <h3 class="text-xl font-black text-white">Urban Commuter Pro E-Bike</h3>
                        </div>
                    </div>
                @endforelse

                @if($banners->count() > 1)
                    <!-- Sleek Navigation Arrows -->
                    <button @click="prev()" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/50 hover:bg-brandOrange-500 text-white backdrop-blur-md flex items-center justify-center transition-all opacity-80 hover:opacity-100 z-20 border border-white/20 hover:scale-110 shadow-lg">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <button @click="next()" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/50 hover:bg-brandOrange-500 text-white backdrop-blur-md flex items-center justify-center transition-all opacity-80 hover:opacity-100 z-20 border border-white/20 hover:scale-110 shadow-lg">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>

                    <!-- Top Right Slide Indicator Pills -->
                    <div class="absolute top-4 right-4 z-20 flex items-center space-x-1.5 bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/20">
                        <template x-for="(b, i) in totalSlides" :key="i">
                            <button @click="activeSlide = i" 
                                    :class="activeSlide === i ? 'w-6 bg-brandOrange-500' : 'w-2 bg-white/40 hover:bg-white'" 
                                    class="h-2 rounded-full transition-all duration-300"></button>
                        </template>
                    </div>
                @endif

            </div>
        </div>

    </div>
</section>

<!-- Categories Section -->
<section class="py-16 bg-slate-50">
    <div class="container mx-auto px-6 md:px-12">
        <div class="flex justify-between items-end mb-8">
            <div>
                <span class="text-xs font-black text-brandOrange-500 uppercase tracking-widest">E-Bike Range</span>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 mt-1">Explore E-Bike Categories</h2>
            </div>
            <a href="{{ route('catalog.index', ['type' => 'ebike']) }}" class="text-xs font-black text-slate-900 hover:text-brandOrange-500 flex items-center">
                View All Categories <i class="fa-solid fa-arrow-right ml-1 text-brandOrange-500"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($ebikeCategories as $cat)
                <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" class="group bg-white p-5 rounded-3xl border border-slate-200 shadow-sm hover:border-brandOrange-500 hover:shadow-lg transition-all text-center">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-slate-100 group-hover:bg-brandOrange-500 text-slate-900 group-hover:text-white flex items-center justify-center text-xl transition-colors">
                        <i class="fa-solid fa-bicycle"></i>
                    </div>
                    <h3 class="text-xs font-black text-slate-900 group-hover:text-brandOrange-500 transition-colors">{{ $cat->name }}</h3>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products Grid -->
<section class="py-20 bg-white border-t border-slate-200">
    <div class="container mx-auto px-6 md:px-12">
        <div class="flex justify-between items-end mb-10">
            <div>
                <span class="text-xs font-black text-brandOrange-500 uppercase tracking-widest">Handpicked Fleet</span>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 mt-1">Featured E-Bikes & Gear</h2>
            </div>
            <a href="{{ route('catalog.index') }}" class="text-xs font-black text-slate-900 hover:text-brandOrange-500 flex items-center">
                Explore Full Catalog <i class="fa-solid fa-arrow-right ml-1 text-brandOrange-500"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredEBikes as $product)
                <div class="group bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                    <div class="relative h-48 bg-slate-100 flex items-center justify-center overflow-hidden">
                        <img src="{{ $product->primary_image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @if($product->discount_percentage > 0)
                            <span class="absolute top-3 left-3 bg-brandOrange-500 text-white text-[10px] font-black uppercase px-2.5 py-1 rounded-md shadow-sm">
                                SAVE {{ $product->discount_percentage }}%
                            </span>
                        @endif
                        <span class="absolute top-3 right-3 bg-darkBlack-950 text-white text-[10px] font-bold px-2.5 py-1 rounded-md">
                            Rent £{{ number_format($product->rental_price_daily, 0) }}/day
                        </span>
                    </div>

                    <div class="p-5 flex-grow flex flex-col justify-between">
                        <div>
                            <div class="text-[10px] font-bold text-brandOrange-600 uppercase tracking-wider mb-1">{{ $product->brand->name ?? 'Premium' }}</div>
                            <a href="{{ route('products.show', $product->slug) }}" class="text-sm font-black text-slate-900 hover:text-brandOrange-500 line-clamp-1 mb-2">
                                {{ $product->name }}
                            </a>

                            <div class="flex items-center space-x-1 mb-3">
                                <span class="text-amber-500 text-xs"><i class="fa-solid fa-star"></i></span>
                                <span class="text-xs font-bold text-slate-900">{{ $product->average_rating }}</span>
                                <span class="text-[11px] text-slate-400">({{ $product->reviews_count }})</span>
                            </div>

                            <div class="flex flex-wrap gap-1.5 mb-4">
                                <span class="px-2 py-0.5 bg-slate-100 rounded-md text-[10px] font-semibold text-slate-600">⚡ {{ $product->range_specs ?? '75 miles' }}</span>
                                <span class="px-2 py-0.5 bg-slate-100 rounded-md text-[10px] font-semibold text-slate-600">◎ {{ $product->motor_specs ?? '250W' }}</span>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-baseline justify-between mb-3">
                                <div class="flex items-baseline space-x-1.5">
                                    <span class="text-lg font-black text-brandOrange-500">£{{ number_format($product->effective_price, 2) }}</span>
                                    @if($product->discount_price)
                                        <span class="text-xs text-slate-400 line-through">£{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <button @click="addToCart({{ $product->id }}, 'purchase')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 hover:bg-brandOrange-500 hover:text-white flex items-center justify-center transition-colors">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="text-center py-2 bg-darkBlack-950 hover:bg-black text-white rounded-xl text-xs font-bold transition-colors">
                                    Buy Now
                                </a>
                                <a href="{{ route('products.show', $product->slug) }}#rental" class="text-center py-2 bg-brandOrange-500 hover:bg-brandOrange-600 text-white rounded-xl text-xs font-bold transition-colors shadow-sm">
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

<!-- How E-Bike Rental Works Section -->
<section class="py-20 bg-darkBlack-900 text-white border-t border-darkBlack-800">
    <div class="container mx-auto px-6 md:px-12">
        <div class="text-center max-w-xl mx-auto mb-16">
            <span class="bg-brandOrange-500/20 text-brandOrange-400 text-xs font-bold uppercase px-3.5 py-1.5 rounded-full border border-brandOrange-500/30">
                Simple & Seamless UK Process
            </span>
            <h2 class="text-3xl md:text-4xl font-black text-white mt-3 mb-3">How E-Bike Rental Works</h2>
            <p class="text-slate-400 text-sm">Rent top-rated electric bikes in under 2 minutes with real-time calendar availability and instant deposit protection.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-darkBlack-950 border border-white/10 p-6 rounded-3xl text-center relative group hover:border-brandOrange-500 transition-all">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-brandOrange-500 text-white font-black text-xl flex items-center justify-center shadow-lg">
                    1
                </div>
                <h3 class="text-base font-bold text-white mb-2">Choose Your E-Bike</h3>
                <p class="text-xs text-slate-400 leading-relaxed">Select from City, Mountain, Folding, or Long-Range E-Bikes suited to your ride.</p>
            </div>

            <div class="bg-darkBlack-950 border border-white/10 p-6 rounded-3xl text-center relative group hover:border-brandOrange-500 transition-all">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-darkBlack-800 text-brandOrange-400 font-black text-xl flex items-center justify-center shadow-lg border border-brandOrange-500/30">
                    2
                </div>
                <h3 class="text-base font-bold text-white mb-2">Pick Rental Dates</h3>
                <p class="text-xs text-slate-400 leading-relaxed">Select daily, weekly, or monthly dates. Our live system checks physical bike availability.</p>
            </div>

            <div class="bg-darkBlack-950 border border-white/10 p-6 rounded-3xl text-center relative group hover:border-brandOrange-500 transition-all">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-brandOrange-500 text-white font-black text-xl flex items-center justify-center shadow-lg">
                    3
                </div>
                <h3 class="text-base font-bold text-white mb-2">30% Advance or Full</h3>
                <p class="text-xs text-slate-400 leading-relaxed">Pay just 30% advance to hold your bike or pay full amount online. Select store pickup or home delivery.</p>
            </div>

            <div class="bg-darkBlack-950 border border-white/10 p-6 rounded-3xl text-center relative group hover:border-brandOrange-500 transition-all">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-darkBlack-800 text-brandOrange-400 font-black text-xl flex items-center justify-center shadow-lg border border-brandOrange-500/30">
                    4
                </div>
                <h3 class="text-base font-bold text-white mb-2">Ride & Easily Return</h3>
                <p class="text-xs text-slate-400 leading-relaxed">Enjoy your ride! Extend online anytime or return to receive instant security deposit refund.</p>
            </div>
        </div>
    </div>
</section>

<!-- Popular Accessories Section -->
<section class="py-16 bg-slate-50">
    <div class="container mx-auto px-6 md:px-12">
        <div class="flex justify-between items-end mb-10">
            <div>
                <span class="text-xs font-black text-brandOrange-500 uppercase tracking-widest">Gear Up</span>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 mt-1">Popular Cycling Accessories</h2>
            </div>
            <a href="{{ route('catalog.index', ['type' => 'accessory']) }}" class="text-xs font-black text-slate-900 hover:text-brandOrange-500 flex items-center">
                Shop All Accessories <i class="fa-solid fa-arrow-right ml-1 text-brandOrange-500"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($popularAccessories as $acc)
                <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between">
                    <div>
                        <div class="aspect-square bg-slate-100 rounded-2xl overflow-hidden mb-3">
                            <img src="{{ $acc->primary_image_url }}" class="w-full h-full object-cover">
                        </div>
                        <h4 class="text-xs font-black text-slate-900 line-clamp-2 mb-1">{{ $acc->name }}</h4>
                        <p class="text-[10px] text-slate-400 font-semibold">{{ $acc->brand->name ?? 'Accessory' }}</p>
                    </div>
                    <div class="mt-3 pt-2 border-t border-slate-100 flex justify-between items-center">
                        <span class="text-xs font-black text-slate-900">£{{ number_format($acc->effective_price, 2) }}</span>
                        <button @click="addToCart({{ $acc->id }}, 'purchase')" class="w-7 h-7 rounded-full bg-brandOrange-500 text-white flex items-center justify-center hover:bg-brandOrange-600 transition-colors">
                            <i class="fa-solid fa-cart-plus text-xs"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Customer Reviews Section -->
<section class="py-16 bg-white border-t border-slate-200">
    <div class="container mx-auto px-6 md:px-12">
        <div class="text-center max-w-xl mx-auto mb-12">
            <span class="text-xs font-black text-brandOrange-500 uppercase tracking-widest">Verified Feedback</span>
            <h2 class="text-2xl md:text-3xl font-black text-slate-900 mt-1">What British Cyclists Say</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($reviews as $rev)
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200 flex flex-col justify-between shadow-xs">
                    <div>
                        <div class="flex text-amber-500 text-xs mb-3">
                            @for($i=1; $i<=5; $i++)
                                <i class="fa-solid fa-star {{ $i <= $rev->rating ? '' : 'text-slate-300' }}"></i>
                            @endfor
                        </div>
                        <h4 class="text-sm font-black text-slate-900 mb-2">"{{ $rev->title }}"</h4>
                        <p class="text-xs text-slate-600 leading-relaxed italic mb-4">"{{ $rev->comment }}"</p>
                    </div>

                    <div class="flex items-center space-x-3 pt-3 border-t border-slate-200">
                        <img src="{{ $rev->user->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" class="w-8 h-8 rounded-full object-cover">
                        <div>
                            <span class="block text-xs font-bold text-slate-900">{{ $rev->user->name ?? 'Verified Rider' }}</span>
                            <span class="block text-[10px] text-brandOrange-600 font-bold"><i class="fa-solid fa-circle-check"></i> Verified Rental Customer</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-16 bg-darkBlack-950 text-white border-t border-darkBlack-800">
    <div class="container mx-auto px-6 md:px-12 text-center max-w-xl" x-data="{ email: '', message: '', success: false }">
        <i class="fa-solid fa-paper-plane text-3xl text-brandOrange-500 mb-3"></i>
        <h2 class="text-2xl font-black text-white mb-2">Join the eb4u Club</h2>
        <p class="text-xs text-slate-400 mb-6">Subscribe for exclusive UK rental discounts, new E-Bike arrival alerts, and £10 off your first accessory order!</p>

        <form @submit.prevent="axios.post('{{ route('newsletter.subscribe') }}', { email: email }).then(r => { message = r.data.message; success = true; email = ''; })" class="flex flex-col sm:flex-row gap-2">
            <input type="email" x-model="email" required placeholder="Enter your email address..." class="flex-grow bg-darkBlack-900 text-white text-xs px-4 py-3.5 rounded-xl border border-white/20 focus:outline-none focus:ring-2 focus:ring-brandOrange-500">
            <button type="submit" class="bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-black text-xs px-6 py-3.5 rounded-xl shadow-lg transition-colors">
                Subscribe
            </button>
        </form>

        <div x-show="message" x-cloak class="mt-3 text-xs font-bold text-brandOrange-400" x-text="message"></div>
    </div>
</section>

<script>
    function heroBannerSlider(count) {
        return {
            activeSlide: 0,
            totalSlides: count || 1,
            timer: null,
            init() {
                if (this.totalSlides > 1) {
                    this.startAutoPlay();
                }
            },
            startAutoPlay() {
                this.timer = setInterval(() => {
                    this.next();
                }, 5000);
            },
            next() {
                this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
            },
            prev() {
                this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
            }
        }
    }
</script>
@endsection
