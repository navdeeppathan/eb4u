<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Suite | Eb4u')</title>

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
    <!-- Alpine.js & FontAwesome -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Poppins', sans-serif; }
        .swal2-popup { font-family: 'Poppins', sans-serif !important; border-radius: 1.5rem !important; }
    </style>
</head>
<body class="h-full bg-slate-100 text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Admin Sidebar (Black & Orange) -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-darkBlack-950 text-white transition-transform duration-300 md:static md:translate-x-0 flex flex-col justify-between border-r border-darkBlack-800 shadow-xl">
            <div>
                <!-- Brand Header -->
                <div class="h-16 flex items-center justify-between px-6 bg-black border-b border-darkBlack-800">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('images/logo.webp') }}" alt="Eb4u Logo" class="h-8 w-auto object-contain bg-white px-2 py-1 rounded-xl">
                        <span class="text-xs uppercase tracking-widest text-brandOrange-500 font-extrabold">Admin</span>
                    </a>
                    <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">&times;</button>
                </div>

                <!-- Navigation Links -->
                <nav class="px-4 py-6 space-y-1.5 font-semibold text-xs text-slate-300">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl hover:bg-darkBlack-800 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-brandOrange-500 text-white font-black' : '' }}">
                        <i class="fa-solid fa-chart-line text-sm w-5"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl hover:bg-darkBlack-800 transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-brandOrange-500 text-white font-black' : '' }}">
                        <i class="fa-solid fa-bicycle text-sm w-5"></i>
                        <span>Products & Rentals</span>
                    </a>
                    <a href="{{ route('admin.fleet.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl hover:bg-darkBlack-800 transition-colors {{ request()->routeIs('admin.fleet.*') ? 'bg-brandOrange-500 text-white font-black' : '' }}">
                        <i class="fa-solid fa-barcode text-sm w-5"></i>
                        <span>Physical Fleet Serial IDs</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl hover:bg-darkBlack-800 transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-brandOrange-500 text-white font-black' : '' }}">
                        <i class="fa-solid fa-receipt text-sm w-5"></i>
                        <span>Orders & Rentals</span>
                    </a>
                    <a href="{{ route('admin.promotions.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl hover:bg-darkBlack-800 transition-colors {{ request()->routeIs('admin.promotions.*') ? 'bg-brandOrange-500 text-white font-black' : '' }}">
                        <i class="fa-solid fa-ticket text-sm w-5"></i>
                        <span>Promotions & Coupons</span>
                    </a>
                    <a href="{{ route('admin.reviews.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl hover:bg-darkBlack-800 transition-colors {{ request()->routeIs('admin.reviews.*') ? 'bg-brandOrange-500 text-white font-black' : '' }}">
                        <i class="fa-solid fa-star text-sm w-5"></i>
                        <span>Review Moderation</span>
                    </a>
                    <a href="{{ route('admin.cms.banners') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl hover:bg-darkBlack-800 transition-colors {{ request()->routeIs('admin.cms.*') ? 'bg-brandOrange-500 text-white font-black' : '' }}">
                        <i class="fa-solid fa-sliders text-sm w-5"></i>
                        <span>CMS Content</span>
                    </a>
                </nav>
            </div>

            <!-- Footer User Action -->
            <div class="p-4 border-t border-darkBlack-800 bg-black">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="{{ auth()->user()->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" class="w-8 h-8 rounded-full border border-brandOrange-500 object-cover">
                        <div>
                            <span class="block text-xs font-bold text-white truncate max-w-[100px]">{{ auth()->user()->name }}</span>
                            <span class="block text-[10px] text-brandOrange-500 font-extrabold uppercase">Super Admin</span>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-rose-500 text-xs p-1" title="Sign Out">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <!-- Top Admin Header Bar -->
            <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = true" class="md:hidden text-slate-600 text-lg"><i class="fa-solid fa-bars"></i></button>
                    <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider">@yield('title', 'Admin Dashboard')</h2>
                </div>
                <div class="flex items-center space-x-4 text-xs font-semibold">
                    <a href="{{ route('home') }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 transition-colors">
                        <i class="fa-solid fa-arrow-up-right-from-square text-brandOrange-500 mr-1.5"></i> Visit Live Storefront
                    </a>
                </div>
            </header>

            <main class="p-6 md:p-8 flex-grow">
                @if(session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Admin Success',
                                text: @json(session('success')),
                                confirmButtonColor: '#f24e00',
                                timer: 3500
                            });
                        });
                    </script>
                @endif
                @yield('content')
            </main>
        </div>

    </div>

    @stack('scripts')
</body>
</html>
