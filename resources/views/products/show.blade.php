@extends('layouts.app')

@section('title', $product->name . ' | eb4u')

@section('content')
<!-- Breadcrumb -->
<div class="border-b border-borderLight bg-[#edf1f8] text-xs">
    <div class="max-w-[1320px] mx-auto px-6 py-3 flex items-center gap-2 text-textMuted font-medium">
        <a href="{{ route('home') }}" class="hover:text-darkSlate-900">Home</a>
        <span>/</span>
        <a href="{{ route('catalog.index') }}" class="hover:text-darkSlate-900">Products</a>
        <span>/</span>
        <span class="text-darkSlate-900 font-bold truncate">{{ $product->name }}</span>
    </div>
</div>

<div class="max-w-[1320px] mx-auto px-6 py-10" x-data="productDetailApp()">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Left: Product Image Gallery -->
        <div class="lg:col-span-6 space-y-4">
            <div class="bg-white p-6 rounded-3xl border border-borderLight shadow-xs relative overflow-hidden aspect-square flex items-center justify-center">
                <img :src="activeImage" class="max-h-full max-w-full object-contain rounded-2xl transition-all duration-300">
            </div>

            <!-- Thumbnail Selector -->
            @if($product->images->count() > 1)
                <div class="flex space-x-3 overflow-x-auto pb-2">
                    @foreach($product->images as $img)
                        <button @click="activeImage = '{{ asset($img->image_path) }}'" 
                                :class="activeImage === '{{ asset($img->image_path) }}' ? 'border-brandOrange-500 ring-2 ring-brandOrange-500/20' : 'border-borderLight'"
                                class="w-20 h-20 rounded-2xl border bg-white p-1 overflow-hidden flex-shrink-0 transition-all cursor-pointer">
                            <img src="{{ asset($img->image_path) }}" class="w-full h-full object-cover rounded-xl">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Details & Purchase/Rental Form -->
        <div class="lg:col-span-6 space-y-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs font-bold text-brandOrange-600 uppercase tracking-wider bg-brandOrange-50 px-2.5 py-1 rounded-md">{{ $product->brand->name ?? 'Premium' }}</span>
                    <span class="text-xs font-semibold text-textMuted">• SKU: {{ $product->sku }}</span>
                </div>
                <h1 class="font-grotesk text-2xl sm:text-3xl font-extrabold text-darkSlate-900 leading-tight mb-3">{{ $product->name }}</h1>

                <!-- Rating -->
                <div class="flex items-center gap-2">
                    <div class="flex text-amber-400 text-sm">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                    </div>
                    <span class="text-xs font-bold text-darkSlate-900">{{ $product->average_rating }}</span>
                    <span class="text-xs text-textMuted">({{ $product->reviews_count }} verified rider reviews)</span>
                </div>
            </div>

            <!-- Specs Badges Grid -->
            @if($product->type === 'ebike')
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white p-3 rounded-2xl border border-borderLight flex flex-col items-center text-center">
                        <i class="fa-solid fa-bolt text-brandOrange-500 text-base mb-1"></i>
                        <span class="text-[10px] text-textMuted font-medium uppercase">Motor</span>
                        <span class="text-xs font-bold text-darkSlate-900">{{ $product->motor_specs ?? '250W German' }}</span>
                    </div>
                    <div class="bg-white p-3 rounded-2xl border border-borderLight flex flex-col items-center text-center">
                        <i class="fa-solid fa-battery-full text-brandOrange-500 text-base mb-1"></i>
                        <span class="text-[10px] text-textMuted font-medium uppercase">Battery</span>
                        <span class="text-xs font-bold text-darkSlate-900">{{ $product->battery_specs ?? '625Wh Bosch' }}</span>
                    </div>
                    <div class="bg-white p-3 rounded-2xl border border-borderLight flex flex-col items-center text-center">
                        <i class="fa-solid fa-gauge-high text-brandOrange-500 text-base mb-1"></i>
                        <span class="text-[10px] text-textMuted font-medium uppercase">Range</span>
                        <span class="text-xs font-bold text-darkSlate-900">{{ $product->range_specs ?? '75 Miles' }}</span>
                    </div>
                </div>
            @endif

            <!-- Variant Selector -->
            @if($product->variants->count() > 0)
                <div class="bg-white p-5 rounded-3xl border border-borderLight shadow-xs">
                    <label class="font-grotesk block text-xs font-black uppercase text-darkSlate-900 mb-2.5">
                        <i class="fa-solid fa-ruler-combined text-brandOrange-500 mr-1.5"></i> Select Variant / Frame Size
                    </label>
                    <div class="flex flex-wrap gap-2.5">
                        @foreach($product->variants as $variant)
                            <button type="button" @click="selectedVariant = {{ $variant->id }}"
                                    :class="selectedVariant === {{ $variant->id }} ? 'bg-brandOrange-500 text-white font-black border-brandOrange-500 shadow-md ring-2 ring-brandOrange-500/30' : 'bg-slate-100 text-slate-800 font-extrabold border-slate-300 hover:border-brandOrange-500 hover:text-brandOrange-600 hover:bg-brandOrange-50'"
                                    class="px-4 py-2.5 text-xs rounded-xl border transition-all cursor-pointer">
                                {{ $variant->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Tab Selection: Buy vs Rent -->
            <div class="bg-white p-6 rounded-3xl border border-borderLight shadow-xs space-y-6">
                @if($product->product_tag === 'rent' || $product->is_rental_eligible)
                    <!-- Rent Only Header -->
                    <div class="border-b border-borderLight pb-3 flex items-center justify-between">
                        <span class="font-grotesk text-sm font-extrabold text-brandOrange-500 flex items-center">
                            <i class="fa-solid fa-calendar-check mr-2"></i> ⚡ Rent E-Bike (from £{{ number_format($product->rental_price_daily, 0) }}/day)
                        </span>
                        <span class="bg-brandOrange-50 text-brandOrange-600 text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider border border-brandOrange-200">
                            Rental Only
                        </span>
                    </div>

                    <!-- Rental Booking Widget -->
                    <div class="space-y-4">
                        <div class="bg-[#f5f7fb] p-4 rounded-2xl border border-borderLight space-y-3">
                            <h4 class="font-grotesk text-xs font-bold uppercase text-darkSlate-900 tracking-wider"><i class="fa-solid fa-calendar-days text-brandOrange-500 mr-1.5"></i> Select Rental Dates</h4>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-textSec uppercase mb-1">Start Date</label>
                                    <input type="date" x-model="startDate" @change="checkAvailability()" class="w-full text-xs bg-white border border-borderLight rounded-xl p-2.5 font-semibold text-darkSlate-900">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-textSec uppercase mb-1">End Date</label>
                                    <input type="date" x-model="endDate" @change="checkAvailability()" class="w-full text-xs bg-white border border-borderLight rounded-xl p-2.5 font-semibold text-darkSlate-900">
                                </div>
                            </div>

                            <template x-if="rentalResult">
                                <div class="bg-white p-3.5 rounded-xl border border-borderLight text-xs space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-textSec">Duration & Rate:</span>
                                        <span class="font-bold text-darkSlate-900" x-text="rentalResult.rental_days + ' Days @ £' + rentalResult.daily_rate + '/day'"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-textSec">Rental Fee:</span>
                                        <span class="font-grotesk font-extrabold text-darkSlate-900">£<span x-text="rentalResult.subtotal"></span></span>
                                    </div>
                                    <div class="flex justify-between text-[11px] text-textMuted">
                                        <span>Refundable Deposit:</span>
                                        <span>£<span x-text="rentalResult.security_deposit"></span></span>
                                    </div>
                                    <div class="border-t border-borderLight pt-2 flex justify-between font-bold text-darkSlate-900">
                                        <span>Pay 30% Advance Now:</span>
                                        <span class="font-grotesk text-brandOrange-500 font-extrabold">£<span x-text="rentalResult.advance_30_percent"></span></span>
                                    </div>
                                </div>
                            </template>

                            <button @click="reserveRental()" :disabled="!rentalResult || !rentalResult.is_available"
                                    class="w-full py-3.5 bg-brandOrange-500 hover:bg-brandOrange-600 disabled:bg-slate-300 text-white text-xs font-bold rounded-xl shadow-lg transition-all flex items-center justify-center">
                                <i class="fa-solid fa-calendar-check mr-2"></i> Reserve & Rent Now
                            </button>
                        </div>
                    </div>
                @else
                    <!-- Buy Only Header -->
                    <div class="border-b border-borderLight pb-3 flex items-center justify-between">
                        <span class="font-grotesk text-sm font-extrabold text-darkSlate-900 flex items-center">
                            <i class="fa-solid fa-tag mr-2 text-brandOrange-500"></i> Buy E-Bike (£{{ number_format($product->effective_price, 2) }})
                        </span>
                        <span class="bg-slate-100 text-slate-700 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wider border border-slate-200">
                            Sales Only
                        </span>
                    </div>

                    <!-- Buy Purchase Section -->
                    <div class="space-y-4">
                        <div class="flex items-baseline space-x-3">
                            <span class="font-grotesk text-3xl font-extrabold text-brandOrange-500">£{{ number_format($product->effective_price, 2) }}</span>
                            @if($product->discount_price)
                                <span class="text-sm text-textMuted line-through">£{{ number_format($product->price, 2) }}</span>
                                <span class="text-xs font-bold text-brandOrange-600 bg-brandOrange-50 px-2 py-0.5 rounded-full">Save {{ $product->discount_percentage }}%</span>
                            @endif
                        </div>
                        <p class="text-xs text-textSec font-medium"><i class="fa-solid fa-check text-brandOrange-500 mr-1"></i> In Stock ({{ $product->stock_quantity }} available) | Free UK Shipping</p>

                        <div class="flex gap-3">
                            <button @click="addToCart({{ $product->id }}, 'purchase')" class="flex-1 py-3.5 bg-brandOrange-500 hover:bg-brandOrange-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-brandOrange-500/20 transition-all flex items-center justify-center">
                                <i class="fa-solid fa-basket-shopping mr-2"></i> Add to Basket
                            </button>
                            <button @click="toggleWishlist({{ $product->id }})" class="p-3.5 bg-[#f5f7fb] hover:bg-rose-50 text-textSec hover:text-rose-600 rounded-xl transition-colors">
                                <i class="fa-regular fa-heart text-lg"></i>
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Overview -->
            <div class="border-t border-borderLight pt-6 space-y-3">
                <h3 class="font-grotesk text-sm font-bold text-darkSlate-900 uppercase tracking-wider">Product Overview</h3>
                <p class="text-xs sm:text-sm text-textSec leading-relaxed font-normal">{{ $product->description }}</p>
            </div>
        </div>

    </div>
</div>

<script>
    function productDetailApp() {
        return {
            activeImage: '{{ $product->primary_image_url }}',
            selectedVariant: {{ $product->variants->first()->id ?? 'null' }},
            activeTab: '{{ ($product->product_tag === "rent" || $product->is_rental_eligible) ? "rent" : "buy" }}',
            startDate: '{{ now()->addDay()->format("Y-m-d") }}',
            endDate: '{{ now()->addDays(8)->format("Y-m-d") }}',
            rentalResult: null,

            init() {
                if ({{ ($product->product_tag === 'rent' || $product->is_rental_eligible) ? 'true' : 'false' }}) {
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
                if (window.addToCart) {
                    window.addToCart({{ $product->id }}, 'rental', 1, this.startDate, this.endDate);
                }
            },
            async toggleWishlist(productId) {
                try {
                    let res = await axios.post(`/customer/wishlist/toggle/${productId}`);
                    if (window.showToast) window.showToast(res.data.message);
                } catch (e) {
                    if (window.showToast) window.showToast('Please sign in to save wishlist items.', true);
                }
            }
        }
    }
</script>
@endsection
