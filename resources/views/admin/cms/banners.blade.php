@extends('layouts.admin')

@section('title', 'Hero Banners & Promotional Slides Management')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">

    <!-- Top Info & Banner Creation Card -->
    <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-xs space-y-6">
        <div class="flex justify-between items-center border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-base font-black text-slate-900 uppercase tracking-wider">Add Hero Banner Slide</h3>
                <p class="text-xs text-slate-500">Upload promotional banners displayed on the storefront home hero slider.</p>
            </div>
            <span class="px-3 py-1 bg-brandOrange-50 text-brandOrange-600 border border-brandOrange-500/20 text-[10px] font-black rounded-full uppercase">
                CMS Manager
            </span>
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

        <form action="{{ route('admin.cms.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Main Banner Title <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Ride Electric. Ride Free." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-semibold text-slate-900 focus:ring-2 focus:ring-brandOrange-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Subtitle / Tagline</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle') }}" placeholder="Buy, rent, or try premium electric bikes across the UK." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-medium text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Top Badge Text</label>
                    <input type="text" name="badge" value="{{ old('badge', '⚡ UK #1 E-Bike Rental') }}" placeholder="e.g. ⚡ Certified 250W German Motor" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Button Text</label>
                    <input type="text" name="button_text" value="{{ old('button_text', 'Shop E-Bikes') }}" placeholder="Shop E-Bikes" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Button Link URL</label>
                    <input type="text" name="button_url" value="{{ old('button_url', route('catalog.index')) }}" placeholder="/catalog?type=ebike" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-medium text-slate-900">
                </div>

                <!-- Banner Image Upload (Max 2MB validation) -->
                <div class="sm:col-span-2 bg-brandOrange-50/40 p-5 rounded-2xl border border-brandOrange-500/20 space-y-3">
                    <label class="block font-bold text-brandOrange-600">
                        <i class="fa-solid fa-cloud-arrow-up mr-1"></i> Banner Image File Upload 
                        <span class="text-[10px] text-slate-500 font-bold block">(Allowed: JPG, PNG, WEBP — Strictly Max 2MB File Size)</span>
                    </label>
                    <input type="file" name="image_file" accept="image/jpeg,image/png,image/jpg,image/webp" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-semibold text-slate-900">

                    <div class="pt-2">
                        <label class="block font-bold text-slate-700 mb-1">Or Image URL Fallback</label>
                        <input type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://images.unsplash.com/..." class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-medium">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="py-3 px-7 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-black rounded-xl shadow-md uppercase transition-all">
                    <i class="fa-solid fa-plus mr-1.5"></i> Publish Banner Slide
                </button>
            </div>
        </form>
    </div>

    <!-- Active Banners List -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs p-8 space-y-6">
        <div class="flex justify-between items-center border-b border-slate-100 pb-4">
            <h3 class="text-base font-black uppercase text-slate-900">Configured Banner Slides ({{ $banners->count() }})</h3>
            <span class="text-xs font-bold text-slate-400">Drag/Sort by Priority</span>
        </div>

        <div class="space-y-4">
            @forelse($banners as $b)
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4 transition-all hover:border-slate-300">
                    <div class="flex items-center space-x-4">
                        <img src="{{ $b->image ? (Str::startsWith($b->image, 'http') ? $b->image : asset($b->image)) : 'https://images.unsplash.com/photo-1571068316344-75bc76f77890?w=800&auto=format&fit=crop&q=80' }}" class="w-24 h-16 object-cover rounded-xl border border-slate-200 flex-shrink-0 shadow-xs">
                        <div>
                            <span class="inline-block text-[9px] font-black uppercase px-2 py-0.5 rounded-md bg-brandOrange-50 text-brandOrange-600 border border-brandOrange-500/20 mb-1">
                                {{ $b->badge ?? 'HOME HERO' }}
                            </span>
                            <h4 class="text-sm font-black text-slate-900 leading-tight">{{ $b->title }}</h4>
                            <p class="text-xs text-slate-500 line-clamp-1 mt-0.5 font-medium">{{ $b->subtitle }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 flex-shrink-0">
                        <!-- Toggle Active Form -->
                        <form action="{{ route('admin.cms.banners.toggle', $b->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase transition-colors {{ $b->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-rose-100 text-rose-800 hover:bg-rose-200' }}">
                                {{ $b->is_active ? '● Active' : '○ Inactive' }}
                            </button>
                        </form>

                        <!-- Edit Button -->
                        <a href="{{ route('admin.cms.banners.edit', $b->id) }}" class="px-3.5 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-800 rounded-xl text-xs font-bold transition-colors">
                            <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                        </a>

                        <!-- Delete Form -->
                        <form action="{{ route('admin.cms.banners.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this banner slide?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3.5 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-xl text-xs font-bold transition-colors">
                                <i class="fa-solid fa-trash mr-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-slate-400 text-xs font-medium">
                    <i class="fa-solid fa-sliders text-3xl mb-2 text-slate-300 block"></i>
                    No banner slides configured yet. Use the form above to add your first banner.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
