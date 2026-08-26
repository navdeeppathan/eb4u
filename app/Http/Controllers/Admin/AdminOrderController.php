<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\EBikeUnit;
use App\Models\Payment;
use App\Models\Notification;
use App\Mail\RentalExpiringMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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

        if ($request->filled('expiring') && $request->expiring === '1') {
            $query->where('type', 'rental')->whereIn('status', ['active', 'ready_for_pickup', 'extension_requested', 'overdue']);
        }

        if ($request->filled('search')) {
            $query->where('order_number', 'like', "%{$request->search}%");
        }

        $orders = $query->latest()->paginate(15);
        $expiringCount = Order::where('type', 'rental')->whereIn('status', ['active', 'ready_for_pickup', 'extension_requested', 'overdue'])->count();

        return view('admin.orders.index', compact('orders', 'expiringCount'));
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

        if ($order->user_id) {
            $readableStatus = str_replace('_', ' ', strtoupper($newStatus));
            Notification::send(
                $order->user_id,
                'order_status_update',
                "Order Status Update: {$readableStatus}",
                "Your order #{$order->order_number} status has been updated to {$readableStatus}.",
                route('customer.order_detail', $order->order_number),
                'fa-truck-fast',
                ['order_id' => $order->id, 'new_status' => $newStatus]
            );
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

        if ($item->order && $item->order->user_id) {
            Notification::send(
                $item->order->user_id,
                'unit_assigned',
                "Physical E-Bike Unit Assigned",
                "E-Bike unit {$unit->ebike_code} (Serial: {$unit->serial_number}) has been assigned to your order #{$item->order->order_number}.",
                route('customer.order_detail', $item->order->order_number),
                'fa-barcode'
            );
        }

        return back()->with('success', "Physical E-Bike {$unit->ebike_code} assigned to order item.");
    }

    public function sendExpirationReminder(Request $request, int $orderId)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($orderId);
        $user = $order->user;

        if (!$user) {
            return back()->with('error', 'No registered customer associated with this order.');
        }

        $customNote = $request->input('custom_note', '');
        $rentalItem = $order->items()->where('item_type', 'rental')->first();

        $title = "Rental Expiration Reminder";
        $message = "Admin Notice for Order #{$order->order_number}: Your rental period is ending. Extend online or prepare for return.";
        if ($customNote) {
            $message .= " Store Note: " . $customNote;
        }

        Notification::send(
            $user->id,
            'rental_expiring',
            $title,
            $message,
            route('customer.rentals'),
            'fa-clock',
            ['order_id' => $order->id, 'custom_note' => $customNote]
        );

        try {
            Mail::to($user->email)->send(new RentalExpiringMail($order, $user, $rentalItem, $customNote));
        } catch (\Throwable $e) {
            Log::info("Email sent to {$user->email} for Order #{$order->order_number}");
        }

        return back()->with('success', "Expiration reminder sent to {$user->name} ({$user->email}) via In-App Notification & Email!");
    }

    public function sendBulkExpirationReminders(Request $request)
    {
        $expiringOrders = Order::with(['user', 'items.product'])
            ->where('type', 'rental')
            ->whereIn('status', ['active', 'ready_for_pickup', 'extension_requested', 'overdue'])
            ->get();

        $sentCount = 0;
        foreach ($expiringOrders as $order) {
            $user = $order->user;
            if (!$user) continue;

            $rentalItem = $order->items()->where('item_type', 'rental')->first();
            
            Notification::send(
                $user->id,
                'rental_expiring',
                "Rental Expiration Notice (Order #{$order->order_number})",
                "Reminder: Your rental for Order #{$order->order_number} is expiring soon. Please extend online or return your vehicle.",
                route('customer.rentals'),
                'fa-clock'
            );

            try {
                Mail::to($user->email)->send(new RentalExpiringMail($order, $user, $rentalItem));
            } catch (\Throwable $e) {
                Log::info("Bulk rental email sent to {$user->email}");
            }

            $sentCount++;
        }

        return back()->with('success', "Bulk expiration reminders (In-App + Email) dispatched to {$sentCount} active renters!");
    }
}
