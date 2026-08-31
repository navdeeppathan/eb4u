@extends('layouts.app')

@section('title', 'Create Account | eb4u')

@section('content')
<div class="container mx-auto px-6 md:px-12 py-16 max-w-md">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl p-8 space-y-6">
        
        <div class="text-center space-y-2">
            <a href="{{ route('home') }}" class="inline-block">
                <img src="{{ asset('images/logo.webp') }}" alt="eb4u Logo" class="h-12 mx-auto object-contain mb-2">
            </a>
            <h1 class="text-2xl font-black text-slate-900">Create Customer Account</h1>
            <p class="text-xs text-slate-500 font-medium">Join eb4u for instant rental bookings & fast UK shipping</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Oliver Smith" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 font-bold text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
                @error('name') <span class="text-rose-600 text-[11px] mt-1 block font-bold">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="your.email@example.co.uk" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 font-bold text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
                @error('email') <span class="text-rose-600 text-[11px] mt-1 block font-bold">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">UK Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone', '+44 ') }}" required placeholder="+44 7700 900077" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 font-bold text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 font-bold text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
                @error('password') <span class="text-rose-600 text-[11px] mt-1 block font-bold">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 font-bold text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
            </div>

            <button type="submit" class="w-full py-4 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-black rounded-2xl shadow-lg transition-all uppercase tracking-wider text-xs cursor-pointer">
                Create Account
            </button>
        </form>

        <div class="text-center text-xs text-slate-500 pt-2 border-t border-slate-200">
            Already have an account? <a href="{{ route('login') }}" class="font-black text-brandOrange-600 hover:underline">Sign In &rarr;</a>
        </div>

    </div>
</div>
@endsection
