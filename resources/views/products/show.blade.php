@extends('layouts.app')

@section('title', $product->name . ' | Eb4u')

@section('content')
<div class="bg-white py-4 border-b border-slate-200 text-xs">
    <div class="container mx-auto px-6 md:px-12 flex items-center space-x-2 text-slate-500 font-medium">
        <a href="{{ route('home') }}" class="hover:text-brandOrange-500">Home</a>
        <span>/</span>
        <a href="{{ route('catalog.index') }}" class="hover:text-brandOrange-500">Catalog</a>
        <span>/</span>
        <span class="text-slate-900 font-bold truncate">{{ $product->name }}</span>
    </div>
</div>

<div class="container mx-auto px-6 md:px-12 py-10" x-data="productDetailApp()">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Left: Image Gallery -->
        <div class="lg:col-span-6 space-y-4">
            <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden aspect-square flex items-center justify-center">
                <img :src="activeImage" class="max-h-full max-w-full object-contain rounded-2xl transition-all duration-300">
            </div>

            <!-- Thumbnail Selector -->
            @if($product->images->count() > 1)
                <div class="flex space-x-3 overflow-x-auto pb-2">
                    @foreach($product->images as $img)
                        <button @click="activeImage = '{{ asset($img->image_path) }}'" 
                                :class="activeImage === '{{ asset($img->image_path) }}' ? 'border-brandOrange-500 ring-2 ring-brandOrange-500/20' : 'border-slate-200'"
                                class="w-20 h-20 rounded-2xl border bg-white p-1 overflow-hidden flex-shrink-0 transition-all">
                            <img src="{{ asset($img->image_path) }}" class="w-full h-full object-cover rounded-xl">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Product Information & Rental / Purchase Box -->
        <div class="lg:col-span-6 space-y-6">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <span class="bg-brandOrange-500 text-white text-[10px] font-black uppercase px-3 py-1 rounded-full shadow-xs">
                        {{ $product->brand->name ?? 'Brand' }}
                    </span>
                    <span class="text-xs text-slate-400 font-mono">SKU: {{ $product->sku }}</span>
                    <div class="flex items-center text-amber-500 text-xs">
                        <i class="fa-solid fa-star mr-1"></i> <strong class="text-slate-900 mr-1">{{ $product->average_rating }}</strong> ({{ $product->reviews_count }} reviews)
                    </div>
                </div>

                <h1 class="text-2xl md:text-3xl font-black text-slate-900 leading-tight mb-3">{{ $product->name }}</h1>
                
                <p class="text-xs text-slate-600 leading-relaxed mb-4 font-normal">{{ $product->short_description }}</p>
            </div>

            <!-- Specs Grid for E-Bikes -->
            @if($product->type === 'ebike')
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-white p-4 rounded-3xl border border-slate-200 shadow-xs">
                    <div class="text-center p-2.5 bg-slate-50 rounded-2xl border border-slate-200">
                        <i class="fa-solid fa-microchip text-brandOrange-500 text-lg mb-1"></i>
                        <span class="block text-[10px] uppercase font-bold text-slate-400">Motor</span>
                        <span class="text-xs font-black text-slate-900 leading-tight">{{ $product->motor_specs ?? '250W Mid-Drive' }}</span>
                    </div>
                    <div class="text-center p-2.5 bg-slate-50 rounded-2xl border border-slate-200">
                        <i class="fa-solid fa-battery-three-quarters text-darkBlack-900 text-lg mb-1"></i>
                        <span class="block text-[10px] uppercase font-bold text-slate-400">Battery</span>
                        <span class="text-xs font-black text-slate-900 leading-tight">{{ $product->battery_specs ?? '625Wh' }}</span>
                    </div>
                    <div class="text-center p-2.5 bg-slate-50 rounded-2xl border border-slate-200">
                        <i class="fa-solid fa-route text-slate-600 text-lg mb-1"></i>
                        <span class="block text-[10px] uppercase font-bold text-slate-400">Range</span>
                        <span class="text-xs font-black text-slate-900 leading-tight">{{ $product->range_specs ?? '75 Miles' }}</span>
                    </div>
                    <div class="text-center p-2.5 bg-slate-50 rounded-2xl border border-slate-200">
                        <i class="fa-solid fa-shield-halved text-brandOrange-600 text-lg mb-1"></i>
                        <span class="block text-[10px] uppercase font-bold text-slate-400">Warranty</span>
                        <span class="text-xs font-black text-slate-900 leading-tight">{{ $product->warranty_specs ?? '5 Years UK' }}</span>
                    </div>
                </div>
            @endif

            <!-- Variant Selector -->
            @if($product->variants->count() > 0)
                <div>
                    <label class="block text-xs font-black uppercase text-slate-900 mb-2">Select Variant / Frame Size</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->variants as $variant)
                            <button @click="selectedVariant = {{ $variant->id }}"
                                    :class="selectedVariant === {{ $variant->id }} ? 'bg-darkBlack-900 text-white border-darkBlack-900' : 'bg-white text-slate-700 border-slate-200 hover:border-brandOrange-500'"
                                    class="px-4 py-2 text-xs font-bold rounded-xl border transition-all">
                                {{ $variant->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Tab Selection: Buy vs Rent -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                <div class="flex border-b border-slate-200">
                    <button @click="activeTab = 'buy'" :class="activeTab === 'buy' ? 'border-darkBlack-900 text-slate-900 font-black' : 'border-transparent text-slate-400 font-bold'" class="pb-3 px-4 text-sm border-b-2 transition-colors">
                        <i class="fa-solid fa-tag mr-2"></i> Buy E-Bike (£{{ number_format($product->effective_price, 2) }})
                    </button>
                    @if($product->is_rental_eligible)
                        <button @click="activeTab = 'rent'" id="rental" :class="activeTab === 'rent' ? 'border-brandOrange-500 text-brandOrange-500 font-black' : 'border-transparent text-slate-400 font-bold'" class="pb-3 px-4 text-sm border-b-2 transition-colors">
                            <i class="fa-solid fa-calendar-check mr-2"></i> Rent E-Bike (from £{{ number_format($product->rental_price_daily, 0) }}/day)
                        </button>
                    @endif
                </div>

                <!-- Tab 1: Buy Purchase -->
                <div x-show="activeTab === 'buy'" class="space-y-4">
                    <div class="flex items-baseline space-x-3">
                        <span class="text-3xl font-black text-slate-900">£{{ number_format($product->effective_price, 2) }}</span>
                        @if($product->discount_price)
                            <span class="text-sm text-slate-400 line-through">£{{ number_format($product->price, 2) }}</span>
                            <span class="text-xs font-bold text-brandOrange-600 bg-brandOrange-50 px-2 py-0.5 rounded-full">Save {{ $product->discount_percentage }}%</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-600 font-medium"><i class="fa-solid fa-check text-brandOrange-500 mr-1"></i> In Stock ({{ $product->stock_quantity }} available) | Free UK Mainland Shipping</p>

                    <div class="flex gap-3">
                        <button @click="addToCart({{ $product->id }}, 'purchase')" class="flex-1 py-3.5 bg-darkBlack-950 hover:bg-black text-white text-xs font-black rounded-2xl shadow-lg transition-all flex items-center justify-center">
                            <i class="fa-solid fa-basket-shopping mr-2"></i> Add to Basket
                        </button>
                        <button @click="toggleWishlist({{ $product->id }})" class="p-3.5 bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-600 rounded-2xl transition-colors">
                            <i class="fa-regular fa-heart text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Tab 2: Interactive E-Bike Rental Calculator Widget -->
                @if($product->is_rental_eligible)
                    <div x-show="activeTab === 'rent'" class="space-y-4">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                            <h4 class="text-xs font-black uppercase text-slate-900 tracking-wider"><i class="fa-solid fa-calendar-days text-brandOrange-500 mr-1.5"></i> Select Rental Dates</h4>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-700 mb-1">Start Date</label>
                                    <input type="date" x-model="startDate" @change="checkAvailability()" class="w-full text-xs bg-white border border-slate-200 rounded-xl p-2.5 font-semibold text-slate-800">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-700 mb-1">End Date</label>
                                    <input type="date" x-model="endDate" @change="checkAvailability()" class="w-full text-xs bg-white border border-slate-200 rounded-xl p-2.5 font-semibold text-slate-800">
                                </div>
                            </div>

                            <!-- Live Price Calculation Result Box -->
                            <div x-show="rentalResult" x-cloak class="bg-white p-3 rounded-xl border border-slate-200 text-xs space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Duration & Rate:</span>
                                    <span class="font-bold text-slate-900" x-text="rentalResult.rental_days + ' Days @ £' + rentalResult.daily_rate + '/day'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Rental Fee:</span>
                                    <span class="font-black text-slate-900">£<span x-text="rentalResult.subtotal"></span></span>
                                </div>
                                <div class="flex justify-between text-[11px] text-slate-500">
                                    <span>Refundable Security Deposit:</span>
                                    <span>£<span x-text="rentalResult.security_deposit"></span></span>
                                </div>
                                <div class="border-t border-slate-200 pt-2 flex justify-between font-bold text-slate-900">
                                    <span>Pay 30% Advance Now:</span>
                                    <span class="text-brandOrange-500 font-black">£<span x-text="rentalResult.advance_30_percent"></span></span>
                                </div>
                            </div>

                            <button @click="reserveRental()" :disabled="!rentalResult || !rentalResult.is_available"
                                    class="w-full py-3.5 bg-brandOrange-500 hover:bg-brandOrange-600 disabled:bg-slate-300 text-white text-xs font-black rounded-2xl shadow-lg transition-all flex items-center justify-center">
                                <i class="fa-solid fa-calendar-check mr-2"></i> Reserve & Rent Now
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Full Description & Specifications Accordion -->
            <div class="border-t border-slate-200 pt-6 space-y-4">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Product Overview</h3>
                <p class="text-xs text-slate-600 leading-relaxed font-normal">{{ $product->description }}</p>
            </div>
        </div>

    </div>
</div>

<script>
    function productDetailApp() {
        return {
            activeImage: '{{ $product->primary_image_url }}',
            selectedVariant: {{ $product->variants->first()->id ?? 'null' }},
            activeTab: 'buy',
            startDate: '{{ now()->addDay()->format("Y-m-d") }}',
            endDate: '{{ now()->addDays(8)->format("Y-m-d") }}',
            rentalResult: null,

            init() {
                if ({{ $product->is_rental_eligible ? 'true' : 'false' }}) {
                    this.checkAvailability();
                }
            },
            async checkAvailability() {
                if (!this.startDate || !this.endDate) return;
                try {
                    let res = await axios.post('/product/{{ $product->id }}/check-rental', {
                        start_date: this.startDate,
                        end_date: this.endDate
                    });
                    if (res.data.success) {
                        this.rentalResult = res.data;
                    }
                } catch (e) {
                    console.error('Availability check failed:', e);
                }
            },
            async reserveRental() {
                if (!this.rentalResult) return;
                this.$root.__x.$data.addToCart({{ $product->id }}, 'rental', 1, this.startDate, this.endDate);
            },
            async toggleWishlist(productId) {
                try {
                    let res = await axios.post(`/customer/wishlist/toggle/${productId}`);
                    this.$root.__x.$data.showToast(res.data.message);
                } catch (e) {
                    this.$root.__x.$data.showToast('Please sign in to save wishlist items.', true);
                }
            }
        }
    }
</script>
@endsection
