<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\EBikeUnit;
use App\Models\User;
use App\Models\Payment;
use App\Models\MaintenanceRecord;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalSales = Order::where('payment_status', 'paid')->orWhere('payment_status', 'partially_paid')->sum('total_amount');
        $ebikeSalesRevenue = Order::where('type', 'purchase')->sum('total_amount');
        $rentalRevenue = Order::where('type', 'rental')->sum('total_amount');
        $accessorySalesRevenue = Order::whereHas('items.product', fn($q) => $q->where('type', 'accessory'))->sum('total_amount');

        $totalOrdersCount = Order::count();
        $totalRentalsCount = Order::where('type', 'rental')->count();
        $activeRentalsCount = Order::whereIn('status', ['active', 'ready_for_pickup', 'extension_requested'])->count();
        $overdueRentalsCount = Order::where('status', 'overdue')->count();

        $totalCustomersCount = User::where('role', 'customer')->count();
        $totalProductsCount = Product::count();

        $availableFleetCount = EBikeUnit::where('status', 'available')->count();
        $rentedFleetCount = EBikeUnit::where('status', 'rented')->count();
        $maintenanceFleetCount = EBikeUnit::where('status', 'maintenance')->count();

        $recentOrders = Order::with(['user', 'items.product'])->latest()->take(6)->get();
        $topSellingBikes = Product::where('type', 'ebike')->orderBy('price', 'desc')->take(5)->get();
        $topRentedBikes = Product::where('is_rental_eligible', true)->take(5)->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'ebikeSalesRevenue',
            'rentalRevenue',
            'accessorySalesRevenue',
            'totalOrdersCount',
            'totalRentalsCount',
            'activeRentalsCount',
            'overdueRentalsCount',
            'totalCustomersCount',
            'totalProductsCount',
            'availableFleetCount',
            'rentedFleetCount',
            'maintenanceFleetCount',
            'recentOrders',
            'topSellingBikes',
            'topRentedBikes'
        ));
    }

    public function analyticsData()
    {
        // Monthly breakdown for Chart.js
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'];
        $salesData = [12400, 15800, 18900, 22400, 28100, 34500, 39000, 42800];
        $rentalData = [4500, 6200, 8400, 11200, 14800, 19500, 23000, 27500];

        return response()->json([
            'months' => $months,
            'sales' => $salesData,
            'rentals' => $rentalData,
        ]);
    }
}
