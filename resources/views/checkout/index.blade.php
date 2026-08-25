@extends('layouts.app')

@section('title', 'Secure Checkout | E-Bike 4 U UK')

@section('content')
<div class="bg-forest-900 text-white py-8 border-b border-forest-800">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-black text-white"><i class="fa-solid fa-lock text-amberAcc-500 mr-2"></i> UK Multi-Step Secure Checkout</h1>
    </div>
</div>

<div class="container mx-auto px-4 py-10" x-data="checkoutApp()">
    <form @submit.prevent="submitOrder()" id="checkoutForm">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Left: Checkout Multi-Step Wizard Form -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Step Bar Indicator -->
                <div class="bg-white p-4 rounded-3xl border border-cream-200 shadow-sm flex justify-between text-xs font-black">
                    <div :class="step >= 1 ? 'text-forest-900' : 'text-slate-400'" class="flex items-center space-x-1.5">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center font-black" :class="step >= 1 ? 'bg-amberAcc-500 text-forest-950' : 'bg-slate-200 text-slate-500'">1</span>
                        <span class="hidden sm:inline">Customer Info</span>
                        <span class="sm:hidden">Customer</span>
                    </div>
                    <div :class="step >= 2 ? 'text-forest-900' : 'text-slate-400'" class="flex items-center space-x-1.5">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center font-black" :class="step >= 2 ? 'bg-amberAcc-500 text-forest-950' : 'bg-slate-200 text-slate-500'">2</span>
                        <span class="hidden sm:inline">Fulfillment & Address</span>
                        <span class="sm:hidden">Shipping</span>
                    </div>
                    <div :class="step >= 3 ? 'text-forest-900' : 'text-slate-400'" class="flex items-center space-x-1.5">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center font-black" :class="step >= 3 ? 'bg-amberAcc-500 text-forest-950' : 'bg-slate-200 text-slate-500'">3</span>
                        <span class="hidden sm:inline">Payment & Place Order</span>
                        <span class="sm:hidden">Payment</span>
                    </div>
                </div>

                <!-- Step 1: Customer Details -->
                <div x-show="step === 1" class="bg-white p-6 rounded-3xl border border-cream-200 shadow-sm space-y-4">
                    <h3 class="text-sm font-black text-forest-900 uppercase tracking-wider pb-3 border-b border-cream-200">Step 1: Contact Details</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Full Name</label>
                            <input type="text" name="customer_name" value="{{ $user->name ?? 'James Harrison' }}" class="w-full text-xs bg-cream-100/60 border border-cream-200 rounded-xl p-3 font-semibold text-slate-900 focus:ring-2 focus:ring-amberAcc-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                            <input type="email" name="customer_email" value="{{ $user->email ?? 'james@example.co.uk' }}" class="w-full text-xs bg-cream-100/60 border border-cream-200 rounded-xl p-3 font-semibold text-slate-900 focus:ring-2 focus:ring-amberAcc-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Mobile Phone (for delivery updates)</label>
                            <input type="text" name="customer_phone" value="{{ $user->phone ?? '+44 7700 900077' }}" class="w-full text-xs bg-cream-100/60 border border-cream-200 rounded-xl p-3 font-semibold text-slate-900 focus:ring-2 focus:ring-amberAcc-500">
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="button" @click="step = 2" class="w-full sm:w-auto py-3.5 px-7 bg-forest-900 hover:bg-forest-800 text-white text-xs font-black rounded-2xl shadow-lg transition-all text-center">
                            Next: Delivery & Address <i class="fa-solid fa-arrow-right ml-1"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Address & Fulfillment -->
                <div x-show="step === 2" class="bg-white p-6 rounded-3xl border border-cream-200 shadow-sm space-y-4">
                    <h3 class="text-sm font-black text-forest-900 uppercase tracking-wider pb-3 border-b border-cream-200">Step 2: Fulfillment & Shipping</h3>

                    <div class="space-y-3">
                        <label class="block text-xs font-black text-forest-900 uppercase">Select Fulfillment Mode</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="border p-4 rounded-2xl cursor-pointer flex items-center space-x-3 transition-colors" :class="fulfillment === 'delivery' ? 'border-amberAcc-500 bg-cream-100/80' : 'border-slate-200'">
                                <input type="radio" name="fulfillment_type" value="delivery" x-model="fulfillment" class="text-amberAcc-600">
                                <div>
                                    <span class="block text-xs font-black text-forest-900"><i class="fa-solid fa-truck-fast text-amberAcc-600 mr-1"></i> UK Home Delivery</span>
                                    <span class="block text-[10px] text-slate-500 font-medium">Delivered fully assembled in 2-3 days</span>
                                </div>
                            </label>
                            <label class="border p-4 rounded-2xl cursor-pointer flex items-center space-x-3 transition-colors" :class="fulfillment === 'pickup' ? 'border-amberAcc-500 bg-cream-100/80' : 'border-slate-200'">
                                <input type="radio" name="fulfillment_type" value="pickup" x-model="fulfillment" class="text-amberAcc-600">
                                <div>
                                    <span class="block text-xs font-black text-forest-900"><i class="fa-solid fa-store text-amberAcc-600 mr-1"></i> Store Pickup (FREE)</span>
                                    <span class="block text-[10px] text-slate-500 font-medium">Regent Street Store, London</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div x-show="fulfillment === 'delivery'" class="space-y-4 pt-2">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Street Address</label>
                                <input type="text" name="address_line_1" value="24 Kensington High Street" class="w-full text-xs bg-cream-100/60 border border-cream-200 rounded-xl p-3 font-semibold text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">City / Town</label>
                                <input type="text" name="city" value="London" class="w-full text-xs bg-cream-100/60 border border-cream-200 rounded-xl p-3 font-semibold text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">UK Postcode</label>
                                <input type="text" name="postcode" value="W8 6AG" class="w-full text-xs bg-cream-100/60 border border-cream-200 rounded-xl p-3 font-semibold uppercase text-slate-900">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col-reverse sm:flex-row gap-3 justify-between items-stretch sm:items-center">
                        <button type="button" @click="step = 1" class="w-full sm:w-auto py-3.5 px-6 bg-slate-200 hover:bg-slate-300 text-slate-900 font-bold text-xs rounded-2xl border border-slate-300 text-center">Back</button>
                        <button type="button" @click="step = 3" class="w-full sm:w-auto py-3.5 px-7 bg-forest-900 hover:bg-forest-800 text-white text-xs font-black rounded-2xl shadow-lg transition-all text-center">Next: Payment <i class="fa-solid fa-arrow-right ml-1"></i></button>
                    </div>
                </div>

                <!-- Step 3: Payment & Order Finalization -->
                <div x-show="step === 3" class="bg-white p-6 rounded-3xl border border-cream-200 shadow-sm space-y-6">
                    <div class="flex justify-between items-center pb-3 border-b border-cream-200">
                        <h3 class="text-sm font-black text-forest-900 uppercase tracking-wider">Step 3: Payment Options & Place Order</h3>
                        <span class="text-xs font-black text-emeraldAcc-600 bg-emerald-50 px-2.5 py-1 rounded-full"><i class="fa-solid fa-shield-check"></i> Instant Approval Mode</span>
                    </div>

                    <!-- Advance vs Full Payment Toggle -->
                    <div class="space-y-3">
                        <label class="block text-xs font-black text-forest-900 uppercase">Payment Plan</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="border p-4 rounded-2xl cursor-pointer flex items-center space-x-3 transition-colors" :class="paymentType === 'advance' ? 'border-amberAcc-500 bg-cream-100/80' : 'border-slate-200'">
                                <input type="radio" name="payment_type" value="advance" x-model="paymentType" class="text-amberAcc-600">
                                <div>
                                    <span class="block text-xs font-black text-forest-900">Pay {{ $advancePct }}% Advance Now</span>
                                    <span class="block text-xs font-black text-amberAcc-600">Pay £{{ number_format($advanceAmount, 2) }} Today</span>
                                    <span class="block text-[10px] text-slate-500 font-medium">Remaining £{{ number_format($remainingAmount, 2) }} due on return/delivery</span>
                                </div>
                            </label>
                            <label class="border p-4 rounded-2xl cursor-pointer flex items-center space-x-3 transition-colors" :class="paymentType === 'full' ? 'border-amberAcc-500 bg-cream-100/80' : 'border-slate-200'">
                                <input type="radio" name="payment_type" value="full" x-model="paymentType" class="text-amberAcc-600">
                                <div>
                                    <span class="block text-xs font-black text-forest-900">Pay Full Amount Now</span>
                                    <span class="block text-xs font-black text-amberAcc-600">Pay £{{ number_format($total, 2) }} Today</span>
                                    <span class="block text-[10px] text-slate-500 font-medium">Fully paid order invoice</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Simulated Credit/Debit Card Form -->
                    <div class="bg-cream-100/60 p-4 rounded-2xl border border-cream-200 space-y-3">
                        <h4 class="text-xs font-black uppercase text-forest-900"><i class="fa-solid fa-credit-card mr-1 text-amberAcc-600"></i> Debit / Credit Card Payment</h4>
                        
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-600 mb-1">Cardholder Name</label>
                            <input type="text" name="card_holder" value="James Harrison" class="w-full text-xs bg-white border border-cream-200 rounded-xl p-2.5 font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-600 mb-1">Card Number</label>
                            <input type="text" name="card_number" value="4532 •••• •••• 8821" class="w-full text-xs bg-white border border-cream-200 rounded-xl p-2.5 font-mono text-slate-900">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-600 mb-1">Expiry (MM/YY)</label>
                                <input type="text" name="card_expiry" value="08/28" class="w-full text-xs bg-white border border-cream-200 rounded-xl p-2.5 text-center font-mono text-slate-900">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-600 mb-1">Security Code (CVV)</label>
                                <input type="text" name="card_cvv" value="731" class="w-full text-xs bg-white border border-cream-200 rounded-xl p-2.5 text-center font-mono text-slate-900">
                            </div>
                        </div>
                    </div>

                    <!-- Responsive Action Buttons Matching Screenshot Request -->
                    <div class="pt-4 flex flex-col-reverse sm:flex-row gap-3 justify-between items-stretch sm:items-center">
                        <button type="button" @click="step = 2" class="w-full sm:w-auto py-3.5 px-6 bg-slate-200 hover:bg-slate-300 text-slate-900 font-bold text-xs rounded-2xl border border-slate-300 text-center">Back</button>
                        <button type="submit" :disabled="submitting" class="w-full sm:w-auto flex-1 py-3.5 px-6 bg-amberAcc-500 hover:bg-amberAcc-400 text-forest-950 text-xs font-black rounded-2xl shadow-xl transition-all uppercase tracking-wider text-center flex items-center justify-center">
                            <span x-show="!submitting"><i class="fa-solid fa-lock mr-2"></i> Confirm & Authorize Payment</span>
                            <span x-show="submitting"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Processing Order...</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Right: Order Summary Sidebar -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white p-6 rounded-3xl border border-cream-200 shadow-sm space-y-4">
                    <h3 class="text-sm font-black text-forest-900 uppercase tracking-wider pb-3 border-b border-cream-200">Summary</h3>

                    <div class="space-y-3">
                        @foreach($cartItems as $c)
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold text-slate-800 line-clamp-1 pr-2">{{ $c->product->name }} (x{{ $c->quantity }})</span>
                                <span class="font-black text-forest-900">£{{ number_format($c->subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-cream-200 pt-3 space-y-2 text-xs text-slate-600">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-bold text-forest-900">£{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>UK VAT (20%)</span>
                            <span class="font-semibold text-slate-700">£{{ number_format($tax, 2) }}</span>
                        </div>
                        @if($depositTotal > 0)
                            <div class="flex justify-between text-purple-900 font-semibold bg-purple-50 p-2 rounded-xl border border-purple-100">
                                <span>Security Deposit</span>
                                <span>£{{ number_format($depositTotal, 2) }}</span>
                            </div>
                        @endif
                        <div class="border-t border-cream-200 pt-2 flex justify-between text-base font-black text-forest-900">
                            <span>Total</span>
                            <span class="text-amberAcc-600 text-lg font-black">£{{ number_format($total, 2) }}</span>
                        </div>

                        <div x-show="paymentType === 'advance'" class="bg-cream-100/80 p-3 rounded-2xl border border-cream-200 text-xs">
                            <div class="flex justify-between font-extrabold text-forest-900">
                                <span>Pay Today (30%):</span>
                                <span class="text-amberAcc-600 font-black">£{{ number_format($advanceAmount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    function checkoutApp() {
        return {
            step: 1,
            fulfillment: 'delivery',
            paymentType: 'advance',
            submitting: false,

            async submitOrder() {
                this.submitting = true;
                let form = document.getElementById('checkoutForm');
                let formData = new FormData(form);

                try {
                    let res = await axios.post('{{ route("checkout.process") }}', formData);
                    if (res.data.success) {
                        Swal.fire({
                            title: 'Order Placed Successfully! 🎉',
                            html: `<p style="font-size:14px; margin-top:8px;">Order Reference: <strong style="color:#e88d36;">${res.data.order_number || ''}</strong></p><p style="font-size:12px; color:#555; margin-top:6px;">Thank you for choosing E-Bike 4 U! Your order has been placed.</p>`,
                            icon: 'success',
                            confirmButtonText: 'Return to Homepage',
                            confirmButtonColor: '#06281e',
                            background: '#ffffff',
                            color: '#06281e',
                            customClass: {
                                popup: 'rounded-3xl shadow-2xl p-6',
                                confirmButton: 'px-8 py-3.5 rounded-2xl font-black text-xs'
                            },
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                        }).then((result) => {
                            window.location.href = '{{ route("home") }}';
                        });
                    } else {
                        this.submitting = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'Checkout Error',
                            text: res.data.message || 'Checkout failed.',
                            confirmButtonColor: '#06281e'
                        });
                    }
                } catch (e) {
                    this.submitting = false;
                    let msg = e.response?.data?.message || 'Checkout failed. Please try again.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Checkout Error',
                        text: msg,
                        confirmButtonColor: '#06281e'
                    });
                }
            }
        }
    }
</script>
@endsection
