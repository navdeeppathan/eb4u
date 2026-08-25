@extends('layouts.app')

@section('title', 'Sign In | E-Bike 4 U UK')

@section('content')
<div class="container mx-auto px-4 py-16 max-w-md">
    <div class="bg-white rounded-3xl border border-cream-200 shadow-xl p-8 space-y-6">
        
        <div class="text-center space-y-1">
            <div class="w-12 h-12 mx-auto rounded-2xl bg-forest-900 text-amberAcc-500 flex items-center justify-center text-xl mb-3 shadow-md">
                <i class="fa-solid fa-user-lock"></i>
            </div>
            <h1 class="text-2xl font-black text-forest-900">Sign In to E-Bike 4 U</h1>
            <p class="text-xs text-slate-500">Access your active E-Bike rentals and order history</p>
        </div>

        <!-- Quick Demo Login Buttons for Evaluator / Testing -->
        <div class="bg-cream-100/60 p-4 rounded-2xl border border-cream-200 space-y-2">
            <span class="block text-[10px] font-black uppercase text-forest-900 text-center">One-Click Demo Sign In</span>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" @click="fillAdmin()" class="py-2.5 px-3 bg-forest-900 hover:bg-forest-800 text-white rounded-xl text-[11px] font-black transition-colors shadow-xs">
                    <i class="fa-solid fa-gauge text-amberAcc-500 mr-1"></i> Admin Demo
                </button>
                <button type="button" @click="fillCustomer()" class="py-2.5 px-3 bg-amberAcc-500 hover:bg-amberAcc-400 text-forest-950 rounded-xl text-[11px] font-black transition-colors shadow-xs">
                    <i class="fa-solid fa-user mr-1"></i> Customer Demo
                </button>
            </div>
        </div>

        <form action="{{ route('login') }}" method="POST" id="loginForm" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" id="emailInput" value="{{ old('email') }}" required class="w-full bg-cream-100/50 border border-cream-200 rounded-xl p-3 font-bold text-forest-900 focus:ring-2 focus:ring-amberAcc-500">
                @error('email') <span class="text-rose-600 text-[11px] mt-1 block font-bold">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" id="passwordInput" required class="w-full bg-cream-100/50 border border-cream-200 rounded-xl p-3 font-bold text-forest-900 focus:ring-2 focus:ring-amberAcc-500">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center space-x-2 text-slate-600 font-semibold">
                    <input type="checkbox" name="remember" class="text-amberAcc-600 rounded">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 bg-forest-900 hover:bg-forest-800 text-white font-black rounded-2xl shadow-lg transition-all uppercase tracking-wider text-xs">
                Sign In to Account
            </button>
        </form>

        <div class="text-center text-xs text-slate-500 pt-2">
            Don't have an account yet? <a href="{{ route('register') }}" class="font-black text-amberAcc-600 hover:underline">Create Account &rarr;</a>
        </div>

    </div>
</div>

<script>
    function fillAdmin() {
        document.getElementById('emailInput').value = 'admin@eb4u.co.uk';
        document.getElementById('passwordInput').value = 'password123';
    }
    function fillCustomer() {
        document.getElementById('emailInput').value = 'james@example.co.uk';
        document.getElementById('passwordInput').value = 'password123';
    }
</script>
@endsection
