<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Coupon;
use Carbon\Carbon;

class CartController extends Controller
{
    private function getSessionId(): string
    {
        if (!session()->has('cart_session_id')) {
            session()->put('cart_session_id', bin2hex(random_bytes(16)));
        }
        return session()->get('cart_session_id');
    }

    public function index()
    {
        $cartItems = $this->getCartItems();
        $totals = $this->calculateTotals($cartItems);

        return view('cart.index', compact('cartItems', 'totals'));
    }

    public function getMiniCart()
    {
        $cartItems = $this->getCartItems();
        $totals = $this->calculateTotals($cartItems);

        return response()->json([
            'success' => true,
            'count' => $cartItems->sum('quantity'),
            'subtotal' => number_format($totals['subtotal'], 2),
            'tax' => number_format($totals['tax'], 2),
            'total' => number_format($totals['total'], 2),
            'items' => $cartItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->product->name,
                    'image' => $item->product->primary_image_url,
                    'type' => $item->item_type,
                    'quantity' => $item->quantity,
                    'subtotal' => number_format($item->subtotal, 2),
                    'rental_dates' => $item->item_type === 'rental' ? ($item->rental_start_date->format('d M') . ' - ' . $item->rental_end_date->format('d M Y')) : null,
                ];
            })
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'item_type' => 'required|in:purchase,rental',
            'quantity' => 'nullable|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id',
            'rental_start_date' => 'nullable|date',
            'rental_end_date' => 'nullable|date',
        ]);

        $product = Product::findOrFail($request->product_id);
        $sessionId = $this->getSessionId();
        $userId = auth()->id();
        $qty = $request->input('quantity', 1);

        if ($request->item_type === 'rental') {
            if (!$product->is_rental_eligible) {
                return response()->json(['success' => false, 'message' => 'Product is not available for rental.'], 422);
            }
            if (!$request->rental_start_date || !$request->rental_end_date) {
                return response()->json(['success' => false, 'message' => 'Please select rental start and end dates.'], 422);
            }

            $startDate = Carbon::parse($request->rental_start_date);
            $endDate = Carbon::parse($request->rental_end_date);
            $days = max(1, (int) ceil($startDate->diffInDays($endDate)));

            // Check availability
            $availableUnits = $product->getAvailableRentalUnitsCount($startDate->toDateString(), $endDate->toDateString());
            if ($availableUnits < $qty) {
                return response()->json(['success' => false, 'message' => 'Selected rental dates are no longer available for this E-Bike.'], 422);
            }

            $dailyRate = (float) $product->rental_price_daily;
            if ($days >= 30 && $product->rental_price_monthly) {
                $dailyRate = min($dailyRate, (float) $product->rental_price_monthly / 30);
            } elseif ($days >= 7 && $product->rental_price_weekly) {
                $dailyRate = min($dailyRate, (float) $product->rental_price_weekly / 7);
            }

            CartItem::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'product_id' => $product->id,
                'variant_id' => $request->variant_id,
                'item_type' => 'rental',
                'quantity' => $qty,
                'rental_start_date' => $startDate,
                'rental_end_date' => $endDate,
                'rental_days' => $days,
                'daily_rate' => $dailyRate,
                'security_deposit' => $product->rental_security_deposit ?? 150.00,
            ]);

            $msg = "{$product->name} rental added to cart for {$days} day(s)!";
        } else {
            // Purchase item
            $existing = CartItem::where('session_id', $sessionId)
                ->where('product_id', $product->id)
                ->where('variant_id', $request->variant_id)
                ->where('item_type', 'purchase')
                ->first();

            if ($existing) {
                $existing->increment('quantity', $qty);
            } else {
                CartItem::create([
                    'session_id' => $sessionId,
                    'user_id' => $userId,
                    'product_id' => $product->id,
                    'variant_id' => $request->variant_id,
                    'item_type' => 'purchase',
                    'quantity' => $qty,
                ]);
            }

            $msg = "{$product->name} added to cart!";
        }

        $cartItems = $this->getCartItems();
        $totals = $this->calculateTotals($cartItems);

        return response()->json([
            'success' => true,
            'message' => $msg,
            'cart_count' => $cartItems->sum('quantity'),
            'totals' => $totals,
        ]);
    }

    public function updateQuantity(Request $request, int $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cartItem = CartItem::where('session_id', $this->getSessionId())->findOrFail($id);
        $cartItem->update(['quantity' => $request->quantity]);

        $cartItems = $this->getCartItems();
        $totals = $this->calculateTotals($cartItems);

        return response()->json([
            'success' => true,
            'item_subtotal' => number_format($cartItem->subtotal, 2),
            'totals' => $totals,
            'cart_count' => $cartItems->sum('quantity'),
        ]);
    }

    public function remove(int $id)
    {
        CartItem::where('session_id', $this->getSessionId())->where('id', $id)->delete();
        $cartItems = $this->getCartItems();
        $totals = $this->calculateTotals($cartItems);

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart.',
            'totals' => $totals,
            'cart_count' => $cartItems->sum('quantity'),
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $coupon = Coupon::where('code', strtoupper($request->code))->first();
        $cartItems = $this->getCartItems();
        $subtotal = $cartItems->sum(fn($i) => $i->subtotal);

        if (!$coupon || !$coupon->isValidFor($subtotal)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired coupon code.'
            ], 422);
        }

        session()->put('applied_coupon', [
            'code' => $coupon->code,
            'amount' => $coupon->calculateDiscount($subtotal),
        ]);

        $totals = $this->calculateTotals($cartItems);

        return response()->json([
            'success' => true,
            'message' => "Coupon '{$coupon->code}' applied successfully!",
            'discount' => number_format($totals['discount'], 2),
            'totals' => $totals,
        ]);
    }

    private function getCartItems()
    {
        return CartItem::with(['product.images', 'variant'])
            ->where('session_id', $this->getSessionId())
            ->get();
    }

    private function calculateTotals($cartItems): array
    {
        $subtotal = (float) $cartItems->sum(fn($item) => $item->subtotal);
        $securityDeposit = (float) $cartItems->where('item_type', 'rental')->sum(fn($item) => $item->security_deposit * $item->quantity);
        
        $discount = 0.00;
        if (session()->has('applied_coupon')) {
            $discount = (float) session('applied_coupon.amount');
        }

        $taxable = max(0, $subtotal - $discount);
        $tax = round($taxable * 0.20, 2); // UK 20% VAT
        $delivery = $subtotal > 0 ? 15.00 : 0.00; // £15 standard UK delivery, free over £500
        if ($subtotal >= 500) {
            $delivery = 0.00;
        }

        $total = $taxable + $tax + $delivery + $securityDeposit;

        // Advance 30% calculation if selected
        $advance30 = round(($taxable + $tax + $delivery) * 0.30 + $securityDeposit, 2);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'delivery' => $delivery,
            'security_deposit' => $securityDeposit,
            'total' => $total,
            'advance_30' => $advance30,
            'remaining_balance' => round($total - $advance30, 2),
        ];
    }
}
