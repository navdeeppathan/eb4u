@extends('layouts.app')

@section('title', 'My Saved Wishlist | E-Bike 4 U')

@section('content')
<div class="bg-slate-900 text-white py-10 border-b border-slate-800">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-black text-white"><i class="fa-solid fa-heart text-rose-500 mr-2"></i> My Wishlist</h1>
    </div>
</div>

<div class="container mx-auto px-4 py-10">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($wishlists as $w)
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <img src="{{ $w->product->primary_image_url }}" class="w-full aspect-square object-cover rounded-2xl mb-3">
                    <h4 class="text-xs font-bold text-slate-900 line-clamp-2 mb-1">{{ $w->product->name }}</h4>
                    <span class="text-xs font-black text-slate-900">£{{ number_format($w->product->effective_price, 2) }}</span>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex gap-2">
                    <a href="{{ route('products.show', $w->product->slug) }}" class="flex-1 text-center py-2 bg-brand-600 text-white rounded-xl text-xs font-bold">
                        View Product
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 bg-white rounded-3xl border border-slate-200">
                <i class="fa-regular fa-heart text-4xl text-slate-300 mb-3"></i>
                <p class="text-xs text-slate-500 font-bold">Your wishlist is currently empty.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
