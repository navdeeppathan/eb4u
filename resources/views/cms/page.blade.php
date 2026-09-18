@extends('layouts.app')

@section('title', ($page->title ?? 'Store Policy') . ' | eb4u')

@section('content')
@if(isset($page) && !empty($page->content) && Str::startsWith(trim($page->content), '<'))
    {!! $page->content !!}
@else
    <div class="bg-slate-900 text-white py-12 border-b border-slate-800">
        <div class="container mx-auto px-4 max-w-4xl">
            <h1 class="text-3xl font-black text-white">{{ $page->title ?? 'Store Policy' }}</h1>
            <p class="text-xs text-slate-400 mt-1">Official eb4u UK Business Documentation & Policies</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12 max-w-4xl">
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-sm text-xs leading-relaxed">
            {!! nl2br($page->content ?? '') !!}
        </div>
    </div>
@endif
@endsection
