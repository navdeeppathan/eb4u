@extends('layouts.admin')

@section('title', 'E-Bike Maintenance & Repair Manager')

@section('content')
<div class="space-y-6">
    
    <!-- Add Maintenance Log Header -->
    <div class="flex justify-between items-center bg-white p-6 rounded-3xl border border-slate-200 shadow-xs" x-data="{ showForm: false }">
        <div>
            <h3 class="text-sm font-black text-slate-900 uppercase">Service & Repair Logs</h3>
            <p class="text-xs text-slate-500">Log service activities. Scheduling maintenance automatically blocks physical units from customer rental availability.</p>
        </div>

        <button @click="showForm = !showForm" class="py-2.5 px-5 bg-amber-600 hover:bg-amber-700 text-white font-black text-xs rounded-xl shadow-sm">
            <i class="fa-solid fa-plus mr-1"></i> Log Service / Repair
        </button>

        <!-- Inline Modal -->
        <div x-show="showForm" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 text-xs">
            <div class="bg-white rounded-3xl p-6 max-w-lg w-full shadow-2xl space-y-4">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="font-black text-slate-900 uppercase">Schedule Maintenance Record</h3>
                    <button @click="showForm = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <form action="{{ route('admin.maintenance.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Select Physical E-Bike Unit</label>
                        <select name="ebike_unit_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold">
                            @foreach($units as $u)
                                <option value="{{ $u->id }}">{{ $u->ebike_code }} - {{ $u->product->name ?? '' }} (Current: {{ $u->status }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Service Type</label>
                            <select name="service_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 font-bold">
                                <option value="routine">Routine Service</option>
                                <option value="repair">Repair</option>
                                <option value="inspection">Inspection</option>
                                <option value="battery_check">Battery Check</option>
                                <option value="brake_service">Brake Service</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Repair Cost (£)</label>
                            <input type="number" step="0.01" name="cost" value="45.00" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 font-bold">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Service Date</label>
                            <input type="date" name="service_date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 font-bold">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Status</label>
                            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 font-bold">
                                <option value="in_progress">In Progress (Locks Unit)</option>
                                <option value="scheduled">Scheduled (Locks Unit)</option>
                                <option value="completed">Completed (Frees Unit)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Technician Name</label>
                        <input type="text" name="technician_name" value="Dave Miller (Senior Mechanic)" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 font-semibold">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Technician Notes</label>
                        <textarea name="notes" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 font-medium"></textarea>
                    </div>

                    <div class="pt-4 flex justify-end space-x-2">
                        <button type="button" @click="showForm = false" class="py-2 px-4 bg-slate-100 text-slate-700 font-bold rounded-xl">Cancel</button>
                        <button type="submit" class="py-2 px-5 bg-amber-600 text-white font-black rounded-xl">Save Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Maintenance Records Table Container -->
    <div class="xl:hidden flex items-center justify-between text-[10px] text-brandOrange-700 bg-brandOrange-50 px-3.5 py-2 rounded-2xl border border-brandOrange-200 font-bold shadow-xs">
        <span class="flex items-center gap-1.5"><i class="fa-solid fa-arrows-left-right text-brandOrange-500"></i> Scroll table horizontally to view full details</span>
        <i class="fa-solid fa-hand-pointer text-brandOrange-500 animate-pulse"></i>
    </div>
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs whitespace-nowrap min-w-[850px]">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-4 whitespace-nowrap">Unit Code</th>
                        <th class="p-4 whitespace-nowrap">E-Bike Model</th>
                        <th class="p-4 whitespace-nowrap">Service Type</th>
                        <th class="p-4 whitespace-nowrap">Date</th>
                        <th class="p-4 whitespace-nowrap">Cost</th>
                        <th class="p-4 whitespace-nowrap">Technician</th>
                        <th class="p-4 whitespace-nowrap">Status</th>
                        <th class="p-4 text-right whitespace-nowrap">Update</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($records as $r)
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-4 font-black font-mono text-slate-900 whitespace-nowrap">{{ $r->ebikeUnit->ebike_code ?? 'N/A' }}</td>
                            <td class="p-4 font-bold text-slate-800 whitespace-nowrap">{{ $r->ebikeUnit->product->name ?? 'Deleted' }}</td>
                            <td class="p-4 font-bold uppercase text-[10px] text-amber-700 whitespace-nowrap">{{ str_replace('_', ' ', $r->service_type) }}</td>
                            <td class="p-4 text-slate-600 whitespace-nowrap">{{ $r->service_date ? $r->service_date->format('d M Y') : 'N/A' }}</td>
                            <td class="p-4 font-black text-slate-900 whitespace-nowrap">£{{ number_format($r->cost, 2) }}</td>
                            <td class="p-4 text-slate-600 font-semibold whitespace-nowrap">{{ $r->technician_name ?? 'Staff' }}</td>
                            <td class="p-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase
                                    {{ $r->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $r->status }}
                                </span>
                            </td>
                            <td class="p-4 text-right whitespace-nowrap">
                                <form action="{{ route('admin.maintenance.update', $r->id) }}" method="POST" class="inline-flex items-center space-x-1">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 rounded-lg text-[11px] p-1 font-bold">
                                        <option value="in_progress" {{ $r->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $r->status == 'completed' ? 'selected' : '' }}>Mark Completed</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="p-4 bg-[#fafbfc] border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs text-slate-600 font-medium">
                Showing <strong class="text-slate-900 font-black">{{ $records->firstItem() ?? 0 }}</strong> to <strong class="text-slate-900 font-black">{{ $records->lastItem() ?? 0 }}</strong> of <strong class="text-slate-900 font-black">{{ $records->total() }}</strong> maintenance logs
                @if($records->lastPage() > 1)
                    <span class="ml-1 text-[11px] text-brandOrange-600 font-bold">(Page {{ $records->currentPage() }} of {{ $records->lastPage() }})</span>
                @endif
            </div>

            <div>
                @if($records->hasPages())
                    {{ $records->appends(request()->query())->links() }}
                @else
                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs font-extrabold bg-slate-100 text-slate-600 border border-slate-200">
                        Page 1 of 1
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
