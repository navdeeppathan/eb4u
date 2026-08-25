@extends('layouts.admin')

@section('title', 'Customer Review Moderation Hub')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                <tr>
                    <th class="p-4">Customer</th>
                    <th class="p-4">Product</th>
                    <th class="p-4">Rating</th>
                    <th class="p-4">Title & Comment</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($reviews as $r)
                    <tr class="hover:bg-slate-50/50">
                        <td class="p-4 font-bold text-slate-900">{{ $r->user->name ?? 'User' }}</td>
                        <td class="p-4 text-slate-800 font-medium">{{ $r->product->name ?? 'Product' }}</td>
                        <td class="p-4 font-black text-amber-500"><i class="fa-solid fa-star"></i> {{ $r->rating }} / 5</td>
                        <td class="p-4 max-w-xs">
                            <strong class="block text-slate-900 leading-tight">"{{ $r->title }}"</strong>
                            <p class="text-slate-500 text-[11px] line-clamp-2 mt-0.5">{{ $r->comment }}</p>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase
                                {{ $r->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                {{ $r->status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                {{ $r->status === 'rejected' ? 'bg-rose-100 text-rose-800' : '' }}">
                                {{ $r->status }}
                            </span>
                        </td>
                        <td class="p-4 text-right space-x-2">
                            <form action="{{ route('admin.reviews.status', $r->id) }}" method="POST" class="inline-flex items-center space-x-1">
                                @csrf
                                <input type="hidden" name="status" value="{{ $r->status === 'approved' ? 'rejected' : 'approved' }}">
                                <button type="submit" class="px-3 py-1 text-[11px] font-bold rounded-lg {{ $r->status === 'approved' ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700' }}">
                                    {{ $r->status === 'approved' ? 'Reject' : 'Approve' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4 border-t border-slate-100">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection
