<footer class="bg-darkSlate-900 text-white/80 pt-16 pb-8 border-t border-darkSlate-800">
    <div class="max-w-[1320px] mx-auto px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
        
        <!-- Column 1: Brand & UK Store Info -->
        <div class="lg:col-span-2 space-y-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-shrink-0">
                <div class="w-9 h-9 bg-brandOrange-500 rounded-xl flex items-center justify-center text-darkSlate-950 font-black shadow-sm">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M9 2L5 9.5h3L6.5 16l6.5-8.5h-3.5L13 2H9z" fill="#0f172a"/></svg>
                </div>
                <span class="font-grotesk text-2xl font-extrabold tracking-tight text-white">
                    eb<span class="text-brandOrange-500">4</span>u
                </span>
            </a>
            <p class="text-xs text-textMuted leading-relaxed pr-6 font-normal">
                Britain's premier destination for electric bike sales, flexible short-term and monthly e-bike rentals, and certified cycling accessories. Engineered for UK city commuting and countryside adventures.
            </p>
            <div class="space-y-2 text-xs text-slate-300 pt-2 font-medium">
                <p><i class="fa-solid fa-location-dot text-brandOrange-500 w-5"></i> 142 Regent Street, London, W1B 5SE, UK</p>
                <p><i class="fa-solid fa-phone text-brandOrange-500 w-5"></i> +44 (0) 20 7946 0912</p>
                <p><i class="fa-solid fa-envelope text-brandOrange-500 w-5"></i> support@eb4u.co.uk</p>
            </div>
        </div>

        <!-- Column 2: Categories -->
        <div>
            <h4 class="font-grotesk text-xs font-bold text-white uppercase tracking-wider mb-4 border-b border-darkSlate-800 pb-2">Categories</h4>
            <ul class="space-y-2 text-xs font-semibold text-textMuted">
                <li><a href="{{ route('catalog.index', ['category' => 'city-e-bikes']) }}" class="hover:text-brandOrange-500 transition-colors">City E-Bikes</a></li>
                <li><a href="{{ route('catalog.index', ['category' => 'mountain-e-bikes']) }}" class="hover:text-brandOrange-500 transition-colors">Mountain E-Bikes</a></li>
                <li><a href="{{ route('catalog.index', ['category' => 'folding-e-bikes']) }}" class="hover:text-brandOrange-500 transition-colors">Folding E-Bikes</a></li>
                <li><a href="{{ route('catalog.index', ['category' => 'helmets']) }}" class="hover:text-brandOrange-500 transition-colors">Helmets & Protection</a></li>
                <li><a href="{{ route('catalog.index', ['category' => 'bike-lights']) }}" class="hover:text-brandOrange-500 transition-colors">Bike Lights & Locks</a></li>
                <li><a href="{{ route('catalog.index', ['type' => 'rental']) }}" class="text-brandOrange-500 font-bold hover:underline">E-Bike Rental Hub</a></li>
            </ul>
        </div>

        <!-- Column 3: Customer Care (FAQs & Contact Us) -->
        <div>
            <h4 class="font-grotesk text-xs font-bold text-white uppercase tracking-wider mb-4 border-b border-darkSlate-800 pb-2">Customer Care</h4>
            <ul class="space-y-2 text-xs font-semibold text-textMuted">
                <li><a href="{{ route('cms.faqs') }}" class="hover:text-brandOrange-500 transition-colors text-white font-bold"><i class="fa-solid fa-circle-question text-brandOrange-500 mr-1.5"></i> FAQs</a></li>
                <li><a href="{{ route('cms.contact') }}" class="hover:text-brandOrange-500 transition-colors text-white font-bold"><i class="fa-solid fa-headset text-brandOrange-500 mr-1.5"></i> Contact Us</a></li>
                <li><a href="{{ route('customer.dashboard') }}" class="hover:text-brandOrange-500 transition-colors">Account Dashboard</a></li>
                <li><a href="{{ route('customer.orders') }}" class="hover:text-brandOrange-500 transition-colors">Order Tracking</a></li>
                <li><a href="{{ route('customer.rentals') }}" class="hover:text-brandOrange-500 transition-colors">Manage Active Rental</a></li>
            </ul>
        </div>

        <!-- Column 4: UK Store Policies -->
        <div>
            <h4 class="font-grotesk text-xs font-bold text-white uppercase tracking-wider mb-4 border-b border-darkSlate-800 pb-2">UK Legal & Policies</h4>
            <ul class="space-y-2 text-xs font-semibold text-textMuted">
                <li><a href="{{ route('cms.page', 'privacy-policy') }}" class="hover:text-brandOrange-500 transition-colors">Privacy Policy</a></li>
                <li><a href="{{ route('cms.page', 'terms-and-conditions') }}" class="hover:text-brandOrange-500 transition-colors">Terms & Conditions</a></li>
                <li><a href="{{ route('cms.page', 'rental-policy') }}" class="hover:text-brandOrange-500 transition-colors">E-Bike Rental Policy</a></li>
                <li><a href="{{ route('cms.page', 'refund-policy') }}" class="hover:text-brandOrange-500 transition-colors">Refund & Deposit Policy</a></li>
                <li><a href="{{ route('cms.page', 'shipping-policy') }}" class="hover:text-brandOrange-500 transition-colors">UK Shipping & Delivery</a></li>
            </ul>
        </div>

    </div>

    <!-- Copyright & Social -->
    <div class="max-w-[1320px] mx-auto px-6 border-t border-darkSlate-800 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-textMuted font-medium">
        <p>&copy; {{ date('Y') }} eb4u Ltd. Registered in England & Wales. All rights reserved.</p>
        <div class="flex space-x-4 mt-4 md:mt-0">
            <a href="#" class="hover:text-brandOrange-500 text-lg transition-colors"><i class="fa-brands fa-facebook"></i></a>
            <a href="#" class="hover:text-brandOrange-500 text-lg transition-colors"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" class="hover:text-brandOrange-500 text-lg transition-colors"><i class="fa-brands fa-x-twitter"></i></a>
            <a href="#" class="hover:text-brandOrange-500 text-lg transition-colors"><i class="fa-brands fa-youtube"></i></a>
        </div>
    </div>
</footer>
