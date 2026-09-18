@extends('layouts.admin')

@section('title', 'Edit Legal Page: ' . $page->title)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.cms.pages') }}" class="text-xs font-bold text-slate-400 hover:text-slate-700 transition-colors">
                    &larr; Back to Policies List
                </a>
            </div>
            <h1 class="text-xl font-black text-slate-900 mt-1 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-brandOrange-500"></i> Edit Page: {{ $page->title }}
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">
                URL Slug: <code class="font-mono text-brandOrange-600 bg-brandOrange-50 px-2 py-0.5 rounded">/{{ $page->slug }}</code>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('cms.page', $page->slug) }}" target="_blank" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-eye text-xs"></i> View Live Page
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.cms.pages.update', $page->id) }}" method="POST" class="space-y-6 text-xs">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Page Title</label>
                    <input type="text" name="title" value="{{ old('title', $page->title) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Slug</label>
                    <input type="text" value="{{ $page->slug }}" disabled class="w-full bg-slate-100 border border-slate-200 rounded-xl p-3 font-mono font-bold text-slate-400 cursor-not-allowed">
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="block font-bold text-slate-700">HTML / Page Body Content</label>
                    <span class="text-[11px] font-medium text-slate-400">Supports full HTML, Tailwind CSS classes, & Markdown</span>
                </div>
                <textarea name="content" rows="22" required class="w-full bg-slate-950 text-slate-100 border border-slate-800 rounded-2xl p-5 font-mono text-xs leading-relaxed focus:ring-2 focus:ring-brandOrange-500 focus:outline-none custom-scrollbar">{{ old('content', $page->content) }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-[11px] text-slate-400 font-medium">
                    <i class="fa-solid fa-circle-info text-brandOrange-500 mr-1"></i> Changes will immediately take effect on the public website upon saving.
                </div>
                <div class="flex items-center space-x-3 w-full sm:w-auto">
                    <a href="{{ route('admin.cms.pages') }}" class="flex-1 sm:flex-none text-center py-3 px-6 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="flex-1 sm:flex-none py-3.5 px-8 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-black rounded-xl uppercase shadow-lg shadow-brandOrange-500/25 transition-all transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-floppy-disk mr-1.5"></i> Save & Publish Changes
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection
