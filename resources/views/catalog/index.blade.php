@extends('layouts.app')

@section('title', 'Catalog & E-Bike Rental | Eb4u')

@section('content')
<div class="bg-darkBlack-950 text-white py-8 md:py-12 border-b border-darkBlack-800">
    <div class="container mx-auto px-6 md:px-12">
        <h1 class="text-2xl md:text-3xl font-black text-white">E-Bikes & Accessories Catalog</h1>
        <p class="text-xs text-slate-400 mt-1">Browse British certified electric bikes for sale, flexible rentals & top-rated cycling gear.</p>
    </div>
</div>

<div class="container mx-auto px-6 md:px-12 py-6 md:py-10" x-data="catalogApp()">
    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
        
        <!-- Mobile Filter Toggle Button -->
        <div class="lg:hidden">
            <button @click="mobileFilterOpen = !mobileFilterOpen" class="w-full py-3 px-4 bg-white border border-slate-200 rounded-2xl font-black text-xs text-slate-900 flex justify-between items-center shadow-xs">
                <span><i class="fa-solid fa-sliders text-brandOrange-500 mr-2"></i> Filter Products</span>
                <i class="fa-solid" :class="mobileFilterOpen ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </button>
        </div>

        <!-- Filter Sidebar -->
        <div x-show="mobileFilterOpen || isDesktop" x-cloak class="w-full lg:w-64 flex-shrink-0 bg-white p-5 md:p-6 rounded-3xl border border-slate-200 shadow-sm h-fit">
            <div class="flex justify-between items-center mb-6 pb-3 border-b border-slate-100">
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider"><i class="fa-solid fa-sliders text-brandOrange-500 mr-2"></i> Filters</h3>
                <button @click="resetFilters()" class="text-[11px] font-bold text-slate-400 hover:text-brandOrange-500">Reset All</button>
            </div>

            <form @change="filterProducts()" id="filterForm" class="space-y-6">
                <!-- Type Filter -->
                <div>
                    <label class="block text-xs font-black text-slate-900 uppercase tracking-wider mb-2">Category Type</label>
                    <div class="space-y-2 text-xs font-semibold">
                        <label class="flex items-center space-x-2 text-slate-700 cursor-pointer hover:text-brandOrange-500">
                            <input type="radio" name="type" value="" {{ request('type') == '' ? 'checked' : '' }} class="text-brandOrange-500 focus:ring-brandOrange-500">
                            <span>All Products</span>
                        </label>
                        <label class="flex items-center space-x-2 text-slate-700 cursor-pointer hover:text-brandOrange-500">
                            <input type="radio" name="type" value="ebike" {{ request('type') == 'ebike' ? 'checked' : '' }} class="text-brandOrange-500 focus:ring-brandOrange-500">
                            <span>E-Bikes Only</span>
                        </label>
                        <label class="flex items-center space-x-2 text-brandOrange-600 font-extrabold cursor-pointer">
                            <input type="radio" name="type" value="rental" {{ request('type') == 'rental' ? 'checked' : '' }} class="text-brandOrange-500 focus:ring-brandOrange-500">
                            <span>Rental Eligible E-Bikes</span>
                        </label>
                        <label class="flex items-center space-x-2 text-slate-700 cursor-pointer hover:text-brandOrange-500">
                            <input type="radio" name="type" value="accessory" {{ request('type') == 'accessory' ? 'checked' : '' }} class="text-brandOrange-500 focus:ring-brandOrange-500">
                            <span>Accessories</span>
                        </label>
                    </div>
                </div>

                <!-- Categories List -->
                <div>
                    <label class="block text-xs font-black text-slate-900 uppercase tracking-wider mb-2">Categories</label>
                    <select name="category" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-2.5 font-bold focus:ring-2 focus:ring-brandOrange-500 text-slate-900">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Brands List -->
                <div>
                    <label class="block text-xs font-black text-slate-900 uppercase tracking-wider mb-2">Brands</label>
                    <select name="brand" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-2.5 font-bold focus:ring-2 focus:ring-brandOrange-500 text-slate-900">
                        <option value="">All Brands</option>
                        @foreach($brands as $b)
                            <option value="{{ $b->slug }}" {{ request('brand') == $b->slug ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Range -->
                <div>
                    <label class="block text-xs font-black text-slate-900 uppercase tracking-wider mb-2">Price Range (£)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min £" class="text-xs bg-slate-50 border border-slate-200 rounded-xl p-2 font-bold text-slate-900">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max £" class="text-xs bg-slate-50 border border-slate-200 rounded-xl p-2 font-bold text-slate-900">
                    </div>
                </div>
            </form>
        </div>

        <!-- Catalog Product Grid & Sort -->
        <div class="flex-grow">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 bg-white p-4 rounded-3xl border border-slate-200 shadow-xs gap-3">
                <span class="text-xs text-slate-600 font-bold">
                    Showing <strong class="text-slate-900" x-text="count"></strong> items
                </span>

                <div class="flex items-center space-x-3 text-xs w-full sm:w-auto justify-between sm:justify-end">
                    <span class="text-slate-600 font-bold">Sort By:</span>
                    <select @change="filterProducts()" form="filterForm" name="sort" class="bg-slate-50 border border-slate-200 rounded-xl p-2 font-bold focus:ring-2 focus:ring-brandOrange-500 text-slate-900">
                        <option value="latest">Newest Arrivals</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="name_asc">Alphabetical</option>
                    </select>
                </div>
            </div>

            <!-- AJAX Container -->
            <div id="productGridContainer">
                @include('catalog.partials.product_grid', ['products' => $products])
            </div>
            
            <div id="paginationContainer">
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
