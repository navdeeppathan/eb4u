@extends('layouts.app')

@section('title', 'My Profile & Address Book | E-Bike 4 U')

@section('content')
<div class="bg-slate-900 text-white py-10 border-b border-slate-800">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-black text-white"><i class="fa-solid fa-user-gear text-emerald-400 mr-2"></i> Account Profile & Address Book</h1>
    </div>
</div>

<div class="container mx-auto px-4 py-10 max-w-4xl space-y-8">
    <!-- Profile Form -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
        <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider pb-3 border-b border-slate-100">Personal Information</h3>

        <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email Address</label>
                    <input type="email" value="{{ $user->email }}" disabled class="w-full bg-slate-100 border border-slate-200 rounded-xl p-3 font-semibold text-slate-400">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ $user->phone }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold">
                </div>
            </div>
            <button type="submit" class="py-2.5 px-6 bg-brand-600 hover:bg-brand-700 text-white text-xs font-black rounded-xl shadow-md transition-colors">
                Save Profile Changes
            </button>
        </form>
    </div>

    <!-- Address Book -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
        <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider pb-3 border-b border-slate-100">UK Saved Addresses</h3>

        @foreach($addresses as $addr)
            <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50 text-xs space-y-1">
                <span class="font-bold text-slate-900">{{ $addr->name }} ({{ ucfirst($addr->type) }})</span>
                <p class="text-slate-600">{{ $addr->address_line_1 }}, {{ $addr->city }}, {{ $addr->postcode }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection
