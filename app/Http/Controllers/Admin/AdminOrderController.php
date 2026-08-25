<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\EBikeUnit;
use App\Models\Payment;
use Illuminate\Support\Str;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product', 'items.ebikeUnit']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('order_number', 'like', "%{$request->search}%");
        }

        $orders = $query->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(int $id)
    {
        $order = Order::with(['user', 'items.product', 'items.ebikeUnit', 'payments'])->findOrFail($id);
        $availableUnits = EBikeUnit::where('status', 'available')->get();
        return view('admin.orders.show', compact('order', 'availableUnits'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'status' => 'required|string',
            'admin_notes' => 'nullable|string',
            'late_fee_charged' => 'nullable|numeric|min:0',
            'damage_fee_charged' => 'nullable|numeric|min:0',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update([
            'status' => $newStatus,
            'admin_notes' => $request->admin_notes ?? $order->admin_notes,
            'late_fee_charged' => $request->late_fee_charged ?? $order->late_fee_charged,
            'damage_fee_charged' => $request->damage_fee_charged ?? $order->damage_fee_charged,
        ]);

        // If returned or completed, release physical E-Bike units back to available status
        if (in_array($newStatus, ['returned', 'completed', 'cancelled'])) {
            foreach ($order->items as $item) {
                if ($item->ebike_unit_id) {
                    $unit = EBikeUnit::find($item->ebike_unit_id);
                    if ($unit) {
                        $unit->update(['status' => 'available']);
                    }
                }
            }
        }

        return back()->with('success', "Order #{$order->order_number} status updated to " . ucfirst($newStatus));
    }

    public function assignUnit(Request $request, int $orderItemId)
    {
        $request->validate([
            'ebike_unit_id' => 'required|exists:ebike_units,id'
        ]);

        $item = OrderItem::findOrFail($orderItemId);
        $unit = EBikeUnit::findOrFail($request->ebike_unit_id);

        $item->update(['ebike_unit_id' => $unit->id]);
        $unit->update(['status' => 'rented']);

        return back()->with('success', "Physical E-Bike {$unit->ebike_code} assigned to order item.");
    }
}
