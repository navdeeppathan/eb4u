<!DOCTYPE html>
<html lang="en" class="h-full bg-[#f5f7fb]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'eb4u | UK Premium E-Bike Rental, Sales & Accessories')</title>

    <!-- Google Fonts: Space Grotesk (Headings) & Outfit (Body & UI) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        grotesk: ['Space Grotesk', 'sans-serif'],
                    },
                    colors: {
                        brandOrange: {
                            50: 'rgba(249,115,22,.09)',
                            100: 'rgba(249,115,22,.15)',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                        },
                        darkSlate: {
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        },
                        surf: '#edf1f8',
                        cardHover: '#f0f4fc',
                        borderLight: '#dde4f0',
                        borderMid: '#b8cce0',
                        textSec: '#445568',
                        textMuted: '#8898b0',
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
        body { font-family: 'Outfit', sans-serif; background-color: #f5f7fb; color: #0f172a; -webkit-font-smoothing: antialiased; }
        h1, h2, h3, h4, .font-grotesk { font-family: 'Space Grotesk', sans-serif; }
        .swal2-popup { font-family: 'Outfit', sans-serif !important; border-radius: 1.25rem !important; }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans antialiased" x-data="cartApp()">

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
                        confirmButtonColor: '#f97316',
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
                        confirmButtonColor: '#f97316'
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
                        position: 'bottom-end',
                        icon: isErr ? 'error' : 'success',
                        title: msg,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        background: '#ffffff',
                        color: '#0f172a',
                        customClass: {
                            popup: 'shadow-2xl border border-borderMid rounded-2xl'
                        }
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
                        confirmButtonColor: '#f97316',
                        cancelButtonColor: '#0f172a',
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
