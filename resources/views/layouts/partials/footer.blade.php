<footer class="bg-forest-950 text-cream-100/80 pt-16 pb-8 border-t border-forest-900">
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
        
        <!-- Column 1: Brand & UK Store Info -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center space-x-2.5">
                <div class="w-9 h-9 rounded-xl bg-amberAcc-500 text-forest-950 flex items-center justify-center font-extrabold">
                    <i class="fa-solid fa-bolt text-lg"></i>
                </div>
                <span class="text-xl font-black text-white tracking-tight">E-BIKE <span class="text-amberAcc-500">4 U</span></span>
            </div>
            <p class="text-xs text-cream-200/70 leading-relaxed pr-6 font-normal">
                Britain's premier destination for electric bike sales, flexible short-term and monthly e-bike rentals, and certified cycling accessories. Engineered for UK city commuting and countryside adventures.
            </p>
            <div class="space-y-2 text-xs text-cream-100/90 pt-2 font-medium">
                <p><i class="fa-solid fa-location-dot text-amberAcc-500 w-5"></i> 142 Regent Street, London, W1B 5SE, UK</p>
                <p><i class="fa-solid fa-phone text-amberAcc-500 w-5"></i> +44 (0) 20 7946 0912</p>
                <p><i class="fa-solid fa-envelope text-amberAcc-500 w-5"></i> support@eb4u.co.uk</p>
            </div>
        </div>

        <!-- Column 2: Quick Links & E-Bikes -->
        <div>
            <h4 class="text-xs font-black text-white uppercase tracking-wider mb-4 border-b border-forest-900 pb-2">E-Bike Range</h4>
            <ul class="space-y-2 text-xs font-semibold">
                <li><a href="{{ route('catalog.index', ['category' => 'city-e-bikes']) }}" class="hover:text-amberAcc-500 transition-colors">City E-Bikes</a></li>
                <li><a href="{{ route('catalog.index', ['category' => 'mountain-e-bikes']) }}" class="hover:text-amberAcc-500 transition-colors">Mountain E-Bikes</a></li>
                <li><a href="{{ route('catalog.index', ['category' => 'folding-e-bikes']) }}" class="hover:text-amberAcc-500 transition-colors">Folding E-Bikes</a></li>
                <li><a href="{{ route('catalog.index', ['category' => 'commuter-e-bikes']) }}" class="hover:text-amberAcc-500 transition-colors">Commuter E-Bikes</a></li>
                <li><a href="{{ route('catalog.index', ['category' => 'road-e-bikes']) }}" class="hover:text-amberAcc-500 transition-colors">Road E-Bikes</a></li>
                <li><a href="{{ route('catalog.index', ['type' => 'rental']) }}" class="text-amberAcc-500 font-black hover:underline">E-Bike Rental Hub</a></li>
            </ul>
        </div>

        <!-- Column 3: Customer Service & Rental Info -->
        <div>
            <h4 class="text-xs font-black text-white uppercase tracking-wider mb-4 border-b border-forest-900 pb-2">Customer Care</h4>
            <ul class="space-y-2 text-xs font-semibold">
                <li><a href="{{ route('cms.faqs') }}" class="hover:text-amberAcc-500 transition-colors">How Rental Works</a></li>
                <li><a href="{{ route('customer.dashboard') }}" class="hover:text-amberAcc-500 transition-colors">Account Dashboard</a></li>
                <li><a href="{{ route('customer.orders') }}" class="hover:text-amberAcc-500 transition-colors">Order Tracking</a></li>
                <li><a href="{{ route('customer.rentals') }}" class="hover:text-amberAcc-500 transition-colors">Manage Active Rental</a></li>
                <li><a href="{{ route('cms.contact') }}" class="hover:text-amberAcc-500 transition-colors">Contact Support</a></li>
            </ul>
        </div>

        <!-- Column 4: UK Store Policies -->
        <div>
            <h4 class="text-xs font-black text-white uppercase tracking-wider mb-4 border-b border-forest-900 pb-2">UK Legal & Policies</h4>
            <ul class="space-y-2 text-xs font-semibold">
                <li><a href="{{ route('cms.page', 'privacy-policy') }}" class="hover:text-amberAcc-500 transition-colors">Privacy Policy</a></li>
                <li><a href="{{ route('cms.page', 'terms-and-conditions') }}" class="hover:text-amberAcc-500 transition-colors">Terms & Conditions</a></li>
                <li><a href="{{ route('cms.page', 'rental-policy') }}" class="hover:text-amberAcc-500 transition-colors">E-Bike Rental Policy</a></li>
                <li><a href="{{ route('cms.page', 'refund-policy') }}" class="hover:text-amberAcc-500 transition-colors">Refund & Deposit Policy</a></li>
                <li><a href="{{ route('cms.page', 'shipping-policy') }}" class="hover:text-amberAcc-500 transition-colors">UK Shipping & Delivery</a></li>
            </ul>
        </div>

    </div>

    <!-- Newsletter & Copyright -->
    <div class="container mx-auto px-4 border-t border-forest-900 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-cream-300/60 font-medium">
        <p>&copy; {{ date('Y') }} E-Bike 4 U Ltd. Registered in England & Wales. All rights reserved.</p>
        <div class="flex space-x-4 mt-4 md:mt-0">
            <a href="#" class="hover:text-amberAcc-500 text-lg transition-colors"><i class="fa-brands fa-facebook"></i></a>
            <a href="#" class="hover:text-amberAcc-500 text-lg transition-colors"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" class="hover:text-amberAcc-500 text-lg transition-colors"><i class="fa-brands fa-x-twitter"></i></a>
            <a href="#" class="hover:text-amberAcc-500 text-lg transition-colors"><i class="fa-brands fa-youtube"></i></a>
        </div>
    </div>
</footer>
