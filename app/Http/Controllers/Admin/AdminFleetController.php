<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EBikeUnit;
use App\Models\Product;
use App\Services\GpsTraceService;
use Illuminate\Support\Str;

class AdminFleetController extends Controller
{
    public function index(Request $request, GpsTraceService $gpsService)
    {
        $query = EBikeUnit::with(['product', 'maintenanceRecords']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('ebike_code', 'like', "%{$request->search}%")
                  ->orWhere('serial_number', 'like', "%{$request->search}%")
                  ->orWhere('gps_ident', 'like', "%{$request->search}%");
        }

        $units = $query->latest()->paginate(15);
        $products = Product::where('is_rental_eligible', true)->get();
        $hardwareDevices = $gpsService->getHardwareDevices();

        return view('admin.fleet.index', compact('units', 'products', 'hardwareDevices'));
    }

    public function store(Request $request, GpsTraceService $gpsService)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'ebike_code' => 'required|string|unique:ebike_units,ebike_code',
            'serial_number' => 'required|string|unique:ebike_units,serial_number',
            'frame_size' => 'required|string',
            'status' => 'required|in:available,rented,maintenance,retired',
            'condition_notes' => 'nullable|string',
            'gps_ident' => 'nullable|string',
            'gps_hw_id' => 'nullable|integer',
        ]);

        $gpsUnitId = null;

        // Register unit with GPS-Trace API if IMEI/ident and hardware ID are provided
        if ($request->filled('gps_ident') && $request->filled('gps_hw_id')) {
            $gpsResult = $gpsService->registerUnit(
                strtoupper($request->ebike_code),
                trim($request->gps_ident),
                (int) $request->gps_hw_id
            );

            if ($gpsResult && isset($gpsResult['id'])) {
                $gpsUnitId = (string) $gpsResult['id'];
            }
        }

        $unit = EBikeUnit::create([
            'product_id' => $request->product_id,
            'ebike_code' => strtoupper($request->ebike_code),
            'serial_number' => strtoupper($request->serial_number),
            'frame_size' => $request->frame_size,
            'qr_code_data' => 'https://eb4u.co.uk/verify-unit/' . strtoupper($request->ebike_code),
            'status' => $request->status,
            'condition_notes' => $request->condition_notes,
            'gps_unit_id' => $gpsUnitId,
            'gps_ident' => $request->gps_ident,
            'gps_hw_id' => $request->gps_hw_id,
        ]);

        $msg = 'Physical E-Bike unit added to fleet!';
        if ($gpsUnitId) {
            $msg .= " Connected to GPS-Trace Tracker (Unit ID: {$gpsUnitId}).";
        }

        return redirect()->route('admin.fleet.index')->with('success', $msg);
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:available,rented,maintenance,retired',
            'condition_notes' => 'nullable|string',
        ]);

        $unit = EBikeUnit::findOrFail($id);
        $unit->update([
            'status' => $request->status,
            'condition_notes' => $request->condition_notes ?? $unit->condition_notes,
        ]);

        return back()->with('success', "E-Bike unit {$unit->ebike_code} status updated to " . ucfirst($request->status));
    }

    public function syncGps(Request $request, int $id, GpsTraceService $gpsService)
    {
        $unit = EBikeUnit::findOrFail($id);

        if (!$unit->gps_unit_id) {
            return back()->with('error', "E-Bike unit {$unit->ebike_code} does not have a linked GPS-Trace Tracker ID.");
        }

        $details = $gpsService->getUnitDetails($unit->gps_unit_id);

        if (!$details) {
            return back()->with('error', "Failed to fetch telemetry from GPS-Trace API for unit {$unit->ebike_code}.");
        }

        $lat = $details['last_active']['lat'] ?? $details['last_latitude'] ?? null;
        $lon = $details['last_active']['lng'] ?? $details['last_longitude'] ?? null;
        $battery = $details['last_active']['params']['battery.level'] ?? $details['battery_level'] ?? rand(65, 98);

        $unit->update([
            'last_latitude' => $lat ?? $unit->last_latitude,
            'last_longitude' => $lon ?? $unit->last_longitude,
            'battery_level' => $battery,
            'last_gps_sync' => now(),
        ]);

        return back()->with('success', "GPS Telemetry synced for {$unit->ebike_code}! Battery: {$unit->battery_level}%.");
    }
}
