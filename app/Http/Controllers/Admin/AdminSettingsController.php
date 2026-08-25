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
            'rental_advance_percentage' => SystemSetting::get('rental_advance_percentage', 30),
            'rental_full_payment_enabled' => SystemSetting::get('rental_full_payment_enabled', true),
            'default_security_deposit' => SystemSetting::get('default_security_deposit', 150.00),
            'default_delivery_charge' => SystemSetting::get('default_delivery_charge', 15.00),
            'default_late_fee_per_day' => SystemSetting::get('default_late_fee_per_day', 25.00),
            'vat_rate_percentage' => SystemSetting::get('vat_rate_percentage', 20.00),
            'store_name' => SystemSetting::get('store_name', 'E-Bike 4 U (UK)'),
            'store_phone' => SystemSetting::get('store_phone', '+44 (0) 20 7946 0912'),
            'store_email' => SystemSetting::get('store_email', 'support@eb4u.co.uk'),
            'store_address' => SystemSetting::get('store_address', '142 Regent Street, London, W1B 5SE, United Kingdom'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'rental_advance_percentage' => 'required|numeric|min:5|max:100',
            'default_security_deposit' => 'required|numeric|min:0',
            'default_delivery_charge' => 'required|numeric|min:0',
            'default_late_fee_per_day' => 'required|numeric|min:0',
            'store_name' => 'required|string|max:255',
        ]);

        SystemSetting::set('rental_advance_percentage', (float) $request->rental_advance_percentage);
        SystemSetting::set('rental_full_payment_enabled', $request->boolean('rental_full_payment_enabled'));
        SystemSetting::set('default_security_deposit', (float) $request->default_security_deposit);
        SystemSetting::set('default_delivery_charge', (float) $request->default_delivery_charge);
        SystemSetting::set('default_late_fee_per_day', (float) $request->default_late_fee_per_day);
        SystemSetting::set('store_name', $request->store_name);
        SystemSetting::set('store_phone', $request->store_phone);
        SystemSetting::set('store_email', $request->store_email);
        SystemSetting::set('store_address', $request->store_address);

        return back()->with('success', 'UK Store & Rental Payment Settings updated successfully!');
    }
}
