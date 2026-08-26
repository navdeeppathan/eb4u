<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\EBikeUnit;
use App\Models\SystemSetting;
use App\Models\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ApiCheckoutController extends Controller
{
    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:30',
            'fulfillment_type' => 'nullable|string|in:delivery,pickup',
            'payment_type' => 'nullable|string|in:advance,full',
            'address_line_1' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postcode' => 'nullable|string|max:20',
        ]);

        $user = $request->user();
        $sessionId = 'user_' . $user->id;
        $cartItems = CartItem::with('product')->where('session_id', $sessionId)->get();

        if ($cartItems->isEmpty()) {
            // Fallback check guest session header
            $guestSession = $request->header('X-Session-ID') ?: session()->get('cart_session_id', '');
            $cartItems = CartItem::with('product')->where('session_id', $guestSession)->get();
        }

        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Your basket is empty.'], 422);
        }

        DB::beginTransaction();
        try {
            $customerName = $request->customer_name;
            $customerEmail = $request->customer_email;
            $customerPhone = $request->customer_phone;
            $fulfillmentType = $request->fulfillment_type ?: 'delivery';
            $paymentType = $request->payment_type ?: 'advance';

            $subtotal = (float) $cartItems->sum(fn($i) => $i->subtotal);
            $depositTotal = (float) $cartItems->where('item_type', 'rental')->sum(fn($i) => $i->security_deposit * $i->quantity);
            $discount = 0.00;
            $taxable = max(0, $subtotal - $discount);
            $tax = round($taxable * 0.20, 2);
            $delivery = ($fulfillmentType === 'pickup' || $subtotal >= 500) ? 0.00 : 15.00;
            $total = $taxable + $tax + $delivery + $depositTotal;

            $hasRental = $cartItems->contains('item_type', 'rental');
            $hasPurchase = $cartItems->contains('item_type', 'purchase');
            $orderType = ($hasRental && $hasPurchase) ? 'mixed' : ($hasRental ? 'rental' : 'purchase');

            $advancePct = SystemSetting::get('rental_advance_percentage', 30);
            
            if ($paymentType === 'advance' && $hasRental) {
                $payNow = round(($taxable + $tax + $delivery) * ($advancePct / 100) + $depositTotal, 2);
                $remaining = max(0, round($total - $payNow, 2));
                $paymentStatus = 'partially_paid';
            } else {
                $payNow = $total;
                $remaining = 0.00;
                $paymentStatus = 'paid';
            }

            $orderNumber = 'UK-' . strtoupper($orderType === 'rental' ? 'RNT' : 'ORD') . '-' . date('Y') . '-' . rand(1000, 9999);

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'type' => $orderType,
                'status' => $hasRental ? 'active' : 'confirmed',
                'payment_status' => $paymentStatus,
                'payment_type' => $paymentType,
                'advance_percentage' => $paymentType === 'advance' ? $advancePct : 100.00,
                'advance_amount' => $payNow,
                'remaining_amount' => $remaining,
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'delivery_fee' => $delivery,
                'security_deposit_total' => $depositTotal,
                'discount_amount' => $discount,
                'total_amount' => $total,
                'fulfillment_type' => $fulfillmentType,
                'shipping_address' => [
                    'name' => $customerName,
                    'phone' => $customerPhone,
                    'email' => $customerEmail,
                    'address_line_1' => $request->address_line_1 ?: '24 Kensington High Street',
                    'city' => $request->city ?: 'London',
                    'postcode' => $request->postcode ?: 'W8 6AG',
                    'country' => 'United Kingdom',
                ],
                'pickup_location' => $fulfillmentType === 'pickup' ? 'Flagship Store - 142 Regent Street, London' : null,
                'customer_notes' => $request->customer_notes,
            ]);

            // Process items & Assign Physical E-Bike units for rentals
            foreach ($cartItems as $cItem) {
                $assignedUnit = null;

                if ($cItem->item_type === 'rental') {
                    $unit = EBikeUnit::where('product_id', $cItem->product_id)
                        ->where('status', 'available')
                        ->first() ?: EBikeUnit::where('product_id', $cItem->product_id)->first();

                    if ($unit) {
                        $unit->update(['status' => 'rented']);
                        $assignedUnit = $unit->id;
                    }
                } else {
                    if ($cItem->product && $cItem->product->stock_quantity > 0) {
                        $cItem->product->decrement('stock_quantity', min($cItem->product->stock_quantity, $cItem->quantity));
                    }
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cItem->product_id,
                    'variant_id' => $cItem->variant_id,
                    'ebike_unit_id' => $assignedUnit,
                    'item_type' => $cItem->item_type,
                    'product_name' => $cItem->product->name ?? 'E-Bike',
                    'variant_name' => $cItem->variant ? $cItem->variant->name : null,
                    'unit_price' => $cItem->item_type === 'rental' ? $cItem->daily_rate : ($cItem->product->effective_price ?? 0),
                    'quantity' => $cItem->quantity,
                    'subtotal' => $cItem->subtotal,
                    'rental_start_date' => $cItem->rental_start_date,
                    'rental_end_date' => $cItem->rental_end_date,
                    'rental_days' => $cItem->rental_days,
                    'rental_rate' => $cItem->daily_rate,
                    'security_deposit' => $cItem->security_deposit ?? 0.00,
                ]);
            }

            // Record Payment
            Payment::create([
                'order_id' => $order->id,
                'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
                'payment_method' => 'card',
                'amount' => $payNow,
                'type' => $paymentType === 'advance' ? 'advance' : 'full',
                'status' => 'completed',
                'notes' => "Flutter Mobile App Checkout (£{$payNow})",
            ]);

            // Clear Cart
            CartItem::whereIn('session_id', [$sessionId, $request->header('X-Session-ID')])->delete();

            // Dispatch Notifications
            Notification::send(
                $user->id,
                'order_placed',
                'Order Placed Successfully!',
                "Thank you! Your order #{$order->order_number} has been placed. Total paid: £" . number_format($payNow, 2),
                route('customer.order_detail', $order->order_number),
                'fa-bag-shopping',
                ['order_id' => $order->id, 'order_number' => $order->order_number]
            );

            if ($hasRental) {
                Notification::send(
                    $user->id,
                    'rental_booked',
                    'E-Bike Rental Confirmed',
                    "Your E-Bike rental (Order #{$order->order_number}) is active. Check your app for pickup/delivery details.",
                    route('customer.rentals'),
                    'fa-bicycle',
                    ['order_id' => $order->id]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Checkout completed successfully!',
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'type' => $order->type,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'paid_now' => $payNow,
                    'remaining_balance' => $remaining,
                    'total_amount' => $total,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Checkout failed: ' . $e->getMessage()], 500);
        }
    }
}
