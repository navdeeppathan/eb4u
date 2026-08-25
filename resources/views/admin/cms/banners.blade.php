@extends('layouts.admin')

@section('title', 'Hero Banners & CMS Manager')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
        <h3 class="text-xs font-black uppercase text-slate-900">Add Hero Banner Slide</h3>
        <form action="{{ route('admin.cms.banners.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Banner Title</label>
                    <input type="text" name="title" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Subtitle</label>
                    <input type="text" name="subtitle" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3">
                </div>
            </div>
            <button type="submit" class="py-2.5 px-5 bg-brand-600 text-white font-black rounded-xl">Add Banner</button>
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs p-6">
        <h3 class="text-xs font-black uppercase text-slate-900 mb-4">Active Hero Slides</h3>
        <div class="space-y-3">
            @foreach($banners as $b)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex justify-between items-center text-xs">
                    <div>
                        <strong class="text-slate-900 font-black">{{ $b->title }}</strong>
                        <p class="text-slate-500 text-[11px]">{{ $b->subtitle }}</p>
                    </div>
                    <span class="text-emerald-700 font-bold bg-emerald-100 px-2.5 py-1 rounded-full text-[10px]">Active</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
