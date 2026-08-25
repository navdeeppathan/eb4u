@extends('layouts.admin')

@section('title', 'Manage Order #' . $order->order_number)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="text-xs text-slate-400 font-bold uppercase">Order Reference</span>
            <h1 class="text-2xl font-black text-slate-900">{{ $order->order_number }}</h1>
            <p class="text-xs text-slate-500">Customer: {{ $order->user->name ?? 'Guest' }} ({{ $order->user->email ?? 'N/A' }})</p>
        </div>

        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex items-center space-x-2">
            @csrf
            <select name="status" class="bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-bold">
                @if($order->type === 'rental')
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="ready_for_pickup" {{ $order->status == 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                    <option value="picked_up" {{ $order->status == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                    <option value="active" {{ $order->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="extension_requested" {{ $order->status == 'extension_requested' ? 'selected' : '' }}>Extension Requested</option>
                    <option value="return_requested" {{ $order->status == 'return_requested' ? 'selected' : '' }}>Return Requested</option>
                    <option value="returned" {{ $order->status == 'returned' ? 'selected' : '' }}>Returned (Frees Bike)</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed (Frees Bike)</option>
                    <option value="overdue" {{ $order->status == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                @else
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="packed" {{ $order->status == 'packed' ? 'selected' : '' }}>Packed</option>
                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                @endif
            </select>
            <button type="submit" class="py-2.5 px-4 bg-brand-600 hover:bg-brand-700 text-white text-xs font-black rounded-xl">Update Status</button>
        </form>
    </div>

    <!-- Order Items & Physical Unit Assignment -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
        <h3 class="text-xs font-black uppercase text-slate-900 tracking-wider pb-3 border-b">Order Items & Physical E-Bike Allocation</h3>

        <div class="space-y-4">
            @foreach($order->items as $item)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-xs">
                    <div>
                        <span class="font-bold text-slate-900 text-sm">{{ $item->product_name }}</span>
                        @if($item->item_type === 'rental')
                            <p class="text-purple-800 font-semibold mt-0.5">
                                Rental Period: {{ $item->rental_start_date ? $item->rental_start_date->format('d M Y') : 'N/A' }} - {{ $item->rental_end_date ? $item->rental_end_date->format('d M Y') : 'N/A' }}
                            </p>
                        @endif
                    </div>

                    <!-- Assign Unit Form for Rental Items -->
                    @if($item->item_type === 'rental')
                        <div class="flex items-center space-x-2">
                            @if($item->ebikeUnit)
                                <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1.5 rounded-xl border border-emerald-200">
                                    <i class="fa-solid fa-qrcode mr-1"></i> {{ $item->ebikeUnit->ebike_code }} (SN: {{ $item->ebikeUnit->serial_number }})
                                </span>
                            @else
                                <form action="{{ route('admin.orders.assign_unit', $item->id) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    <select name="ebike_unit_id" required class="bg-white border border-slate-200 rounded-xl p-2 text-xs font-bold">
                                        <option value="">-- Assign Physical E-Bike Unit --</option>
                                        @foreach($availableUnits as $unit)
                                            @if($unit->product_id === $item->product_id)
                                                <option value="{{ $unit->id }}">{{ $unit->ebike_code }} (SN: {{ $unit->serial_number }})</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <button type="submit" class="py-2 px-3 bg-purple-700 text-white font-bold rounded-xl text-xs">Assign</button>
                                </form>
                            @endif
                        </div>
                    @endif

                    <span class="font-black text-slate-900 text-sm">£{{ number_format($item->subtotal, 2) }}</span>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
