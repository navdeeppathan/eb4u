<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->enum('item_type', ['purchase', 'rental'])->default('purchase');
            $table->integer('quantity')->default(1);
            
            // Rental fields
            $table->date('rental_start_date')->nullable();
            $table->date('rental_end_date')->nullable();
            $table->integer('rental_days')->nullable();
            $table->enum('rental_plan', ['daily', 'weekly', 'monthly'])->nullable();
            $table->decimal('daily_rate', 10, 2)->nullable();
            $table->decimal('security_deposit', 10, 2)->nullable();
            $table->json('options')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
