<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Eb4u | UK Premium E-Bike Rental, Sales & Accessories')</title>

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
                        brandOrange: {
                            400: '#ff621a',
                            500: '#f24e00',
                            600: '#d64300',
                            700: '#b53700',
                        },
                        darkBlack: {
                            800: '#1f1f1f',
                            900: '#121212',
                            950: '#0a0a0a',
                        },
                        brand: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            500: '#f24e00',
                            600: '#0a0a0a',
                            700: '#121212',
                            800: '#1f1f1f',
                            900: '#0a0a0a',
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
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; color: #0f172a; }
        .swal2-popup { font-family: 'Poppins', sans-serif !important; border-radius: 1.5rem !important; }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans antialiased" x-data="cartApp()">

    <!-- Top Announcement Bar (Black & Orange) -->
    <div class="bg-darkBlack-950 text-white text-xs py-2 px-6 md:px-12 flex justify-between items-center z-50 border-b border-darkBlack-800">
        <div class="container mx-auto flex justify-between items-center px-2 md:px-6">
            <div class="flex items-center space-x-4">
                <span><i class="fa-solid fa-truck-fast text-brandOrange-500 mr-1"></i> Free UK Delivery on orders over £500</span>
                <span class="hidden md:inline text-slate-700">|</span>
                <span class="hidden md:inline"><i class="fa-solid fa-shield-halved text-brandOrange-500 mr-1"></i> Official UK Warranty & Battery Safety</span>
            </div>
            <div class="flex items-center space-x-4">
                <span class="font-bold text-brandOrange-500"><i class="fa-solid fa-sterling-sign mr-1"></i> GBP (£)</span>
                <span><i class="fa-solid fa-phone text-brandOrange-500 mr-1"></i> +44 (0) 20 7946 0912</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    @include('layouts.partials.header')

    <!-- Flash Messages Container -->
    <div class="container mx-auto px-6 md:px-12 mt-4">
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: @json(session('success')),
                        confirmButtonColor: '#f24e00',
                        timer: 3500
                    });
                });
            </script>
        @endif
        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Notice',
                        text: @json(session('error')),
                        confirmButtonColor: '#f24e00'
                    });
                });
            </script>
        @endif
    </div>

    <!-- Main Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Slide-over Mini Cart Drawer -->
    @include('layouts.partials.cart_drawer')

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

                init() {
                    this.fetchCart();
                },
                showToast(msg, isErr = false) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: isErr ? 'error' : 'success',
                        title: msg,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        background: '#ffffff',
                        color: '#0f172a',
                    });
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
                    let confirmRes = await Swal.fire({
                        title: 'Remove item?',
                        text: 'Remove this item from your basket?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f24e00',
                        cancelButtonColor: '#121212',
                        confirmButtonText: 'Yes, remove',
                        cancelButtonText: 'Cancel'
                    });

                    if (confirmRes.isConfirmed) {
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
                }
            }));
        });
    </script>
    @stack('scripts')
</body>
</html>
