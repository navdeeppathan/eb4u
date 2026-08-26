@extends('layouts.admin')

@section('title', 'Edit Banner: ' . $banner->title)

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-3xl border border-slate-200 shadow-xs space-y-6">
    <div class="border-b border-slate-100 pb-4 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-black text-slate-900">Edit Banner Slide</h2>
            <p class="text-xs text-slate-500">Update banner details, button CTA, or upload a new banner image.</p>
        </div>
        <a href="{{ route('admin.cms.banners') }}" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-xs rounded-xl">
            &larr; Back to Banners
        </a>
    </div>

    <!-- Error Validation Display -->
    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-2xl text-xs space-y-1 font-semibold">
            <p class="font-bold text-rose-800"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Please fix validation errors:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.cms.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-xs">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-700 mb-1">Main Banner Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $banner->title) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold text-slate-900">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Subtitle / Tagline</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-medium text-slate-900">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Top Badge Text</label>
                <input type="text" name="badge" value="{{ old('badge', $banner->badge) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold text-slate-900">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Button Text</label>
                <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold text-slate-900">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Button Link URL</label>
                <input type="text" name="button_url" value="{{ old('button_url', $banner->button_url) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-medium text-slate-900">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Status</label>
                <select name="is_active" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold text-slate-900">
                    <option value="1" {{ $banner->is_active ? 'selected' : '' }}>Active (Displayed on Storefront)</option>
                    <option value="0" {{ !$banner->is_active ? 'selected' : '' }}>Inactive (Hidden)</option>
                </select>
            </div>

            <!-- Banner Image Preview & Replacement -->
            <div class="sm:col-span-2 bg-brandOrange-50/40 p-6 rounded-2xl border border-brandOrange-500/20 space-y-4">
                <h4 class="font-bold text-brandOrange-600 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-image text-base"></i> Banner Image File Upload (Max File Size: 2MB)
                </h4>

                @if($banner->image)
                    <div class="flex items-center space-x-4 mb-2">
                        <img src="{{ Str::startsWith($banner->image, 'http') ? $banner->image : asset($banner->image) }}" class="w-32 h-20 object-cover rounded-2xl border border-slate-200 shadow-sm flex-shrink-0">
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Current Banner Image</span>
                            <span class="text-[11px] text-slate-500 block truncate max-w-sm">{{ $banner->image }}</span>
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block font-bold text-slate-900 mb-1">
                        Replace Image File 
                        <span class="text-[10px] text-brandOrange-600 font-bold block">(JPG, PNG, WEBP — Max 2MB)</span>
                    </label>
                    <input type="file" name="image_file" accept="image/jpeg,image/png,image/jpg,image/webp" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-semibold text-slate-900">
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.cms.banners') }}" class="py-3 px-5 bg-slate-100 text-slate-700 font-bold rounded-xl">Cancel</a>
            <button type="submit" class="py-3.5 px-7 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-black rounded-xl shadow-md uppercase">Update Banner Slide</button>
        </div>
    </form>
</div>
@endsection
