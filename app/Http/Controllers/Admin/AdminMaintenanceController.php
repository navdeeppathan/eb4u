<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceRecord;
use App\Models\EBikeUnit;

class AdminMaintenanceController extends Controller
{
    public function index()
    {
        $records = MaintenanceRecord::with('ebikeUnit.product')->latest()->paginate(15);
        $units = EBikeUnit::with('product')->get();
        return view('admin.maintenance.index', compact('records', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ebike_unit_id' => 'required|exists:ebike_units,id',
            'service_type' => 'required|in:routine,repair,inspection,battery_check,brake_service',
            'service_date' => 'required|date',
            'next_service_date' => 'nullable|date|after_or_equal:service_date',
            'cost' => 'required|numeric|min:0',
            'technician_name' => 'nullable|string',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
            'damage_details' => 'nullable|string',
        ]);

        $record = MaintenanceRecord::create($request->all());

        // Auto-lock physical E-Bike unit if maintenance is active
        $unit = EBikeUnit::findOrFail($request->ebike_unit_id);
        if (in_array($request->status, ['scheduled', 'in_progress'])) {
            $unit->update(['status' => 'maintenance']);
        } elseif ($request->status === 'completed') {
            $unit->update(['status' => 'available']);
        }

        return redirect()->route('admin.maintenance.index')->with('success', 'Maintenance record created. Unit status updated automatically.');
    }

    public function update(Request $request, int $id)
    {
        $record = MaintenanceRecord::findOrFail($id);
        $request->validate([
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'cost' => 'required|numeric|min:0',
        ]);

        $record->update($request->all());

        $unit = $record->ebikeUnit;
        if (in_array($request->status, ['scheduled', 'in_progress'])) {
            $unit->update(['status' => 'maintenance']);
        } elseif ($request->status === 'completed') {
            $unit->update(['status' => 'available']);
        }

        return back()->with('success', 'Maintenance record updated.');
    }
}
