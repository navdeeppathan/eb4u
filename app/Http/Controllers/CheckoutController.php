<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\EBikeUnit;
use App\Models\SystemSetting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    private function getSessionId(): string
    {
        return session()->get('cart_session_id', '');
    }

    public function index()
    {
        $cartItems = CartItem::with(['product', 'variant'])
            ->where('session_id', $this->getSessionId())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your shopping cart is currently empty.');
        }

        $subtotal = (float) $cartItems->sum(fn($i) => $i->subtotal);
        $depositTotal = (float) $cartItems->where('item_type', 'rental')->sum(fn($i) => $i->security_deposit * $i->quantity);
        $discount = session()->has('applied_coupon') ? (float) session('applied_coupon.amount') : 0.00;
        $taxable = max(0, $subtotal - $discount);
        $tax = round($taxable * 0.20, 2);
        $delivery = $subtotal >= 500 || $subtotal == 0 ? 0.00 : 15.00;
        $total = $taxable + $tax + $delivery + $depositTotal;

        $advancePct = SystemSetting::get('rental_advance_percentage', 30);
        $advanceAmount = round(($taxable + $tax + $delivery) * ($advancePct / 100) + $depositTotal, 2);
        $remainingAmount = max(0, round($total - $advanceAmount, 2));

        $user = auth()->user();
        $addresses = $user ? $user->addresses : collect();

        return view('checkout.index', compact(
            'cartItems',
            'subtotal',
            'depositTotal',
            'discount',
            'tax',
            'delivery',
            'total',
            'advancePct',
            'advanceAmount',
            'remainingAmount',
            'user',
            'addresses'
        ));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:30',
            'fulfillment_type' => 'required|in:delivery,pickup',
            'address_line_1' => 'required_if:fulfillment_type,delivery|nullable|string',
            'city' => 'required_if:fulfillment_type,delivery|nullable|string',
            'postcode' => 'required_if:fulfillment_type,delivery|nullable|string',
            'payment_type' => 'required|in:full,advance',
            'card_number' => 'required|string',
            'card_holder' => 'required|string',
            'card_expiry' => 'required|string',
            'card_cvv' => 'required|string',
        ]);

        $cartItems = CartItem::with('product')
            ->where('session_id', $this->getSessionId())
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.'], 422);
        }

        DB::beginTransaction();
        try {
            $subtotal = (float) $cartItems->sum(fn($i) => $i->subtotal);
            $depositTotal = (float) $cartItems->where('item_type', 'rental')->sum(fn($i) => $i->security_deposit * $i->quantity);
            $discount = session()->has('applied_coupon') ? (float) session('applied_coupon.amount') : 0.00;
            $taxable = max(0, $subtotal - $discount);
            $tax = round($taxable * 0.20, 2);
            $delivery = ($request->fulfillment_type === 'pickup' || $subtotal >= 500) ? 0.00 : 15.00;
            $total = $taxable + $tax + $delivery + $depositTotal;

            $hasRental = $cartItems->contains('item_type', 'rental');
            $hasPurchase = $cartItems->contains('item_type', 'purchase');
            $orderType = ($hasRental && $hasPurchase) ? 'mixed' : ($hasRental ? 'rental' : 'purchase');

            $advancePct = SystemSetting::get('rental_advance_percentage', 30);
            $paymentType = $request->payment_type;
            
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
                'user_id' => auth()->id(),
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
                'coupon_code' => session('applied_coupon.code'),
                'fulfillment_type' => $request->fulfillment_type,
                'shipping_address' => [
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                    'email' => $request->customer_email,
                    'address_line_1' => $request->address_line_1 ?? '142 Regent Street',
                    'city' => $request->city ?? 'London',
                    'postcode' => $request->postcode ?? 'W1B 5SE',
                    'country' => 'United Kingdom',
                ],
                'pickup_location' => $request->fulfillment_type === 'pickup' ? 'Flagship Store - 142 Regent Street, London' : null,
                'customer_notes' => $request->customer_notes,
            ]);

            // Process items & Assign Physical E-Bike units for rentals
            foreach ($cartItems as $cItem) {
                $assignedUnit = null;

                if ($cItem->item_type === 'rental') {
                    // Find available unit
                    $unit = EBikeUnit::where('product_id', $cItem->product_id)
                        ->where('status', 'available')
                        ->first();

                    if ($unit) {
                        $unit->update(['status' => 'rented']);
                        $assignedUnit = $unit->id;
                    }
                } else {
                    // Decrement stock
                    $cItem->product->decrement('stock_quantity', $cItem->quantity);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cItem->product_id,
                    'variant_id' => $cItem->variant_id,
                    'ebike_unit_id' => $assignedUnit,
                    'item_type' => $cItem->item_type,
                    'product_name' => $cItem->product->name,
                    'variant_name' => $cItem->variant ? $cItem->variant->name : null,
                    'unit_price' => $cItem->item_type === 'rental' ? $cItem->daily_rate : $cItem->product->effective_price,
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
                'notes' => "Payment of £{$payNow} via Card ending in " . substr($request->card_number, -4),
            ]);

            // Clear Cart & Coupon
            CartItem::where('session_id', $this->getSessionId())->delete();
            session()->forget('applied_coupon');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'redirect_url' => route('checkout.confirmation', $order->order_number),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Checkout failed: ' . $e->getMessage()], 500);
        }
    }

    public function confirmation(string $orderNumber)
    {
        $order = Order::with(['items.product', 'payments'])->where('order_number', $orderNumber)->firstOrFail();
        return view('checkout.confirmation', compact('order'));
    }
}
