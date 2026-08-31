@extends('layouts.app')

@section('title', 'Manage E-Bike Rentals | eb4u')

@section('content')
<div class="bg-darkBlack-950 text-white py-10 border-b border-darkBlack-800">
    <div class="container mx-auto px-6 md:px-12">
        <h1 class="text-2xl font-black text-white"><i class="fa-solid fa-bicycle text-brandOrange-500 mr-2"></i> E-Bike Rental Management</h1>
    </div>
</div>

<div class="container mx-auto px-6 md:px-12 py-10" x-data="customerRentalApp()">
    
    <!-- Active Rentals Cards -->
    <div class="space-y-6 mb-12">
        <h2 class="text-base font-black uppercase tracking-wider text-slate-900">Active & Ongoing Rentals</h2>

        @forelse($activeRentals as $rental)
            <div class="bg-white rounded-3xl border border-slate-200 shadow-md p-6 space-y-4 relative overflow-hidden">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-200 pb-4 gap-2">
                    <div>
                        <span class="text-xs font-mono font-bold text-slate-500">Order: {{ $rental->order_number }}</span>
                        <span class="ml-2 text-xs font-bold uppercase px-3 py-1 rounded-full border {{ $rental->status_badge_class }}">
                            {{ str_replace('_', ' ', $rental->status) }}
                        </span>
                    </div>
                    <span class="text-xs text-slate-900 font-bold bg-brandOrange-50 text-brandOrange-700 px-3 py-1 rounded-full border border-brandOrange-200">
                        Rental Return Date: {{ $rental->rental_end_date ? $rental->rental_end_date->format('d M Y') : 'N/A' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($rental->items as $item)
                        <div class="flex space-x-4 items-center bg-slate-50 p-4 rounded-2xl border border-slate-200">
                            <img src="{{ $item->product->primary_image_url }}" class="w-16 h-16 object-cover rounded-xl border border-slate-200">
                            <div>
                                <h4 class="text-xs font-black text-slate-900">{{ $item->product_name }}</h4>
                                @if($item->ebikeUnit)
                                    <p class="text-[11px] text-emerald-700 font-bold mt-1">
                                        <i class="fa-solid fa-qrcode mr-1"></i> Physical E-Bike Code: <strong>{{ $item->ebikeUnit->ebike_code }}</strong>
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-mono">Serial No: {{ $item->ebikeUnit->serial_number }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Financial Status -->
                <div class="flex flex-wrap justify-between items-center bg-slate-50 p-4 rounded-2xl border border-slate-200 text-xs">
                    <div>
                        <span class="text-slate-600">Total Rental Cost: <strong class="text-slate-900">£{{ number_format($rental->total_amount, 2) }}</strong></span>
                        <span class="mx-2">|</span>
                        <span class="text-emerald-700 font-bold">Paid: <strong>£{{ number_format($rental->advance_amount, 2) }}</strong></span>
                        <span class="mx-2">|</span>
                        <span class="text-brandOrange-600 font-bold">Remaining Balance: <strong>£{{ number_format($rental->remaining_amount, 2) }}</strong></span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-2 mt-2 sm:mt-0">
                        <button @click="openExtendModal({{ $rental->id }}, '{{ $rental->order_number }}')" class="px-4 py-2.5 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-black rounded-xl text-xs shadow-md transition-colors cursor-pointer">
                            <i class="fa-solid fa-calendar-plus mr-1"></i> Extend Rental
                        </button>
                        <button @click="requestReturn({{ $rental->id }})" class="px-4 py-2.5 bg-darkBlack-950 hover:bg-black text-white font-black rounded-xl text-xs shadow-md transition-colors cursor-pointer">
                            <i class="fa-solid fa-rotate-left mr-1"></i> Request Return
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-8 rounded-3xl border border-slate-200 text-center text-xs text-slate-400 font-medium">
                You currently have no active E-Bike rentals.
            </div>
        @endforelse
    </div>

    <!-- Rental History Table -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-6 pb-3 border-b border-slate-200">Completed & Returned Rental History</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 uppercase text-[10px] border-b border-slate-200">
                        <th class="pb-3">Order #</th>
                        <th class="pb-3">Start Date</th>
                        <th class="pb-3">End Date</th>
                        <th class="pb-3">Total Paid</th>
                        <th class="pb-3">Deposit Status</th>
                        <th class="pb-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($rentalHistory as $rHist)
                        <tr>
                            <td class="py-3 font-bold text-slate-900">{{ $rHist->order_number }}</td>
                            <td class="py-3 text-slate-600">{{ $rHist->rental_start_date ? $rHist->rental_start_date->format('d M Y') : 'N/A' }}</td>
                            <td class="py-3 text-slate-600">{{ $rHist->rental_end_date ? $rHist->rental_end_date->format('d M Y') : 'N/A' }}</td>
                            <td class="py-3 font-black text-slate-900">£{{ number_format($rHist->total_amount, 2) }}</td>
                            <td class="py-3 text-emerald-600 font-bold"><i class="fa-solid fa-check"></i> £{{ number_format($rHist->security_deposit_total, 2) }} Refunded</td>
                            <td class="py-3"><span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase border {{ $rHist->status_badge_class }}">{{ $rHist->status }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- AJAX Extension Modal -->
    <div x-show="showExtendModal" x-cloak class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                <h3 class="text-sm font-black uppercase text-slate-900">Extend Rental Period</h3>
                <button @click="showExtendModal = false" class="text-slate-400 hover:text-slate-900 font-bold">&times;</button>
            </div>
            
            <p class="text-xs text-slate-600 font-medium">How many extra days would you like to keep your E-Bike for Order <strong x-text="selectedOrderNumber"></strong>?</p>

            <div>
                <label class="block text-xs font-bold text-slate-900 mb-1">Extension Duration (Days)</label>
                <select x-model="extensionDays" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 font-bold text-slate-900">
                    <option value="1">1 Extra Day (+£35.00)</option>
                    <option value="3">3 Extra Days (+£105.00)</option>
                    <option value="7">1 Extra Week (+£180.00)</option>
                    <option value="14">2 Extra Weeks (+£320.00)</option>
                    <option value="30">1 Extra Month (+£550.00)</option>
                </select>
            </div>

            <div class="pt-4 flex justify-end space-x-2">
                <button type="button" @click="showExtendModal = false" class="px-5 py-2.5 bg-slate-200 text-slate-800 text-xs font-bold rounded-xl">Cancel</button>
                <button type="button" @click="submitExtension()" class="px-5 py-2.5 bg-brandOrange-500 hover:bg-brandOrange-600 text-white text-xs font-black rounded-xl shadow-md">Confirm Extension</button>
            </div>
        </div>
    </div>

</div>

<script>
    function customerRentalApp() {
        return {
            showExtendModal: false,
            selectedRentalId: null,
            selectedOrderNumber: '',
            extensionDays: 3,

            openExtendModal(rentalId, orderNumber) {
                this.selectedRentalId = rentalId;
                this.selectedOrderNumber = orderNumber;
                this.showExtendModal = true;
            },

            async submitExtension() {
                try {
                    let res = await axios.post(`/customer/rentals/${this.selectedRentalId}/extend`, {
                        extension_days: this.extensionDays
                    });
                    if (res.data.success) {
                        this.showExtendModal = false;
                        if (window.showToast) window.showToast(res.data.message);
                        setTimeout(() => window.location.reload(), 1500);
                    }
                } catch (e) {
                    if (window.showToast) window.showToast('Failed to extend rental.', true);
                }
            },

            async requestReturn(rentalId) {
                let confirmRes = await Swal.fire({
                    title: 'Return Vehicle?',
                    text: 'Request vehicle return and security deposit refund?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#f24e00',
                    cancelButtonColor: '#121212',
                    confirmButtonText: 'Yes, Request Return'
                });

                if (confirmRes.isConfirmed) {
                    try {
                        let res = await axios.post(`/customer/rentals/${rentalId}/return`);
                        if (res.data.success) {
                            if (window.showToast) window.showToast(res.data.message);
                            setTimeout(() => window.location.reload(), 1500);
                        }
                    } catch (e) {
                        if (window.showToast) window.showToast('Failed to submit return request.', true);
                    }
                }
            }
        }
    }
</script>
@endsection
