<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderItem;
use App\Models\Notification;
use Carbon\Carbon;

class CheckRentalExpirations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ebike:check-rental-expirations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for upcoming and expired e-bike rentals and dispatches user notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // 1. Fetch active rental items
        $rentalItems = OrderItem::whereNotNull('rental_start_date')
            ->whereNotNull('rental_end_date')
            ->whereHas('order', function ($q) {
                $q->whereNotIn('status', ['cancelled', 'refunded']);
            })
            ->with(['order.user', 'product'])
            ->get();

        $expiringCount = 0;
        $expiredCount = 0;

        foreach ($rentalItems as $item) {
            $user = $item->order->user ?? null;
            if (!$user) continue;

            $endDate = Carbon::parse($item->rental_end_date)->startOfDay();
            $productName = $item->product->name ?? 'E-Bike';
            $orderNumber = $item->order->order_number ?? '';

            // Check if rental is expiring today or tomorrow (1-2 days left)
            if ($endDate->equalTo($today) || $endDate->equalTo($tomorrow)) {
                $alreadyNotified = Notification::where('user_id', $user->id)
                    ->where('type', 'rental_expiring')
                    ->whereJsonContains('data->order_item_id', $item->id)
                    ->exists();

                if (!$alreadyNotified) {
                    Notification::send(
                        $user->id,
                        'rental_expiring',
                        'E-Bike Rental Expiring Soon! ⏰',
                        "Your rental for {$productName} (Order #{$orderNumber}) is set to expire on " . $endDate->format('d M Y') . ". Extend your lease online or prepare for return.",
                        route('customer.rentals'),
                        'fa-clock',
                        ['order_item_id' => $item->id, 'order_number' => $orderNumber]
                    );
                    $expiringCount++;
                }
            }
            // Check if rental is already past end date (Overdue)
            elseif ($endDate->lt($today)) {
                $alreadyNotified = Notification::where('user_id', $user->id)
                    ->where('type', 'rental_expired')
                    ->whereJsonContains('data->order_item_id', $item->id)
                    ->exists();

                if (!$alreadyNotified) {
                    Notification::send(
                        $user->id,
                        'rental_expired',
                        'E-Bike Rental Expired / Overdue 🚨',
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
