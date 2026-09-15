@extends('layouts.admin')

@section('title', 'Physical Fleet Units & GPS Telemetry')

@section('content')
<div class="space-y-6">
    
    <!-- Add Unit Modal Trigger & Stats -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white p-6 rounded-3xl border border-slate-200 shadow-xs gap-4">
        <div>
            <h3 class="text-sm font-black text-slate-900 uppercase">Physical Fleet Inventory & GPS Telemetry</h3>
            <p class="text-xs text-slate-500">Track physical E-Bikes by Code, Serial Number, QR Code & Live GPS-Trace API Telemetry.</p>
        </div>

        <!-- Add Unit Form Toggle -->
        <div x-data="{ showForm: false }">
            <button @click="showForm = !showForm" class="py-2.5 px-5 bg-brand-600 hover:bg-brand-700 text-white font-black text-xs rounded-xl shadow-sm">
                <i class="fa-solid fa-plus mr-1"></i> Register Physical Unit & GPS Tracker
            </button>

            <!-- Inline Form Modal -->
            <div x-show="showForm" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl p-6 max-w-lg w-full shadow-2xl space-y-4 text-xs">
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="font-black text-slate-900 uppercase">Register E-Bike Unit & GPS Tracker</h3>
                        <button @click="showForm = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                    </div>

                    <form action="{{ route('admin.fleet.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Select E-Bike Model</label>
                            <select name="product_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold">
                                @foreach($products as $prod)
                                    <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">E-Bike Unit Code</label>
                                <input type="text" name="ebike_code" placeholder="EB-UNIT-1005" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 font-mono uppercase font-bold">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Serial Number</label>
                                <input type="text" name="serial_number" placeholder="SN-UK-88219" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 font-mono uppercase">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Frame Size</label>
                                <select name="frame_size" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 font-bold">
                                    <option value="Medium">Medium</option>
                                    <option value="Large">Large</option>
                                    <option value="Extra Large">Extra Large</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Initial Status</label>
                                <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 font-bold">
                                    <option value="available">Available</option>
                                    <option value="rented">Rented</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>

                        <!-- GPS-Trace API Tracker Details -->
                        <div class="border-t pt-3 space-y-3">
                            <h4 class="font-black text-brand-700 uppercase text-[10px] tracking-wider">
                                <i class="fa-solid fa-location-crosshairs mr-1"></i> GPS-Trace Telematics Setup (Optional)
                            </h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Tracker IMEI / Ident</label>
                                    <input type="text" name="gps_ident" placeholder="862684660123456" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 font-mono">
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Tracker Device Protocol / Hardware ID</label>
                                    <select name="gps_hw_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 font-bold">
                                        <option value="">-- Select Hardware Protocol --</option>
                                        @foreach($hardwareDevices as $device)
                                            <option value="{{ $device['id'] }}">{{ $device['title'] }} (ID: {{ $device['id'] }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end space-x-2">
                            <button type="button" @click="showForm = false" class="py-2 px-4 bg-slate-100 text-slate-700 font-bold rounded-xl">Cancel</button>
                            <button type="submit" class="py-2 px-5 bg-brand-600 text-white font-black rounded-xl">Save Unit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Fleet Units Table -->
    <div class="md:hidden flex items-center justify-between text-[10px] text-brandOrange-700 bg-brandOrange-50 px-3.5 py-2 rounded-2xl border border-brandOrange-200 font-bold shadow-xs">
        <span class="flex items-center gap-1.5"><i class="fa-solid fa-arrows-left-right text-brandOrange-500"></i> Scroll table horizontally to view full details</span>
        <i class="fa-solid fa-hand-pointer text-brandOrange-500 animate-pulse"></i>
    </div>
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                <tr>
                    <th class="p-4">Unit Code</th>
                    <th class="p-4">E-Bike Model</th>
                    <th class="p-4">Serial / Frame</th>
                    <th class="p-4">GPS Telemetry & Battery</th>
                    <th class="p-4">QR Link</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($units as $u)
                    <tr class="hover:bg-slate-50/50">
                        <td class="p-4 font-black font-mono text-slate-900">
                            {{ $u->ebike_code }}
                            @if($u->gps_unit_id)
                                <span class="block text-[9px] text-emerald-600 font-mono" title="GPS-Trace Unit ID: {{ $u->gps_unit_id }}">
                                    <i class="fa-solid fa-satellite-dish"></i> Live GPS
                                </span>
                            @endif
                        </td>
                        <td class="p-4 font-bold text-slate-800">{{ $u->product->name ?? 'Deleted' }}</td>
                        <td class="p-4 font-mono text-slate-600">
                            {{ $u->serial_number }}
                            <span class="block text-[10px] text-slate-400 font-sans font-bold">{{ $u->frame_size }}</span>
                        </td>
                        <td class="p-4">
                            @if($u->hasGpsTracker())
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ ($u->battery_level ?? 80) > 30 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            <i class="fa-solid fa-battery-three-quarters mr-1"></i> {{ $u->battery_level ?? '--' }}%
                                        </span>
                                        @if($u->map_location_url)
                                            <a href="{{ $u->map_location_url }}" target="_blank" class="text-brand-600 hover:underline text-[10px] font-bold">
                                                <i class="fa-solid fa-location-dot"></i> View Location
                                            </a>
                                        @endif
                                    </div>
                                    @if($u->last_gps_sync)
                                        <span class="block text-[9px] text-slate-400 font-mono">
                                            Synced: {{ $u->last_gps_sync->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-slate-400 italic text-[10px]">No GPS Tracker</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="text-[10px] text-brand-700 bg-brand-50 px-2 py-1 rounded font-mono border border-brand-100">
                                <i class="fa-solid fa-qrcode mr-1"></i> {{ $u->qr_code_data }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase
                                {{ $u->status === 'available' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                {{ $u->status === 'rented' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $u->status === 'maintenance' ? 'bg-rose-100 text-rose-800' : '' }}
                                {{ $u->status === 'retired' ? 'bg-slate-100 text-slate-600' : '' }}">
                                {{ $u->status }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="inline-flex items-center space-x-2">
                                @if($u->gps_unit_id)
                                    <form action="{{ route('admin.fleet.sync_gps', $u->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="py-1 px-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[10px] rounded-lg border border-slate-200" title="Sync live telemetry from GPS-Trace">
                                            <i class="fa-solid fa-arrows-rotate"></i> Sync GPS
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.fleet.status', $u->id) }}" method="POST" class="inline">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 rounded-lg text-[11px] p-1 font-bold">
                                        <option value="available" {{ $u->status == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="rented" {{ $u->status == 'rented' ? 'selected' : '' }}>Rented</option>
                                        <option value="maintenance" {{ $u->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="retired" {{ $u->status == 'retired' ? 'selected' : '' }}>Retired</option>
                                    </select>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $units->links() }}
        </div>
    </div>
</div>
@endsection
