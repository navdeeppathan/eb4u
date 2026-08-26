<header class="sticky top-0 z-40 bg-darkBlack-900 border-b border-darkBlack-800 shadow-md text-white" x-data="{ mobileMenuOpen: false }">
    <div class="container mx-auto px-6 md:px-12 py-3 flex items-center justify-between">
        
        <!-- Responsive Official Eb4u Image Logo -->
        <a href="{{ route('home') }}" class="flex items-center flex-shrink-0 group">
            <img src="{{ asset('images/logo.webp') }}" alt="Eb4u Logo" 
                 class="h-8 sm:h-10 md:h-11 w-auto object-contain bg-white/95 px-2.5 py-1 rounded-2xl shadow-sm border border-white/20 transition-transform group-hover:scale-105">
        </a>

        <!-- Desktop Navigation Menu -->
        <nav class="hidden lg:flex items-center space-x-8 font-bold text-white text-sm">
            <a href="{{ route('home') }}" class="hover:text-brandOrange-500 transition-colors border-b-2 py-1 {{ request()->routeIs('home') ? 'border-brandOrange-500 text-brandOrange-500' : 'border-transparent' }}">Home</a>
            <a href="{{ route('catalog.index', ['type' => 'ebike']) }}" class="hover:text-brandOrange-500 transition-colors py-1 {{ request('type') == 'ebike' ? 'text-brandOrange-500 font-extrabold' : '' }}">E-Bikes</a>
            <a href="{{ route('catalog.index', ['type' => 'rental']) }}" class="hover:text-brandOrange-500 transition-colors py-1 text-brandOrange-400 font-extrabold flex items-center">
                <span class="w-2 h-2 rounded-full bg-brandOrange-500 animate-pulse mr-1.5"></span> E-Bike Rental
            </a>
            <a href="{{ route('catalog.index', ['type' => 'accessory']) }}" class="hover:text-brandOrange-500 transition-colors py-1">Accessories</a>
            <a href="{{ route('cms.page', 'about-us') }}" class="hover:text-brandOrange-500 transition-colors py-1">About</a>
        </nav>

        <!-- Header Actions (Wishlist, Cart, Real-Time Notifications, Account) -->
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

            <!-- Real-Time Notification Bell Dropdown -->
            @auth
                <div class="relative" x-data="notificationDropdown()">
                    <button @click="open = !open" class="relative text-white hover:text-brandOrange-500 transition-colors p-1.5" title="Notifications">
                        <i class="fa-regular fa-bell text-lg"></i>
                        <span x-show="unreadCount > 0" x-text="unreadCount" x-cloak class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-rose-600 text-white text-[9px] font-black flex items-center justify-center shadow-md animate-pulse"></span>
                    </button>

                    <div x-show="open" @click.outside="open = false" x-transition x-cloak class="absolute right-0 mt-2 w-80 sm:w-96 bg-darkBlack-900 text-white rounded-2xl shadow-2xl border border-darkBlack-800 overflow-hidden z-50">
                        <div class="p-4 bg-black text-white flex justify-between items-center border-b border-darkBlack-800">
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-bell text-brandOrange-500"></i>
                                <h4 class="text-xs font-bold uppercase tracking-wider">Notifications</h4>
                            </div>
                            <button @click="markAllRead()" class="text-[10px] text-brandOrange-500 hover:underline font-bold">Mark all read</button>
                        </div>

                        <div class="max-h-80 overflow-y-auto divide-y divide-darkBlack-800">
                            <template x-if="notifications.length === 0">
                                <div class="p-6 text-center text-xs text-slate-400 font-medium">
                                    <i class="fa-solid fa-circle-check text-2xl text-emerald-500 mb-2 block"></i>
                                    No notifications right now.
                                </div>
                            </template>

                            <template x-for="item in notifications" :key="item.id">
                                <div :class="item.is_read ? 'bg-darkBlack-900' : 'bg-darkBlack-800/80'" class="p-3.5 hover:bg-darkBlack-800 transition-colors flex space-x-3 items-start relative">
                                    <div class="w-8 h-8 rounded-xl bg-brandOrange-500/20 text-brandOrange-500 border border-brandOrange-500/30 flex items-center justify-center flex-shrink-0 text-xs mt-0.5">
                                        <i :class="'fa-solid ' + (item.icon || 'fa-bell')"></i>
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <div class="flex justify-between items-baseline mb-0.5">
                                            <h5 class="text-xs font-bold text-white truncate" x-text="item.title"></h5>
                                            <span class="text-[9px] text-slate-400 ml-2 flex-shrink-0" x-text="item.created_at_human"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-300 leading-snug font-normal line-clamp-2" x-text="item.message"></p>

                                        <template x-if="item.action_url">
                                            <a :href="item.action_url" @click="markRead(item.id)" class="inline-block mt-1.5 text-[10px] font-bold text-brandOrange-400 hover:underline">
                                                View Details &rarr;
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            @endauth

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
            <a href="{{ route('catalog.index', ['type' => 'rental']) }}" class="block py-2 text-brandOrange-500 font-extrabold border-b border-darkBlack-800">⚡ E-Bike Rental</a>
            <a href="{{ route('catalog.index', ['type' => 'accessory']) }}" class="block py-2 text-white">Accessories</a>
            <a href="{{ route('cms.faqs') }}" class="block py-2 text-white border-b border-darkBlack-800">FAQs</a>
            <a href="{{ route('cms.contact') }}" class="block py-2 text-white">Contact Us</a>
        </nav>
    </div>
</header>

<script>
    function notificationDropdown() {
        return {
            open: false,
            unreadCount: 0,
            notifications: [],

            init() {
                this.pollNotifications();
                setInterval(() => {
                    this.pollNotifications();
                }, 10000);
            },
            async pollNotifications() {
                try {
                    let res = await axios.get('{{ route("customer.notifications.index") }}');
                    if (res.data.success) {
                        this.unreadCount = res.data.count;
                        this.notifications = res.data.notifications;
                    }
                } catch (e) {}
            },
            async markRead(id) {
                try {
                    let res = await axios.post(`/customer/notifications/read/${id}`);
                    if (res.data.success) {
                        this.pollNotifications();
                    }
                } catch (e) {}
            },
            async markAllRead() {
                try {
                    let res = await axios.post('{{ route("customer.notifications.read_all") }}');
                    if (res.data.success) {
                        this.unreadCount = 0;
                        this.notifications.forEach(n => n.is_read = true);
                    }
                } catch (e) {}
            }
        }
    }
</script>
