@extends('layouts.admin')

@section('title', 'Product Detail & Logs Report - ' . $product->name)

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'rentals', showDamageModal: false, showMaintModal: false, showStatusModal: false, editDamageModal: false, selectedDamage: {} }">

    <!-- Top Navigation & Product Header Card -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-start space-x-4">
                <a href="{{ route('admin.rental_logs.index') }}" class="mt-1 w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-20 h-20 rounded-2xl object-cover border border-slate-200 shadow-sm">
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-brandOrange-50 text-brandOrange-600 text-[10px] font-black uppercase tracking-wider">Rental Product Report</span>
                        <span class="text-xs text-slate-400 font-bold">SKU: PROD-{{ $product->id }}</span>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900 mt-1">{{ $product->name }}</h1>
                    <div class="flex flex-wrap items-center gap-4 mt-2 text-xs font-semibold text-slate-600">
                        <span><i class="fa-solid fa-tag text-brandOrange-500 mr-1"></i> Weekly Rate: <strong class="text-slate-900">£{{ number_format($product->rental_price_weekly ?? 0, 2) }}</strong></span>
                        <span><i class="fa-solid fa-shield-halved text-emerald-500 mr-1"></i> Deposit: <strong class="text-slate-900">£{{ number_format($product->rental_security_deposit ?? 250, 2) }}</strong></span>
                        @if($product->motor_specs)
                            <span><i class="fa-solid fa-bolt text-amber-500 mr-1"></i> {{ $product->motor_specs }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Header Action Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                <button @click="showDamageModal = true" class="px-4 py-2.5 rounded-2xl bg-rose-500 hover:bg-rose-600 text-white font-bold text-xs shadow-md transition-colors flex items-center space-x-1.5">
                    <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                    <span>Log Damage & User Liability</span>
                </button>
                <button @click="showMaintModal = true" class="px-4 py-2.5 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-md transition-colors flex items-center space-x-1.5">
                    <i class="fa-solid fa-wrench text-xs"></i>
                    <span>Log Maintenance</span>
                </button>
                <button @click="showStatusModal = true" class="px-4 py-2.5 rounded-2xl bg-darkBlack-900 hover:bg-black text-white font-bold text-xs shadow-md transition-colors flex items-center space-x-1.5">
                    <i class="fa-solid fa-sliders text-xs"></i>
                    <span>Update Fleet Status</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Financial & Fleet Analytics KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        <!-- Total Revenue -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs">
            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Rental Revenue</span>
            <span class="text-2xl font-black text-emerald-600 mt-1 block">£{{ number_format($totalEarnings, 2) }}</span>
            <span class="text-[10px] text-slate-400 font-semibold block mt-1">Earned across {{ $totalRentalsCount }} rentals</span>
        </div>

        <!-- Rental Count & Active Rents -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs">
            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Rental Frequency</span>
            <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $totalRentalsCount }} Times</span>
            <span class="text-[10px] text-blue-600 font-extrabold block mt-1">{{ $activeRentalsCount }} Currently On Rent</span>
        </div>

        <!-- Shop Repair Expenses -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs">
            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Shop Repair Expenses</span>
            <span class="text-2xl font-black text-rose-600 mt-1 block">£{{ number_format($totalShopRepairCost, 2) }}</span>
            <span class="text-[10px] text-slate-400 font-semibold block mt-1">Total repair & maintenance cost</span>
        </div>

        <!-- User Liability Charged -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs">
            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">User Liability Recovered</span>
            <span class="text-2xl font-black text-emerald-600 mt-1 block">£{{ number_format($userChargesPaid, 2) }}</span>
            <span class="text-[10px] text-amber-600 font-extrabold block mt-1">£{{ number_format($userChargesPending, 2) }} Pending Recovery</span>
        </div>

        <!-- Fleet Status Summary -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs">
            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Fleet Status Breakdown</span>
            <div class="flex items-center space-x-2 mt-2">
                <span class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-800 font-black text-xs">
                    {{ $product->ebikeUnits->where('status', 'available')->count() }} Avail
                </span>
                <span class="px-2 py-1 rounded-lg bg-blue-100 text-blue-800 font-black text-xs">
                    {{ $product->ebikeUnits->where('status', 'rented')->count() }} Rent
                </span>
                <span class="px-2 py-1 rounded-lg bg-rose-100 text-rose-800 font-black text-xs">
                    {{ $product->ebikeUnits->whereIn('status', ['maintenance', 'retired'])->count() }} Maint
                </span>
            </div>
            <span class="text-[10px] text-slate-400 font-semibold block mt-1.5">Total Physical Units: {{ $product->ebikeUnits->count() }}</span>
        </div>

    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs p-2 flex flex-wrap gap-2">
        <button @click="activeTab = 'rentals'" :class="activeTab === 'rentals' ? 'bg-brandOrange-500 text-white font-black' : 'text-slate-600 font-bold hover:bg-slate-100'" class="px-5 py-3 rounded-2xl text-xs transition-colors flex items-center space-x-2">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span>Rental Orders History ({{ $rentalOrders->count() }})</span>
        </button>
        <button @click="activeTab = 'damage'" :class="activeTab === 'damage' ? 'bg-brandOrange-500 text-white font-black' : 'text-slate-600 font-bold hover:bg-slate-100'" class="px-5 py-3 rounded-2xl text-xs transition-colors flex items-center space-x-2">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span>Damage & User Liabilities ({{ $damageLogs->count() }})</span>
        </button>
        <button @click="activeTab = 'maintenance'" :class="activeTab === 'maintenance' ? 'bg-brandOrange-500 text-white font-black' : 'text-slate-600 font-bold hover:bg-slate-100'" class="px-5 py-3 rounded-2xl text-xs transition-colors flex items-center space-x-2">
            <i class="fa-solid fa-wrench"></i>
            <span>Maintenance & Services ({{ $maintenanceRecords->count() }})</span>
        </button>
        <button @click="activeTab = 'timeline'" :class="activeTab === 'timeline' ? 'bg-brandOrange-500 text-white font-black' : 'text-slate-600 font-bold hover:bg-slate-100'" class="px-5 py-3 rounded-2xl text-xs transition-colors flex items-center space-x-2">
            <i class="fa-solid fa-timeline"></i>
            <span>Full Audit Trail</span>
        </button>
    </div>

    <!-- TAB 1: RENTAL ORDERS HISTORY LOG -->
    <div x-show="activeTab === 'rentals'" x-cloak class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-black text-slate-900">Rental Orders History</h3>
                <p class="text-xs text-slate-400 mt-0.5">Complete log of every time this product was rented out, start/end dates, return date, customer details, and earnings.</p>
            </div>
            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full font-bold text-xs">
                Total Earned: £{{ number_format($totalEarnings, 2) }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <th class="p-4">Order #</th>
                        <th class="p-4">Customer Details</th>
                        <th class="p-4">Assigned Unit Serial</th>
                        <th class="p-4">Rental Start & End Date</th>
                        <th class="p-4">Actual Return Date</th>
                        <th class="p-4">Duration & Rate</th>
                        <th class="p-4">Total Paid</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">View Order</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($rentalOrders as $item)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="p-4 font-bold text-slate-900">
                                {{ $item->order->order_number ?? 'N/A' }}
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-slate-900">{{ $item->order->user->name ?? 'Guest Customer' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $item->order->user->email ?? 'N/A' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $item->order->user->phone ?? '' }}</div>
                            </td>
                            <td class="p-4">
                                @if($item->ebikeUnit)
                                    <span class="px-2 py-1 bg-slate-100 rounded-lg text-slate-800 font-mono font-bold text-[11px] border border-slate-200">
                                        {{ $item->ebikeUnit->ebike_code }}
                                    </span>
                                    <span class="block text-[10px] text-slate-400 mt-0.5">SN: {{ $item->ebikeUnit->serial_number }}</span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Unassigned</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="font-semibold text-slate-900">
                                    {{ $item->rental_start_date ? \Carbon\Carbon::parse($item->rental_start_date)->format('d M Y') : 'N/A' }} 
                                    <i class="fa-solid fa-arrow-right text-[10px] text-slate-400 mx-1"></i>
                                    {{ $item->rental_end_date ? \Carbon\Carbon::parse($item->rental_end_date)->format('d M Y') : 'N/A' }}
                                </div>
                            </td>
                            <td class="p-4">
                                @if($item->order && $item->order->actual_return_date)
                                    <span class="font-bold text-emerald-600">
                                        {{ \Carbon\Carbon::parse($item->order->actual_return_date)->format('d M Y') }}
                                    </span>
                                @elseif($item->order && $item->order->status === 'active')
                                    <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-[10px] font-bold">Currently On Rent</span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Pending Return</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-slate-900 block">{{ $item->rental_days ?? 7 }} Days</span>
                                <span class="text-[10px] text-slate-400 block">@ £{{ number_format($item->rental_rate ?? 0, 2) }}/day</span>
                            </td>
                            <td class="p-4 font-black text-emerald-600">
                                £{{ number_format($item->subtotal, 2) }}
                            </td>
                            <td class="p-4">
                                @php
                                    $st = $item->order->status ?? 'pending';
                                    $badge = 'bg-slate-100 text-slate-700';
                                    if(in_array($st, ['active', 'picked_up'])) $badge = 'bg-emerald-100 text-emerald-800 font-extrabold';
                                    if($st === 'completed' || $st === 'returned') $badge = 'bg-blue-100 text-blue-800';
                                    if($st === 'overdue') $badge = 'bg-rose-100 text-rose-800 font-extrabold animate-pulse';
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] uppercase font-bold {{ $badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $st)) }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                @if($item->order)
                                    <a href="{{ route('admin.orders.show', $item->order->id) }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition-colors">
                                        Details <i class="fa-solid fa-chevron-right text-[10px] ml-1"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-400 font-semibold">
                                No rental orders found for this product yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 2: DAMAGE & USER LIABILITY LOGS -->
    <div x-show="activeTab === 'damage'" x-cloak class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-black text-slate-900">Damage Reports & Customer Liabilities</h3>
                <p class="text-xs text-slate-400 mt-0.5">Track user-caused damage incidents, shop repair costs, customer recovery charges, payment recovery status, and repair dates.</p>
            </div>
            <button @click="showDamageModal = true" class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white font-bold text-xs rounded-xl shadow-xs transition-colors">
                <i class="fa-solid fa-plus mr-1"></i> Log Damage Incident
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <th class="p-4">Incident Date</th>
                        <th class="p-4">Customer Details</th>
                        <th class="p-4">Unit & Damage Type</th>
                        <th class="p-4">Damage Description</th>
                        <th class="p-4">Shop Repair Cost</th>
                        <th class="p-4">User Liability Charge</th>
                        <th class="p-4">User Payment Status</th>
                        <th class="p-4">Repair Status & Duration</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($damageLogs as $dmg)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="p-4 font-bold text-slate-900 whitespace-nowrap">
                                {{ $dmg->incident_date ? $dmg->incident_date->format('d M Y') : 'N/A' }}
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-slate-900">{{ $dmg->user->name ?? 'Unspecified User' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $dmg->user->phone ?? $dmg->user->email ?? '' }}</div>
                                @if($dmg->order)
                                    <div class="text-[10px] text-brandOrange-500 font-semibold">Order: {{ $dmg->order->order_number }}</div>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded-lg bg-slate-100 font-mono font-bold text-slate-800 text-[11px] block w-max">
                                    {{ $dmg->ebikeUnit->ebike_code ?? 'Fleet Unit' }}
                                </span>
                                <span class="px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 text-[10px] font-bold block w-max mt-1">
                                    {{ $dmg->damage_type }}
                                </span>
                            </td>
                            <td class="p-4 max-w-xs">
                                <p class="line-clamp-2 text-slate-800">{{ $dmg->damage_description }}</p>
                                @if($dmg->admin_notes)
                                    <span class="text-[10px] text-slate-400 italic block mt-0.5">Note: {{ $dmg->admin_notes }}</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="font-extrabold text-rose-600 block text-sm">
                                    £{{ number_format($dmg->repair_cost, 2) }}
                                </span>
                                <span class="text-[10px] text-slate-400 block">Shop Expense</span>
                            </td>
                            <td class="p-4">
                                <span class="font-extrabold text-slate-900 block text-sm">
                                    £{{ number_format($dmg->user_charge_amount, 2) }}
                                </span>
                                <span class="text-[10px] text-slate-400 block">Customer Charge</span>
                            </td>
                            <td class="p-4">
                                @if($dmg->user_payment_status === 'paid')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-extrabold text-[10px] uppercase">
                                        <i class="fa-solid fa-check mr-1"></i> Paid / Recovered
                                    </span>
                                @elseif($dmg->user_payment_status === 'pending')
                                    <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 font-extrabold text-[10px] uppercase">
                                        <i class="fa-solid fa-clock mr-1"></i> Pending Recovery
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-bold text-[10px] uppercase">
                                        Waived
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-slate-900 text-[11px]">
                                    @if($dmg->repair_status === 'under_repair')
                                        <span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 font-bold">Under Repair</span>
                                    @elseif($dmg->repair_status === 'repaired')
                                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-bold">Repaired / Available</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 font-bold">Written Off</span>
                                    @endif
                                </div>
                                @if($dmg->repair_start_date || $dmg->repair_completion_date)
                                    <div class="text-[10px] text-slate-500 mt-1">
                                        Start: {{ $dmg->repair_start_date ? $dmg->repair_start_date->format('d M') : 'N/A' }} | 
                                        Returned: <strong class="text-slate-900">{{ $dmg->repair_completion_date ? $dmg->repair_completion_date->format('d M Y') : 'In Repair' }}</strong>
                                    </div>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <button @click="selectedDamage = {{ json_encode($dmg) }}; editDamageModal = true" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition-colors" title="Edit / Update Payment & Repair Status">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('admin.rental_logs.destroy_damage', $dmg->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this damage log?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold transition-colors" title="Delete Log">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-400 font-semibold">
                                No damage or user liability incidents logged for this product.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 3: MAINTENANCE HISTORY LOG -->
    <div x-show="activeTab === 'maintenance'" x-cloak class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-black text-slate-900">Maintenance & Service Records</h3>
                <p class="text-xs text-slate-400 mt-0.5">Routine maintenance, brake services, battery checks, technician notes, and maintenance expenses.</p>
            </div>
            <button @click="showMaintModal = true" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-xs transition-colors">
                <i class="fa-solid fa-wrench mr-1"></i> Add Maintenance Record
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <th class="p-4">Service Date</th>
                        <th class="p-4">Physical Unit</th>
                        <th class="p-4">Service Type</th>
                        <th class="p-4">Cost</th>
                        <th class="p-4">Technician</th>
                        <th class="p-4">Notes & Damage Details</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($maintenanceRecords as $maint)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="p-4 font-bold text-slate-900 whitespace-nowrap">
                                {{ $maint->service_date ? $maint->service_date->format('d M Y') : 'N/A' }}
                                @if($maint->next_service_date)
                                    <span class="block text-[10px] text-slate-400">Next: {{ $maint->next_service_date->format('d M Y') }}</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 bg-slate-100 rounded-lg text-slate-800 font-mono font-bold text-[11px]">
                                    {{ $maint->ebikeUnit->ebike_code ?? 'Unit #' . $maint->ebike_unit_id }}
                                </span>
                            </td>
                            <td class="p-4 font-bold text-slate-900 capitalize">
                                {{ str_replace('_', ' ', $maint->service_type) }}
                            </td>
                            <td class="p-4 font-extrabold text-rose-600 text-sm">
                                £{{ number_format($maint->cost, 2) }}
                            </td>
                            <td class="p-4 font-semibold text-slate-800">
                                {{ $maint->technician_name ?? 'N/A' }}
                            </td>
                            <td class="p-4 max-w-xs">
                                <p class="text-slate-800">{{ $maint->notes }}</p>
                                @if($maint->damage_details)
                                    <p class="text-[10px] text-rose-600 font-semibold mt-0.5">Damage: {{ $maint->damage_details }}</p>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($maint->status === 'completed')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px] uppercase">Completed</span>
                                @elseif($maint->status === 'in_progress')
                                    <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px] uppercase">In Progress</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 font-bold text-[10px] uppercase">{{ ucfirst($maint->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400 font-semibold">
                                No maintenance records logged for units of this product.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 4: CHRONOLOGICAL AUDIT TIMELINE -->
    <div x-show="activeTab === 'timeline'" x-cloak class="bg-white rounded-3xl border border-slate-200 shadow-xs p-6">
        <h3 class="text-base font-black text-slate-900 mb-4">Chronological Event Timeline</h3>
        
        <div class="relative border-l-2 border-slate-100 pl-6 space-y-6">
            @forelse($sortedTimeline as $event)
                <div class="relative">
                    <div class="absolute -left-[31px] top-1.5 w-6 h-6 rounded-full bg-white border-2 border-brandOrange-500 flex items-center justify-center text-[10px] text-brandOrange-500">
                        <i class="fa-solid {{ $event['icon'] }}"></i>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-900 text-xs">{{ $event['title'] }}</span>
                            <span class="px-2 py-0.5 rounded-md font-bold text-[10px] uppercase {{ $event['badge_color'] }}">
                                {{ ucfirst($event['status']) }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-600 mt-1">{{ $event['details'] }}</p>
                        <span class="text-[10px] text-slate-400 font-semibold mt-2 block">
                            {{ $event['date'] ? \Carbon\Carbon::parse($event['date'])->format('d M Y, g:i A') : 'N/A' }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 font-semibold">No timeline activity recorded yet.</p>
            @endforelse
        </div>
    </div>

    <!-- MODAL 1: LOG DAMAGE & USER LIABILITY -->
    <div x-show="showDamageModal" x-cloak class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 space-y-4 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto" @click.outside="showDamageModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-base font-black text-slate-900">Log Damage & Customer Liability</h3>
                <button @click="showDamageModal = false" class="text-slate-400 hover:text-slate-700 text-lg font-bold">&times;</button>
            </div>

            <form action="{{ route('admin.rental_logs.store_damage') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Select Physical Unit</label>
                        <select name="ebike_unit_id" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                            <option value="">-- Any / Product Level --</option>
                            @foreach($product->ebikeUnits as $u)
                                <option value="{{ $u->id }}">{{ $u->ebike_code }} ({{ $u->serial_number }}) - {{ ucfirst($u->status) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Customer / User</label>
                        <select name="user_id" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                            <option value="">-- Unspecified Customer --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Associated Order (Optional)</label>
                        <select name="order_id" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                            <option value="">-- No Order Linked --</option>
                            @foreach($orders as $o)
                                <option value="{{ $o->id }}">Order #{{ $o->order_number }} - {{ $o->user->name ?? 'User' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Incident Date *</label>
                        <input type="date" name="incident_date" value="{{ date('Y-m-d') }}" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Damage Type *</label>
                        <select name="damage_type" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                            <option value="Accident">Accident / Collision</option>
                            <option value="Battery Damage">Battery / Electronics Damage</option>
                            <option value="Component Failure">Component / Gear Damage</option>
                            <option value="Brake Damage">Brake / Disc Damage</option>
                            <option value="Tire / Wheel">Tire Puncture / Wheel Damage</option>
                            <option value="Cosmetic Scratch">Cosmetic Scratch / Frame Bending</option>
                            <option value="Other">Other Damage</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Shop Repair Cost (£) *</label>
                        <input type="number" step="0.01" min="0" name="repair_cost" value="0.00" required placeholder="Shop repair expense" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                        <span class="text-[10px] text-slate-400">Kitna kharch aya repair krne me</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">User Recovery Charge (£) *</label>
                        <input type="number" step="0.01" min="0" name="user_charge_amount" value="0.00" required placeholder="Amount to recover from user" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                        <span class="text-[10px] text-slate-400">Kitna user se lena hai</span>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">User Payment Recovery Status *</label>
                        <select name="user_payment_status" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                            <option value="pending">Pending Recovery</option>
                            <option value="paid">Paid / Recovered</option>
                            <option value="waived">Waived</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Repair Status *</label>
                        <select name="repair_status" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                            <option value="under_repair">Under Repair (In Maintenance)</option>
                            <option value="repaired">Repaired (Available)</option>
                            <option value="written_off">Written Off (Retired)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Repair Start Date</label>
                        <input type="date" name="repair_start_date" value="{{ date('Y-m-d') }}" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Returned / Completion Date</label>
                        <input type="date" name="repair_completion_date" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                        <span class="text-[10px] text-slate-400">Kab vaps ayi</span>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Damage Description *</label>
                    <textarea name="damage_description" rows="2" required placeholder="Describe what happened and exact damage details..." class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Admin Notes</label>
                    <input type="text" name="admin_notes" placeholder="Internal notes or technician remarks..." class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                </div>

                <div class="pt-2 flex justify-end space-x-2">
                    <button type="button" @click="showDamageModal = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-500 text-white font-bold text-xs hover:bg-rose-600 transition-colors shadow-sm">Save Damage Record</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: EDIT DAMAGE EVENT -->
    <div x-show="editDamageModal" x-cloak class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 space-y-4 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto" @click.outside="editDamageModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-base font-black text-slate-900">Update Damage & Recovery Record</h3>
                <button @click="editDamageModal = false" class="text-slate-400 hover:text-slate-700 text-lg font-bold">&times;</button>
            </div>

            <form :action="'{{ url('/admin/rental-logs/damage-log') }}/' + selectedDamage.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Damage Type *</label>
                    <input type="text" name="damage_type" x-model="selectedDamage.damage_type" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Shop Repair Cost (£) *</label>
                        <input type="number" step="0.01" min="0" name="repair_cost" x-model="selectedDamage.repair_cost" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">User Charge Amount (£) *</label>
                        <input type="number" step="0.01" min="0" name="user_charge_amount" x-model="selectedDamage.user_charge_amount" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">User Payment Recovery Status *</label>
                        <select name="user_payment_status" x-model="selectedDamage.user_payment_status" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                            <option value="pending">Pending Recovery</option>
                            <option value="paid">Paid / Recovered</option>
                            <option value="waived">Waived</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Repair Lifecycle Status *</label>
                        <select name="repair_status" x-model="selectedDamage.repair_status" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                            <option value="under_repair">Under Repair (In Maintenance)</option>
                            <option value="repaired">Repaired (Available)</option>
                            <option value="written_off">Written Off (Retired)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Repair Start Date</label>
                        <input type="date" name="repair_start_date" x-model="selectedDamage.repair_start_date" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Returned / Completion Date</label>
                        <input type="date" name="repair_completion_date" x-model="selectedDamage.repair_completion_date" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Admin Notes</label>
                    <textarea name="admin_notes" x-model="selectedDamage.admin_notes" rows="2" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none"></textarea>
                </div>

                <div class="pt-2 flex justify-end space-x-2">
                    <button type="button" @click="editDamageModal = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-brandOrange-500 text-white font-bold text-xs hover:bg-brandOrange-600 transition-colors shadow-sm">Update Damage Record</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3: LOG MAINTENANCE RECORD -->
    <div x-show="showMaintModal" x-cloak class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 space-y-4 shadow-2xl border border-slate-200" @click.outside="showMaintModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-base font-black text-slate-900">Add Maintenance & Service Record</h3>
                <button @click="showMaintModal = false" class="text-slate-400 hover:text-slate-700 text-lg font-bold">&times;</button>
            </div>

            <form action="{{ route('admin.rental_logs.store_maintenance') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Select Physical Unit *</label>
                    <select name="ebike_unit_id" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                        @foreach($product->ebikeUnits as $u)
                            <option value="{{ $u->id }}">{{ $u->ebike_code }} ({{ $u->serial_number }}) - {{ ucfirst($u->status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Service Type *</label>
                        <select name="service_type" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                            <option value="routine">Routine Service</option>
                            <option value="repair">Repair</option>
                            <option value="inspection">Inspection</option>
                            <option value="battery_check">Battery Check</option>
                            <option value="brake_service">Brake Service</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Service Date *</label>
                        <input type="date" name="service_date" value="{{ date('Y-m-d') }}" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Service Cost (£) *</label>
                        <input type="number" step="0.01" min="0" name="cost" value="0.00" required placeholder="0.00" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Technician Name</label>
                        <input type="text" name="technician_name" placeholder="e.g. Dave Miller" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Status *</label>
                    <select name="status" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                        <option value="in_progress">In Progress (Set Unit to Maintenance)</option>
                        <option value="completed">Completed (Set Unit to Available)</option>
                        <option value="scheduled">Scheduled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Maintenance Notes</label>
                    <textarea name="notes" rows="2" placeholder="Details of work performed..." class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none"></textarea>
                </div>

                <div class="pt-2 flex justify-end space-x-2">
                    <button type="button" @click="showMaintModal = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-500 text-white font-bold text-xs hover:bg-amber-600 transition-colors shadow-sm">Save Maintenance Record</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 4: UPDATE FLEET UNIT STATUS -->
    <div x-show="showStatusModal" x-cloak class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-2xl border border-slate-200" @click.outside="showStatusModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-base font-black text-slate-900">Update Physical Unit Status</h3>
                <button @click="showStatusModal = false" class="text-slate-400 hover:text-slate-700 text-lg font-bold">&times;</button>
            </div>

            <form action="{{ route('admin.rental_logs.update_status', $product->id) }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Select Physical Unit *</label>
                    <select name="unit_id" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                        @foreach($product->ebikeUnits as $u)
                            <option value="{{ $u->id }}">{{ $u->ebike_code }} ({{ $u->serial_number }}) - Current: {{ ucfirst($u->status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">New Operational Status *</label>
                    <select name="status" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none">
                        <option value="available">Available for Rent</option>
                        <option value="rented">On Rent (Active Order)</option>
                        <option value="maintenance">Maintenance / Under Repair</option>
                        <option value="retired">Retired / Out of Service</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Condition Notes</label>
                    <textarea name="condition_notes" rows="2" placeholder="Update physical unit notes..." class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-brandOrange-500 focus:outline-none"></textarea>
                </div>

                <div class="pt-2 flex justify-end space-x-2">
                    <button type="button" @click="showStatusModal = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-darkBlack-900 text-white font-bold text-xs hover:bg-black transition-colors shadow-sm">Update Unit Status</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
