<!-- Slide-Over Mini Cart Drawer (Exact match to Testing Platform) -->
<div x-show="isCartOpen" x-cloak class="relative z-50">
    <!-- Backdrop -->
    <div x-show="isCartOpen" x-transition.opacity @click="isCartOpen = false" class="fixed inset-0 bg-darkSlate-950/60 backdrop-blur-xs"></div>

    <div class="fixed inset-y-0 right-0 max-w-full flex pl-4 sm:pl-10">
        <div x-show="isCartOpen" x-transition:enter="transform transition ease-in-out duration-300"
             x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-300"
             x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
             class="w-screen max-w-full sm:max-w-md bg-surf shadow-2xl flex flex-col justify-between">

            <!-- Cart Header -->
            <div class="p-5 border-b border-borderLight flex items-center justify-between bg-white">
                <div class="flex items-center space-x-2">
                    <h2 class="font-grotesk text-base font-bold text-darkSlate-900">Your Cart</h2>
                    <span class="text-xs bg-brandOrange-500 text-white font-bold px-2 py-0.5 rounded-full" x-text="cartCount + ' items'"></span>
                </div>
                <button @click="isCartOpen = false" class="w-8 h-8 rounded-lg bg-surf text-textSec hover:text-darkSlate-900 flex items-center justify-center font-bold">&times;</button>
            </div>

            <!-- Cart Items List -->
            <div class="flex-grow overflow-y-auto p-4 space-y-3">
                <template x-if="cartItems.length === 0">
                    <div class="text-center py-16">
                        <i class="fa-solid fa-bicycle text-4xl text-textMuted mb-3"></i>
                        <p class="font-grotesk text-sm font-bold text-darkSlate-900">Your cart is empty</p>
                        <p class="text-xs text-textMuted mt-1">Explore our range of UK E-Bikes and accessories!</p>
                        <a href="{{ route('catalog.index') }}" @click="isCartOpen = false" class="inline-block mt-4 text-xs font-bold text-white bg-brandOrange-500 px-6 py-2.5 rounded-xl shadow-sm">
                            Start Shopping
                        </a>
                    </div>
                </template>

                <template x-for="item in cartItems" :key="item.id">
                    <div class="flex space-x-3 p-3 rounded-xl border border-borderLight bg-white hover:border-borderMid transition-colors relative">
                        <img :src="item.image" class="w-14 h-14 object-cover rounded-lg border border-borderLight flex-shrink-0">
                        <div class="flex-grow">
                            <div class="flex justify-between items-start">
                                <h4 class="font-grotesk text-xs font-bold text-darkSlate-900 pr-2" x-text="item.name"></h4>
                                <button @click="removeItem(item.id)" class="text-textMuted hover:text-rose-600 text-xs"><i class="fa-solid fa-trash"></i></button>
                            </div>

                            <span class="inline-block mt-1 text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-brandOrange-50 text-brandOrange-600 border border-brandOrange-500/20"
                                  x-text="item.type === 'rental' ? 'E-Bike Rental' : 'Purchase'"></span>

                            <div x-show="item.rental_dates" class="text-[10px] text-brandOrange-600 font-semibold mt-0.5">
                                <i class="fa-regular fa-calendar-check mr-1"></i> <span x-text="item.rental_dates"></span>
                            </div>

                            <div class="flex justify-between items-center mt-2">
                                <div class="flex items-center border border-borderLight rounded-lg bg-[#f5f7fb]">
                                    <button @click="updateQty(item.id, item.quantity - 1)" class="px-2 py-0.5 text-xs font-bold text-textSec hover:bg-slate-200">-</button>
                                    <span class="px-2 text-xs font-bold text-darkSlate-900" x-text="item.quantity"></span>
                                    <button @click="updateQty(item.id, item.quantity + 1)" class="px-2 py-0.5 text-xs font-bold text-textSec hover:bg-slate-200">+</button>
                                </div>
                                <span class="font-grotesk text-xs font-extrabold text-brandOrange-500">£<span x-text="item.subtotal"></span></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Cart Footer Summary -->
            <div x-show="cartItems.length > 0" class="p-5 border-t border-borderLight bg-white space-y-3">
                <div class="flex justify-between text-xs text-textSec">
                    <span>Subtotal</span>
                    <span class="font-bold text-darkSlate-900">£<span x-text="subtotal"></span></span>
                </div>
                <div class="flex justify-between text-xs text-textSec">
                    <span>UK Delivery</span>
                    <span class="font-bold text-emerald-600">FREE</span>
                </div>
                <div class="border-t border-borderLight pt-2 flex justify-between">
                    <span class="font-grotesk text-sm font-bold text-darkSlate-900">Total</span>
                    <span class="font-grotesk text-brandOrange-500 text-lg font-extrabold">£<span x-text="total"></span></span>
                </div>

                <a href="{{ route('checkout.index') }}" class="w-full text-center py-3.5 text-xs font-bold text-white bg-brandOrange-500 hover:bg-brandOrange-600 rounded-xl shadow-lg transition-colors flex items-center justify-center gap-2">
                    <span>Proceed to Checkout</span>
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="2" y1="7.5" x2="13" y2="7.5"/><polyline points="8.5,3 13,7.5 8.5,12"/></svg>
                </a>
            </div>

        </div>
    </div>
</div>
