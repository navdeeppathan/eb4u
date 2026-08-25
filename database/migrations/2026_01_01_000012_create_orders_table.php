<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['purchase', 'rental', 'mixed'])->default('purchase');
            
            // Order Status (for both selling and rental lifecycle)
            $table->enum('status', [
                'pending',
                'confirmed',
                'processing',
                'packed',
                'shipped',
                'delivered',
                'ready_for_pickup',
                'picked_up',
                'active',
                'extension_requested',
                'return_requested',
                'returned',
                'completed',
                'cancelled',
                'overdue',
                'refunded'
            ])->default('pending');
            
            // Payment Status & Advance Calculation
            $table->enum('payment_status', ['unpaid', 'partially_paid', 'paid', 'refunded'])->default('unpaid');
            $table->enum('payment_type', ['full', 'advance'])->default('full');
            $table->decimal('advance_percentage', 5, 2)->default(100.00);
            $table->decimal('advance_amount', 10, 2)->default(0.00);
            $table->decimal('remaining_amount', 10, 2)->default(0.00);
            
            // Financials
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('tax_amount', 10, 2)->default(0.00); // UK VAT 20%
            $table->decimal('delivery_fee', 10, 2)->default(0.00);
            $table->decimal('security_deposit_total', 10, 2)->default(0.00);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->string('coupon_code')->nullable();
            
            // Fulfillment Details
            $table->enum('fulfillment_type', ['delivery', 'pickup'])->default('delivery');
            $table->json('shipping_address')->nullable();
            $table->json('billing_address')->nullable();
            $table->string('pickup_location')->nullable();
            
            // Global Rental Dates (for rental orders)
            $table->date('rental_start_date')->nullable();
            $table->date('rental_end_date')->nullable();
            $table->date('actual_return_date')->nullable();
            $table->decimal('late_fee_charged', 10, 2)->default(0.00);
            $table->decimal('damage_fee_charged', 10, 2)->default(0.00);
            
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
