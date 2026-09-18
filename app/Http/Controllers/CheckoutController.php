<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Storage;

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
        $delivery = 0.00; // Store Pickup Only (Free)
        $total = $taxable + $tax + $delivery + $depositTotal;

        $hasRental = $cartItems->contains('item_type', 'rental');

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
            'hasRental',
            'user',
            'addresses'
        ));
    }

    public function process(Request $request)
    {
        $cartItems = CartItem::with('product')
            ->where('session_id', $this->getSessionId())
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.'], 422);
        }

        $hasRental = $cartItems->contains('item_type', 'rental');

        $rules = [
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:30',
            'fulfillment_type' => 'nullable|string',
            'payment_type' => 'nullable|string',
        ];

        if ($hasRental) {
            $rules['proof_of_id'] = 'required|file|mimes:jpeg,jpg,png,webp,pdf|max:2048';
            $rules['proof_of_address'] = 'required|file|mimes:jpeg,jpg,png,webp,pdf|max:2048';
        } else {
            $rules['proof_of_id'] = 'nullable|file|mimes:jpeg,jpg,png,webp,pdf|max:2048';
            $rules['proof_of_address'] = 'nullable|file|mimes:jpeg,jpg,png,webp,pdf|max:2048';
        }

        $request->validate($rules, [
            'proof_of_id.required' => 'Proof of ID (Passport, UK BRP/Visa, or Driving License) is required for e-bike rental checkout.',
            'proof_of_id.mimes' => 'Proof of ID must be an image (JPG, PNG, WEBP) or PDF file.',
            'proof_of_id.max' => 'Proof of ID file size cannot exceed 2MB.',
            'proof_of_address.required' => 'UK Proof of Address (Utility bill, Bank statement, Council tax) is required for e-bike rental checkout.',
            'proof_of_address.mimes' => 'Proof of Address must be an image (JPG, PNG, WEBP) or PDF file.',
            'proof_of_address.max' => 'Proof of Address file size cannot exceed 2MB.',
        ]);

        foreach ($cartItems->where('item_type', 'rental') as $rItem) {
            if ($rItem->rental_days < 14) {
                return response()->json([
                    'success' => false,
                    'message' => "The rental product '{$rItem->product->name}' has a duration of less than 2 weeks (14 days). Minimum rental booking duration is 14 days."
                ], 422);
            }
        }

        DB::beginTransaction();
        try {
            // Handle Document File Uploads
            $proofOfIdPath = null;
            if ($request->hasFile('proof_of_id')) {
                $file = $request->file('proof_of_id');
                $filename = 'id_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $proofOfIdPath = $file->storeAs('verification_documents', $filename, 'public');
            }

            $proofOfAddressPath = null;
            if ($request->hasFile('proof_of_address')) {
                $file = $request->file('proof_of_address');
                $filename = 'address_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $proofOfAddressPath = $file->storeAs('verification_documents', $filename, 'public');
            }

            $user = auth()->user();
            $customerName = $request->customer_name ?: ($user->name ?? 'James Harrison');
            $customerEmail = $request->customer_email ?: ($user->email ?? 'james@example.co.uk');
            $customerPhone = $request->customer_phone ?: ($user->phone ?? '+44 7700 900077');
            $fulfillmentType = 'pickup';
            $paymentType = 'full';

            $subtotal = (float) $cartItems->sum(fn($i) => $i->subtotal);
            $depositTotal = (float) $cartItems->where('item_type', 'rental')->sum(fn($i) => $i->security_deposit * $i->quantity);
            $discount = session()->has('applied_coupon') ? (float) session('applied_coupon.amount') : 0.00;
            $taxable = max(0, $subtotal - $discount);
            $tax = round($taxable * 0.20, 2);
            $delivery = 0.00;
            $total = $taxable + $tax + $delivery + $depositTotal;

            $hasPurchase = $cartItems->contains('item_type', 'purchase');
            $orderType = ($hasRental && $hasPurchase) ? 'mixed' : ($hasRental ? 'rental' : 'purchase');

            $payNow = $total;
            $remaining = 0.00;
            $paymentStatus = 'paid';

            $orderNumber = 'UK-' . strtoupper($orderType === 'rental' ? 'RNT' : 'ORD') . '-' . date('Y') . '-' . rand(1000, 9999);

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => auth()->id(),
                'type' => $orderType,
                'status' => $hasRental ? 'active' : 'confirmed',
                'payment_status' => $paymentStatus,
                'payment_type' => $paymentType,
                'advance_percentage' => 100.00,
                'advance_amount' => $payNow,
                'remaining_amount' => $remaining,
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'delivery_fee' => $delivery,
                'security_deposit_total' => $depositTotal,
                'discount_amount' => $discount,
                'total_amount' => $total,
                'coupon_code' => session('applied_coupon.code'),
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
                'pickup_location' => $fulfillmentType === 'pickup' ? SystemSetting::get('store_address', 'Near, 103 Inwood Rd, Hounslow TW3 1XA') : null,
                'customer_notes' => $request->customer_notes,
                'proof_of_id_path' => $proofOfIdPath,
                'proof_of_address_path' => $proofOfAddressPath,
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
                    if ($cItem->product->stock_quantity > 0) {
                        $cItem->product->decrement('stock_quantity', min($cItem->product->stock_quantity, $cItem->quantity));
                    }
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

            // Record Payment directly as completed
            Payment::create([
                'order_id' => $order->id,
                'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
                'payment_method' => 'card',
                'amount' => $payNow,
                'type' => $paymentType === 'advance' ? 'advance' : 'full',
                'status' => 'completed',
                'notes' => "Successful order checkout (£{$payNow})",
            ]);

            // Clear Cart & Coupon
            CartItem::where('session_id', $this->getSessionId())->delete();
            session()->forget('applied_coupon');

            // Dispatch Notifications to User
            if (auth()->check()) {
                Notification::send(
                    auth()->id(),
                    'order_placed',
                    'Order Placed Successfully!',
                    "Thank you! Your order #{$order->order_number} has been confirmed. Total paid: £" . number_format($payNow, 2),
                    route('customer.order_detail', $order->order_number),
                    'fa-bag-shopping',
                    ['order_id' => $order->id, 'order_number' => $order->order_number]
                );

                if ($hasRental) {
                    Notification::send(
                        auth()->id(),
                        'rental_booked',
                        'E-Bike Rental Confirmed',
                        "Your E-Bike rental (Order #{$order->order_number}) is active. Documents uploaded & verified.",
                        route('customer.rentals'),
                        'fa-bicycle',
                        ['order_id' => $order->id]
                    );
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'order_number' => $order->order_number,
                'message' => 'Order placed successfully! Redirecting...',
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
