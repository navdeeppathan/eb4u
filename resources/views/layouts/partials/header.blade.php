<header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-borderLight shadow-xs text-darkSlate-900" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-[1320px] mx-auto px-6 h-18 py-3.5 flex items-center justify-between gap-6">
        
        <!-- Logo matching Testing Platform -->
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-shrink-0 group">
            <div class="w-9 h-9 bg-brandOrange-500 rounded-xl flex items-center justify-center text-darkSlate-950 font-black shadow-sm group-hover:scale-105 transition-transform">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M9 2L5 9.5h3L6.5 16l6.5-8.5h-3.5L13 2H9z" fill="#0f172a"/></svg>
            </div>
            <span class="font-grotesk text-2xl font-extrabold tracking-tight text-darkSlate-900">
                eb<span class="text-brandOrange-500">4</span>u
            </span>
        </a>

        <!-- Desktop Navigation Menu (Matching Testing Platform) -->
        <nav class="hidden lg:flex items-center space-x-1 font-medium text-sm text-textSec">
            <a href="{{ route('catalog.index', ['type' => 'ebike']) }}" class="px-3.5 py-2 rounded-xl hover:text-darkSlate-900 hover:bg-slate-100 transition-colors {{ request('type') == 'ebike' ? 'text-darkSlate-900 font-bold bg-slate-100' : '' }}">E-Bikes</a>
            <a href="{{ route('catalog.index', ['type' => 'rental']) }}" class="px-3.5 py-2 rounded-xl hover:text-darkSlate-900 hover:bg-slate-100 transition-colors text-brandOrange-600 font-bold flex items-center">
                <span class="w-2 h-2 rounded-full bg-brandOrange-500 animate-pulse mr-1.5"></span> Rental
            </a>
            <a href="{{ route('catalog.index', ['type' => 'accessory']) }}" class="px-3.5 py-2 rounded-xl hover:text-darkSlate-900 hover:bg-slate-100 transition-colors">Accessories</a>
            <a href="{{ route('catalog.index') }}" class="px-3.5 py-2 rounded-xl hover:text-darkSlate-900 hover:bg-slate-100 transition-colors">Categories</a>
            <a href="{{ route('cms.page', 'about-us') }}" class="px-3.5 py-2 rounded-xl hover:text-darkSlate-900 hover:bg-slate-100 transition-colors">About</a>
        </nav>

        <!-- Header Action Icons -->
        <div class="flex items-center space-x-2 flex-shrink-0">
            <!-- Search Trigger -->
            <a href="{{ route('catalog.index') }}" class="w-10 h-10 rounded-xl text-textSec hover:text-darkSlate-900 hover:bg-slate-100 flex items-center justify-center transition-colors" title="Search">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </a>

            <!-- Wishlist Icon -->
            <a href="{{ route('customer.wishlist') }}" class="relative w-10 h-10 rounded-xl text-textSec hover:text-darkSlate-900 hover:bg-slate-100 flex items-center justify-center transition-colors" title="Wishlist">
                <i class="fa-regular fa-heart text-base"></i>
                <span class="absolute top-1.5 right-1.5 w-4 h-4 rounded-full bg-brandOrange-500 text-white text-[9px] font-black flex items-center justify-center">0</span>
            </a>

            <!-- Cart Trigger Button -->
            <button @click="isCartOpen = true" class="relative w-10 h-10 rounded-xl text-textSec hover:text-darkSlate-900 hover:bg-slate-100 flex items-center justify-center transition-colors" title="Shopping Cart">
                <i class="fa-solid fa-basket-shopping text-base"></i>
                <span x-text="cartCount" class="absolute top-1.5 right-1.5 min-w-[16px] h-4 px-1 rounded-full bg-brandOrange-500 text-white text-[9px] font-black flex items-center justify-center shadow-xs"></span>
            </button>

            <!-- User Account / Admin Badge -->
            @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 border border-borderLight rounded-xl py-1.5 px-3 hover:bg-slate-100 transition-colors">
                        <img src="{{ auth()->user()->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" class="w-6 h-6 rounded-full object-cover">
                        <span class="text-xs font-bold text-darkSlate-900 hidden sm:inline max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-textMuted"></i>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition x-cloak class="absolute right-0 mt-2 w-48 bg-white text-darkSlate-900 rounded-2xl shadow-2xl border border-borderLight py-2 z-50">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-xs font-bold text-brandOrange-600 hover:bg-slate-50"><i class="fa-solid fa-gauge mr-2"></i> Admin Suite</a>
                        @endif
                        <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 font-semibold"><i class="fa-solid fa-user mr-2 text-slate-400"></i> Dashboard</a>
                        <a href="{{ route('customer.orders') }}" class="block px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 font-semibold"><i class="fa-solid fa-box mr-2 text-slate-400"></i> Sales Orders</a>
                        <a href="{{ route('customer.rentals') }}" class="block px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 font-semibold"><i class="fa-solid fa-bicycle mr-2 text-brandOrange-500"></i> Active Rentals</a>
                        <div class="border-t border-slate-100 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-bold"><i class="fa-solid fa-right-from-bracket mr-2"></i> Sign Out</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="w-10 h-10 rounded-xl text-textSec hover:text-darkSlate-900 hover:bg-slate-100 flex items-center justify-center transition-colors" title="Account">
                    <i class="fa-regular fa-user text-base"></i>
                </a>
            @endauth

            <!-- Mobile Hamburger Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden w-10 h-10 rounded-xl text-darkSlate-900 flex items-center justify-center hover:bg-slate-100 focus:outline-none">
                <i class="fa-solid fa-bars" x-show="!mobileMenuOpen"></i>
                <i class="fa-solid fa-xmark text-xl" x-show="mobileMenuOpen" x-cloak></i>
            </button>
        </div>
    </div>

    <!-- Mobile Responsive Drawer Menu -->
    <div x-show="mobileMenuOpen" x-transition x-cloak class="lg:hidden bg-white text-darkSlate-900 border-b border-borderLight px-6 py-5 space-y-4 shadow-2xl">
        <nav class="space-y-2 font-semibold text-sm">
            <a href="{{ route('home') }}" class="block py-2 text-darkSlate-900 border-b border-slate-100">Home</a>
            <a href="{{ route('catalog.index', ['type' => 'ebike']) }}" class="block py-2 text-darkSlate-900 border-b border-slate-100">E-Bikes</a>
            <a href="{{ route('catalog.index', ['type' => 'rental']) }}" class="block py-2 text-brandOrange-500 font-extrabold border-b border-slate-100">⚡ E-Bike Rental</a>
            <a href="{{ route('catalog.index', ['type' => 'accessory']) }}" class="block py-2 text-darkSlate-900 border-b border-slate-100">Accessories</a>
            <a href="{{ route('cms.faqs') }}" class="block py-2 text-darkSlate-900 border-b border-slate-100">FAQs</a>
            <a href="{{ route('cms.contact') }}" class="block py-2 text-darkSlate-900">Contact Us</a>
        </nav>
    </div>
</header>
