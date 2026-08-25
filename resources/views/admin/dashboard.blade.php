@extends('layouts.admin')

@section('title', 'Admin Dashboard & Revenue Analytics')

@section('content')
<div class="space-y-8">
    
    <!-- Top Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-black uppercase text-slate-400">Total Revenue Generated</span>
            <div class="text-2xl font-black text-slate-900 mt-1">£{{ number_format($totalSales, 2) }}</div>
            <div class="text-[11px] text-emerald-600 font-bold mt-2"><i class="fa-solid fa-arrow-trend-up mr-1"></i> Sales & Rental Income</div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-black uppercase text-slate-400">Rental Income</span>
            <div class="text-2xl font-black text-purple-900 mt-1">£{{ number_format($rentalRevenue, 2) }}</div>
            <div class="text-[11px] text-purple-700 font-bold mt-2"><i class="fa-solid fa-calendar-check mr-1"></i> {{ $totalRentalsCount }} Total Rental Bookings</div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-black uppercase text-slate-400">Active E-Bike Rentals</span>
            <div class="text-2xl font-black text-brand-600 mt-1">{{ $activeRentalsCount }}</div>
            <div class="text-[11px] text-slate-500 font-semibold mt-2">{{ $availableFleetCount }} Units Available for Rent</div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-black uppercase text-slate-400">Maintenance & Fleet Alerts</span>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $maintenanceFleetCount }}</div>
            <div class="text-[11px] text-amber-700 font-bold mt-2"><i class="fa-solid fa-screwdriver-wrench mr-1"></i> Units Under Service</div>
        </div>
    </div>

    <!-- Chart.js Revenue Trend -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
        <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 mb-4">Monthly Sales & Rental Revenue (£)</h3>
        <div class="h-64">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
        <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">Recent Customer Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">View All Orders &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 uppercase text-[10px] border-b border-slate-100">
                        <th class="pb-3">Order #</th>
                        <th class="pb-3">Customer</th>
                        <th class="pb-3">Type</th>
                        <th class="pb-3">Amount</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recentOrders as $ord)
                        <tr>
                            <td class="py-3 font-bold text-slate-900">{{ $ord->order_number }}</td>
                            <td class="py-3 text-slate-700 font-semibold">{{ $ord->user->name ?? 'Guest' }}</td>
                            <td class="py-3 font-bold uppercase text-purple-700">{{ $ord->type }}</td>
                            <td class="py-3 font-black text-slate-900">£{{ number_format($ord->total_amount, 2) }}</td>
                            <td class="py-3"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $ord->status_badge_class }}">{{ $ord->status }}</span></td>
                            <td class="py-3 text-right">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="text-emerald-700 font-bold hover:underline">Manage &rarr;</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        try {
            let res = await axios.get('{{ route("admin.analytics") }}');
            let data = res.data;

            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.months,
                    datasets: [
                        {
                            label: 'E-Bike & Accessory Sales (£)',
                            data: data.sales,
                            borderColor: '#059669',
                            backgroundColor: 'rgba(5, 150, 105, 0.1)',
                            fill: true,
                            tension: 0.3
                        },
                        {
                            label: 'E-Bike Rental Revenue (£)',
                            data: data.rentals,
                            borderColor: '#7c3aed',
                            backgroundColor: 'rgba(124, 58, 237, 0.1)',
                            fill: true,
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        } catch (e) {
            console.error('Analytics load error:', e);
        }
    });
</script>
@endpush
@endsection
