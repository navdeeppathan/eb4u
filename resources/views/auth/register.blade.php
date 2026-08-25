@extends('layouts.app')

@section('title', 'Register Account | E-Bike 4 U UK')

@section('content')
<div class="container mx-auto px-4 py-16 max-w-md">
    <div class="bg-white rounded-3xl border border-cream-200 shadow-xl p-8 space-y-6">
        
        <div class="text-center space-y-1">
            <h1 class="text-2xl font-black text-forest-900">Create Customer Account</h1>
            <p class="text-xs text-slate-500">Join E-Bike 4 U for instant rental bookings & fast UK shipping</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-cream-100/50 border border-cream-200 rounded-xl p-3 font-semibold text-forest-900">
                @error('name') <span class="text-rose-600 text-[11px] mt-1 block font-bold">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-cream-100/50 border border-cream-200 rounded-xl p-3 font-semibold text-forest-900">
                @error('email') <span class="text-rose-600 text-[11px] mt-1 block font-bold">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">UK Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone', '+44 ') }}" required class="w-full bg-cream-100/50 border border-cream-200 rounded-xl p-3 font-semibold text-forest-900">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full bg-cream-100/50 border border-cream-200 rounded-xl p-3 font-semibold text-forest-900">
                @error('password') <span class="text-rose-600 text-[11px] mt-1 block font-bold">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required class="w-full bg-cream-100/50 border border-cream-200 rounded-xl p-3 font-semibold text-forest-900">
            </div>

            <button type="submit" class="w-full py-3.5 bg-forest-900 hover:bg-forest-800 text-white font-black rounded-2xl shadow-lg transition-all uppercase tracking-wider text-xs">
                Create Account
            </button>
        </form>

        <div class="text-center text-xs text-slate-500 pt-2">
            Already have an account? <a href="{{ route('login') }}" class="font-black text-amberAcc-600 hover:underline">Sign In &rarr;</a>
        </div>

    </div>
</div>
@endsection
