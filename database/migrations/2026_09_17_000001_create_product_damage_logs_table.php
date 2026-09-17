<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_damage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('ebike_unit_id')->nullable()->constrained('ebike_units')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            
            $table->string('damage_type')->default('Accident');
            $table->date('incident_date');
            $table->text('damage_description');
            
            // Financials (Expenses & Recovery)
            $table->decimal('repair_cost', 10, 2)->default(0.00); // shop expense
            $table->decimal('user_charge_amount', 10, 2)->default(0.00); // customer liability
            $table->enum('user_payment_status', ['pending', 'paid', 'waived'])->default('pending');
            
            // Repair & Maintenance Lifecycle
            $table->enum('repair_status', ['under_repair', 'repaired', 'written_off'])->default('under_repair');
            $table->date('repair_start_date')->nullable();
            $table->date('repair_completion_date')->nullable();
            
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_damage_logs');
    }
};
