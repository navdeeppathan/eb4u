@extends('layouts.app')

@section('title', 'Sign In | Eb4u')

@section('content')
<div class="container mx-auto px-6 md:px-12 py-16 max-w-md">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl p-8 space-y-6">
        
        <div class="text-center space-y-2">
            <a href="{{ route('home') }}" class="inline-block">
                <img src="{{ asset('images/logo.webp') }}" alt="Eb4u Logo" class="h-12 mx-auto object-contain mb-2">
            </a>
            <h1 class="text-2xl font-black text-slate-900">Sign In to Your Account</h1>
            <p class="text-xs text-slate-500 font-medium">Enter your credentials to access your E-Bike rentals and orders</p>
        </div>

        <form action="{{ route('login') }}" method="POST" id="loginForm" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" id="emailInput" value="{{ old('email') }}" required placeholder="your.email@example.co.uk" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 font-bold text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
                @error('email') <span class="text-rose-600 text-[11px] mt-1 block font-bold">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Password</label>
                <div x-data="{ showPassword: false }" class="relative">
                    <input :type="showPassword ? 'text' : 'password'" name="password" id="passwordInput" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 pr-11 font-bold text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
                    <button type="button" @click="showPassword = !showPassword" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 focus:outline-none p-1" aria-label="Toggle password visibility">
                        <i x-show="!showPassword" class="fa-solid fa-eye text-sm"></i>
                        <i x-show="showPassword" class="fa-solid fa-eye-slash text-sm" x-cloak></i>
                    </button>
                </div>
                @error('password') <span class="text-rose-600 text-[11px] mt-1 block font-bold">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center space-x-2 text-slate-600 font-semibold cursor-pointer">
                    <input type="checkbox" name="remember" class="text-brandOrange-500 rounded focus:ring-brandOrange-500">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full py-4 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-black rounded-2xl shadow-lg transition-all uppercase tracking-wider text-xs">
                Sign In
            </button>
        </form>

        <div class="text-center text-xs text-slate-500 pt-2 border-t border-slate-200">
            Don't have an account yet? <a href="{{ route('register') }}" class="font-black text-brandOrange-600 hover:underline">Create Account &rarr;</a>
        </div>

    </div>
</div>
@endsection
