@extends('layouts.app')

@section('title', 'Contact Us | E-Bike 4 U London')

@section('content')
<div class="bg-slate-900 text-white py-12 border-b border-slate-800">
    <div class="container mx-auto px-4 max-w-4xl text-center">
        <h1 class="text-3xl font-black text-white">Get in Touch</h1>
        <p class="text-xs text-slate-400 mt-1">Our London store & customer support team are available 7 days a week.</p>
    </div>
</div>

<div class="container mx-auto px-4 py-12 max-w-4xl" x-data="{ sent: false, message: '' }">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Contact Info -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider pb-3 border-b border-slate-100">UK Store & Showroom</h3>
            
            <div class="space-y-4 text-xs text-slate-600">
                <div class="flex items-start space-x-3">
                    <i class="fa-solid fa-location-dot text-brand-600 text-lg mt-0.5"></i>
                    <div>
                        <strong class="block text-slate-900 font-bold">Flagship Store Location</strong>
                        <span>142 Regent Street, London, W1B 5SE, United Kingdom</span>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fa-solid fa-phone text-brand-600 text-lg mt-0.5"></i>
                    <div>
                        <strong class="block text-slate-900 font-bold">Phone Support</strong>
                        <span>+44 (0) 20 7946 0912</span>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fa-solid fa-envelope text-brand-600 text-lg mt-0.5"></i>
                    <div>
                        <strong class="block text-slate-900 font-bold">Email</strong>
                        <span>support@eb4u.co.uk</span>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fa-solid fa-clock text-brand-600 text-lg mt-0.5"></i>
                    <div>
                        <strong class="block text-slate-900 font-bold">Opening Hours</strong>
                        <span>Mon - Sat: 9:00 AM - 7:00 PM | Sun: 10:00 AM - 5:00 PM</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider pb-3 border-b border-slate-100">Send Us a Message</h3>

            <div x-show="sent" class="p-4 bg-emerald-50 text-emerald-800 text-xs rounded-2xl border border-emerald-200 font-bold" x-text="message"></div>

            <form x-show="!sent" @submit.prevent="axios.post('{{ route('cms.contact.submit') }}', { name: $el.name.value, email: $el.email.value, subject: $el.subject.value, message: $el.message.value }).then(r => { sent = true; message = r.data.message; })" class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Your Name</label>
                    <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email Address</label>
                    <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Subject</label>
                    <input type="text" name="subject" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Message</label>
                    <textarea name="message" rows="4" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold"></textarea>
                </div>
                <button type="submit" class="w-full py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-black rounded-2xl shadow-md transition-colors uppercase">
                    Submit Inquiry
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
