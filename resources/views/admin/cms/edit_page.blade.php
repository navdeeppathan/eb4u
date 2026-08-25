@extends('layouts.admin')

@section('title', 'Edit Page: ' . $page->title)

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-3xl border border-slate-200 shadow-xs space-y-6">
    <form action="{{ route('admin.cms.pages.update', $page->id) }}" method="POST" class="space-y-4 text-xs">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-bold text-slate-700 mb-1">Page Title</label>
            <input type="text" value="{{ $page->title }}" disabled class="w-full bg-slate-100 border border-slate-200 rounded-xl p-3 font-bold text-slate-500">
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">Content (Markdown / Plaintext)</label>
            <textarea name="content" rows="12" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 font-mono font-medium leading-relaxed">{{ $page->content }}</textarea>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.cms.pages') }}" class="py-3 px-5 bg-slate-100 text-slate-700 font-bold rounded-xl">Cancel</a>
            <button type="submit" class="py-3 px-6 bg-brand-600 hover:bg-brand-700 text-white font-black rounded-xl uppercase">Update Page</button>
        </div>
    </form>
</div>
@endsection
