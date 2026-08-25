<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($products as $product)
        <div class="group bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 flex flex-col justify-between">
            <div class="relative bg-slate-100 aspect-video overflow-hidden">
                <img src="{{ $product->primary_image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                
                <span class="absolute top-3 left-3 bg-darkBlack-950 text-white text-[10px] font-black uppercase px-2.5 py-1 rounded-full border border-white/20">
                    {{ $product->type === 'ebike' ? 'E-Bike' : 'Accessory' }}
                </span>

                @if($product->is_rental_eligible)
                    <span class="absolute top-3 right-3 bg-brandOrange-500 text-white text-[10px] font-black px-2.5 py-1 rounded-full shadow-md">
                        Rent £{{ number_format($product->rental_price_daily, 0) }}/day
                    </span>
                @endif
            </div>

            <div class="p-5 flex-grow flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between text-[11px] text-slate-500 font-semibold mb-1">
                        <span class="uppercase font-bold text-brandOrange-600">{{ $product->brand->name ?? 'Premium' }}</span>
                        <span class="text-amber-500"><i class="fa-solid fa-star"></i> {{ $product->average_rating }}</span>
                    </div>
                    <a href="{{ route('products.show', $product->slug) }}" class="text-sm font-black text-slate-900 hover:text-brandOrange-500 line-clamp-2 mb-2">
                        {{ $product->name }}
                    </a>

                    @if($product->type === 'ebike')
                        <div class="bg-slate-50 p-2.5 rounded-2xl text-[11px] text-slate-800 space-y-1 mb-4 border border-slate-200 font-medium">
                            <div><i class="fa-solid fa-microchip text-brandOrange-500 mr-1.5"></i> {{ $product->motor_specs }}</div>
                            <div><i class="fa-solid fa-battery-three-quarters text-slate-900 mr-1.5"></i> {{ $product->battery_specs }}</div>
                        </div>
                    @else
                        <p class="text-xs text-slate-600 line-clamp-2 mb-4 font-normal">{{ $product->short_description }}</p>
                    @endif
                </div>

                <div>
                    <div class="flex items-baseline justify-between mb-4">
                        <div>
                            <span class="text-xl font-black text-slate-900">£{{ number_format($product->effective_price, 2) }}</span>
                            @if($product->discount_price)
                                <span class="text-xs text-slate-400 line-through ml-1">£{{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                        <span class="text-[10px] font-bold text-slate-400">Inc. UK VAT</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('products.show', $product->slug) }}" class="text-center py-2.5 bg-darkBlack-900 hover:bg-black text-white rounded-xl text-xs font-black transition-colors">
                            View Details
                        </a>
                        @if($product->is_rental_eligible)
                            <a href="{{ route('products.show', $product->slug) }}#rental" class="text-center py-2.5 bg-brandOrange-500 hover:bg-brandOrange-600 text-white rounded-xl text-xs font-black transition-colors shadow-sm">
                                Rent E-Bike
                            </a>
                        @else
                            <button @click="addToCart({{ $product->id }}, 'purchase')" class="py-2.5 bg-brandOrange-500 hover:bg-brandOrange-600 text-white rounded-xl text-xs font-black transition-colors shadow-sm">
                                Add to Cart
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-16 bg-white rounded-3xl border border-slate-200">
            <i class="fa-solid fa-bicycle text-4xl text-slate-300 mb-3"></i>
            <h3 class="text-base font-bold text-slate-900">No products found</h3>
            <p class="text-xs text-slate-500 mt-1">Try clearing some of your search filters.</p>
        </div>
    @endforelse
</div>
