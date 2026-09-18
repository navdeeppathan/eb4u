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
    <form @submit.prevent="submitOrder()" id="checkoutForm" enctype="multipart/form-data">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left: Multi-Step Checkout Form -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Step Indicator Bar -->
                <div class="bg-white p-4 rounded-2xl border border-borderLight shadow-xs flex justify-between text-xs font-bold gap-2 overflow-x-auto">
                    <div :class="step >= 1 ? 'text-darkSlate-900' : 'text-textMuted'" class="flex items-center space-x-1.5 whitespace-nowrap">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center font-grotesk font-extrabold text-xs" :class="step >= 1 ? 'bg-brandOrange-500 text-white' : 'bg-slate-200 text-slate-500'">1</span>
                        <span>Customer Info</span>
                    </div>
                    <div :class="step >= 2 ? 'text-darkSlate-900' : 'text-textMuted'" class="flex items-center space-x-1.5 whitespace-nowrap">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center font-grotesk font-extrabold text-xs" :class="step >= 2 ? 'bg-brandOrange-500 text-white' : 'bg-slate-200 text-slate-500'">2</span>
                        <span>Fulfillment</span>
                    </div>
                    <div :class="step >= 3 ? 'text-darkSlate-900' : 'text-textMuted'" class="flex items-center space-x-1.5 whitespace-nowrap">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center font-grotesk font-extrabold text-xs" :class="step >= 3 ? 'bg-brandOrange-500 text-white' : 'bg-slate-200 text-slate-500'">3</span>
                        <span>Verification Docs</span>
                    </div>
                    <div :class="step >= 4 ? 'text-darkSlate-900' : 'text-textMuted'" class="flex items-center space-x-1.5 whitespace-nowrap">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center font-grotesk font-extrabold text-xs" :class="step >= 4 ? 'bg-brandOrange-500 text-white' : 'bg-slate-200 text-slate-500'">4</span>
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
                            Continue to Fulfillment &rarr;
                        </button>
                    </div>
                </div>

                <!-- Step 2: Store Pickup Info -->
                <div x-show="step === 2" class="bg-white p-6 rounded-3xl border border-borderLight shadow-xs space-y-4">
                    <h3 class="font-grotesk text-sm font-bold text-darkSlate-900 uppercase tracking-wider pb-3 border-b border-borderLight">Step 2: Store Pickup Location</h3>

                    <input type="hidden" name="fulfillment_type" value="pickup">

                    <div class="bg-brandOrange-50/50 border border-brandOrange-200 p-4 rounded-2xl space-y-3">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-brandOrange-500 text-white flex items-center justify-center font-bold text-lg shrink-0 mt-0.5">
                                <i class="fa-solid fa-store"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-darkSlate-900 text-sm">Flagship Store Pickup</h4>
                                <p class="text-xs text-textSec font-medium mt-0.5">{{ \App\Models\SystemSetting::get('store_address', 'Near, 103 Inwood Rd, Hounslow TW3 1XA') }}</p>
                                <p class="text-[11px] text-textMuted mt-1"><i class="fa-solid fa-clock text-brandOrange-500 mr-1"></i> Pickup Hours: Mon - Sat (9:00 AM - 6:00 PM)</p>
                            </div>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-brandOrange-100 text-xs text-textSec leading-relaxed">
                            <i class="fa-solid fa-circle-info text-brandOrange-500 mr-1"></i> Please bring your <strong>Proof of ID</strong> and <strong>Proof of Address</strong> when collecting your E-Bike in store.
                        </div>
                    </div>

                    <div class="pt-4 flex justify-between items-center">
                        <button type="button" @click="step = 1" class="py-2.5 px-5 bg-[#f5f7fb] text-darkSlate-900 font-semibold text-xs rounded-xl border border-borderLight">Back</button>
                        <button type="button" @click="step = 3" class="py-3 px-6 bg-brandOrange-500 hover:bg-brandOrange-600 text-white text-xs font-bold rounded-xl shadow-md transition-all">Next: Upload Verification Docs &rarr;</button>
                    </div>
                </div>

                <!-- Step 3: Verification Documents Upload -->
                <div x-show="step === 3" class="bg-white p-6 rounded-3xl border border-borderLight shadow-xs space-y-5">
                    <div class="flex justify-between items-center pb-3 border-b border-borderLight">
                        <div>
                            <h3 class="font-grotesk text-sm font-bold text-darkSlate-900 uppercase tracking-wider">Step 3: Identity & Address Verification Documents</h3>
                            <p class="text-xs text-textMuted mt-0.5">Mandatory security check before payment authorization under UK rental regulations.</p>
                        </div>
                        <span class="text-xs font-bold text-brandOrange-600 bg-brandOrange-50 px-2.5 py-1 rounded-full border border-brandOrange-500/20"><i class="fa-solid fa-shield-halved"></i> Official UK Verification</span>
                    </div>

                    <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200 text-xs text-amber-900 space-y-1">
                        <div class="font-bold flex items-center gap-1.5"><i class="fa-solid fa-triangle-exclamation text-amber-600"></i> Required Documents Notice</div>
                        <p class="leading-relaxed">Please upload clear photos or PDF documents for your <strong>Proof of ID</strong> (Passport, UK BRP/Visa, or Driving License) and <strong>UK Proof of Address</strong> (Utility bill, Bank Statement, or Council tax). Accepted formats: JPG, PNG, WEBP, PDF (Max 2MB per file).</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Upload 1: Proof of ID -->
                        <div class="p-5 bg-[#f8fafc] rounded-2xl border border-dashed border-slate-300 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="font-grotesk text-xs font-bold text-darkSlate-900 uppercase"><i class="fa-solid fa-id-card text-brandOrange-500 mr-1.5"></i> Proof of Identity (Photo ID)</span>
                                <span class="text-[10px] font-bold text-brandOrange-600 bg-brandOrange-50 px-2 py-0.5 rounded-md">Passport / BRP / License</span>
                            </div>
                            <p class="text-[11px] text-textMuted">Upload Passport, UK Visa/BRP, or UK Driving License.</p>

                            <div class="relative">
                                <input type="file" name="proof_of_id" id="proof_of_id" accept="image/*,.pdf" @change="handleFileChange($event, 'id')" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brandOrange-500 file:text-white hover:file:bg-brandOrange-600 cursor-pointer">
                            </div>

                            <template x-if="idFileName">
                                <div class="p-2.5 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center justify-between text-xs text-emerald-900 font-medium">
                                    <span class="truncate max-w-[200px]" x-text="idFileName"></span>
                                    <span class="bg-emerald-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md" x-text="idFileType"></span>
                                </div>
                            </template>
                        </div>

                        <!-- Upload 2: Proof of Address -->
                        <div class="p-5 bg-[#f8fafc] rounded-2xl border border-dashed border-slate-300 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="font-grotesk text-xs font-bold text-darkSlate-900 uppercase"><i class="fa-solid fa-house-user text-brandOrange-500 mr-1.5"></i> UK Proof of Address</span>
                                <span class="text-[10px] font-bold text-brandOrange-600 bg-brandOrange-50 px-2 py-0.5 rounded-md">Utility / Bank Statement</span>
                            </div>
                            <p class="text-[11px] text-textMuted">Utility bill, Bank statement, or Council tax (last 3 months).</p>

                            <div class="relative">
                                <input type="file" name="proof_of_address" id="proof_of_address" accept="image/*,.pdf" @change="handleFileChange($event, 'address')" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brandOrange-500 file:text-white hover:file:bg-brandOrange-600 cursor-pointer">
                            </div>

                            <template x-if="addressFileName">
                                <div class="p-2.5 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center justify-between text-xs text-emerald-900 font-medium">
                                    <span class="truncate max-w-[200px]" x-text="addressFileName"></span>
                                    <span class="bg-emerald-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md" x-text="addressFileType"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-between items-center">
                        <button type="button" @click="step = 2" class="py-2.5 px-5 bg-[#f5f7fb] text-darkSlate-900 font-semibold text-xs rounded-xl border border-borderLight">Back</button>
                        <button type="button" @click="goToPaymentStep()" class="py-3 px-6 bg-brandOrange-500 hover:bg-brandOrange-600 text-white text-xs font-bold rounded-xl shadow-md transition-all">
                            Next: Proceed to Payment &rarr;
                        </button>
                    </div>
                </div>

                <!-- Step 4: Payment & Order Finalization -->
                <div x-show="step === 4" class="bg-white p-6 rounded-3xl border border-borderLight shadow-xs space-y-6">
                    <div class="flex justify-between items-center pb-3 border-b border-borderLight">
                        <h3 class="font-grotesk text-sm font-bold text-darkSlate-900 uppercase tracking-wider">Step 4: Payment Method & Finalization</h3>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full"><i class="fa-solid fa-shield-check"></i> Instant Approval</span>
                    </div>

                    <input type="hidden" name="payment_type" value="full">
                    <input type="hidden" name="payment_method" :value="paymentMethod">

                    <!-- Select Payment Method Tabs -->
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-darkSlate-900 uppercase tracking-wider">Select How You Wish to Pay</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Option 1: Card Online -->
                            <label @click="paymentMethod = 'card'" :class="paymentMethod === 'card' ? 'border-brandOrange-500 bg-brandOrange-50/20 ring-2 ring-brandOrange-500/20' : 'border-borderLight bg-[#f5f7fb] hover:border-slate-300'" class="p-4 rounded-2xl border cursor-pointer transition-all flex items-start space-x-3">
                                <input type="radio" name="pay_choice" value="card" x-model="paymentMethod" class="mt-0.5 text-brandOrange-500 focus:ring-brandOrange-500">
                                <div>
                                    <span class="font-grotesk font-bold text-xs text-darkSlate-900 block"><i class="fa-solid fa-credit-card text-brandOrange-500 mr-1.5"></i> Debit / Credit Card</span>
                                    <span class="text-[11px] text-textMuted leading-tight block mt-0.5">Pay online instantly with encrypted 3D secure card checkout.</span>
                                </div>
                            </label>

                            <!-- Option 2: Pay at Store (Cash/Counter) -->
                            <label @click="paymentMethod = 'cash_on_pickup'" :class="paymentMethod === 'cash_on_pickup' ? 'border-brandOrange-500 bg-brandOrange-50/20 ring-2 ring-brandOrange-500/20' : 'border-borderLight bg-[#f5f7fb] hover:border-slate-300'" class="p-4 rounded-2xl border cursor-pointer transition-all flex items-start space-x-3">
                                <input type="radio" name="pay_choice" value="cash_on_pickup" x-model="paymentMethod" class="mt-0.5 text-brandOrange-500 focus:ring-brandOrange-500">
                                <div>
                                    <span class="font-grotesk font-bold text-xs text-darkSlate-900 block"><i class="fa-solid fa-store text-emerald-600 mr-1.5"></i> Pay at Store (Cash / Counter)</span>
                                    <span class="text-[11px] text-textMuted leading-tight block mt-0.5">Pay cash or card at counter when collecting bike at store.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Credit/Debit Card Form -->
                    <div x-show="paymentMethod === 'card'" class="bg-[#f5f7fb] p-4 rounded-2xl border border-borderLight space-y-3">
                        <h4 class="font-grotesk text-xs font-bold uppercase text-darkSlate-900"><i class="fa-solid fa-credit-card mr-1 text-brandOrange-500"></i> Debit / Credit Card Details</h4>
                        
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

                    <!-- Pay at Store Notice Box -->
                    <div x-show="paymentMethod === 'cash_on_pickup'" class="bg-emerald-50 border border-emerald-200 p-5 rounded-2xl space-y-3 text-xs text-emerald-950">
                        <div class="flex items-center gap-2 font-black text-sm text-emerald-900">
                            <i class="fa-solid fa-store text-emerald-600 text-lg"></i>
                            <span>Pay in Store / Cash on Pickup Selected</span>
                        </div>
                        <p class="leading-relaxed font-medium">
                            Your order will be reserved immediately. Full payment of <strong>£{{ number_format($total, 2) }}</strong> will be collected in cash or by card at our store counter upon collection.
                        </p>
                        <div class="bg-white p-3 rounded-xl border border-emerald-200 text-slate-700 space-y-1">
                            <span class="font-bold text-emerald-800 block"><i class="fa-solid fa-location-dot text-brandOrange-500 mr-1"></i> Pickup Location & Store Counter:</span>
                            <span>{{ \App\Models\SystemSetting::get('store_address', 'Near, 103 Inwood Rd, Hounslow TW3 1XA') }}</span>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-between items-center">
                        <button type="button" @click="step = 3" class="py-2.5 px-5 bg-[#f5f7fb] text-darkSlate-900 font-semibold text-xs rounded-xl border border-borderLight">Back</button>
                        <button type="submit" :disabled="submitting" class="py-3.5 px-7 bg-brandOrange-500 hover:bg-brandOrange-600 text-white text-xs font-bold rounded-xl shadow-lg transition-all flex items-center justify-center">
                            <span x-show="!submitting && paymentMethod === 'card'"><i class="fa-solid fa-lock mr-2"></i> Confirm & Authorize Payment</span>
                            <span x-show="!submitting && paymentMethod === 'cash_on_pickup'"><i class="fa-solid fa-store mr-2"></i> Confirm Order (Pay at Store)</span>
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
            paymentMethod: 'card',
            submitting: false,
            hasRental: {{ $hasRental ? 'true' : 'false' }},
            idFileName: '',
            idFileType: '',
            addressFileName: '',
            addressFileType: '',

            handleFileChange(event, type) {
                const file = event.target.files[0];
                if (!file) return;

                const maxBytes = 2 * 1024 * 1024; // 2MB
                if (file.size > maxBytes) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Size Exceeds 2MB Limit',
                        text: `The selected file "${file.name}" is ${(file.size / (1024 * 1024)).toFixed(2)}MB. Please select a file under 2MB.`,
                        confirmButtonColor: '#f97316'
                    });
                    event.target.value = '';
                    if (type === 'id') {
                        this.idFileName = '';
                        this.idFileType = '';
                    } else if (type === 'address') {
                        this.addressFileName = '';
                        this.addressFileType = '';
                    }
                    return;
                }

                const ext = file.name.split('.').pop().toUpperCase();
                if (type === 'id') {
                    this.idFileName = file.name;
                    this.idFileType = ext === 'PDF' ? 'PDF' : 'IMAGE';
                } else if (type === 'address') {
                    this.addressFileName = file.name;
                    this.addressFileType = ext === 'PDF' ? 'PDF' : 'IMAGE';
                }
            },

            goToPaymentStep() {
                if (this.hasRental) {
                    const idInput = document.getElementById('proof_of_id');
                    const addressInput = document.getElementById('proof_of_address');

                    if (!idInput || !idInput.files.length) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Proof of ID Required',
                            text: 'Please select a valid Proof of ID file (Passport, UK BRP/Visa, or Driving License) before proceeding to payment.',
                            confirmButtonColor: '#f97316'
                        });
                        return;
                    }

                    if (!addressInput || !addressInput.files.length) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Proof of Address Required',
                            text: 'Please select a valid UK Proof of Address file (Utility bill, Bank Statement, or Council tax) before proceeding to payment.',
                            confirmButtonColor: '#f97316'
                        });
                        return;
                    }
                }
                this.step = 4;
            },

            async submitOrder() {
                this.submitting = true;
                let form = document.getElementById('checkoutForm');
                let formData = new FormData(form);

                try {
                    let res = await axios.post('{{ route("checkout.process") }}', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    if (res.data.success) {
                        Swal.fire({
                            title: 'Order Placed Successfully! 🎉',
                            html: `<p style="font-size:14px; margin-top:8px;">Order Reference: <strong style="color:#f97316;">${res.data.order_number || ''}</strong></p><p style="font-size:12px; color:#555; margin-top:6px;">Thank you! Your rental documents have been submitted & order confirmed.</p>`,
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
                    let msg = e.response?.data?.message || 'Checkout failed. Please check your uploaded document files.';
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
