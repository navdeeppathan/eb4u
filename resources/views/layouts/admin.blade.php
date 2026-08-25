<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Suite | E-Bike 4 U UK')</title>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600&display=swap" rel="stylesheet">

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
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#047857',
                            700: '#065f46',
                            800: '#064e3b',
                            900: '#022c22',
                        },
                        forest: {
                            900: '#06281e',
                        },
                        amberAcc: {
                            500: '#e88d36',
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="flex min-h-full font-sans text-slate-800 antialiased">

    <!-- Admin Sidebar -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col justify-between flex-shrink-0 min-h-screen">
        <div>
            <div class="p-6 border-b border-slate-800 flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black">
                    <i class="fa-solid fa-gauge"></i>
                </div>
                <div>
                    <span class="text-base font-black text-white tracking-tight">E-BIKE <span class="text-emerald-400">ADMIN</span></span>
                    <span class="block text-[10px] uppercase font-bold text-slate-400">UK Management</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1 text-xs font-semibold">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400' }}">
                    <i class="fa-solid fa-chart-line text-base"></i>
                    <span>Dashboard & Analytics</span>
                </a>
                
                <a href="{{ route('admin.products.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400' }}">
                    <i class="fa-solid fa-bicycle text-base"></i>
                    <span>E-Bikes & Accessories</span>
                </a>

                <a href="{{ route('admin.fleet.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.fleet.*') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400' }}">
                    <i class="fa-solid fa-qrcode text-base"></i>
                    <span>Physical Fleet Units</span>
                </a>

                <a href="{{ route('admin.maintenance.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.maintenance.*') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400' }}">
                    <i class="fa-solid fa-screwdriver-wrench text-base"></i>
                    <span>Maintenance & Repairs</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400' }}">
                    <i class="fa-solid fa-box text-base"></i>
                    <span>Sales & Rental Orders</span>
                </a>

                <a href="{{ route('admin.promotions.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.promotions.*') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400' }}">
                    <i class="fa-solid fa-ticket text-base"></i>
                    <span>Coupons & Promotions</span>
                </a>

                <a href="{{ route('admin.reviews.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.reviews.*') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400' }}">
                    <i class="fa-solid fa-star text-base"></i>
                    <span>Customer Reviews</span>
                </a>

                <a href="{{ route('admin.cms.banners') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.cms.*') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400' }}">
                    <i class="fa-solid fa-layer-group text-base"></i>
                    <span>CMS & Policy Pages</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-slate-800 text-slate-400' }}">
                    <i class="fa-solid fa-sliders text-base"></i>
                    <span>Payment & Store Settings</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Bottom Admin Info -->
        <div class="p-4 border-t border-slate-800 flex items-center justify-between">
            <a href="{{ route('home') }}" target="_blank" class="text-xs text-emerald-400 font-bold hover:underline">
                <i class="fa-solid fa-store mr-1"></i> View Live Store
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-rose-400 text-xs font-bold"><i class="fa-solid fa-right-from-bracket"></i></button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-w-0">
        <!-- Top Bar -->
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between">
            <h2 class="text-base font-black text-slate-900">@yield('title')</h2>
            <div class="flex items-center space-x-3 text-xs">
                <span class="bg-emerald-100 text-emerald-900 font-black px-3 py-1 rounded-full border border-emerald-300">Admin Mode</span>
                <span class="font-bold text-slate-700">{{ auth()->user()->name }}</span>
            </div>
        </header>

        <!-- Flash Alert Messages -->
        <div class="p-6 pb-0">
            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-600 text-emerald-900 p-4 rounded-xl shadow-xs text-xs font-bold flex justify-between items-center mb-4">
                    <span><i class="fa-solid fa-circle-check text-emerald-600 mr-2"></i> {{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()">&times;</button>
                </div>
            @endif
        </div>

        <main class="p-6 flex-grow">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
