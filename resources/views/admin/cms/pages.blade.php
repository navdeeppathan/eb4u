@extends('layouts.admin')

@section('title', 'Manage Store Policies & CMS Pages')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-3xl border border-slate-200 shadow-xs p-6">
    <div class="space-y-3">
        @foreach($pages as $p)
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex justify-between items-center text-xs">
                <div>
                    <strong class="text-slate-900 font-bold text-sm">{{ $p->title }}</strong>
                    <span class="block text-[10px] text-slate-400 font-mono">/page/{{ $p->slug }}</span>
                </div>
                <a href="{{ route('admin.cms.pages.edit', $p->id) }}" class="px-4 py-2 bg-slate-900 text-white font-bold rounded-xl text-xs">Edit Content &rarr;</a>
            </div>
        @endforeach
    </div>
</div>
@endsection
