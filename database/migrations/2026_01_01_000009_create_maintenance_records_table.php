<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ebike_unit_id')->constrained('ebike_units')->onDelete('cascade');
            $table->enum('service_type', ['routine', 'repair', 'inspection', 'battery_check', 'brake_service'])->default('routine');
            $table->date('service_date');
            $table->date('next_service_date')->nullable();
            $table->decimal('cost', 10, 2)->default(0.00);
            $table->string('technician_name')->nullable();
            $table->text('notes')->nullable();
            $table->text('damage_details')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_records');
    }
};
