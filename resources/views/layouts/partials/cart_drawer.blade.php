<!-- Slide-Over Mini Cart Drawer -->
<div x-show="isCartOpen" x-cloak class="relative z-50">
    <!-- Backdrop -->
    <div x-show="isCartOpen" x-transition.opacity @click="isCartOpen = false" class="fixed inset-0 bg-forest-950/70 backdrop-blur-sm"></div>

    <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
        <div x-show="isCartOpen" x-transition:enter="transform transition ease-in-out duration-300"
             x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-300"
             x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
             class="w-screen max-w-md bg-white shadow-2xl flex flex-col justify-between">

            <!-- Cart Header -->
            <div class="p-6 border-b border-cream-200 flex items-center justify-between bg-cream-100/60">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-basket-shopping text-amberAcc-600 text-xl"></i>
                    <h2 class="text-lg font-black text-forest-900">Your Basket</h2>
                    <span class="text-xs bg-amberAcc-500 text-forest-950 font-black px-2.5 py-0.5 rounded-full" x-text="cartCount + ' items'"></span>
                </div>
                <button @click="isCartOpen = false" class="text-slate-400 hover:text-forest-900 text-xl p-1 font-bold">&times;</button>
            </div>

            <!-- Cart Items List -->
            <div class="flex-grow overflow-y-auto p-6 space-y-4">
                <template x-if="cartItems.length === 0">
                    <div class="text-center py-16">
                        <i class="fa-solid fa-bicycle text-4xl text-slate-300 mb-3"></i>
                        <p class="text-sm font-bold text-forest-900">Your basket is currently empty.</p>
                        <p class="text-xs text-slate-500 mt-1">Explore our range of UK E-Bikes and accessories!</p>
                        <a href="{{ route('catalog.index') }}" @click="isCartOpen = false" class="inline-block mt-4 text-xs font-black text-white bg-forest-900 px-6 py-3 rounded-2xl hover:bg-forest-800 shadow-md">
                            Browse Catalog
                        </a>
                    </div>
                </template>

                <template x-for="item in cartItems" :key="item.id">
                    <div class="flex space-x-4 p-3 rounded-2xl border border-cream-200 bg-cream-50/60 hover:bg-white transition-colors relative group">
                        <img :src="item.image" class="w-16 h-16 object-cover rounded-xl border border-cream-200">
                        <div class="flex-grow">
                            <div class="flex justify-between items-start">
                                <h4 class="text-xs font-black text-forest-900 leading-tight pr-4" x-text="item.name"></h4>
                                <button @click="removeItem(item.id)" class="text-slate-400 hover:text-rose-600 text-xs"><i class="fa-solid fa-trash"></i></button>
                            </div>

                            <span class="inline-block mt-1 text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full"
                                  :class="item.type === 'rental' ? 'bg-purple-100 text-purple-900' : 'bg-emerald-100 text-emerald-900'"
                                  x-text="item.type === 'rental' ? 'E-Bike Rental' : 'Purchase'"></span>

                            <div x-show="item.rental_dates" class="text-[11px] text-purple-900 font-bold mt-1">
                                <i class="fa-regular fa-calendar-check mr-1"></i> <span x-text="item.rental_dates"></span>
                            </div>

                            <div class="flex justify-between items-center mt-2">
                                <div class="flex items-center border border-slate-200 rounded-lg bg-white">
                                    <button @click="updateQty(item.id, item.quantity - 1)" class="px-2 py-0.5 text-xs font-bold text-slate-600 hover:bg-slate-100">-</button>
                                    <span class="px-2 text-xs font-black text-slate-900" x-text="item.quantity"></span>
                                    <button @click="updateQty(item.id, item.quantity + 1)" class="px-2 py-0.5 text-xs font-bold text-slate-600 hover:bg-slate-100">+</button>
                                </div>
                                <span class="text-xs font-black text-forest-900">£<span x-text="item.subtotal"></span></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Cart Footer Summary -->
            <div x-show="cartItems.length > 0" class="p-6 border-t border-cream-200 bg-cream-100/60 space-y-3">
                <div class="flex justify-between text-xs text-slate-600">
                    <span>Subtotal</span>
                    <span class="font-bold text-forest-900">£<span x-text="subtotal"></span></span>
                </div>
                <div class="flex justify-between text-xs text-slate-600">
                    <span>UK VAT (20%)</span>
                    <span class="font-bold text-slate-700">£<span x-text="tax"></span></span>
                </div>
                <div class="border-t border-cream-200 pt-2 flex justify-between text-sm font-black text-forest-900">
                    <span>Estimated Total</span>
                    <span class="text-amberAcc-600 text-lg font-black">£<span x-text="total"></span></span>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <a href="{{ route('cart.index') }}" @click="isCartOpen = false" class="text-center py-3 text-xs font-black text-forest-900 bg-white border-2 border-forest-900 rounded-2xl hover:bg-cream-100 transition-colors shadow-xs">
                        View Cart
                    </a>
                    <a href="{{ route('checkout.index') }}" class="text-center py-3 text-xs font-black text-forest-950 bg-amberAcc-500 border border-amberAcc-600 rounded-2xl hover:bg-amberAcc-400 shadow-md transition-colors">
                        Checkout Now
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
