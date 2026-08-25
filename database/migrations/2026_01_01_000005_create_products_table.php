<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->enum('type', ['ebike', 'accessory'])->default('ebike');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');
            
            // Purchase Pricing & Stock
            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            
            // Rental Config
            $table->boolean('is_rental_eligible')->default(false);
            $table->decimal('rental_price_daily', 10, 2)->nullable();
            $table->decimal('rental_price_weekly', 10, 2)->nullable();
            $table->decimal('rental_price_monthly', 10, 2)->nullable();
            $table->decimal('rental_security_deposit', 10, 2)->nullable();
            
            // E-Bike Specs
            $table->string('motor_specs')->nullable();
            $table->string('battery_specs')->nullable();
            $table->string('range_specs')->nullable();
            $table->string('charging_time')->nullable();
            $table->string('warranty_specs')->nullable();
            
            // Descriptions & Specs
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->json('specifications')->nullable();
            
            // Marketing flags & Status
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_best_seller')->default(false);
            $table->boolean('is_most_rented')->default(false);
            $table->boolean('is_new_arrival')->default(false);
            $table->boolean('is_active')->default(true);
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
