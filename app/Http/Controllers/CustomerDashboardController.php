<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wishlist;
use App\Models\Review;
use App\Models\Address;
use App\Models\Notification;
use Carbon\Carbon;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $recentOrders = Order::with('items.product')->where('user_id', $user->id)->latest()->take(5)->get();
        $activeRentals = Order::with('items.product')->where('user_id', $user->id)->whereIn('status', ['active', 'ready_for_pickup', 'extension_requested', 'return_requested'])->get();
        $upcomingRentals = Order::with('items.product')->where('user_id', $user->id)->where('type', 'rental')->where('status', 'confirmed')->get();
        
        $totalOrdersCount = Order::where('user_id', $user->id)->count();
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();

        return view('customer.dashboard', compact(
            'user',
            'recentOrders',
            'activeRentals',
            'upcomingRentals',
            'totalOrdersCount',
            'wishlistCount'
        ));
    }

    public function orders()
    {
        $orders = Order::with('items.product')->where('user_id', auth()->id())->latest()->paginate(10);
        return view('customer.orders', compact('orders'));
    }

    public function orderDetail(string $orderNumber)
    {
        $order = Order::with(['items.product', 'items.ebikeUnit', 'payments'])->where('user_id', auth()->id())->where('order_number', $orderNumber)->firstOrFail();
        return view('customer.order_detail', compact('order'));
    }

    public function rentals()
    {
        $user = auth()->user();
        $activeRentals = Order::with(['items.product', 'items.ebikeUnit'])->where('user_id', $user->id)->whereIn('status', ['active', 'ready_for_pickup', 'extension_requested', 'return_requested', 'overdue'])->get();
        $upcomingRentals = Order::with(['items.product', 'items.ebikeUnit'])->where('user_id', $user->id)->where('type', 'rental')->where('status', 'confirmed')->get();
        $rentalHistory = Order::with(['items.product', 'items.ebikeUnit'])->where('user_id', $user->id)->where('type', 'rental')->whereIn('status', ['completed', 'returned', 'cancelled'])->latest()->get();

        return view('customer.rentals', compact('activeRentals', 'upcomingRentals', 'rentalHistory'));
    }

    public function extendRental(Request $request, int $orderId)
    {
        $request->validate([
            'extension_days' => 'required|integer|min:1|max:30',
        ]);

        $order = Order::where('user_id', auth()->id())->findOrFail($orderId);
        $days = (int) $request->extension_days;

        $firstItem = $order->items()->where('item_type', 'rental')->first();
        if (!$firstItem) {
            return response()->json(['success' => false, 'message' => 'No rental item found in this order.'], 422);
        }

        $dailyRate = $firstItem->rental_rate ?? 35.00;
        $additionalAmount = round($dailyRate * $days, 2);

        $newEndDate = Carbon::parse($order->rental_end_date)->addDays($days);
        $order->update([
            'rental_end_date' => $newEndDate,
            'total_amount' => $order->total_amount + $additionalAmount,
            'remaining_amount' => $order->remaining_amount + $additionalAmount,
            'status' => 'extension_requested',
            'admin_notes' => ($order->admin_notes ? $order->admin_notes . "\n" : "") . "Customer requested extension of {$days} day(s) until {$newEndDate->format('Y-m-d')}.",
        ]);

        $firstItem->update([
            'rental_end_date' => $newEndDate,
            'rental_days' => $firstItem->rental_days + $days,
            'subtotal' => $firstItem->subtotal + $additionalAmount,
        ]);

        // Send Notification to Renter
        Notification::send(
            auth()->id(),
            'rental_extended',
            'Rental Extension Requested 🎉',
            "Your extension of {$days} day(s) for Order #{$order->order_number} has been logged. New end date: " . $newEndDate->format('d M Y') . ".",
            route('customer.rentals'),
            'fa-calendar-plus',
            ['order_id' => $order->id]
        );

        return response()->json([
            'success' => true,
            'message' => "Rental extension request submitted for {$days} extra day(s)! Added £" . number_format($additionalAmount, 2) . " to order balance.",
        ]);
    }

    public function requestReturn(Request $request, int $orderId)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($orderId);
        $order->update([
            'status' => 'return_requested',
            'actual_return_date' => now(),
            'customer_notes' => ($order->customer_notes ? $order->customer_notes . "\n" : "") . "Return requested by customer on " . now()->format('d M Y H:i') . ".",
        ]);

        // Send Notification to Renter
        Notification::send(
            auth()->id(),
            'return_requested',
            'Return Request Received 📦',
            "Return request for Order #{$order->order_number} received. Your deposit of £" . number_format($order->security_deposit_total, 2) . " will be refunded after inspection.",
            route('customer.rentals'),
            'fa-truck-ramp-box',
            ['order_id' => $order->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Return request submitted. Our team will verify the E-Bike condition and finalize your security deposit refund.',
        ]);
    }

    public function wishlist()
    {
        $wishlists = Wishlist::with('product.images')->where('user_id', auth()->id())->latest()->get();
        return view('customer.wishlist', compact('wishlists'));
    }

    public function toggleWishlist(Request $request, int $productId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please sign in to save items to your wishlist.'], 401);
        }

        $userId = auth()->id();
        $exists = Wishlist::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($exists) {
            $exists->delete();
            $added = false;
            $msg = 'Product removed from wishlist.';
        } else {
            Wishlist::create(['user_id' => $userId, 'product_id' => $productId]);
            $added = true;
            $msg = 'Product added to wishlist!';
        }

        $count = Wishlist::where('user_id', $userId)->count();

        return response()->json([
            'success' => true,
            'added' => $added,
            'message' => $msg,
            'count' => $count,
        ]);
    }

    public function profile()
    {
        $user = auth()->user();
        $addresses = $user->addresses;
        return view('customer.profile', compact('user', 'addresses'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
        ]);

        $user->update($request->only('name', 'phone'));

        return back()->with('success', 'Profile updated successfully.');
    }
}
