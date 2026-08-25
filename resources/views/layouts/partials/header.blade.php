<header class="sticky top-0 z-40 bg-forest-900 border-b border-forest-800 shadow-md text-cream-100" x-data="{ mobileMenuOpen: false }">
    <div class="container mx-auto px-4 py-3.5 flex items-center justify-between">
        
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center space-x-2.5">
            <div class="w-10 h-10 rounded-xl bg-amberAcc-500 text-forest-950 flex items-center justify-center font-extrabold shadow-md">
                <i class="fa-solid fa-bolt text-xl"></i>
            </div>
            <div>
                <span class="text-xl sm:text-2xl font-black tracking-tight text-white">E-BIKE <span class="text-amberAcc-500">4 U</span></span>
                <span class="block text-[9px] sm:text-[10px] uppercase font-bold tracking-widest text-cream-200/70 -mt-1">UK Sales & Rentals</span>
            </div>
        </a>

        <!-- Desktop Navigation Menu -->
        <nav class="hidden lg:flex items-center space-x-7 font-bold text-cream-100 text-sm">
            <a href="{{ route('home') }}" class="hover:text-amberAcc-500 transition-colors border-b-2 {{ request()->routeIs('home') ? 'border-amberAcc-500 text-amberAcc-500' : 'border-transparent' }}">Home</a>
            <a href="{{ route('catalog.index', ['type' => 'ebike']) }}" class="hover:text-amberAcc-500 transition-colors">E-Bikes</a>
            <a href="{{ route('catalog.index', ['type' => 'rental']) }}" class="hover:text-amberAcc-500 transition-colors text-amberAcc-500 font-extrabold flex items-center">
                <span class="w-2 h-2 rounded-full bg-amberAcc-500 animate-pulse mr-1.5"></span> Bike Rental
            </a>
            <a href="{{ route('catalog.index', ['type' => 'accessory']) }}" class="hover:text-amberAcc-500 transition-colors">Accessories</a>
            
            <!-- Categories Dropdown -->
            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <button class="hover:text-amberAcc-500 transition-colors flex items-center py-2">
                    Categories <i class="fa-solid fa-chevron-down text-xs ml-1 text-cream-300"></i>
                </button>
                <div x-show="open" x-transition x-cloak class="absolute top-full left-0 w-64 bg-white text-forest-900 rounded-2xl shadow-2xl border border-cream-200 py-3 z-50">
                    <div class="px-4 pb-2 border-b border-slate-100 font-bold text-xs text-amberAcc-700 uppercase tracking-wider">E-Bike Range</div>
                    <a href="{{ route('catalog.index', ['category' => 'city-e-bikes']) }}" class="block px-4 py-1.5 text-xs text-slate-800 hover:bg-cream-100 hover:text-amberAcc-600 font-semibold">City E-Bikes</a>
                    <a href="{{ route('catalog.index', ['category' => 'mountain-e-bikes']) }}" class="block px-4 py-1.5 text-xs text-slate-800 hover:bg-cream-100 hover:text-amberAcc-600 font-semibold">Mountain E-Bikes</a>
                    <a href="{{ route('catalog.index', ['category' => 'folding-e-bikes']) }}" class="block px-4 py-1.5 text-xs text-slate-800 hover:bg-cream-100 hover:text-amberAcc-600 font-semibold">Folding E-Bikes</a>
                    <a href="{{ route('catalog.index', ['category' => 'commuter-e-bikes']) }}" class="block px-4 py-1.5 text-xs text-slate-800 hover:bg-cream-100 hover:text-amberAcc-600 font-semibold">Commuter E-Bikes</a>
                    <a href="{{ route('catalog.index', ['category' => 'road-e-bikes']) }}" class="block px-4 py-1.5 text-xs text-slate-800 hover:bg-cream-100 hover:text-amberAcc-600 font-semibold">Road E-Bikes</a>
                    <a href="{{ route('catalog.index', ['category' => 'step-through-e-bikes']) }}" class="block px-4 py-1.5 text-xs text-slate-800 hover:bg-cream-100 hover:text-amberAcc-600 font-semibold">Step-Through E-Bikes</a>
                    <a href="{{ route('catalog.index', ['category' => 'long-range-e-bikes']) }}" class="block px-4 py-1.5 text-xs text-slate-800 hover:bg-cream-100 hover:text-amberAcc-600 font-semibold">Long Range E-Bikes</a>
                    
                    <div class="px-4 pt-3 pb-2 border-t border-slate-100 font-bold text-xs text-amberAcc-700 uppercase tracking-wider">Accessories</div>
                    <a href="{{ route('catalog.index', ['category' => 'helmets']) }}" class="block px-4 py-1.5 text-xs text-slate-800 hover:bg-cream-100 hover:text-amberAcc-600 font-semibold">Helmets & Protection</a>
                    <a href="{{ route('catalog.index', ['category' => 'bike-lights']) }}" class="block px-4 py-1.5 text-xs text-slate-800 hover:bg-cream-100 hover:text-amberAcc-600 font-semibold">Bike Lights</a>
                    <a href="{{ route('catalog.index', ['category' => 'bike-locks']) }}" class="block px-4 py-1.5 text-xs text-slate-800 hover:bg-cream-100 hover:text-amberAcc-600 font-semibold">Gold Rated Locks</a>
                </div>
            </div>
            
            <a href="{{ route('cms.faqs') }}" class="hover:text-amberAcc-500 transition-colors">FAQs</a>
            <a href="{{ route('cms.contact') }}" class="hover:text-amberAcc-500 transition-colors">Contact</a>
        </nav>

        <!-- Desktop Search Bar -->
        <form action="{{ route('catalog.index') }}" method="GET" class="hidden md:flex items-center flex-1 max-w-xs mx-6">
            <div class="relative w-full flex items-center bg-forest-950 border border-cream-200/30 rounded-full pl-4 pr-1 py-1">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search E-Bikes, helmets, locks..." 
                       class="w-full bg-transparent text-cream-100 placeholder-cream-300/60 text-xs focus:outline-none font-medium">
                <button type="submit" class="w-8 h-8 rounded-full bg-white text-forest-950 flex items-center justify-center hover:bg-amberAcc-500 transition-colors">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </div>
        </form>

        <!-- Header Actions (Wishlist, Cart, Account) -->
        <div class="flex items-center space-x-3 sm:space-x-4">
            <!-- Wishlist Icon -->
            <a href="{{ route('customer.wishlist') }}" class="relative text-cream-100 hover:text-amberAcc-500 transition-colors p-1.5 sm:p-2" title="Wishlist">
                <i class="fa-regular fa-heart text-base sm:text-lg"></i>
            </a>

            <!-- Cart Trigger Button -->
            <button @click="isCartOpen = true" class="relative flex items-center space-x-1.5 bg-forest-950/80 hover:bg-forest-950 border border-cream-200/20 px-3 py-1.5 rounded-full text-xs font-bold transition-all">
                <span class="hidden sm:inline">Cart</span>
                <i class="fa-solid fa-basket-shopping text-sm sm:hidden"></i>
                <span x-text="cartCount" class="w-5 h-5 rounded-full bg-amberAcc-500 text-forest-950 text-[10px] font-black flex items-center justify-center"></span>
            </button>

            <!-- User Account / Admin Badge -->
            @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-1.5 sm:space-x-2 border border-cream-200/30 rounded-full py-1 px-2.5 sm:px-3 hover:bg-forest-800 transition-colors">
                        <img src="{{ auth()->user()->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" class="w-6 h-6 sm:w-7 sm:h-7 rounded-full object-cover">
                        <span class="text-xs font-bold text-cream-100 hidden sm:inline">{{ auth()->user()->name }}</span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-cream-300"></i>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition x-cloak class="absolute right-0 mt-2 w-48 bg-white text-slate-900 rounded-2xl shadow-2xl border border-cream-200 py-2 z-50">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-xs font-bold text-forest-800 hover:bg-cream-100"><i class="fa-solid fa-gauge mr-2 text-amberAcc-600"></i> Admin Suite</a>
                        @endif
                        <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2 text-xs text-slate-700 hover:bg-cream-100 font-semibold"><i class="fa-solid fa-user mr-2 text-slate-400"></i> Dashboard</a>
                        <a href="{{ route('customer.orders') }}" class="block px-4 py-2 text-xs text-slate-700 hover:bg-cream-100 font-semibold"><i class="fa-solid fa-box mr-2 text-slate-400"></i> Sales Orders</a>
                        <a href="{{ route('customer.rentals') }}" class="block px-4 py-2 text-xs text-slate-700 hover:bg-cream-100 font-semibold"><i class="fa-solid fa-bicycle mr-2 text-amberAcc-600"></i> Active Rentals</a>
                        <div class="border-t border-slate-100 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-bold"><i class="fa-solid fa-right-from-bracket mr-2"></i> Sign Out</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-xs font-black text-forest-950 bg-amberAcc-500 hover:bg-amberAcc-400 rounded-full px-3.5 py-1.5 sm:px-4 sm:py-2 shadow-md transition-all flex items-center">
                    <i class="fa-solid fa-user mr-1"></i> <span class="hidden sm:inline">Account</span>
                </a>
            @endauth

            <!-- Mobile Hamburger Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-cream-100 text-xl p-1.5 focus:outline-none">
                <i class="fa-solid fa-bars" x-show="!mobileMenuOpen"></i>
                <i class="fa-solid fa-xmark text-2xl" x-show="mobileMenuOpen" x-cloak></i>
            </button>
        </div>
    </div>

    <!-- Mobile Responsive Drawer Menu -->
    <div x-show="mobileMenuOpen" x-transition x-cloak class="lg:hidden bg-forest-950 text-cream-100 border-b border-forest-800 px-5 py-5 space-y-4 shadow-2xl">
        <!-- Mobile Search Input -->
        <form action="{{ route('catalog.index') }}" method="GET" class="flex items-center bg-forest-900 border border-cream-200/30 rounded-full pl-4 pr-1 py-1">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search E-Bikes, accessories..." 
                   class="w-full bg-transparent text-cream-100 placeholder-cream-300/60 text-xs focus:outline-none font-medium">
            <button type="submit" class="w-8 h-8 rounded-full bg-amberAcc-500 text-forest-950 flex items-center justify-center">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </button>
        </form>

        <nav class="space-y-3 font-bold text-sm">
            <a href="{{ route('home') }}" class="block py-1 text-white border-b border-forest-800">Home</a>
            <a href="{{ route('catalog.index', ['type' => 'ebike']) }}" class="block py-1 text-white border-b border-forest-800">E-Bikes</a>
            <a href="{{ route('catalog.index', ['type' => 'rental']) }}" class="block py-1 text-amberAcc-500 font-extrabold border-b border-forest-800">⚡ E-Bike Rental</a>
            <a href="{{ route('catalog.index', ['type' => 'accessory']) }}" class="block py-1 text-white border-b border-forest-800">Accessories</a>
            <a href="{{ route('cms.faqs') }}" class="block py-1 text-white border-b border-forest-800">FAQs</a>
            <a href="{{ route('cms.contact') }}" class="block py-1 text-white">Contact Us</a>
        </nav>
    </div>
</header>
