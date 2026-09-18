<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'default_security_deposit' => SystemSetting::get('default_security_deposit', 150.00),
            'default_late_fee_per_day' => SystemSetting::get('default_late_fee_per_day', 25.00),
            'vat_rate_percentage' => SystemSetting::get('vat_rate_percentage', 20.00),
            'store_name' => SystemSetting::get('store_name', 'E-Bike 4 U (UK)'),
            'store_phone' => SystemSetting::get('store_phone', '+44 (0) 20 7946 0912'),
            'store_email' => SystemSetting::get('store_email', 'support@eb4u.co.uk'),
            'store_address' => SystemSetting::get('store_address', 'Near, 103 Inwood Rd, Hounslow TW3 1XA'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'default_security_deposit' => 'required|numeric|min:0',
            'default_late_fee_per_day' => 'required|numeric|min:0',
            'store_name' => 'required|string|max:255',
        ]);

        SystemSetting::set('default_security_deposit', (float) $request->default_security_deposit);
        SystemSetting::set('default_late_fee_per_day', (float) $request->default_late_fee_per_day);
        SystemSetting::set('store_name', $request->store_name);
        SystemSetting::set('store_phone', $request->store_phone);
        SystemSetting::set('store_email', $request->store_email);
        SystemSetting::set('store_address', $request->store_address);

        return back()->with('success', 'UK Store & Rental Payment Settings updated successfully!');
    }
}
