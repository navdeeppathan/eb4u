@extends('layouts.app')

@section('title', $page->title . ' | E-Bike 4 U UK')

@section('content')
<div class="bg-slate-900 text-white py-12 border-b border-slate-800">
    <div class="container mx-auto px-4 max-w-4xl">
        <h1 class="text-3xl font-black text-white">{{ $page->title }}</h1>
        <p class="text-xs text-slate-400 mt-1">Official E-Bike 4 U UK Business Documentation & Policies</p>
    </div>
</div>

<div class="container mx-auto px-4 py-12 max-w-4xl">
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-sm prose prose-slate max-w-none text-xs leading-relaxed">
        {!! nl2br(e($page->content)) !!}
    </div>
</div>
@endsection
