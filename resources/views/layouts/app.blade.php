<!DOCTYPE html>
<html lang="en" class="h-full bg-[#f4f0e6]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Bike 4 U | UK Premium E-Bike Rental, Sales & Cycling Accessories')</title>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#faf8f2',
                            100: '#f4f0e6',
                            200: '#e8dfcc',
                            500: '#e88d36',
                            600: '#06281e',
                            700: '#0a3d2e',
                            800: '#041d16',
                            900: '#03140e',
                        },
                        forest: {
                            50: '#eef8f5',
                            100: '#d7efea',
                            700: '#145c47',
                            800: '#0e4535',
                            900: '#06281e',
                            950: '#031913',
                        },
                        cream: {
                            50: '#faf8f2',
                            100: '#f4f0e6',
                            200: '#e8dfcc',
                            300: '#dbcbb0',
                        },
                        amberAcc: {
                            400: '#fb923c',
                            500: '#e88d36',
                            600: '#d97706',
                            700: '#b45309',
                        },
                        emeraldAcc: {
                            500: '#10b981',
                            600: '#00a86b',
                            700: '#047857',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js & FontAwesome Icons -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Flatpickr Date Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Poppins', sans-serif; background-color: #f4f0e6; color: #06281e; }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans antialiased" x-data="cartApp()">

    <!-- Top Announcement Bar -->
    <div class="bg-forest-950 text-cream-100 text-xs py-2 px-4 flex justify-between items-center z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <span><i class="fa-solid fa-truck-fast text-amberAcc-500 mr-1"></i> Free UK Delivery on orders over £500</span>
                <span class="hidden md:inline">|</span>
                <span class="hidden md:inline"><i class="fa-solid fa-shield-halved text-amberAcc-500 mr-1"></i> Official UK Warranty & Certified Battery Safety</span>
            </div>
            <div class="flex items-center space-x-4">
                <span class="font-semibold text-amberAcc-500"><i class="fa-solid fa-sterling-sign mr-1"></i> GBP (£)</span>
                <span><i class="fa-solid fa-phone text-amberAcc-500 mr-1"></i> +44 (0) 20 7946 0912</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    @include('layouts.partials.header')

    <!-- Flash Messages Container -->
    <div class="container mx-auto px-4 mt-4">
        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emeraldAcc-600 text-forest-900 p-4 rounded-xl shadow-sm mb-4 flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fa-solid fa-circle-check text-emeraldAcc-600 text-xl mr-3"></i>
                    <span class="text-xs font-bold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-forest-700">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl shadow-sm mb-4 flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-xl mr-3"></i>
                    <span class="text-xs font-bold">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600">&times;</button>
            </div>
        @endif
    </div>

    <!-- Main Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Slide-over Mini Cart Drawer -->
    @include('layouts.partials.cart_drawer')

    <!-- Toast Notification Banner -->
    <div x-show="toast.show" x-transition x-cloak
         class="fixed bottom-5 right-5 z-50 bg-forest-900 text-cream-50 px-5 py-3 rounded-2xl shadow-2xl flex items-center space-x-3 border border-amberAcc-500">
        <i class="fa-solid fa-circle-info text-amberAcc-500 text-lg" :class="toast.isError ? 'text-rose-400 fa-circle-xmark' : 'text-amberAcc-500 fa-circle-check'"></i>
        <span x-text="toast.message" class="text-xs font-bold"></span>
    </div>

    <!-- Footer -->
    @include('layouts.partials.footer')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cartApp', () => ({
                isCartOpen: false,
                cartCount: {{ \App\Models\CartItem::where('session_id', session('cart_session_id'))->sum('quantity') }},
                cartItems: [],
                subtotal: '0.00',
                tax: '0.00',
                total: '0.00',
                toast: { show: false, message: '', isError: false },

                init() {
                    this.fetchCart();
                },
                showToast(msg, isErr = false) {
                    this.toast.message = msg;
                    this.toast.isError = isErr;
                    this.toast.show = true;
                    setTimeout(() => { this.toast.show = false; }, 4000);
                },
                async fetchCart() {
                    try {
                        let res = await axios.get('{{ route("cart.mini") }}');
                        if (res.data.success) {
                            this.cartCount = res.data.count;
                            this.cartItems = res.data.items;
                            this.subtotal = res.data.subtotal;
                            this.tax = res.data.tax;
                            this.total = res.data.total;
                        }
                    } catch (e) {
                        console.error('Failed to fetch mini cart:', e);
                    }
                },
                async addToCart(productId, type = 'purchase', qty = 1, startDate = null, endDate = null) {
                    try {
                        let res = await axios.post('{{ route("cart.add") }}', {
                            product_id: productId,
                            item_type: type,
                            quantity: qty,
                            rental_start_date: startDate,
                            rental_end_date: endDate
                        });
                        if (res.data.success) {
                            this.showToast(res.data.message);
                            this.fetchCart();
                            this.isCartOpen = true;
                        }
                    } catch (e) {
                        let msg = e.response?.data?.message || 'Failed to add item to cart.';
                        this.showToast(msg, true);
                    }
                },
                async updateQty(itemId, newQty) {
                    try {
                        let res = await axios.post(`/cart/update/${itemId}`, { quantity: newQty });
                        if (res.data.success) {
                            this.fetchCart();
                        }
                    } catch (e) {
                        this.showToast('Failed to update quantity.', true);
                    }
                },
                async removeItem(itemId) {
                    try {
                        let res = await axios.delete(`/cart/remove/${itemId}`);
                        if (res.data.success) {
                            this.showToast(res.data.message);
                            this.fetchCart();
                        }
                    } catch (e) {
                        this.showToast('Failed to remove item.', true);
                    }
                }
            }));
        });
    </script>
    @stack('scripts')
</body>
</html>
