<header class="sticky top-0 z-40 bg-darkBlack-900 border-b border-darkBlack-800 shadow-md text-white" x-data="{ mobileMenuOpen: false }">
    <div class="container mx-auto px-6 md:px-12 py-4 flex items-center justify-between">
        
        <!-- Logo matching uploaded image: Orange Bicycle Icon + Eb4u typography -->
        <a href="{{ route('home') }}" class="flex items-center space-x-3 flex-shrink-0 group">
            <div class="text-brandOrange-500 text-3xl sm:text-4xl flex items-center justify-center transition-transform group-hover:scale-105">
                <i class="fa-solid fa-bicycle"></i>
            </div>
            <span class="text-2xl sm:text-3xl font-black tracking-tight text-brandOrange-500 font-sans italic" style="font-family: 'Poppins', sans-serif;">
                Eb4u
            </span>
        </a>

        <!-- Desktop Navigation Menu (Home, E-Bikes, Accessories ONLY) -->
        <nav class="hidden lg:flex items-center space-x-8 font-bold text-white text-sm">
            <a href="{{ route('home') }}" class="hover:text-brandOrange-500 transition-colors border-b-2 py-1 {{ request()->routeIs('home') ? 'border-brandOrange-500 text-brandOrange-500' : 'border-transparent' }}">Home</a>
            <a href="{{ route('catalog.index', ['type' => 'ebike']) }}" class="hover:text-brandOrange-500 transition-colors py-1">E-Bikes</a>
            <a href="{{ route('catalog.index', ['type' => 'accessory']) }}" class="hover:text-brandOrange-500 transition-colors py-1">Accessories</a>
        </nav>

        <!-- Header Actions (Wishlist, Cart, Account) -->
        <div class="flex items-center space-x-3 sm:space-x-4 flex-shrink-0">
            <!-- Wishlist Icon -->
            <a href="{{ route('customer.wishlist') }}" class="relative text-white hover:text-brandOrange-500 transition-colors p-1.5" title="Wishlist">
                <i class="fa-regular fa-heart text-lg"></i>
            </a>

            <!-- Cart Trigger Button -->
            <button @click="isCartOpen = true" class="relative flex items-center space-x-1.5 bg-darkBlack-800 hover:bg-black border border-white/10 px-3.5 py-1.5 rounded-full text-xs font-bold transition-all">
                <span class="hidden sm:inline">Cart</span>
                <i class="fa-solid fa-basket-shopping text-sm sm:hidden"></i>
                <span x-text="cartCount" class="w-5 h-5 rounded-full bg-brandOrange-500 text-white text-[10px] font-black flex items-center justify-center shadow-md"></span>
            </button>

            <!-- User Account / Admin Badge -->
            @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-1.5 border border-white/10 rounded-full py-1 px-2.5 hover:bg-darkBlack-800 transition-colors">
                        <img src="{{ auth()->user()->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" class="w-6 h-6 sm:w-7 sm:h-7 rounded-full object-cover">
                        <span class="text-xs font-bold text-white hidden sm:inline max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition x-cloak class="absolute right-0 mt-2 w-48 bg-darkBlack-900 text-white rounded-2xl shadow-2xl border border-darkBlack-800 py-2 z-50">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-xs font-bold text-brandOrange-500 hover:bg-darkBlack-800"><i class="fa-solid fa-gauge mr-2"></i> Admin Suite</a>
                        @endif
                        <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2 text-xs text-white hover:bg-darkBlack-800 font-semibold"><i class="fa-solid fa-user mr-2 text-slate-400"></i> Dashboard</a>
                        <a href="{{ route('customer.orders') }}" class="block px-4 py-2 text-xs text-white hover:bg-darkBlack-800 font-semibold"><i class="fa-solid fa-box mr-2 text-slate-400"></i> Sales Orders</a>
                        <a href="{{ route('customer.rentals') }}" class="block px-4 py-2 text-xs text-white hover:bg-darkBlack-800 font-semibold"><i class="fa-solid fa-bicycle mr-2 text-brandOrange-500"></i> Active Rentals</a>
                        <div class="border-t border-darkBlack-800 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-xs text-rose-500 hover:bg-rose-950/40 font-bold"><i class="fa-solid fa-right-from-bracket mr-2"></i> Sign Out</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-xs font-black text-white bg-brandOrange-500 hover:bg-brandOrange-600 rounded-full px-4 py-2 shadow-md transition-all flex items-center">
                    <i class="fa-solid fa-user mr-1.5"></i> <span class="hidden sm:inline">Account</span>
                </a>
            @endauth

            <!-- Mobile Hamburger Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-white text-xl p-1.5 focus:outline-none">
                <i class="fa-solid fa-bars" x-show="!mobileMenuOpen"></i>
                <i class="fa-solid fa-xmark text-2xl" x-show="mobileMenuOpen" x-cloak></i>
            </button>
        </div>
    </div>

    <!-- Mobile Responsive Drawer Menu -->
    <div x-show="mobileMenuOpen" x-transition x-cloak class="lg:hidden bg-darkBlack-950 text-white border-b border-darkBlack-800 px-6 py-5 space-y-4 shadow-2xl">
        <nav class="space-y-3 font-bold text-sm">
            <a href="{{ route('home') }}" class="block py-2 text-white border-b border-darkBlack-800">Home</a>
            <a href="{{ route('catalog.index', ['type' => 'ebike']) }}" class="block py-2 text-white border-b border-darkBlack-800">E-Bikes</a>
            <a href="{{ route('catalog.index', ['type' => 'accessory']) }}" class="block py-2 text-white">Accessories</a>
        </nav>
    </div>
</header>
