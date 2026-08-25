@extends('layouts.app')

@section('title', 'Frequently Asked Questions | E-Bike 4 U')

@section('content')
<div class="bg-slate-900 text-white py-12 border-b border-slate-800">
    <div class="container mx-auto px-4 max-w-4xl text-center">
        <h1 class="text-3xl font-black text-white">Frequently Asked Questions</h1>
        <p class="text-xs text-slate-400 mt-1">Everything you need to know about UK E-Bike sales, rental availability, security deposits & warranties.</p>
    </div>
</div>

<div class="container mx-auto px-4 py-12 max-w-4xl space-y-8">
    @foreach($faqs as $category => $items)
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center">
                <i class="fa-solid fa-circle-question text-brand-600 mr-2"></i> {{ $category }}
            </h3>

            <div class="space-y-3">
                @foreach($items as $faq)
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                        <h4 class="text-xs font-bold text-slate-900">{{ $faq->question }}</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $faq->answer }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
