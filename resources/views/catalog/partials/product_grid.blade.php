<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($products as $product)
        <div class="group bg-white rounded-2xl border border-borderLight overflow-hidden shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
            <div class="relative h-44 bg-gradient-to-br from-[#dde8f7] to-[#cddaf2] flex items-center justify-center overflow-hidden">
                <img src="{{ $product->primary_image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                
                <span class="absolute top-2.5 left-2.5 bg-darkSlate-900 text-white text-[10px] font-bold px-2 py-0.5 rounded-md">
                    {{ $product->type === 'ebike' ? 'E-Bike' : 'Accessory' }}
                </span>

                @if($product->product_tag === 'rent' || $product->is_rental_eligible)
                    <span class="absolute top-2.5 right-2.5 bg-brandOrange-500 text-white text-[10px] font-black px-2.5 py-0.5 rounded-md shadow-xs">
                        ⚡ Rent Only
                    </span>
                @else
                    <span class="absolute top-2.5 right-2.5 bg-slate-900 text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-xs">
                        🛒 Buy Only
                    </span>
                @endif
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

                    @if($product->type === 'ebike')
                        <div class="flex flex-wrap gap-1.5 mb-4">
                            <span class="px-2 py-0.5 bg-[#edf1f8] rounded-md text-[10px] font-medium text-textSec">⚡ {{ $product->range_specs ?? '100km' }}</span>
                            <span class="px-2 py-0.5 bg-[#edf1f8] rounded-md text-[10px] font-medium text-textSec">◎ {{ $product->motor_specs ?? '250W' }}</span>
                        </div>
                    @else
                        <p class="text-xs text-textSec line-clamp-2 mb-4 font-normal">{{ $product->short_description }}</p>
                    @endif
                </div>

                <div>
                    <div class="flex items-baseline justify-between mb-3">
                        @if($product->product_tag === 'rent' || $product->is_rental_eligible)
                            <div class="flex items-baseline gap-1">
                                <span class="text-xs text-textMuted font-medium">From</span>
                                <span class="font-grotesk text-lg font-extrabold text-brandOrange-500">£{{ number_format($product->rental_price_daily, 0) }}</span>
                                <span class="text-xs text-textMuted font-bold">/day</span>
                            </div>
                        @else
                            <div class="flex items-baseline gap-1.5">
                                <span class="font-grotesk text-lg font-extrabold text-brandOrange-500">£{{ number_format($product->effective_price, 2) }}</span>
                                @if($product->discount_price)
                                    <span class="text-xs text-textMuted line-through">£{{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            <button @click="addToCart({{ $product->id }}, 'purchase')" class="w-8 h-8 rounded-lg bg-brandOrange-50 border border-brandOrange-500/30 text-brandOrange-500 hover:bg-brandOrange-500 hover:text-white flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('products.show', $product->slug) }}" class="text-center py-2 bg-darkSlate-900 hover:bg-black text-white rounded-xl text-xs font-bold transition-colors">
                            Details
                        </a>
                        @if($product->product_tag === 'rent' || $product->is_rental_eligible)
                            <a href="{{ route('products.show', $product->slug) }}" class="text-center py-2 bg-brandOrange-500 hover:bg-brandOrange-600 text-white rounded-xl text-xs font-bold transition-colors shadow-xs">
                                ⚡ Rent E-Bike
                            </a>
                        @else
                            <button @click="addToCart({{ $product->id }}, 'purchase')" class="py-2 bg-brandOrange-500 hover:bg-brandOrange-600 text-white rounded-xl text-xs font-bold transition-colors shadow-xs">
                                Add to Cart
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-borderLight">
            <i class="fa-solid fa-bicycle text-4xl text-textMuted mb-3"></i>
            <h3 class="font-grotesk text-base font-bold text-darkSlate-900">No products found</h3>
            <p class="text-xs text-textMuted mt-1">Try resetting your filter parameters.</p>
        </div>
    @endforelse
</div>
