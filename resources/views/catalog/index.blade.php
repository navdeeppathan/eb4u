@extends('layouts.app')

@section('title', 'Electric Bikes & Accessories | eb4u')

@section('content')
<!-- Breadcrumb -->
<div class="border-b border-borderLight bg-[#edf1f8]">
    <div class="max-w-[1320px] mx-auto px-6 py-3 flex items-center gap-2 text-xs text-textMuted font-medium">
        <a href="{{ route('home') }}" class="hover:text-darkSlate-900 transition-colors">Home</a>
        <span>/</span>
        <span class="text-darkSlate-900 font-bold">Electric Bikes & Accessories</span>
    </div>
</div>

<div class="max-w-[1320px] mx-auto px-6 py-8" x-data="catalogApp()">
    <div class="flex flex-col lg:flex-row gap-8 items-start">
        
        <!-- Mobile Filter Toggle Button -->
        <div class="lg:hidden w-full">
            <button @click="mobileFilterOpen = !mobileFilterOpen" class="w-full py-3 px-4 bg-white border border-borderLight rounded-xl font-bold text-xs text-darkSlate-900 flex justify-between items-center shadow-xs">
                <span><i class="fa-solid fa-sliders text-brandOrange-500 mr-2"></i> Filter Products</span>
                <i class="fa-solid" :class="mobileFilterOpen ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </button>
        </div>

        <!-- Sidebar Filters (Exact design from Testing Platform Products.dc.html) -->
        <aside x-show="mobileFilterOpen || isDesktop" x-cloak class="w-full lg:w-60 flex-shrink-0 bg-white border border-borderLight rounded-2xl overflow-hidden shadow-xs">
            <div class="p-4 border-b border-borderLight flex items-center justify-between">
                <span class="font-grotesk text-sm font-bold text-darkSlate-900">Filters</span>
                <button @click="resetFilters()" class="text-xs font-semibold text-brandOrange-500 hover:opacity-80">Clear all</button>
            </div>

            <form @change="filterProducts()" id="filterForm">
                <!-- Purpose Tag (Buy vs Rent) -->
                <div class="p-4 border-b border-borderLight space-y-2">
                    <div class="font-grotesk text-[11px] font-bold text-textSec uppercase tracking-wider mb-2">Product Purpose</div>
                    <label class="flex items-center space-x-2 text-xs font-semibold text-textSec cursor-pointer hover:text-darkSlate-900">
                        <input type="radio" name="tag" value="" {{ request('tag') == '' && request('type') == '' ? 'checked' : '' }} class="text-brandOrange-500 focus:ring-brandOrange-500">
                        <span>All Products</span>
                    </label>
                    <label class="flex items-center space-x-2 text-xs font-bold text-darkSlate-900 cursor-pointer hover:text-brandOrange-500">
                        <input type="radio" name="tag" value="sell" {{ request('tag') == 'sell' ? 'checked' : '' }} class="text-brandOrange-500 focus:ring-brandOrange-500">
                        <span>🛒 Buy Only (Sales)</span>
                    </label>
                    <label class="flex items-center space-x-2 text-xs font-black text-brandOrange-600 cursor-pointer">
                        <input type="radio" name="tag" value="rent" {{ request('tag') == 'rent' || request('type') == 'rental' ? 'checked' : '' }} class="text-brandOrange-500 focus:ring-brandOrange-500">
                        <span>⚡ Rent Only (Rentals)</span>
                    </label>
                </div>

                <!-- Category Selector -->
                <div class="p-4 border-b border-borderLight">
                    <div class="font-grotesk text-[11px] font-bold text-textSec uppercase tracking-wider mb-2">Category</div>
                    <select name="category" class="w-full text-xs bg-[#f5f7fb] border border-borderLight rounded-xl p-2.5 font-semibold text-darkSlate-900 focus:ring-2 focus:ring-brandOrange-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Brand Selector -->
                <div class="p-4 border-b border-borderLight">
                    <div class="font-grotesk text-[11px] font-bold text-textSec uppercase tracking-wider mb-2">Brand</div>
                    <select name="brand" class="w-full text-xs bg-[#f5f7fb] border border-borderLight rounded-xl p-2.5 font-semibold text-darkSlate-900 focus:ring-2 focus:ring-brandOrange-500">
                        <option value="">All Brands</option>
                        @foreach($brands as $b)
                            <option value="{{ $b->slug }}" {{ request('brand') == $b->slug ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Range -->
                <div class="p-4">
                    <div class="font-grotesk text-[11px] font-bold text-textSec uppercase tracking-wider mb-2">Price Range (£)</div>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min £" class="text-xs bg-[#f5f7fb] border border-borderLight rounded-xl p-2 font-bold text-darkSlate-900">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max £" class="text-xs bg-[#f5f7fb] border border-borderLight rounded-xl p-2 font-bold text-darkSlate-900">
                    </div>
                </div>
            </form>
        </aside>

        <!-- Main Product Area -->
        <div class="flex-grow min-w-0 w-full">
            <!-- Sort & Results Header -->
            <div class="flex flex-wrap items-center justify-between mb-6 gap-4 bg-white p-4 rounded-2xl border border-borderLight shadow-xs">
                <div class="text-xs text-textSec">Showing <strong class="text-darkSlate-900 font-bold" x-text="count"></strong> products</div>
                
                <div class="flex items-center gap-3 text-xs">
                    <span class="text-textSec font-medium">Sort:</span>
                    <select @change="filterProducts()" form="filterForm" name="sort" class="bg-[#f5f7fb] border border-borderLight rounded-xl p-2 font-bold text-darkSlate-900 focus:ring-2 focus:ring-brandOrange-500">
                        <option value="latest">Sort: Featured</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="name_asc">Alphabetical</option>
                    </select>
                </div>
            </div>

            <!-- Product Grid Container -->
            <div id="productGridContainer">
                @include('catalog.partials.product_grid', ['products' => $products])
            </div>
            
            <div id="paginationContainer" class="mt-8">
                @include('catalog.partials.pagination', ['products' => $products])
            </div>
        </div>

    </div>
</div>

<script>
    function catalogApp() {
        return {
            count: {{ $products->total() }},
            mobileFilterOpen: false,
            isDesktop: window.innerWidth >= 1024,
            init() {
                window.addEventListener('resize', () => {
                    this.isDesktop = window.innerWidth >= 1024;
                });
            },
            async filterProducts() {
                let form = document.getElementById('filterForm');
                let formData = new FormData(form);
                let params = new URLSearchParams(formData);

                try {
                    let res = await axios.get(`{{ route("catalog.index") }}?${params.toString()}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (res.data.success) {
                        document.getElementById('productGridContainer').innerHTML = res.data.html;
                        document.getElementById('paginationContainer').innerHTML = res.data.pagination;
                        this.count = res.data.count;
                    }
                } catch (e) {
                    console.error('Filter error:', e);
                }
            },
            resetFilters() {
                let form = document.getElementById('filterForm');
                form.reset();
                this.filterProducts();
            }
        }
    }
</script>
@endsection
