<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ebike_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('ebike_code')->unique(); // e.g. EB-2026-001
            $table->string('serial_number')->unique();
            $table->string('frame_size')->nullable(); // e.g. M, L, XL
            $table->string('qr_code_data')->nullable();
            $table->enum('status', ['available', 'rented', 'maintenance', 'retired'])->default('available');
            $table->text('condition_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebike_units');
    }
};
