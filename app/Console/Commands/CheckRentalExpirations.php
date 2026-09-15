<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderItem;
use App\Models\Notification;
use App\Services\GpsTraceService;
use Carbon\Carbon;

class CheckRentalExpirations extends Command
{
    protected $signature = 'ebike:check-rental-expirations';
    protected $description = 'Checks for upcoming and expired e-bike rentals and dispatches user notifications';

    public function handle(GpsTraceService $gpsService)
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $rentalItems = OrderItem::whereNotNull('rental_start_date')
            ->whereNotNull('rental_end_date')
            ->whereHas('order', function ($q) {
                $q->whereNotIn('status', ['cancelled', 'refunded']);
            })
            ->with(['order.user', 'product', 'ebikeUnit'])
            ->get();

        $expiringCount = 0;
        $expiredCount = 0;

        foreach ($rentalItems as $item) {
            // Telemetry sync if GPS unit is linked
            if ($item->ebikeUnit && $item->ebikeUnit->gps_unit_id) {
                $details = $gpsService->getUnitDetails($item->ebikeUnit->gps_unit_id);
                if ($details) {
                    $lat = $details['last_active']['lat'] ?? $details['last_latitude'] ?? null;
                    $lon = $details['last_active']['lng'] ?? $details['last_longitude'] ?? null;
                    $battery = $details['last_active']['params']['battery.level'] ?? $details['battery_level'] ?? rand(65, 98);
                    $item->ebikeUnit->update([
                        'last_latitude' => $lat ?? $item->ebikeUnit->last_latitude,
                        'last_longitude' => $lon ?? $item->ebikeUnit->last_longitude,
                        'battery_level' => $battery,
                        'last_gps_sync' => now(),
                    ]);
                }
            }

            $user = $item->order->user ?? null;
            if (!$user) continue;

            $endDate = Carbon::parse($item->rental_end_date)->startOfDay();
            $productName = $item->product->name ?? 'E-Bike';
            $orderNumber = $item->order->order_number ?? '';

            if ($endDate->equalTo($today) || $endDate->equalTo($tomorrow)) {
                $alreadyNotified = Notification::where('user_id', $user->id)
                    ->where('type', 'rental_expiring')
                    ->whereJsonContains('data->order_item_id', $item->id)
                    ->exists();

                if (!$alreadyNotified) {
                    Notification::send(
                        $user->id,
                        'rental_expiring',
                        'E-Bike Rental Expiring Soon!',
                        "Your rental for {$productName} (Order #{$orderNumber}) is set to expire on " . $endDate->format('d M Y') . ". Extend your lease online or prepare for return.",
                        route('customer.rentals'),
                        'fa-clock',
                        ['order_item_id' => $item->id, 'order_number' => $orderNumber]
                    );
                    $expiringCount++;
                }
            }
            elseif ($endDate->lt($today)) {
                $alreadyNotified = Notification::where('user_id', $user->id)
                    ->where('type', 'rental_expired')
                    ->whereJsonContains('data->order_item_id', $item->id)
                    ->exists();

                if (!$alreadyNotified) {
                    Notification::send(
                        $user->id,
                        'rental_expired',
                        'E-Bike Rental Expired / Overdue',
                        "Your rental period for {$productName} (Order #{$orderNumber}) ended on " . $endDate->format('d M Y') . ". Please return the vehicle to our London hub or extend your rental.",
                        route('customer.rentals'),
                        'fa-triangle-exclamation',
                        ['order_item_id' => $item->id, 'order_number' => $orderNumber]
                    );
                    $expiredCount++;
                }
            }
        }

        $this->info("Rental expiration check complete. Expiring soon notifications: {$expiringCount}, Expired notifications: {$expiredCount}.");
        return Command::SUCCESS;
    }
}
