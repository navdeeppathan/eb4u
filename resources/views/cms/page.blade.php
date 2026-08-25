@extends('layouts.app')

@section('title', $page->title . ' | eb4u')

@section('content')
<!-- Header Banner -->
<div class="bg-darkSlate-900 text-white py-12 border-b border-darkSlate-800">
    <div class="max-w-[1100px] mx-auto px-6">
        <h1 class="font-grotesk text-3xl sm:text-4xl font-extrabold text-white">{{ $page->title }}</h1>
        <p class="text-xs text-textMuted mt-2 font-medium">Official eb4u Ltd Business Documentation & Legal Compliance</p>
    </div>
</div>

<!-- Main Content Area -->
<div class="max-w-[1100px] mx-auto px-6 py-12">
    <div class="bg-white rounded-3xl border border-borderLight p-8 sm:p-12 shadow-xs space-y-6 text-sm text-darkSlate-900 leading-relaxed">
        <style>
            .policy-content h2 { font-family: 'Space Grotesk', sans-serif; font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-top: 1.75rem; margin-bottom: 0.75rem; border-bottom: 1px solid #dde4f0; padding-bottom: 0.5rem; }
            .policy-content h3 { font-family: 'Space Grotesk', sans-serif; font-size: 1rem; font-weight: 700; color: #0f172a; margin-top: 1.25rem; margin-bottom: 0.5rem; }
            .policy-content p { color: #445568; margin-bottom: 1rem; font-size: 0.875rem; line-height: 1.7; }
            .policy-content ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1.25rem; color: #445568; }
            .policy-content li { margin-bottom: 0.5rem; font-size: 0.875rem; line-height: 1.6; }
            .policy-content strong { color: #0f172a; }
            .policy-content a { color: #f97316; font-weight: 600; text-decoration: underline; }
            .policy-content a:hover { color: #ea580c; }
        </style>

        <div class="policy-content">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection
