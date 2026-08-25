@extends('layouts.app')

@section('title', 'Cart & Secure Checkout | eb4u')

@section('content')
<div class="border-b border-borderLight bg-[#edf1f8] text-xs">
    <div class="max-w-[1320px] mx-auto px-6 py-3 flex items-center gap-2 text-textMuted font-medium">
        <a href="{{ route('home') }}" class="hover:text-darkSlate-900">Home</a>
        <span>/</span>
        <span class="text-darkSlate-900 font-bold">Secure Checkout</span>
    </div>
</div>

<div class="max-w-[1100px] mx-auto px-6 py-10" x-data="checkoutApp()">
    <form @submit.prevent="submitOrder()" id="checkoutForm">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left: Multi-Step Checkout Form -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Step Indicator Bar -->
                <div class="bg-white p-4 rounded-2xl border border-borderLight shadow-xs flex justify-between text-xs font-bold">
                    <div :class="step >= 1 ? 'text-darkSlate-900' : 'text-textMuted'" class="flex items-center space-x-2">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center font-grotesk font-extrabold text-xs" :class="step >= 1 ? 'bg-brandOrange-500 text-white' : 'bg-slate-200 text-slate-500'">1</span>
                        <span>Customer Info</span>
                    </div>
                    <div :class="step >= 2 ? 'text-darkSlate-900' : 'text-textMuted'" class="flex items-center space-x-2">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center font-grotesk font-extrabold text-xs" :class="step >= 2 ? 'bg-brandOrange-500 text-white' : 'bg-slate-200 text-slate-500'">2</span>
                        <span>Fulfillment & Address</span>
                    </div>
                    <div :class="step >= 3 ? 'text-darkSlate-900' : 'text-textMuted'" class="flex items-center space-x-2">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center font-grotesk font-extrabold text-xs" :class="step >= 3 ? 'bg-brandOrange-500 text-white' : 'bg-slate-200 text-slate-500'">3</span>
                        <span>Payment & Confirm</span>
                    </div>
                </div>

                <!-- Step 1: Customer Details -->
                <div x-show="step === 1" class="bg-white p-6 rounded-3xl border border-borderLight shadow-xs space-y-4">
                    <h3 class="font-grotesk text-sm font-bold text-darkSlate-900 uppercase tracking-wider pb-3 border-b border-borderLight">Step 1: Contact Details</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-textSec mb-1">Full Name</label>
                            <input type="text" name="customer_name" value="{{ $user->name ?? 'James Harrison' }}" class="w-full text-xs bg-[#f5f7fb] border border-borderLight rounded-xl p-3 font-semibold text-darkSlate-900 focus:ring-2 focus:ring-brandOrange-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-textSec mb-1">Email Address</label>
                            <input type="email" name="customer_email" value="{{ $user->email ?? 'james@example.co.uk' }}" class="w-full text-xs bg-[#f5f7fb] border border-borderLight rounded-xl p-3 font-semibold text-darkSlate-900 focus:ring-2 focus:ring-brandOrange-500">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-textSec mb-1">Mobile Phone (for delivery updates)</label>
                            <input type="text" name="customer_phone" value="{{ $user->phone ?? '+44 7700 900077' }}" class="w-full text-xs bg-[#f5f7fb] border border-borderLight rounded-xl p-3 font-semibold text-darkSlate-900 focus:ring-2 focus:ring-brandOrange-500">
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="button" @click="step = 2" class="py-3 px-6 bg-brandOrange-500 hover:bg-brandOrange-600 text-white text-xs font-bold rounded-xl shadow-md transition-all">
                            Continue to Shipping &rarr;
                        </button>
                    </div>
                </div>

                <!-- Step 2: Address & Fulfillment -->
                <div x-show="step === 2" class="bg-white p-6 rounded-3xl border border-borderLight shadow-xs space-y-4">
                    <h3 class="font-grotesk text-sm font-bold text-darkSlate-900 uppercase tracking-wider pb-3 border-b border-borderLight">Step 2: Fulfillment & Shipping</h3>

                    <div class="space-y-3">
                        <label class="font-grotesk block text-xs font-bold text-darkSlate-900 uppercase">Fulfillment Mode</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="border p-4 rounded-2xl cursor-pointer flex items-center space-x-3 transition-colors" :class="fulfillment === 'delivery' ? 'border-brandOrange-500 bg-brandOrange-50/50' : 'border-borderLight'">
                                <input type="radio" name="fulfillment_type" value="delivery" x-model="fulfillment" class="text-brandOrange-500">
                                <div>
                                    <span class="block text-xs font-bold text-darkSlate-900"><i class="fa-solid fa-truck-fast text-brandOrange-500 mr-1"></i> UK Delivery (FREE)</span>
                                    <span class="block text-[10px] text-textMuted font-medium">Delivered assembled in 2-3 days</span>
                                </div>
                            </label>
                            <label class="border p-4 rounded-2xl cursor-pointer flex items-center space-x-3 transition-colors" :class="fulfillment === 'pickup' ? 'border-brandOrange-500 bg-brandOrange-50/50' : 'border-borderLight'">
                                <input type="radio" name="fulfillment_type" value="pickup" x-model="fulfillment" class="text-brandOrange-500">
                                <div>
                                    <span class="block text-xs font-bold text-darkSlate-900"><i class="fa-solid fa-store text-brandOrange-500 mr-1"></i> Store Pickup (FREE)</span>
                                    <span class="block text-[10px] text-textMuted font-medium">142 Regent Street, London</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div x-show="fulfillment === 'delivery'" class="space-y-4 pt-2">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-textSec mb-1">Street Address</label>
                                <input type="text" name="address_line_1" value="24 Kensington High Street" class="w-full text-xs bg-[#f5f7fb] border border-borderLight rounded-xl p-3 font-semibold text-darkSlate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-textSec mb-1">City / Town</label>
                                <input type="text" name="city" value="London" class="w-full text-xs bg-[#f5f7fb] border border-borderLight rounded-xl p-3 font-semibold text-darkSlate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-textSec mb-1">UK Postcode</label>
                                <input type="text" name="postcode" value="W8 6AG" class="w-full text-xs bg-[#f5f7fb] border border-borderLight rounded-xl p-3 font-semibold uppercase text-darkSlate-900">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-between items-center">
                        <button type="button" @click="step = 1" class="py-2.5 px-5 bg-[#f5f7fb] text-darkSlate-900 font-semibold text-xs rounded-xl border border-borderLight">Back</button>
                        <button type="button" @click="step = 3" class="py-3 px-6 bg-brandOrange-500 hover:bg-brandOrange-600 text-white text-xs font-bold rounded-xl shadow-md transition-all">Next: Payment &rarr;</button>
                    </div>
                </div>

                <!-- Step 3: Payment & Order Finalization -->
                <div x-show="step === 3" class="bg-white p-6 rounded-3xl border border-borderLight shadow-xs space-y-6">
                    <div class="flex justify-between items-center pb-3 border-b border-borderLight">
                        <h3 class="font-grotesk text-sm font-bold text-darkSlate-900 uppercase tracking-wider">Step 3: Payment & Finalization</h3>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full"><i class="fa-solid fa-shield-check"></i> Instant Approval</span>
                    </div>

                    <!-- Payment Plan Toggle -->
                    <div class="space-y-3">
                        <label class="font-grotesk block text-xs font-bold text-darkSlate-900 uppercase">Payment Option</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="border p-4 rounded-2xl cursor-pointer flex items-center space-x-3 transition-colors" :class="paymentType === 'advance' ? 'border-brandOrange-500 bg-brandOrange-50/50' : 'border-borderLight'">
                                <input type="radio" name="payment_type" value="advance" x-model="paymentType" class="text-brandOrange-500">
                                <div>
                                    <span class="block text-xs font-bold text-darkSlate-900">Pay {{ $advancePct }}% Advance Now</span>
                                    <span class="font-grotesk block text-xs font-extrabold text-brandOrange-500">Pay £{{ number_format($advanceAmount, 2) }} Today</span>
                                    <span class="block text-[10px] text-textMuted font-medium">Remaining £{{ number_format($remainingAmount, 2) }} due on return/delivery</span>
                                </div>
                            </label>
                            <label class="border p-4 rounded-2xl cursor-pointer flex items-center space-x-3 transition-colors" :class="paymentType === 'full' ? 'border-brandOrange-500 bg-brandOrange-50/50' : 'border-borderLight'">
                                <input type="radio" name="payment_type" value="full" x-model="paymentType" class="text-brandOrange-500">
                                <div>
                                    <span class="block text-xs font-bold text-darkSlate-900">Pay Full Amount Now</span>
                                    <span class="font-grotesk block text-xs font-extrabold text-brandOrange-500">Pay £{{ number_format($total, 2) }} Today</span>
                                    <span class="block text-[10px] text-textMuted font-medium">Fully paid order invoice</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Credit/Debit Card Form -->
                    <div class="bg-[#f5f7fb] p-4 rounded-2xl border border-borderLight space-y-3">
                        <h4 class="font-grotesk text-xs font-bold uppercase text-darkSlate-900"><i class="fa-solid fa-credit-card mr-1 text-brandOrange-500"></i> Debit / Credit Card</h4>
                        
                        <div>
                            <label class="block text-[10px] font-semibold uppercase text-textSec mb-1">Cardholder Name</label>
                            <input type="text" name="card_holder" value="James Harrison" class="w-full text-xs bg-white border border-borderLight rounded-xl p-2.5 font-bold text-darkSlate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold uppercase text-textSec mb-1">Card Number</label>
                            <input type="text" name="card_number" value="4532 •••• •••• 8821" class="w-full text-xs bg-white border border-borderLight rounded-xl p-2.5 font-mono text-darkSlate-900">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-semibold uppercase text-textSec mb-1">Expiry (MM/YY)</label>
                                <input type="text" name="card_expiry" value="08/28" class="w-full text-xs bg-white border border-borderLight rounded-xl p-2.5 text-center font-mono text-darkSlate-900">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold uppercase text-textSec mb-1">CVV</label>
                                <input type="text" name="card_cvv" value="731" class="w-full text-xs bg-white border border-borderLight rounded-xl p-2.5 text-center font-mono text-darkSlate-900">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-between items-center">
                        <button type="button" @click="step = 2" class="py-2.5 px-5 bg-[#f5f7fb] text-darkSlate-900 font-semibold text-xs rounded-xl border border-borderLight">Back</button>
                        <button type="submit" :disabled="submitting" class="py-3.5 px-7 bg-brandOrange-500 hover:bg-brandOrange-600 text-white text-xs font-bold rounded-xl shadow-lg transition-all flex items-center justify-center">
                            <span x-show="!submitting"><i class="fa-solid fa-lock mr-2"></i> Confirm & Authorize Payment</span>
                            <span x-show="submitting"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Processing Order...</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Right: Summary Sidebar -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white p-6 rounded-3xl border border-borderLight shadow-xs space-y-4">
                    <h3 class="font-grotesk text-sm font-bold text-darkSlate-900 uppercase tracking-wider pb-3 border-b border-borderLight">Order Summary</h3>

                    <div class="space-y-3">
                        @foreach($cartItems as $c)
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-semibold text-darkSlate-900 line-clamp-1 pr-2">{{ $c->product->name }} (x{{ $c->quantity }})</span>
                                <span class="font-grotesk font-bold text-darkSlate-900">£{{ number_format($c->subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-borderLight pt-3 space-y-2 text-xs text-textSec">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-bold text-darkSlate-900">£{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>UK VAT (20%)</span>
                            <span class="font-semibold text-slate-700">£{{ number_format($tax, 2) }}</span>
                        </div>
                        @if($depositTotal > 0)
                            <div class="flex justify-between text-darkSlate-900 font-semibold bg-[#f5f7fb] p-2 rounded-xl border border-borderLight">
                                <span>Security Deposit</span>
                                <span>£{{ number_format($depositTotal, 2) }}</span>
                            </div>
                        @endif
                        <div class="border-t border-borderLight pt-2 flex justify-between text-base font-bold text-darkSlate-900">
                            <span>Total</span>
                            <span class="font-grotesk text-brandOrange-500 text-lg font-extrabold">£{{ number_format($total, 2) }}</span>
                        </div>

                        <div x-show="paymentType === 'advance'" class="bg-[#f5f7fb] p-3 rounded-2xl border border-borderLight text-xs">
                            <div class="flex justify-between font-bold text-darkSlate-900">
                                <span>Pay Today (30%):</span>
                                <span class="font-grotesk text-brandOrange-500 font-extrabold">£{{ number_format($advanceAmount, 2) }}</span>
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
                            html: `<p style="font-size:14px; margin-top:8px;">Order Reference: <strong style="color:#f97316;">${res.data.order_number || ''}</strong></p><p style="font-size:12px; color:#555; margin-top:6px;">Thank you for choosing eb4u! Your order has been placed.</p>`,
                            icon: 'success',
                            confirmButtonText: 'Return to Homepage',
                            confirmButtonColor: '#f97316',
                            background: '#ffffff',
                            color: '#0f172a',
                            customClass: {
                                popup: 'rounded-3xl shadow-2xl p-6',
                                confirmButton: 'px-8 py-3.5 rounded-2xl font-bold text-xs'
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
                            confirmButtonColor: '#f97316'
                        });
                    }
                } catch (e) {
                    this.submitting = false;
                    let msg = e.response?.data?.message || 'Checkout failed. Please try again.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Checkout Error',
                        text: msg,
                        confirmButtonColor: '#f97316'
                    });
                }
            }
        }
    }
</script>
@endsection
