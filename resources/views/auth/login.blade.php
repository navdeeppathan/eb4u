@extends('layouts.app')

@section('title', 'Sign In | E-Bike 4 U UK')

@section('content')
<div class="container mx-auto px-4 py-16 max-w-md">
    <div class="bg-white rounded-3xl border border-cream-200 shadow-xl p-8 space-y-6">
        
        <div class="text-center space-y-1">
            <div class="w-12 h-12 mx-auto rounded-2xl bg-forest-900 text-amberAcc-500 flex items-center justify-center text-xl mb-3 shadow-md">
                <i class="fa-solid fa-user-lock"></i>
            </div>
            <h1 class="text-2xl font-black text-forest-900">Sign In to Your Account</h1>
            <p class="text-xs text-slate-500 font-medium">Enter your credentials to access your E-Bike rentals and orders</p>
        </div>

        <form action="{{ route('login') }}" method="POST" id="loginForm" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" id="emailInput" value="{{ old('email') }}" required placeholder="your.email@example.co.uk" class="w-full bg-cream-100/50 border border-cream-200 rounded-xl p-3.5 font-bold text-forest-900 focus:ring-2 focus:ring-amberAcc-500">
                @error('email') <span class="text-rose-600 text-[11px] mt-1 block font-bold">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" id="passwordInput" required placeholder="••••••••" class="w-full bg-cream-100/50 border border-cream-200 rounded-xl p-3.5 font-bold text-forest-900 focus:ring-2 focus:ring-amberAcc-500">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center space-x-2 text-slate-600 font-semibold cursor-pointer">
                    <input type="checkbox" name="remember" class="text-amberAcc-600 rounded focus:ring-amberAcc-500">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full py-4 bg-forest-900 hover:bg-forest-800 text-white font-black rounded-2xl shadow-lg transition-all uppercase tracking-wider text-xs">
                Sign In
            </button>
        </form>

        <div class="text-center text-xs text-slate-500 pt-2 border-t border-cream-200">
            Don't have an account yet? <a href="{{ route('register') }}" class="font-black text-amberAcc-600 hover:underline">Create Account &rarr;</a>
        </div>

    </div>
</div>
@endsection
