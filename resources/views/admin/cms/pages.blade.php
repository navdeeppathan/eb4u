@extends('layouts.admin')

@section('title', 'Manage Store Policies & Legal Pages')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-xl font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-scale-balanced text-brandOrange-500"></i> Terms & Store Policies Management
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-1">
                Edit and update Terms & Conditions, Privacy Policy, and legal page content instantly across the platform.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-200">
                <i class="fa-solid fa-circle-check mr-1"></i> Live CMS Engine
            </span>
        </div>
    </div>

    <!-- CMS Pages List Table / Cards -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-900 text-sm">Editable Legal Pages & Policies</h3>
            <span class="text-xs font-bold text-slate-400">{{ count($pages) }} Total Pages</span>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($pages as $p)
                <div class="p-5 hover:bg-slate-50/80 transition-colors flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-xs">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <strong class="text-slate-900 font-bold text-sm">{{ $p->title }}</strong>
                            @if(in_array($p->slug, ['terms-and-conditions', 'privacy-policy']))
                                <span class="bg-brandOrange-50 text-brandOrange-600 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full border border-brandOrange-200">
                                    Core Legal
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 text-[11px] text-slate-500 font-medium">
                            <span><i class="fa-solid fa-link text-slate-400 mr-1"></i> URL Slug: <code class="font-mono bg-slate-100 px-1.5 py-0.5 rounded text-slate-800">/{{ $p->slug }}</code></span>
                            <span>•</span>
                            <span>Updated: {{ $p->updated_at ? $p->updated_at->diffForHumans() : 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('cms.page', $p->slug) }}" target="_blank" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> View Public
                        </a>
                        <a href="{{ route('admin.cms.pages.edit', $p->id) }}" class="px-4 py-2 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-bold rounded-xl text-xs shadow-md transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-pen-to-square"></i> Edit Content
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs font-medium">
                    No CMS pages found in database.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
