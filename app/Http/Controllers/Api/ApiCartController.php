<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\SystemSetting;
use Carbon\Carbon;

class ApiCartController extends Controller
{
    private function getCartSessionId(Request $request): string
    {
        if ($request->user()) {
            return 'user_' . $request->user()->id;
        }
        return $request->header('X-Session-ID') ?: session()->get('cart_session_id', 'guest_api_session');
    }

    public function index(Request $request)
    {
        $sessionId = $this->getCartSessionId($request);
        $cartItems = CartItem::with(['product', 'variant'])->where('session_id', $sessionId)->get();

        $subtotal = (float) $cartItems->sum(fn($i) => $i->subtotal);
        $depositTotal = (float) $cartItems->where('item_type', 'rental')->sum(fn($i) => $i->security_deposit * $i->quantity);
        $discount = session()->has('applied_coupon') ? (float) session('applied_coupon.amount') : 0.00;
        $taxable = max(0, $subtotal - $discount);
        $tax = round($taxable * 0.20, 2);
        $delivery = ($subtotal >= 500 || $subtotal == 0) ? 0.00 : 15.00;
        $total = $taxable + $tax + $delivery + $depositTotal;

        $advancePct = SystemSetting::get('rental_advance_percentage', 30);
        $advanceAmount = round(($taxable + $tax + $delivery) * ($advancePct / 100) + $depositTotal, 2);

        $itemsFormatted = $cartItems->map(function ($c) {
            return [
                'id' => $c->id,
                'product_id' => $c->product_id,
                'variant_id' => $c->variant_id,
                'name' => $c->product->name ?? 'E-Bike Item',
                'type' => $c->item_type,
                'quantity' => (int) $c->quantity,
                'daily_rate' => (float) $c->daily_rate,
                'subtotal' => (float) $c->subtotal,
                'security_deposit' => (float) $c->security_deposit,
                'rental_start_date' => $c->rental_start_date ? $c->rental_start_date->format('Y-m-d') : null,
                'rental_end_date' => $c->rental_end_date ? $c->rental_end_date->format('Y-m-d') : null,
                'rental_days' => (int) $c->rental_days,
                'rental_dates_formatted' => ($c->rental_start_date && $c->rental_end_date) 
                    ? $c->rental_start_date->format('d M Y') . ' - ' . $c->rental_end_date->format('d M Y') . ' (' . $c->rental_days . ' days)'
                    : null,
                'image' => $c->product ? $c->product->primary_image_url : null,
            ];
        });

        return response()->json([
            'success' => true,
            'count' => (int) $cartItems->sum('quantity'),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'delivery_fee' => $delivery,
            'deposit_total' => $depositTotal,
            'discount' => $discount,
            'total' => $total,
            'advance_30_percent' => $advanceAmount,
            'items' => $itemsFormatted,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'item_type' => 'required|in:purchase,rental',
            'quantity' => 'nullable|integer|min:1',
            'rental_start_date' => 'nullable|required_if:item_type,rental|date',
            'rental_end_date' => 'nullable|required_if:item_type,rental|date|after:rental_start_date',
        ]);

        $product = Product::findOrFail($request->product_id);
        $sessionId = $this->getCartSessionId($request);
        $itemType = $request->item_type;
        $qty = (int) ($request->quantity ?: 1);

        $dailyRate = 0.00;
        $rentalDays = 0;
        $subtotal = 0.00;
        $startDate = null;
        $endDate = null;
        $deposit = 0.00;

        if ($itemType === 'rental') {
            if (!$product->is_rental_eligible) {
                return response()->json(['success' => false, 'message' => 'This product is not available for rental.'], 422);
            }

            $startDate = Carbon::parse($request->rental_start_date);
            $endDate = Carbon::parse($request->rental_end_date);
            $rentalDays = (int) max(1, $startDate->diffInDays($endDate));
            $dailyRate = (float) $product->rental_price_daily;

            if ($rentalDays >= 30) {
                $dailyRate = round($dailyRate * 0.70, 2);
            } elseif ($rentalDays >= 7) {
                $dailyRate = round($dailyRate * 0.85, 2);
            }

            $subtotal = round($dailyRate * $rentalDays * $qty, 2);
            $deposit = (float) ($product->rental_security_deposit ?? 150.00);
        } else {
            $subtotal = round($product->effective_price * $qty, 2);
        }

        $cartItem = CartItem::create([
            'session_id' => $sessionId,
            'product_id' => $product->id,
            'variant_id' => $request->variant_id,
            'item_type' => $itemType,
            'quantity' => $qty,
            'daily_rate' => $dailyRate,
            'rental_start_date' => $startDate,
            'rental_end_date' => $endDate,
            'rental_days' => $rentalDays,
            'security_deposit' => $deposit,
            'subtotal' => $subtotal,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item added to basket successfully.',
            'item_id' => $cartItem->id,
        ]);
    }

    public function updateQuantity(Request $request, int $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $sessionId = $this->getCartSessionId($request);
        $cartItem = CartItem::where('session_id', $sessionId)->findOrFail($id);

        $qty = (int) $request->quantity;
        if ($cartItem->item_type === 'rental') {
            $subtotal = round($cartItem->daily_rate * $cartItem->rental_days * $qty, 2);
        } else {
            $subtotal = round($cartItem->product->effective_price * $qty, 2);
        }

        $cartItem->update([
            'quantity' => $qty,
            'subtotal' => $subtotal,
        ]);

        return response()->json(['success' => true, 'message' => 'Basket updated.']);
    }

    public function remove(Request $request, int $id)
    {
        $sessionId = $this->getCartSessionId($request);
        CartItem::where('session_id', $sessionId)->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Item removed from basket.']);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);
        $coupon = Coupon::where('code', strtoupper(trim($request->coupon_code)))
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired coupon code.'], 422);
        }

        return response()->json([
            'success' => true,
            'message' => "Coupon '{$coupon->code}' applied successfully!",
            'discount_amount' => (float) $coupon->discount_amount,
        ]);
    }
}
