<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\EBikeUnit;
use App\Models\ProductDamageLog;
use App\Models\MaintenanceRecord;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminRentalLogController extends Controller
{
    /**
     * Display a listing of all rental products with status, earnings, repair expenses, & user damage summaries.
     */
    public function index(Request $request)
    {
        $query = Product::where('is_rental_eligible', true)
            ->orWhere('product_tag', 'rent');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $products = $query->with(['ebikeUnits', 'damageLogs', 'images'])->latest()->paginate(10);

        // Calculate System-Wide Aggregate Metrics
        $totalRentalProducts = Product::where('is_rental_eligible', true)->orWhere('product_tag', 'rent')->count();
        $totalRentalOrders = OrderItem::where('item_type', 'rental')->count();
        $totalRentalEarnings = (float) OrderItem::where('item_type', 'rental')->sum('subtotal');
        $totalDamageRepairCost = (float) ProductDamageLog::sum('repair_cost') + (float) MaintenanceRecord::sum('cost');
        $totalUserChargesPending = (float) ProductDamageLog::where('user_payment_status', 'pending')->sum('user_charge_amount');
        $totalUserChargesPaid = (float) ProductDamageLog::where('user_payment_status', 'paid')->sum('user_charge_amount');

        // Augment product metrics
        foreach ($products as $prod) {
            $prod->total_units_count = $prod->ebikeUnits->count();
            $prod->available_units_count = $prod->ebikeUnits->where('status', 'available')->count();
            $prod->rented_units_count = $prod->ebikeUnits->where('status', 'rented')->count();
            $prod->maintenance_units_count = $prod->ebikeUnits->whereIn('status', ['maintenance', 'retired'])->count();
            
            $prod->rental_history_count = OrderItem::where('product_id', $prod->id)->where('item_type', 'rental')->count();
            $prod->total_earnings = (float) OrderItem::where('product_id', $prod->id)->where('item_type', 'rental')->sum('subtotal');
            
            $unitIds = $prod->ebikeUnits->pluck('id')->toArray();
            $maintCost = (float) MaintenanceRecord::whereIn('ebike_unit_id', $unitIds)->sum('cost');
            $damageCost = (float) ProductDamageLog::where('product_id', $prod->id)->sum('repair_cost');
            $prod->total_repair_cost = $maintCost + $damageCost;

            $prod->user_pending_charges = (float) ProductDamageLog::where('product_id', $prod->id)->where('user_payment_status', 'pending')->sum('user_charge_amount');
            $prod->user_paid_charges = (float) ProductDamageLog::where('product_id', $prod->id)->where('user_payment_status', 'paid')->sum('user_charge_amount');
        }

        return view('admin.rental_logs.index', compact(
            'products',
            'totalRentalProducts',
            'totalRentalOrders',
            'totalRentalEarnings',
            'totalDamageRepairCost',
            'totalUserChargesPending',
            'totalUserChargesPaid'
        ));
    }

    /**
     * Display detailed report & logs for a specific rental product.
     */
    public function show($id)
    {
        $product = Product::with(['ebikeUnits.maintenanceRecords', 'damageLogs.user', 'damageLogs.ebikeUnit', 'damageLogs.order', 'images'])
            ->findOrFail($id);

        // Rental History Log Items
        $rentalOrders = OrderItem::with(['order.user', 'ebikeUnit'])
            ->where('product_id', $product->id)
            ->where('item_type', 'rental')
            ->latest()
            ->get();

        // Damage Logs for this product
        $damageLogs = ProductDamageLog::with(['user', 'ebikeUnit', 'order'])
            ->where('product_id', $product->id)
            ->latest('incident_date')
            ->get();

        // Maintenance Records for units of this product
        $unitIds = $product->ebikeUnits->pluck('id')->toArray();
        $maintenanceRecords = MaintenanceRecord::with('ebikeUnit')
            ->whereIn('ebike_unit_id', $unitIds)
            ->latest('service_date')
            ->get();

        // Summary Calculations
        $totalEarnings = (float) $rentalOrders->sum('subtotal');
        $totalRentalsCount = $rentalOrders->count();
        $activeRentalsCount = $rentalOrders->filter(function ($item) {
            return $item->order && in_array($item->order->status, ['active', 'picked_up']);
        })->count();

        $totalShopRepairCost = (float) $damageLogs->sum('repair_cost') + (float) $maintenanceRecords->sum('cost');
        $userChargesTotal = (float) $damageLogs->sum('user_charge_amount');
        $userChargesPaid = (float) $damageLogs->where('user_payment_status', 'paid')->sum('user_charge_amount');
        $userChargesPending = (float) $damageLogs->where('user_payment_status', 'pending')->sum('user_charge_amount');

        // Customers for dropdown
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        // Active orders for dropdown
        $orders = Order::where('type', 'rental')->latest()->take(30)->get();

        // Build Chronological Activity Timeline
        $timeline = collect();

        foreach ($rentalOrders as $item) {
            $timeline->push([
                'type' => 'rental',
                'date' => $item->created_at,
                'title' => 'Rented out to ' . ($item->order->user->name ?? 'Customer'),
                'details' => "Order #{$item->order->order_number} | Duration: {$item->rental_days} days (£{$item->subtotal})",
                'status' => $item->order->status ?? 'active',
                'badge_color' => 'bg-emerald-100 text-emerald-800',
                'icon' => 'fa-bicycle',
            ]);
        }

        foreach ($damageLogs as $dmg) {
            $timeline->push([
                'type' => 'damage',
                'date' => $dmg->incident_date,
                'title' => 'Damage Reported: ' . $dmg->damage_type,
                'details' => "User: " . ($dmg->user->name ?? 'Unknown') . " | Repair Cost: £" . number_format($dmg->repair_cost, 2) . " | User Liability: £" . number_format($dmg->user_charge_amount, 2) . " ({$dmg->user_payment_status})",
                'status' => $dmg->repair_status,
                'badge_color' => 'bg-rose-100 text-rose-800',
                'icon' => 'fa-triangle-exclamation',
            ]);
        }

        foreach ($maintenanceRecords as $maint) {
            $timeline->push([
                'type' => 'maintenance',
                'date' => $maint->service_date,
                'title' => 'Maintenance: ' . ucfirst($maint->service_type),
                'details' => "Unit: " . ($maint->ebikeUnit->ebike_code ?? 'Fleet Unit') . " | Cost: £" . number_format($maint->cost, 2) . " | Tech: " . ($maint->technician_name ?? 'N/A'),
                'status' => $maint->status,
                'badge_color' => 'bg-amber-100 text-amber-800',
                'icon' => 'fa-wrench',
            ]);
        }

        $sortedTimeline = $timeline->sortByDesc('date')->values();

        return view('admin.rental_logs.show', compact(
            'product',
            'rentalOrders',
            'damageLogs',
            'maintenanceRecords',
            'totalEarnings',
            'totalRentalsCount',
            'activeRentalsCount',
            'totalShopRepairCost',
            'userChargesTotal',
            'userChargesPaid',
            'userChargesPending',
            'customers',
            'orders',
            'sortedTimeline'
        ));
    }

    /**
     * Update unit or product status manually.
     */
    public function updateProductStatus(Request $request, $id)
    {
        $request->validate([
            'unit_id' => 'required|exists:ebike_units,id',
            'status' => 'required|in:available,rented,maintenance,retired',
            'condition_notes' => 'nullable|string',
        ]);

        $unit = EBikeUnit::findOrFail($request->unit_id);
        $unit->update([
            'status' => $request->status,
            'condition_notes' => $request->condition_notes ?? $unit->condition_notes,
        ]);

        return back()->with('success', "Unit {$unit->ebike_code} status updated to " . ucfirst($request->status));
    }

    /**
     * Store a new Damage / User Liability Record.
     */
    public function storeDamageLog(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'ebike_unit_id' => 'nullable|exists:ebike_units,id',
            'user_id' => 'nullable|exists:users,id',
            'order_id' => 'nullable|exists:orders,id',
            'damage_type' => 'required|string|max:100',
            'incident_date' => 'required|date',
            'damage_description' => 'required|string',
            'repair_cost' => 'required|numeric|min:0',
            'user_charge_amount' => 'required|numeric|min:0',
            'user_payment_status' => 'required|in:pending,paid,waived',
            'repair_status' => 'required|in:under_repair,repaired,written_off',
            'repair_start_date' => 'nullable|date',
            'repair_completion_date' => 'nullable|date',
            'admin_notes' => 'nullable|string',
        ]);

        $damageLog = ProductDamageLog::create($request->all());

        // Auto lock unit if under repair
        if ($request->filled('ebike_unit_id')) {
            $unit = EBikeUnit::find($request->ebike_unit_id);
            if ($unit) {
                if ($request->repair_status === 'under_repair') {
                    $unit->update(['status' => 'maintenance']);
                } elseif ($request->repair_status === 'repaired') {
                    $unit->update(['status' => 'available']);
                } elseif ($request->repair_status === 'written_off') {
                    $unit->update(['status' => 'retired']);
                }
            }
        }

        return back()->with('success', 'Damage & User Liability log recorded successfully.');
    }

    /**
     * Update an existing Damage / Recovery Log entry.
     */
    public function updateDamageLog(Request $request, $id)
    {
        $log = ProductDamageLog::findOrFail($id);

        $request->validate([
            'damage_type' => 'required|string|max:100',
            'repair_cost' => 'required|numeric|min:0',
            'user_charge_amount' => 'required|numeric|min:0',
            'user_payment_status' => 'required|in:pending,paid,waived',
            'repair_status' => 'required|in:under_repair,repaired,written_off',
            'repair_start_date' => 'nullable|date',
            'repair_completion_date' => 'nullable|date',
            'admin_notes' => 'nullable|string',
        ]);

        $log->update($request->all());

        // Update physical unit status accordingly
        if ($log->ebike_unit_id) {
            $unit = EBikeUnit::find($log->ebike_unit_id);
            if ($unit) {
                if ($request->repair_status === 'under_repair') {
                    $unit->update(['status' => 'maintenance']);
                } elseif ($request->repair_status === 'repaired') {
                    $unit->update(['status' => 'available']);
                } elseif ($request->repair_status === 'written_off') {
                    $unit->update(['status' => 'retired']);
                }
            }
        }

        return back()->with('success', 'Damage log & user recovery status updated successfully.');
    }

    /**
     * Delete a damage log entry.
     */
    public function destroyDamageLog($id)
    {
        $log = ProductDamageLog::findOrFail($id);
        $log->delete();

        return back()->with('success', 'Damage log entry deleted.');
    }

    /**
     * Store maintenance record directly from product details.
     */
    public function storeMaintenanceLog(Request $request)
    {
        $request->validate([
            'ebike_unit_id' => 'required|exists:ebike_units,id',
            'service_type' => 'required|in:routine,repair,inspection,battery_check,brake_service',
            'service_date' => 'required|date',
            'next_service_date' => 'nullable|date',
            'cost' => 'required|numeric|min:0',
            'technician_name' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
        ]);

        MaintenanceRecord::create($request->all());

        $unit = EBikeUnit::findOrFail($request->ebike_unit_id);
        if (in_array($request->status, ['scheduled', 'in_progress'])) {
            $unit->update(['status' => 'maintenance']);
        } elseif ($request->status === 'completed') {
            $unit->update(['status' => 'available']);
        }

        return back()->with('success', 'Maintenance record logged and unit status updated.');
    }
}
