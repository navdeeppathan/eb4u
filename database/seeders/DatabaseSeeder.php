<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Address;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\EBikeUnit;
use App\Models\MaintenanceRecord;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Coupon;
use App\Models\Review;
use App\Models\ProductDamageLog;
use App\Models\CmsBanner;
use App\Models\CmsPage;
use App\Models\Faq;
use App\Models\SystemSetting;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Settings
        SystemSetting::set('default_security_deposit', 150.00);
        SystemSetting::set('default_late_fee_per_day', 25.00);
        SystemSetting::set('vat_rate_percentage', 20.00);
        SystemSetting::set('store_name', 'E-Bike 4 U (UK)');
        SystemSetting::set('store_phone', '+44 (0) 20 7946 0912');
        SystemSetting::set('store_email', 'support@eb4u.co.uk');
        SystemSetting::set('store_address', '142 Regent Street, London, W1B 5SE, United Kingdom');

        // 2. Create Users (Admin & Customers)
        $admin = User::create([
            'name' => 'Admin Manager',
            'email' => 'admin@eb4u.co.uk',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '+44 7700 900077',
            'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
            'email_verified_at' => now(),
        ]);

        $customer = User::create([
            'name' => 'James Harrison',
            'email' => 'james@example.co.uk',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'phone' => '+44 7700 900123',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
            'email_verified_at' => now(),
        ]);

        Address::create([
            'user_id' => $customer->id,
            'type' => 'shipping',
            'name' => 'James Harrison',
            'phone' => '+44 7700 900123',
            'address_line_1' => '24 Kensington High Street',
            'address_line_2' => 'Flat 4B',
            'city' => 'London',
            'county' => 'Greater London',
            'postcode' => 'W8 6AG',
            'country' => 'United Kingdom',
            'is_default' => true,
        ]);

        // 3. Default System Settings
        SystemSetting::set('default_security_deposit', 250.00);

        // 4. Create Categories (Accessories only)
        $accessoryCategories = [
            'Helmets' => 'Certified high-protection cycling helmets with MIPS safety technology.',
            'Bike Lights' => 'Ultra-bright rechargeable LED front & rear lights for night safety.',
            'Bike Jackets' => 'Waterproof, breathable UK weather-resistant cycling jackets.',
            'Gloves' => 'Thermal winter & lightweight padded summer cycling gloves.',
            'Bike Locks' => 'Gold Sold Secure rated D-locks, chain locks & heavy duty security.',
            'Bags' => 'Waterproof pannier bags, handlebar bags & frame packs.',
            'Phone Holders' => 'Shockproof, vibration-damped handlebar smartphone mounts.',
            'Chargers' => 'Official fast chargers for Bosch, Shimano & Yamaha battery packs.',
            'Batteries' => 'Replacement & high-capacity auxiliary E-Bike batteries.',
            'Pumps' => 'High-pressure track pumps & portable mini hand pumps.',
            'Mudguards' => 'Full-coverage fenders & quick-release mud protection.',
            'Bike Covers' => 'Heavy-duty waterproof indoor & outdoor bike storage covers.',
            'Spare Parts' => 'E-Bike brake pads, chains, cassettes & inner tubes.',
            'Other Cycling Accessories' => 'Water bottles, mirrors, bells, tools & cleaning kits.',
        ];

        $categoryModels = [];
        $sort = 1;
        foreach ($accessoryCategories as $name => $desc) {
            $categoryModels[$name] = Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'type' => 'accessory',
                'description' => $desc,
                'sort_order' => $sort++,
                'is_active' => true,
            ]);
        }

        // 5. Create E-Bike Products (Category is null, only Weekly Rate & Deposit 250)
        $ebikes = [
            [
                'name' => 'Gazelle Ultimate C380 HMB Step-Through',
                'price' => 3499.00,
                'discount_price' => 3299.00,
                'stock' => 12,
                'rental_weekly' => 180.00,
                'deposit' => 250.00,
                'motor' => 'Bosch Performance Line 3.0 (75 Nm)',
                'battery' => 'Bosch PowerTube 625Wh',
                'range' => '75 Miles / 120 km',
                'charging' => '4.5 Hours',
                'warranty' => '10 Years Frame, 2 Years Motor & Battery',
                'featured' => true,
                'best_seller' => true,
                'most_rented' => true,
                'img' => 'https://images.unsplash.com/photo-1571068316344-75bc76f77890?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Haibike AllMtn 4 Full Suspension eMTB',
                'price' => 4899.00,
                'discount_price' => 4599.00,
                'stock' => 8,
                'rental_weekly' => 290.00,
                'deposit' => 250.00,
                'motor' => 'Yamaha PW-X3 (85 Nm)',
                'battery' => 'InTube 720Wh',
                'range' => '90 Miles / 145 km',
                'charging' => '5 Hours',
                'warranty' => '5 Years Frame, 2 Years Electronics',
                'featured' => true,
                'best_seller' => false,
                'most_rented' => true,
                'img' => 'https://images.unsplash.com/photo-1532298229144-0ec0c57515c7?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Specialized Turbo Vado 4.0 Commuter',
                'price' => 3600.00,
                'discount_price' => null,
                'stock' => 15,
                'rental_weekly' => 210.00,
                'deposit' => 250.00,
                'motor' => 'Specialized 2.0 (70 Nm)',
                'battery' => 'Specialized U2-710Wh',
                'range' => '80 Miles / 130 km',
                'charging' => '4 Hours',
                'warranty' => 'Lifetime Frame, 2 Years Motor',
                'featured' => true,
                'best_seller' => true,
                'most_rented' => false,
                'img' => 'https://images.unsplash.com/photo-1485965120184-e220f721d03e?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Trek Allant+ 9.9 Stagger Long Range',
                'price' => 5400.00,
                'discount_price' => 4999.00,
                'stock' => 6,
                'rental_weekly' => 320.00,
                'deposit' => 250.00,
                'motor' => 'Bosch Performance CX (85 Nm)',
                'battery' => 'DualBattery Ready 1125Wh total',
                'range' => '110 Miles / 175 km',
                'charging' => '6 Hours',
                'warranty' => 'Lifetime Frame, 2 Years Battery',
                'featured' => true,
                'best_seller' => false,
                'most_rented' => false,
                'img' => 'https://images.unsplash.com/photo-1507035895480-2b3156c31fc8?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Raleigh Stow-E-Way Compact Folding E-Bike',
                'price' => 1450.00,
                'discount_price' => 1299.00,
                'stock' => 20,
                'rental_weekly' => 120.00,
                'deposit' => 250.00,
                'motor' => 'TranzX Rear Hub Motor (45 Nm)',
                'battery' => '36V 250Wh TranzX Rack Battery',
                'range' => '30 Miles / 50 km',
                'charging' => '3 Hours',
                'warranty' => '5 Years Frame, 2 Years Motor',
                'featured' => false,
                'best_seller' => true,
                'most_rented' => true,
                'img' => 'https://images.unsplash.com/photo-1511994298241-608e28f14fde?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Cube Creo SL Expert Carbon Road E-Bike',
                'price' => 6200.00,
                'discount_price' => 5800.00,
                'stock' => 5,
                'rental_weekly' => 350.00,
                'deposit' => 250.00,
                'motor' => 'SL 1.1 Lightweight Motor (240W)',
                'battery' => 'SL1-320Wh Internal Battery',
                'range' => '80 Miles / 130 km',
                'charging' => '2.5 Hours',
                'warranty' => '5 Years Frame, 2 Years Motor',
                'featured' => true,
                'best_seller' => false,
                'most_rented' => false,
                'img' => 'https://images.unsplash.com/photo-1576435728678-68d0fbf94e91?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Haibike FatCurve 9.0 All-Terrain Fat Tire',
                'price' => 3899.00,
                'discount_price' => null,
                'stock' => 7,
                'rental_weekly' => 240.00,
                'deposit' => 250.00,
                'motor' => 'Bosch CX Performance (85 Nm)',
                'battery' => 'Bosch PowerPack 500Wh',
                'range' => '55 Miles / 90 km',
                'charging' => '4.5 Hours',
                'warranty' => '5 Years Frame',
                'featured' => false,
                'best_seller' => false,
                'most_rented' => false,
                'img' => 'https://images.unsplash.com/photo-1505705694340-019e1e335916?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Gazelle Medeo T9 City E-Bike',
                'price' => 2499.00,
                'discount_price' => 2299.00,
                'stock' => 18,
                'rental_weekly' => 150.00,
                'deposit' => 250.00,
                'motor' => 'Bosch Active Line Plus (50 Nm)',
                'battery' => 'Bosch PowerPack 400Wh',
                'range' => '50 Miles / 80 km',
                'charging' => '3.5 Hours',
                'warranty' => '10 Years Frame',
                'featured' => false,
                'best_seller' => true,
                'most_rented' => true,
                'img' => 'https://images.unsplash.com/photo-1528629297340-d1d461944d97?w=800&auto=format&fit=crop&q=80',
            ],
        ];

        foreach ($ebikes as $item) {
            $p = Product::create([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'type' => 'ebike',
                'product_tag' => 'rent',
                'category_id' => null, // E-Bikes do NOT have category
                'price' => $item['price'],
                'discount_price' => $item['discount_price'],
                'stock_quantity' => $item['stock'],
                'is_rental_eligible' => true,
                'rental_price_weekly' => $item['rental_weekly'],
                'rental_security_deposit' => $item['deposit'],
                'motor_specs' => $item['motor'],
                'battery_specs' => $item['battery'],
                'range_specs' => $item['range'],
                'charging_time' => $item['charging'],
                'warranty_specs' => $item['warranty'],
                'short_description' => 'Premium British UK standard electric bike engineered for top performance, maximum comfort, and reliability.',
                'description' => 'Full specification premium UK e-bike featuring top-tier motor technology, long-lasting battery range, hydraulic disc brakes, puncture-resistant tyres, and integrated lighting system. Perfect for daily urban commutes and long weekend countryside tours across Britain.',
                'specifications' => [
                    'Brakes' => 'Shimano MT200 Hydraulic Disc Brakes',
                    'Gears' => 'Shimano Deore 10-Speed Transmission',
                    'Display' => 'Bosch Intuvia 100 Smart LCD Display',
                    'Tyres' => 'Schwalbe Marathon E-Plus 28x2.00',
                    'Weight' => '24.5 kg',
                ],
                'is_featured' => $item['featured'],
                'is_best_seller' => $item['best_seller'],
                'is_most_rented' => $item['most_rented'],
                'is_new_arrival' => true,
                'is_active' => true,
            ]);

            // Add Gallery Images
            ProductImage::create([
                'product_id' => $p->id,
                'image_path' => $item['img'],
                'is_primary' => true,
                'sort_order' => 1,
            ]);

            ProductImage::create([
                'product_id' => $p->id,
                'image_path' => 'https://images.unsplash.com/photo-1485965120184-e220f721d03e?w=800&auto=format&fit=crop&q=80',
                'is_primary' => false,
                'sort_order' => 2,
            ]);

            // Variants (Size)
            foreach (['Medium', 'Large', 'Extra Large'] as $sz) {
                ProductVariant::create([
                    'product_id' => $p->id,
                    'name' => 'Frame Size: ' . $sz,
                    'price_modifier' => 0.00,
                    'stock_quantity' => 4,
                    'attributes' => ['size' => $sz, 'colour' => 'Matte Black'],
                ]);
            }

            // Create Physical Units in Fleet for Rental Tracking
            for ($u = 1; $u <= 4; $u++) {
                $unitCode = 'EB-UNIT-' . $p->id . '-00' . $u;
                $status = ($u === 4) ? 'maintenance' : (($u === 3) ? 'rented' : 'available');
                
                $unit = EBikeUnit::create([
                    'product_id' => $p->id,
                    'ebike_code' => $unitCode,
                    'serial_number' => 'SN-UK-' . strtoupper(Str::random(8)),
                    'frame_size' => ($u % 2 === 0) ? 'Large' : 'Medium',
                    'qr_code_data' => 'https://eb4u.co.uk/verify-unit/' . $unitCode,
                    'status' => $status,
                    'condition_notes' => 'Inspected and certified UK road safe.',
                ]);

                if ($status === 'maintenance') {
                    MaintenanceRecord::create([
                        'ebike_unit_id' => $unit->id,
                        'service_type' => 'routine',
                        'service_date' => now()->subDays(2),
                        'next_service_date' => now()->addDays(5),
                        'cost' => 45.00,
                        'technician_name' => 'Dave Miller (Senior Mechanic)',
                        'notes' => 'Brake pad replacement & chain lubrication.',
                        'damage_details' => 'Minor scuff on left pedal crank.',
                        'status' => 'in_progress',
                    ]);
                }
            }
        }

        // 6. Create Accessory Products (Always Sell, Never Rent)
        $accessories = [
            [
                'name' => 'Giro Manifest Spherical MIPS Bike Helmet',
                'category' => 'Helmets',
                'price' => 199.00,
                'discount_price' => 169.00,
                'stock' => 30,
                'img' => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Lezyne Strip Drive Pro 300+ Rear LED Light',
                'category' => 'Bike Lights',
                'price' => 55.00,
                'discount_price' => 48.00,
                'stock' => 50,
                'img' => 'https://images.unsplash.com/photo-1572111504021-46abd7c112ba?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Endura Luminite Waterproof UK Cycling Jacket',
                'category' => 'Bike Jackets',
                'price' => 135.00,
                'discount_price' => 115.00,
                'stock' => 25,
                'img' => 'https://images.unsplash.com/photo-1544441893-675973e31985?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Abus Granit XPlus 540 Gold Sold Secure D-Lock',
                'category' => 'Bike Locks',
                'price' => 110.00,
                'discount_price' => 95.00,
                'stock' => 40,
                'img' => 'https://images.unsplash.com/photo-1584441405886-bc45863446ed?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Ortlieb Waterproof City Pannier Bag Pair 40L',
                'category' => 'Bags',
                'price' => 140.00,
                'discount_price' => 125.00,
                'stock' => 20,
                'img' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Bosch Fast Charger 6A for PowerTube & PowerPack',
                'category' => 'Chargers',
                'price' => 165.00,
                'discount_price' => null,
                'stock' => 15,
                'img' => 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Muc-Off E-Bike Clean & Lube Care Kit',
                'category' => 'Other Cycling Accessories',
                'price' => 32.00,
                'discount_price' => 28.00,
                'stock' => 60,
                'img' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=800&auto=format&fit=crop&q=80',
            ],
        ];

        foreach ($accessories as $acc) {
            $p = Product::create([
                'name' => $acc['name'],
                'slug' => Str::slug($acc['name']),
                'type' => 'accessory',
                'product_tag' => 'sell', // Accessories are ALWAYS selling, NEVER rent
                'category_id' => $categoryModels[$acc['category']]->id,
                'price' => $acc['price'],
                'discount_price' => $acc['discount_price'],
                'stock_quantity' => $acc['stock'],
                'is_rental_eligible' => false,
                'short_description' => 'High quality official cycling accessory designed for reliability and daily UK weather durability.',
                'description' => 'Tough, certified cycling accessory engineered to British and European safety standards. Fits all standard E-Bikes and traditional bicycles.',
                'specifications' => [
                    'Warranty' => '2 Years Manufacturer Warranty',
                    'Material' => 'Reinforced Weatherproof Composite',
                ],
                'is_featured' => true,
                'is_best_seller' => true,
                'is_active' => true,
            ]);

            ProductImage::create([
                'product_id' => $p->id,
                'image_path' => $acc['img'],
                'is_primary' => true,
                'sort_order' => 1,
            ]);
        }

        // 7. Create Coupons
        Coupon::create([
            'code' => 'WELCOME10',
            'type' => 'percentage',
            'amount' => 10,
            'min_order_amount' => 50.00,
            'target_type' => 'all',
            'usage_limit' => 500,
            'used_count' => 14,
            'expires_at' => now()->addYear(),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'EBIKE50',
            'type' => 'fixed',
            'amount' => 50.00,
            'min_order_amount' => 1000.00,
            'target_type' => 'ebikes',
            'usage_limit' => 100,
            'used_count' => 5,
            'expires_at' => now()->addMonths(6),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'RENTAL20',
            'type' => 'percentage',
            'amount' => 20,
            'min_order_amount' => 100.00,
            'target_type' => 'rentals',
            'usage_limit' => 200,
            'used_count' => 8,
            'expires_at' => now()->addMonths(3),
            'is_active' => true,
        ]);

        // 8. Create Sample Orders (1 Rental Order + 1 Sales Order)
        $gazelleBike = Product::where('name', 'Gazelle Ultimate C380 HMB Step-Through')->first();
        $helmet = Product::where('name', 'Giro Manifest Spherical MIPS Bike Helmet')->first();
        $rentalUnit = EBikeUnit::where('product_id', $gazelleBike->id)->where('status', 'rented')->first();

        $rentalOrder = Order::create([
            'order_number' => 'UK-RNT-2026-1001',
            'user_id' => $customer->id,
            'type' => 'rental',
            'status' => 'active',
            'payment_status' => 'paid',
            'payment_type' => 'full',
            'advance_percentage' => 100.00,
            'advance_amount' => 245.00,
            'remaining_amount' => 0.00,
            'subtotal' => 245.00, // 7 days @ £35/day
            'tax_amount' => 49.00,
            'delivery_fee' => 0.00,
            'security_deposit_total' => 150.00,
            'discount_amount' => 0.00,
            'total_amount' => 245.00,
            'fulfillment_type' => 'pickup',
            'pickup_location' => 'Flagship Store - 142 Regent Street, London',
            'shipping_address' => [
                'name' => 'James Harrison',
                'address_line_1' => '24 Kensington High Street',
                'city' => 'London',
                'postcode' => 'W8 6AG',
                'country' => 'United Kingdom',
            ],
            'rental_start_date' => now()->subDays(2),
            'rental_end_date' => now()->addDays(5),
            'customer_notes' => 'Picked up in store, frame size Medium.',
        ]);

        OrderItem::create([
            'order_id' => $rentalOrder->id,
            'product_id' => $gazelleBike->id,
            'ebike_unit_id' => $rentalUnit ? $rentalUnit->id : null,
            'item_type' => 'rental',
            'product_name' => $gazelleBike->name,
            'unit_price' => 35.00,
            'quantity' => 1,
            'subtotal' => 245.00,
            'rental_start_date' => now()->subDays(2),
            'rental_end_date' => now()->addDays(5),
            'rental_days' => 7,
            'rental_rate' => 35.00,
            'security_deposit' => 150.00,
        ]);

        Payment::create([
            'order_id' => $rentalOrder->id,
            'transaction_id' => 'TXN-FULL-' . strtoupper(Str::random(8)),
            'payment_method' => 'card',
            'amount' => 245.00,
            'type' => 'full',
            'status' => 'completed',
            'notes' => 'Paid online.',
        ]);

        // Sample Product Damage & Customer Liability Logs
        $haibike = Product::where('name', 'Haibike AllMtn 4 Full Suspension eMTB')->first();
        $haibikeUnit = EBikeUnit::where('product_id', $haibike->id)->first();

        ProductDamageLog::create([
            'product_id' => $gazelleBike->id,
            'ebike_unit_id' => $rentalUnit ? $rentalUnit->id : null,
            'user_id' => $customer->id,
            'order_id' => $rentalOrder->id,
            'damage_type' => 'Brake Damage',
            'incident_date' => now()->subDays(1),
            'damage_description' => 'Rear hydraulic brake lever bent and fluid leak observed after off-road fall.',
            'repair_cost' => 65.00,
            'user_charge_amount' => 80.00,
            'user_payment_status' => 'pending',
            'repair_status' => 'under_repair',
            'repair_start_date' => now()->subDays(1),
            'repair_completion_date' => now()->addDays(3),
            'admin_notes' => 'Awaiting replacement lever assembly from UK supplier.',
        ]);

        if ($haibike && $haibikeUnit) {
            ProductDamageLog::create([
                'product_id' => $haibike->id,
                'ebike_unit_id' => $haibikeUnit->id,
                'user_id' => $customer->id,
                'order_id' => null,
                'damage_type' => 'Cosmetic Scratch',
                'incident_date' => now()->subDays(10),
                'damage_description' => 'Frame paint scuffed and chainstay protector torn during trail riding.',
                'repair_cost' => 45.00,
                'user_charge_amount' => 45.00,
                'user_payment_status' => 'paid',
                'repair_status' => 'repaired',
                'repair_start_date' => now()->subDays(10),
                'repair_completion_date' => now()->subDays(7),
                'admin_notes' => 'Customer paid via card at counter. Frame touched up.',
            ]);
        }

        // Sales Order
        $salesOrder = Order::create([
            'order_number' => 'UK-ORD-2026-2005',
            'user_id' => $customer->id,
            'type' => 'purchase',
            'status' => 'delivered',
            'payment_status' => 'paid',
            'payment_type' => 'full',
            'subtotal' => 169.00,
            'tax_amount' => 33.80,
            'delivery_fee' => 0.00,
            'total_amount' => 169.00,
            'fulfillment_type' => 'pickup',
            'pickup_location' => 'Flagship Store - 142 Regent Street, London',
            'shipping_address' => [
                'name' => 'James Harrison',
                'address_line_1' => '24 Kensington High Street',
                'city' => 'London',
                'postcode' => 'W8 6AG',
                'country' => 'United Kingdom',
            ],
        ]);

        OrderItem::create([
            'order_id' => $salesOrder->id,
            'product_id' => $helmet->id,
            'item_type' => 'purchase',
            'product_name' => $helmet->name,
            'unit_price' => 169.00,
            'quantity' => 1,
            'subtotal' => 169.00,
        ]);

        Payment::create([
            'order_id' => $salesOrder->id,
            'transaction_id' => 'TXN-FULL-' . strtoupper(Str::random(8)),
            'payment_method' => 'card',
            'amount' => 169.00,
            'type' => 'full',
            'status' => 'completed',
            'notes' => 'Full payment completed via Stripe Card.',
        ]);

        // 9. Customer Reviews
        Review::create([
            'user_id' => $customer->id,
            'product_id' => $gazelleBike->id,
            'rating' => 5,
            'title' => 'Unbelievable smooth electric boost for London hills!',
            'comment' => 'Rented the Gazelle C380 for a week in London. The Bosch motor makes commuting effortless and the step-through frame is super comfortable. Smooth pickup process at the Regent Street store!',
            'status' => 'approved',
            'is_featured' => true,
        ]);

        // 10. CMS Banners
        CmsBanner::create([
            'title' => 'Experience the Future of British Cycling',
            'subtitle' => 'Premium E-Bikes for Sale & Flexible Rental across the UK',
            'badge' => 'UK #1 E-BIKE PLATFORM',
            'button_text' => 'Explore E-Bikes',
            'button_url' => '/catalog?type=ebike',
            'image' => 'https://images.unsplash.com/photo-1571068316344-75bc76f77890?w=1600&auto=format&fit=crop&q=80',
            'position' => 'home_hero',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // 11. FAQs
        Faq::create([
            'category' => 'Rental',
            'question' => 'How does E-Bike rental work?',
            'answer' => 'Select your preferred E-Bike model, choose your rental dates, check real-time availability, select home delivery or store pickup, pay online, and receive your fully serviced E-Bike!',
        ]);

        Faq::create([
            'category' => 'Rental',
            'question' => 'What is the security deposit?',
            'answer' => 'A security deposit is temporarily held during your rental period to cover potential damage or late return. It is fully refunded upon safe return of the E-Bike.',
        ]);

        Faq::create([
            'category' => 'Purchases',
            'question' => 'What warranty comes with purchased E-Bikes?',
            'answer' => 'All new E-Bikes come with full UK manufacturer warranty (typically 5 to 10 years on frames and 2 years on motor and battery).',
        ]);

        // 12. CMS Policy Pages
        $policies = [
            'about-us' => [
                'title' => 'About E-Bike 4 U',
                'content' => 'E-Bike 4 U is Britain\'s leading specialized e-commerce and rental platform for premium electric bicycles and cycling accessories. Headquartered in London, we provide top-tier German and British e-bikes with flexible daily, weekly, and monthly rental plans, alongside full retail sales and store pickup in London.'
            ],
            'privacy-policy' => [
                'title' => 'Privacy Policy',
                'content' => 'We strictly adhere to UK GDPR regulations. Your personal information and payment records are fully encrypted and protected.'
            ],
            'terms-and-conditions' => [
                'title' => 'Terms & Conditions',
                'content' => 'These terms govern all E-Bike sales, rental agreements, security deposit holdings, and store policies across the UK.'
            ],
            'rental-policy' => [
                'title' => 'E-Bike Rental Policy',
                'content' => 'Renter must be at least 18 years of age. All rental bikes must be locked with Gold Sold Secure locks provided. Overdue returns incur a standard daily late fee.'
            ],
            'refund-policy' => [
                'title' => 'Refund & Cancellation Policy',
                'content' => 'Rental cancellations made at least 48 hours prior to start date receive a full refund of online payments.'
            ],
            'shipping-policy' => [
                'title' => 'Store Pickup Policy',
                'content' => 'All orders (sales & rentals) are collected directly at our London flagship store (142 Regent Street, London). Please bring valid photo ID and proof of address when picking up.'
            ],
        ];

        foreach ($policies as $slug => $p) {
            CmsPage::create([
                'title' => $p['title'],
                'slug' => $slug,
                'content' => $p['content'],
                'is_active' => true,
            ]);
        }
    }
}
