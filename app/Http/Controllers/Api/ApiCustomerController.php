<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Wishlist;
use App\Models\Notification;
use Carbon\Carbon;

class ApiCustomerController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $recentOrders = Order::with('items.product')->where('user_id', $user->id)->latest()->take(5)->get();
        $activeRentalsCount = Order::where('user_id', $user->id)->whereIn('status', ['active', 'ready_for_pickup', 'extension_requested', 'return_requested'])->count();
        $totalOrdersCount = Order::where('user_id', $user->id)->count();
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
            'metrics' => [
                'total_orders' => $totalOrdersCount,
                'active_rentals' => $activeRentalsCount,
                'wishlist_count' => $wishlistCount,
                'unread_notifications' => $user->unreadNotificationsCount(),
            ],
            'recent_orders' => $recentOrders->map(fn($o) => $this->formatOrder($o)),
        ]);
    }

    public function orders(Request $request)
    {
        $orders = Order::with('items.product')->where('user_id', $request->user()->id)->latest()->paginate(10);
        
        return response()->json([
            'success' => true,
            'current_page' => $orders->currentPage(),
            'last_page' => $orders->lastPage(),
            'total' => $orders->total(),
            'orders' => collect($orders->items())->map(fn($o) => $this->formatOrder($o)),
        ]);
    }

    public function orderDetail(Request $request, string $orderNumber)
    {
        $order = Order::with(['items.product', 'items.ebikeUnit', 'payments'])
            ->where('user_id', $request->user()->id)
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'order' => $this->formatOrder($order, true),
        ]);
    }

    public function rentals(Request $request)
    {
        $user = $request->user();
        $activeRentals = Order::with(['items.product', 'items.ebikeUnit'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['active', 'ready_for_pickup', 'extension_requested', 'return_requested', 'overdue'])
            ->get();

        $upcomingRentals = Order::with(['items.product', 'items.ebikeUnit'])
            ->where('user_id', $user->id)
            ->where('type', 'rental')
            ->where('status', 'confirmed')
            ->get();

        $rentalHistory = Order::with(['items.product', 'items.ebikeUnit'])
            ->where('user_id', $user->id)
            ->where('type', 'rental')
            ->whereIn('status', ['completed', 'returned', 'cancelled'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'active_rentals' => $activeRentals->map(fn($o) => $this->formatOrder($o, true)),
            'upcoming_rentals' => $upcomingRentals->map(fn($o) => $this->formatOrder($o, true)),
            'rental_history' => $rentalHistory->map(fn($o) => $this->formatOrder($o, true)),
        ]);
    }

    public function extendRental(Request $request, int $orderId)
    {
        $request->validate([
            'extension_days' => 'required|integer|min:1|max:30',
        ]);

        $order = Order::where('user_id', $request->user()->id)->findOrFail($orderId);
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
        ]);

        $firstItem->update([
            'rental_end_date' => $newEndDate,
            'rental_days' => $firstItem->rental_days + $days,
            'subtotal' => $firstItem->subtotal + $additionalAmount,
        ]);

        Notification::send(
            $request->user()->id,
            'rental_extended',
            'Rental Extension Requested',
            "Your extension of {$days} day(s) for Order #{$order->order_number} has been logged. New end date: " . $newEndDate->format('d M Y') . ".",
            route('customer.rentals'),
            'fa-calendar-plus'
        );

        return response()->json([
            'success' => true,
            'message' => "Rental extension request submitted for {$days} extra day(s)!",
            'new_end_date' => $newEndDate->format('Y-m-d'),
            'additional_amount' => $additionalAmount,
        ]);
    }

    public function requestReturn(Request $request, int $orderId)
    {
        $order = Order::where('user_id', $request->user()->id)->findOrFail($orderId);
        $order->update([
            'status' => 'return_requested',
            'actual_return_date' => now(),
        ]);

        Notification::send(
            $request->user()->id,
            'return_requested',
            'Return Request Received',
            "Return request for Order #{$order->order_number} received. Security deposit will be refunded after inspection.",
            route('customer.rentals'),
            'fa-truck-ramp-box'
        );

        return response()->json([
            'success' => true,
            'message' => 'Return request submitted successfully. Our team will verify vehicle condition.',
        ]);
    }

    public function wishlist(Request $request)
    {
        $wishlists = Wishlist::with('product.images')->where('user_id', $request->user()->id)->latest()->get();
        
        return response()->json([
            'success' => true,
            'count' => $wishlists->count(),
            'items' => $wishlists->map(fn($w) => [
                'id' => $w->id,
                'product_id' => $w->product_id,
                'name' => $w->product->name ?? 'Product',
                'slug' => $w->product->slug ?? '',
                'price' => $w->product ? (float) $w->product->effective_price : 0,
                'image' => $w->product ? $w->product->primary_image_url : null,
            ])
        ]);
    }

    public function toggleWishlist(Request $request, int $productId)
    {
        $userId = $request->user()->id;
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

        return response()->json([
            'success' => true,
            'added' => $added,
            'message' => $msg,
            'count' => Wishlist::where('user_id', $userId)->count(),
        ]);
    }

    private function formatOrder($o, bool $fullDetails = false)
    {
        $data = [
            'id' => $o->id,
            'order_number' => $o->order_number,
            'type' => $o->type,
            'status' => $o->status,
            'payment_status' => $o->payment_status,
            'total_amount' => (float) $o->total_amount,
            'advance_amount' => (float) $o->advance_amount,
            'remaining_amount' => (float) $o->remaining_amount,
            'security_deposit_total' => (float) $o->security_deposit_total,
            'created_at' => $o->created_at ? $o->created_at->format('d M Y') : '',
            'items' => $o->items->map(fn($i) => [
                'id' => $i->id,
                'product_id' => $i->product_id,
                'product_name' => $i->product_name,
                'type' => $i->item_type,
                'quantity' => (int) $i->quantity,
                'unit_price' => (float) $i->unit_price,
                'subtotal' => (float) $i->subtotal,
                'rental_start_date' => $i->rental_start_date ? $i->rental_start_date->format('Y-m-d') : null,
                'rental_end_date' => $i->rental_end_date ? $i->rental_end_date->format('Y-m-d') : null,
                'rental_days' => (int) $i->rental_days,
                'ebike_unit_code' => $i->ebikeUnit->ebike_code ?? null,
            ])
        ];

        if ($fullDetails) {
            $data['shipping_address'] = $o->shipping_address;
            $data['fulfillment_type'] = $o->fulfillment_type;
            $data['pickup_location'] = $o->pickup_location;
        }

        return $data;
    }
}
