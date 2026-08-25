<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EBikeUnit;
use App\Models\Product;
use Illuminate\Support\Str;

class AdminFleetController extends Controller
{
    public function index(Request $request)
    {
        $query = EBikeUnit::with(['product', 'maintenanceRecords']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('ebike_code', 'like', "%{$request->search}%")
                  ->orWhere('serial_number', 'like', "%{$request->search}%");
        }

        $units = $query->latest()->paginate(15);
        $products = Product::where('is_rental_eligible', true)->get();

        return view('admin.fleet.index', compact('units', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'ebike_code' => 'required|string|unique:ebike_units,ebike_code',
            'serial_number' => 'required|string|unique:ebike_units,serial_number',
            'frame_size' => 'required|string',
            'status' => 'required|in:available,rented,maintenance,retired',
            'condition_notes' => 'nullable|string',
        ]);

        $unit = EBikeUnit::create([
            'product_id' => $request->product_id,
            'ebike_code' => strtoupper($request->ebike_code),
            'serial_number' => strtoupper($request->serial_number),
            'frame_size' => $request->frame_size,
            'qr_code_data' => 'https://eb4u.co.uk/verify-unit/' . strtoupper($request->ebike_code),
            'status' => $request->status,
            'condition_notes' => $request->condition_notes,
        ]);

        return redirect()->route('admin.fleet.index')->with('success', 'Physical E-Bike unit added to fleet!');
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
}
