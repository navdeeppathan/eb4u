<!-- Slide-Over Mini Cart Drawer (Z-Index Fixed & Theme Aligned) -->
<div x-show="isCartOpen" x-cloak class="relative z-[999]">
    <!-- Backdrop (Overlays header & page content) -->
    <div x-show="isCartOpen" x-transition.opacity @click="isCartOpen = false" class="fixed inset-0 bg-black/70 backdrop-blur-xs z-[999]"></div>

    <!-- Drawer Panel Container -->
    <div class="fixed inset-y-0 right-0 max-w-full flex pl-4 sm:pl-10 z-[1000]">
        <div x-show="isCartOpen" 
             x-transition:enter="transform transition ease-in-out duration-300"
             x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-300"
             x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
             class="w-screen max-w-full sm:max-w-md bg-slate-50 shadow-2xl flex flex-col justify-between border-l border-darkBlack-800">

            <!-- Cart Header (Black & Orange Theme) -->
            <div class="p-5 border-b border-darkBlack-800 flex items-center justify-between bg-darkBlack-950 text-white">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-basket-shopping text-brandOrange-500 text-lg"></i>
                    <h2 class="text-base font-bold text-white">Your Basket</h2>
                    <span class="text-xs bg-brandOrange-500 text-white font-black px-2.5 py-0.5 rounded-full" x-text="cartCount + ' items'"></span>
                </div>
                <button @click="isCartOpen = false" class="w-8 h-8 rounded-full bg-darkBlack-800 text-slate-300 hover:text-white flex items-center justify-center font-bold text-lg transition-colors">&times;</button>
            </div>

            <!-- Cart Items List -->
            <div class="flex-grow overflow-y-auto p-4 space-y-3">
                <template x-if="cartItems.length === 0">
                    <div class="text-center py-16">
                        <i class="fa-solid fa-bicycle text-4xl text-slate-300 mb-3"></i>
                        <p class="text-sm font-bold text-slate-900">Your basket is currently empty</p>
                        <p class="text-xs text-slate-500 mt-1">Explore our range of UK E-Bikes and accessories!</p>
                        <a href="{{ route('catalog.index') }}" @click="isCartOpen = false" class="inline-block mt-4 text-xs font-bold text-white bg-brandOrange-500 hover:bg-brandOrange-600 px-6 py-2.5 rounded-full shadow-md transition-colors">
                            Start Shopping &rarr;
                        </a>
                    </div>
                </template>

                <template x-for="item in cartItems" :key="item.id">
                    <div class="flex space-x-3 p-3.5 rounded-2xl border border-slate-200 bg-white hover:border-brandOrange-500/50 transition-colors relative shadow-xs">
                        <img :src="item.image" class="w-16 h-16 object-cover rounded-xl border border-slate-100 flex-shrink-0">
                        <div class="flex-grow">
                            <div class="flex justify-between items-start">
                                <h4 class="text-xs font-bold text-slate-900 pr-2 leading-snug" x-text="item.name"></h4>
                                <button @click="removeItem(item.id)" class="text-slate-400 hover:text-rose-600 text-xs p-1" title="Remove item"><i class="fa-solid fa-trash"></i></button>
                            </div>

                            <span class="inline-block mt-1 text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-brandOrange-50 text-brandOrange-600 border border-brandOrange-500/20"
                                  x-text="item.type === 'rental' ? 'E-Bike Rental' : 'Purchase'"></span>

                            <div x-show="item.rental_dates" class="text-[10px] text-brandOrange-600 font-semibold mt-0.5">
                                <i class="fa-regular fa-calendar-check mr-1"></i> <span x-text="item.rental_dates"></span>
                            </div>

                            <div class="flex justify-between items-center mt-2.5">
                                <div class="flex items-center border border-slate-200 rounded-lg bg-slate-50">
                                    <button @click="updateQty(item.id, item.quantity - 1)" class="px-2 py-0.5 text-xs font-bold text-slate-600 hover:bg-slate-200 rounded-l-lg">-</button>
                                    <span class="px-2.5 text-xs font-bold text-slate-900" x-text="item.quantity"></span>
                                    <button @click="updateQty(item.id, item.quantity + 1)" class="px-2 py-0.5 text-xs font-bold text-slate-600 hover:bg-slate-200 rounded-r-lg">+</button>
                                </div>
                                <span class="text-xs font-black text-brandOrange-500">£<span x-text="item.subtotal"></span></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Cart Footer Summary -->
            <div x-show="cartItems.length > 0" class="p-5 border-t border-slate-200 bg-white space-y-3 shadow-lg">
                <div class="flex justify-between text-xs text-slate-600 font-medium">
                    <span>Subtotal</span>
                    <span class="font-bold text-slate-900">£<span x-text="subtotal"></span></span>
                </div>
                <div class="flex justify-between text-xs text-slate-600 font-medium">
                    <span>UK Delivery</span>
                    <span class="font-bold text-emerald-600">FREE</span>
                </div>
                <div class="border-t border-slate-200 pt-2.5 flex justify-between">
                    <span class="text-sm font-bold text-slate-900">Total</span>
                    <span class="text-brandOrange-500 text-lg font-black">£<span x-text="total"></span></span>
                </div>

                <a href="{{ route('checkout.index') }}" class="w-full text-center py-3.5 text-xs font-bold text-white bg-brandOrange-500 hover:bg-brandOrange-600 rounded-2xl shadow-lg transition-colors flex items-center justify-center gap-2">
                    <span>Proceed to Checkout</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

        </div>
    </div>
</div>
