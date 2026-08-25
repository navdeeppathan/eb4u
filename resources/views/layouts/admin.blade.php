<!DOCTYPE html>
<html lang="en" class="h-full bg-[#f5f7fb]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Suite | eb4u')</title>

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
                            500: '#f97316',
                            600: '#ea580c',
                        },
                        darkSlate: {
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        },
                        surf: '#edf1f8',
                        borderLight: '#dde4f0',
                        borderMid: '#b8cce0',
                        textSec: '#445568',
                        textMuted: '#8898b0',
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
        body { font-family: 'Outfit', sans-serif; }
        h1, h2, h3, h4, .font-grotesk { font-family: 'Space Grotesk', sans-serif; }
        .swal2-popup { font-family: 'Outfit', sans-serif !important; border-radius: 1.25rem !important; }
    </style>
</head>
<body class="h-full bg-[#f5f7fb] text-darkSlate-900 antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Admin Sidebar (Matching Testing Platform AdminDashboard.dc.html) -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-darkSlate-900 text-white transition-transform duration-300 md:static md:translate-x-0 flex flex-col justify-between border-r border-darkSlate-800 shadow-xl">
            <div>
                <!-- Brand Header -->
                <div class="h-16 flex items-center justify-between px-6 bg-darkSlate-950 border-b border-darkSlate-800">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-brandOrange-500 rounded-xl flex items-center justify-center text-darkSlate-950 font-black shadow-sm">
                            <svg width="16" height="16" viewBox="0 0 18 18" fill="none"><path d="M9 2L5 9.5h3L6.5 16l6.5-8.5h-3.5L13 2H9z" fill="#0f172a"/></svg>
                        </div>
                        <span class="font-grotesk text-xl font-extrabold text-white">eb<span class="text-brandOrange-500">4</span>u <span class="text-[10px] uppercase tracking-widest text-brandOrange-500 font-bold not-italic">Admin</span></span>
                    </a>
                    <button @click="sidebarOpen = false" class="md:hidden text-textMuted hover:text-white">&times;</button>
                </div>

                <!-- Navigation Links -->
                <nav class="px-4 py-6 space-y-1.5 font-semibold text-xs text-textMuted">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-darkSlate-800 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-brandOrange-500 text-white font-bold' : '' }}">
                        <i class="fa-solid fa-chart-line text-sm w-5"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-darkSlate-800 transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-brandOrange-500 text-white font-bold' : '' }}">
                        <i class="fa-solid fa-bicycle text-sm w-5"></i>
                        <span>Products & Rentals</span>
                    </a>
                    <a href="{{ route('admin.fleet.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-darkSlate-800 transition-colors {{ request()->routeIs('admin.fleet.*') ? 'bg-brandOrange-500 text-white font-bold' : '' }}">
                        <i class="fa-solid fa-barcode text-sm w-5"></i>
                        <span>Fleet Serial IDs</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-darkSlate-800 transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-brandOrange-500 text-white font-bold' : '' }}">
                        <i class="fa-solid fa-receipt text-sm w-5"></i>
                        <span>Orders & Rentals</span>
                    </a>
                    <a href="{{ route('admin.promotions.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-darkSlate-800 transition-colors {{ request()->routeIs('admin.promotions.*') ? 'bg-brandOrange-500 text-white font-bold' : '' }}">
                        <i class="fa-solid fa-ticket text-sm w-5"></i>
                        <span>Promotions & Coupons</span>
                    </a>
                    <a href="{{ route('admin.reviews.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-darkSlate-800 transition-colors {{ request()->routeIs('admin.reviews.*') ? 'bg-brandOrange-500 text-white font-bold' : '' }}">
                        <i class="fa-solid fa-star text-sm w-5"></i>
                        <span>Review Moderation</span>
                    </a>
                    <a href="{{ route('admin.cms.banners') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-darkSlate-800 transition-colors {{ request()->routeIs('admin.cms.*') ? 'bg-brandOrange-500 text-white font-bold' : '' }}">
                        <i class="fa-solid fa-sliders text-sm w-5"></i>
                        <span>CMS Content</span>
                    </a>
                </nav>
            </div>

            <!-- Footer User Action -->
            <div class="p-4 border-t border-darkSlate-800 bg-darkSlate-950">
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
                        <button type="submit" class="text-textMuted hover:text-rose-500 text-xs p-1" title="Sign Out">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <!-- Top Admin Header Bar -->
            <header class="h-16 bg-white border-b border-borderLight px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = true" class="md:hidden text-textSec text-lg"><i class="fa-solid fa-bars"></i></button>
                    <h2 class="font-grotesk text-sm font-bold text-darkSlate-900 uppercase tracking-wider">@yield('title', 'Admin Dashboard')</h2>
                </div>
                <div class="flex items-center space-x-4 text-xs font-semibold">
                    <a href="{{ route('home') }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-[#f5f7fb] hover:bg-slate-200 text-darkSlate-900 transition-colors border border-borderLight">
                        <i class="fa-solid fa-arrow-up-right-from-square text-brandOrange-500 mr-1.5"></i> Live Storefront
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
                                confirmButtonColor: '#f97316',
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
