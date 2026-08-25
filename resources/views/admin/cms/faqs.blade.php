@extends('layouts.admin')

@section('title', 'Manage Customer FAQs')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
        <h3 class="text-xs font-black uppercase text-slate-900">Add New FAQ</h3>
        <form action="{{ route('admin.cms.faqs.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">Category</label>
                <select name="category" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold">
                    <option value="Rental">Rental</option>
                    <option value="Purchases">Purchases</option>
                    <option value="Shipping & Delivery">Shipping & Delivery</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Question</label>
                <input type="text" name="question" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Answer</label>
                <textarea name="answer" rows="3" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-medium"></textarea>
            </div>
            <button type="submit" class="py-2.5 px-5 bg-brand-600 text-white font-black rounded-xl">Add FAQ</button>
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs p-6">
        <h3 class="text-xs font-black uppercase text-slate-900 mb-4">Existing FAQs</h3>
        <div class="space-y-3">
            @foreach($faqs as $f)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                    <span class="text-[10px] uppercase font-bold text-purple-700 bg-purple-100 px-2 py-0.5 rounded">{{ $f->category }}</span>
                    <h4 class="font-bold text-slate-900 mt-1">{{ $f->question }}</h4>
                    <p class="text-slate-600 text-[11px] mt-1">{{ $f->answer }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
