@extends('layouts.app')

@section('title', 'Shopping Basket | Eb4u')

@section('content')
<div class="bg-darkBlack-950 text-white py-8 border-b border-darkBlack-800">
    <div class="container mx-auto px-6 md:px-12">
        <h1 class="text-2xl font-black text-white"><i class="fa-solid fa-basket-shopping text-brandOrange-500 mr-2"></i> Shopping Basket</h1>
    </div>
</div>

<div class="container mx-auto px-6 md:px-12 py-10" x-data="cartPageApp()">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Left: Cart Items Table -->
        <div class="lg:col-span-8 space-y-6">
            <template x-if="cartItems.length === 0">
                <div class="bg-white p-12 rounded-3xl border border-slate-200 text-center space-y-4 shadow-sm">
                    <i class="fa-solid fa-bicycle text-5xl text-slate-300"></i>
                    <h2 class="text-lg font-black text-slate-900">Your basket is currently empty</h2>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">Explore our range of UK electric bikes for sale, rentals, and accessories!</p>
                    <a href="{{ route('catalog.index') }}" class="inline-block px-8 py-3.5 bg-brandOrange-500 text-white font-black text-xs rounded-2xl shadow-lg hover:bg-brandOrange-600">
                        Browse Full Catalog
                    </a>
                </div>
            </template>

            <template x-if="cartItems.length > 0">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Basket Items (<span x-text="cartCount"></span>)</h3>
                        <span class="text-xs font-bold text-brandOrange-600"><i class="fa-solid fa-shield-check mr-1"></i> Certified Battery & Rental Guarantee</span>
                    </div>

                    <div class="divide-y divide-slate-100">
                        <template x-for="item in cartItems" :key="item.id">
                            <div class="p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="flex items-center space-x-4 flex-1">
                                    <img :src="item.image" class="w-20 h-20 object-cover rounded-2xl border border-slate-200 flex-shrink-0">
                                    <div>
                                        <h4 class="text-sm font-black text-slate-900" x-text="item.name"></h4>
                                        
                                        <span class="inline-block mt-1 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-darkBlack-900 text-brandOrange-400"
                                              x-text="item.type === 'rental' ? 'E-Bike Rental' : 'Purchase'"></span>

                                        <div x-show="item.rental_dates" class="text-xs text-brandOrange-600 font-bold mt-1">
                                            <i class="fa-regular fa-calendar-check mr-1"></i> <span x-text="item.rental_dates"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between w-full sm:w-auto space-x-6">
                                    <div class="flex items-center border border-slate-200 rounded-xl bg-slate-50">
                                        <button @click="updateQty(item.id, item.quantity - 1)" class="px-3 py-1 text-xs font-bold text-slate-600 hover:bg-slate-200 rounded-l-xl">-</button>
                                        <span class="px-3 text-xs font-black text-slate-900" x-text="item.quantity"></span>
                                        <button @click="updateQty(item.id, item.quantity + 1)" class="px-3 py-1 text-xs font-bold text-slate-600 hover:bg-slate-200 rounded-r-xl">+</button>
                                    </div>

                                    <div class="text-right">
                                        <span class="block text-sm font-black text-slate-900">£<span x-text="item.subtotal"></span></span>
                                        <button @click="removeItem(item.id)" class="text-xs text-rose-500 hover:underline font-semibold mt-0.5">Remove</button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <!-- Right: Order Summary -->
        <div class="lg:col-span-4 space-y-6" x-show="cartItems.length > 0">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider pb-3 border-b border-slate-100">Order Summary</h3>

                <!-- Coupon Form -->
                <form @submit.prevent="applyCoupon()" class="flex space-x-2">
                    <input type="text" x-model="couponCode" placeholder="Promo / Coupon Code" class="flex-grow text-xs bg-slate-50 border border-slate-200 rounded-xl p-2.5 font-bold uppercase text-slate-900">
                    <button type="submit" class="px-4 py-2.5 bg-darkBlack-900 hover:bg-black text-white text-xs font-black rounded-xl transition-colors">
                        Apply
                    </button>
                </form>

                <div class="border-t border-slate-100 pt-3 space-y-2 text-xs text-slate-600 font-medium">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="font-bold text-slate-900">£<span x-text="subtotal"></span></span>
                    </div>
                    <div class="flex justify-between">
                        <span>UK VAT (20%)</span>
                        <span class="font-semibold text-slate-700">£<span x-text="tax"></span></span>
                    </div>
                    <div class="border-t border-slate-100 pt-2 flex justify-between text-base font-black text-slate-900">
                        <span>Estimated Total</span>
                        <span class="text-brandOrange-500 text-lg font-black">£<span x-text="total"></span></span>
                    </div>
                </div>

                <a href="{{ route('checkout.index') }}" class="block text-center py-4 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-black text-xs uppercase tracking-wider rounded-2xl shadow-xl transition-all">
                    Proceed to Checkout <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

    </div>
</div>

<script>
    function cartPageApp() {
        return {
            couponCode: '',
            async updateQty(itemId, newQty) {
                this.$root.__x.$data.updateQty(itemId, newQty);
            },
            async removeItem(itemId) {
                this.$root.__x.$data.removeItem(itemId);
            },
            async applyCoupon() {
                if (!this.couponCode) return;
                try {
                    let res = await axios.post('{{ route("cart.coupon") }}', { coupon_code: this.couponCode });
                    if (res.data.success) {
                        this.$root.__x.$data.showToast(res.data.message);
                        this.$root.__x.$data.fetchCart();
                    } else {
                        this.$root.__x.$data.showToast(res.data.message, true);
                    }
                } catch (e) {
                    this.$root.__x.$data.showToast('Invalid promo code.', true);
                }
            }
        }
    }
</script>
@endsection
