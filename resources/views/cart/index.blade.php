@extends('layouts.app')

@section('title', 'Shopping Basket & Rental Reservations | E-Bike 4 U')

@section('content')
<div class="bg-forest-900 text-white py-10 border-b border-forest-800">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-black text-white"><i class="fa-solid fa-basket-shopping text-amberAcc-500 mr-2"></i> Shopping Basket</h1>
    </div>
</div>

<div class="container mx-auto px-4 py-10" x-data="cartPageApp()">
    @if($cartItems->isEmpty())
        <div class="text-center py-20 bg-white rounded-3xl border border-cream-200 shadow-sm max-w-xl mx-auto">
            <i class="fa-solid fa-bicycle text-5xl text-slate-300 mb-4"></i>
            <h2 class="text-xl font-black text-forest-900">Your basket is currently empty</h2>
            <p class="text-xs text-slate-500 mt-2 mb-6">Explore our range of UK E-Bikes for sale, flexible rentals & accessories!</p>
            <a href="{{ route('catalog.index') }}" class="bg-amberAcc-500 hover:bg-amberAcc-400 text-forest-950 text-xs font-black px-6 py-3 rounded.2xl shadow-lg transition-all inline-block">
                Start Shopping
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left: Cart Items List -->
            <div class="lg:col-span-8 space-y-4">
                <div class="bg-white rounded-3xl border border-cream-200 shadow-sm overflow-hidden p-6">
                    <h3 class="text-sm font-black text-forest-900 uppercase tracking-wider mb-6 pb-3 border-b border-cream-200">
                        Itemized Basket Summary ({{ $cartItems->count() }} items)
                    </h3>

                    <div class="space-y-6">
                        @foreach($cartItems as $item)
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-6 border-b border-cream-100 last:border-b-0 last:pb-0 gap-4">
                                <div class="flex space-x-4 items-center">
                                    <img src="{{ $item->product->primary_image_url }}" class="w-20 h-20 object-cover rounded-2xl border border-cream-200">
                                    <div>
                                        <span class="inline-block text-[10px] font-black uppercase px-2 py-0.5 rounded-full mb-1 {{ $item->item_type === 'rental' ? 'bg-purple-100 text-purple-900' : 'bg-emerald-100 text-emerald-900' }}">
                                            {{ $item->item_type === 'rental' ? 'E-Bike Rental' : 'Purchase' }}
                                        </span>
                                        <h4 class="text-sm font-black text-forest-900 leading-tight">{{ $item->product->name }}</h4>
                                        
                                        @if($item->item_type === 'rental')
                                            <p class="text-xs text-purple-900 font-bold mt-1">
                                                <i class="fa-regular fa-calendar-check mr-1 text-amberAcc-600"></i> {{ $item->rental_start_date->format('d M') }} - {{ $item->rental_end_date->format('d M Y') }} ({{ $item->rental_days }} days @ £{{ number_format($item->daily_rate, 2) }}/day)
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center space-x-6 w-full sm:w-auto justify-between">
                                    <div class="flex items-center border border-slate-200 rounded-xl bg-slate-50">
                                        <button @click="$root.__x.$data.updateQty({{ $item->id }}, {{ $item->quantity - 1 }})" class="px-3 py-1 text-xs font-bold text-slate-600 hover:bg-slate-200 rounded-l-xl">-</button>
                                        <span class="px-3 text-xs font-black text-slate-900">{{ $item->quantity }}</span>
                                        <button @click="$root.__x.$data.updateQty({{ $item->id }}, {{ $item->quantity + 1 }})" class="px-3 py-1 text-xs font-bold text-slate-600 hover:bg-slate-200 rounded-r-xl">+</button>
                                    </div>

                                    <span class="text-base font-black text-forest-900 min-w-[80px] text-right">£{{ number_format($item->subtotal, 2) }}</span>

                                    <button @click="$root.__x.$data.removeItem({{ $item->id }})" class="text-slate-400 hover:text-rose-600 text-sm">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right: Order Financial Summary & Coupon Box -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Coupon Box -->
                <div class="bg-white p-6 rounded-3xl border border-cream-200 shadow-sm space-y-3">
                    <h4 class="text-xs font-black uppercase text-forest-900 tracking-wider">Have a Voucher Code?</h4>
                    <form @submit.prevent="applyCoupon()" class="flex space-x-2">
                        <input type="text" x-model="couponCode" placeholder="Enter coupon code..." class="flex-grow text-xs bg-cream-100/60 border border-cream-200 rounded-xl px-3 py-2.5 uppercase font-bold focus:ring-2 focus:ring-amberAcc-500 text-forest-900">
                        <button type="submit" class="bg-forest-900 text-white text-xs font-black px-4 py-2.5 rounded-xl hover:bg-forest-800 transition-colors">Apply</button>
                    </form>
                    <p class="text-[10px] text-slate-400 font-medium">Try codes: <strong>WELCOME10</strong> (10% off) or <strong>EBIKE50</strong> (£50 off)</p>
                </div>

                <!-- Financial Summary Box -->
                <div class="bg-white p-6 rounded-3xl border border-cream-200 shadow-sm space-y-4">
                    <h3 class="text-sm font-black text-forest-900 uppercase tracking-wider pb-3 border-b border-cream-200">Order Totals</h3>

                    <div class="space-y-2.5 text-xs text-slate-600">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-bold text-forest-900">£{{ number_format($totals['subtotal'], 2) }}</span>
                        </div>
                        @if($totals['discount'] > 0)
                            <div class="flex justify-between text-emeraldAcc-700 font-bold">
                                <span>Discount</span>
                                <span>-£{{ number_format($totals['discount'], 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span>UK VAT (20%)</span>
                            <span class="font-semibold text-slate-700">£{{ number_format($totals['tax'], 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>UK Delivery</span>
                            <span class="font-semibold text-slate-700">{{ $totals['delivery'] > 0 ? '£' . number_format($totals['delivery'], 2) : 'FREE' }}</span>
                        </div>
                        @if($totals['security_deposit'] > 0)
                            <div class="flex justify-between text-purple-900 font-semibold bg-purple-50 p-2 rounded-xl border border-purple-100">
                                <span>Refundable Security Deposit</span>
                                <span>£{{ number_format($totals['security_deposit'], 2) }}</span>
                            </div>
                        @endif
                        <div class="border-t border-cream-200 pt-3 flex justify-between text-base font-black text-forest-900">
                            <span>Total Due</span>
                            <span class="text-amberAcc-600 text-xl font-black">£{{ number_format($totals['total'], 2) }}</span>
                        </div>
                        
                        <div class="bg-cream-100/80 p-3 rounded-2xl border border-cream-200 text-xs space-y-1">
                            <div class="flex justify-between font-extrabold text-forest-900">
                                <span>Pay 30% Advance Option:</span>
                                <span class="text-amberAcc-600 font-black">£{{ number_format($totals['advance_30'], 2) }}</span>
                            </div>
                            <p class="text-[10px] text-slate-500 font-medium">Select 30% advance payment at checkout to reserve your E-Bike today!</p>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="w-full py-4 bg-amberAcc-500 hover:bg-amberAcc-400 text-forest-950 text-xs font-black rounded-2xl shadow-xl transition-all block text-center uppercase tracking-wider">
                        Proceed to Multi-Step Checkout <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function cartPageApp() {
        return {
            couponCode: '',
            async applyCoupon() {
                if (!this.couponCode) return;
                try {
                    let res = await axios.post('{{ route("cart.coupon") }}', { code: this.couponCode });
                    if (res.data.success) {
                        this.$root.__x.$data.showToast(res.data.message);
                        setTimeout(() => location.reload(), 1000);
                    }
                } catch (e) {
                    let msg = e.response?.data?.message || 'Invalid coupon code.';
                    this.$root.__x.$data.showToast(msg, true);
                }
            }
        }
    }
</script>
@endsection
