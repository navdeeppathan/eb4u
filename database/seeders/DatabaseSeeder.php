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
        $termsHtml = <<<'HTML'
<!-- Breadcrumb -->
<div class="border-b border-borderLight bg-[#edf1f8] text-xs">
    <div class="max-w-[1320px] mx-auto px-6 py-3 flex items-center gap-2 text-textMuted font-medium">
        <a href="/" class="hover:text-darkSlate-900 transition-colors">Home</a>
        <span>/</span>
        <span class="text-darkSlate-900 font-bold">Terms & Conditions</span>
    </div>
</div>

<div class="max-w-[1050px] mx-auto px-6 py-12">
    <div class="bg-white rounded-3xl border border-borderLight shadow-xs p-8 md:p-12 space-y-10">
        
        <!-- Page Header -->
        <div class="border-b border-borderLight pb-8">
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="bg-brandOrange-50 text-brandOrange-600 text-xs font-bold uppercase px-3.5 py-1.5 rounded-full border border-brandOrange-500/20">
                    Official UK Rental & Platform Agreement
                </span>
                <span class="bg-slate-100 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-full border border-slate-200">
                    Governing Law: England & Wales
                </span>
            </div>
            <h1 class="font-grotesk text-3xl md:text-5xl font-extrabold text-darkSlate-900 tracking-tight">Terms & Conditions</h1>
            <p class="text-xs md:text-sm text-textMuted font-medium mt-2">
                Last updated: September 2026 | eb4u Ltd (Company Reg: 12849201) | Operating in the United Kingdom
            </p>

            <!-- Quick Highlight Banner -->
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="p-3.5 bg-[#f8fafc] rounded-2xl border border-slate-200 text-center">
                    <div class="text-xs text-textMuted font-medium uppercase tracking-wider">Min. Rental</div>
                    <div class="text-sm font-extrabold text-darkSlate-900 mt-0.5">2 Weeks Minimum</div>
                </div>
                <div class="p-3.5 bg-[#f8fafc] rounded-2xl border border-slate-200 text-center">
                    <div class="text-xs text-textMuted font-medium uppercase tracking-wider">Security Deposit</div>
                    <div class="text-sm font-extrabold text-darkSlate-900 mt-0.5">£250 Refundable</div>
                </div>
                <div class="p-3.5 bg-[#f8fafc] rounded-2xl border border-slate-200 text-center">
                    <div class="text-xs text-textMuted font-medium uppercase tracking-wider">Rental Week</div>
                    <div class="text-sm font-extrabold text-brandOrange-600 mt-0.5">Monday to Monday</div>
                </div>
                <div class="p-3.5 bg-[#f8fafc] rounded-2xl border border-slate-200 text-center">
                    <div class="text-xs text-textMuted font-medium uppercase tracking-wider">Weekly Rates</div>
                    <div class="text-sm font-extrabold text-darkSlate-900 mt-0.5">£50 (Single) / £60 (Double)</div>
                </div>
            </div>
        </div>

        <!-- Section 1: Agreement & Applicability -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">1</span>
                Agreement to Terms & General Applicability
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                By creating an account, making an online booking, executing a rental contract, or purchasing products on <strong>eb4u.co.uk</strong> (operated by <strong>eb4u Ltd</strong>), you ("the Customer", "Renter", or "User") agree to be legally bound by these Terms & Conditions.
            </p>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                If you do not accept these terms in full, you must not hire or purchase electric vehicles or accessories from eb4u. These terms apply to all hire agreements, platform bookings, and vehicle usage across the UK.
            </p>
        </div>

        <!-- Section 2: Customer Identity Verification & Documents Required -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">2</span>
                Customer Identity Verification & Required Documents
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                To comply with UK regulations, prevent identity fraud, and validate rental contracts, every renter must submit valid UK proof of identity and proof of address prior to vehicle handover or dispatch:
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-5 bg-[#f8fafc] rounded-2xl border border-slate-200 space-y-2">
                    <div class="flex items-center gap-2 text-darkSlate-900 font-bold text-sm">
                        <i class="fa-solid fa-id-card text-brandOrange-500"></i>
                        <span>1. Proof of Identity (Photo ID)</span>
                    </div>
                    <p class="text-xs text-textSec leading-relaxed">
                        Renters must provide a valid, unexpired government-issued photo ID. Acceptable identity documents:
                    </p>
                    <ul class="list-disc list-inside text-xs text-textSec space-y-1 font-medium pl-1">
                        <li><strong>Valid Passport</strong> (UK or International)</li>
                        <li><strong>UK Visa / Biometric Residence Permit (BRP)</strong></li>
                        <li><strong>Valid UK Driving License</strong> (Full or Provisional)</li>
                    </ul>
                </div>

                <div class="p-5 bg-[#f8fafc] rounded-2xl border border-slate-200 space-y-2">
                    <div class="flex items-center gap-2 text-darkSlate-900 font-bold text-sm">
                        <i class="fa-solid fa-house-user text-brandOrange-500"></i>
                        <span>2. Proof of Address in the UK</span>
                    </div>
                    <p class="text-xs text-textSec leading-relaxed">
                        Renters must provide a valid UK proof of address document dated within the <strong>last 3 months</strong>:
                    </p>
                    <ul class="list-disc list-inside text-xs text-textSec space-y-1 font-medium pl-1">
                        <li><strong>UK Utility Bill</strong> (Gas, Electricity, Water, or Landline)</li>
                        <li><strong>UK Bank or Building Society Statement</strong></li>
                        <li><strong>Council Tax Bill</strong> or Official Tenancy Agreement</li>
                        <li>Official government letter (HMRC, DWP, or NHS)</li>
                    </ul>
                </div>
            </div>

            <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200 flex items-start gap-3">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-1"></i>
                <p class="text-xs text-amber-900 leading-relaxed font-medium">
                    <strong>Mandatory Verification Policy:</strong> E-bike collection in store will be strictly withheld until valid Proof of ID (Passport, Visa/BRP, or UK License) and Proof of Address are verified by eb4u.
                </p>
            </div>
        </div>

        <!-- Section 3: Rental Pricing & Security Deposit -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">3</span>
                Rental Rates & Security Deposit
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                eb4u provides commercial and personal electric bike rentals billed on a weekly rate based on battery configuration:
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-5 bg-emerald-50/50 rounded-2xl border border-emerald-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-grotesk font-extrabold text-slate-900 text-base">Single Battery E-Bike</span>
                        <span class="bg-emerald-600 text-white font-extrabold text-xs px-3 py-1 rounded-full">£50 / Week</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Standard e-bike equipped with 1 removable lithium battery pack. Rent is billed at <strong>£50 per week</strong>, payable strictly in advance.
                    </p>
                </div>

                <div class="p-5 bg-blue-50/50 rounded-2xl border border-blue-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-grotesk font-extrabold text-slate-900 text-base">Double Battery E-Bike</span>
                        <span class="bg-blue-600 text-white font-extrabold text-xs px-3 py-1 rounded-full">£60 / Week</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Extended-range dual battery e-bike equipped with 2 lithium battery packs. Rent is billed at <strong>£60 per week</strong>, payable strictly in advance.
                    </p>
                </div>
            </div>

            <!-- Deposit Box -->
            <div class="p-5 bg-[#f8fafc] rounded-2xl border border-slate-200 space-y-2">
                <h4 class="font-grotesk font-bold text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-vault text-brandOrange-500"></i> £250 Refundable Security Deposit
                </h4>
                <p class="text-xs md:text-sm text-textSec leading-relaxed">
                    A refundable security deposit of <strong>£250</strong> is mandatory for every e-bike hire. The deposit is held prior to vehicle release and will be refunded in full upon return of the e-bike in undamaged condition, alongside all original accessories (keys, charger, battery unit, lock). Deductions will be made for unpaid rent, missing accessories, or accidental damage.
                </p>
            </div>
        </div>

        <!-- Section 4: Minimum Rental Duration & Monday-to-Monday Billing Cycle -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">4</span>
                Minimum Rental Duration & "Monday-to-Monday" Billing Policy
            </h2>
            
            <div class="space-y-3">
                <div class="flex items-start gap-3 p-4 bg-amber-50 rounded-2xl border border-amber-200">
                    <i class="fa-solid fa-clock text-amber-600 mt-1"></i>
                    <div>
                        <h4 class="font-bold text-xs md:text-sm text-amber-900 mb-1">Minimum 2 Weeks Rental Requirement</h4>
                        <p class="text-xs md:text-sm text-amber-800 leading-relaxed">
                            The minimum contract commitment for any e-bike hire is <strong>two (2) consecutive weeks</strong>. Rental periods under 14 days are not available.
                        </p>
                    </div>
                </div>

                <div class="p-6 bg-slate-900 text-white rounded-2xl space-y-4 shadow-md">
                    <div class="flex items-center gap-2 text-brandOrange-400 font-bold text-xs uppercase tracking-wider">
                        <i class="fa-solid fa-calendar-days"></i> Strict Rental Week Cycle Rules
                    </div>
                    <h3 class="font-grotesk text-lg font-bold text-white">Rental Week is Defined strictly as Monday to Monday</h3>
                    <p class="text-xs md:text-sm text-slate-300 leading-relaxed">
                        Our rental billing week operates on a strict <strong>Monday to Monday schedule</strong>. Rental charges are calculated on full weekly blocks from Monday to Monday, regardless of the day of pickup.
                    </p>
                    
                    <div class="p-4 bg-slate-800/90 rounded-xl border border-slate-700 space-y-2">
                        <div class="font-bold text-xs text-brandOrange-400 uppercase tracking-wide">
                            <i class="fa-solid fa-circle-info mr-1"></i> Mid-Week Pickup Example & Billing Rule:
                        </div>
                        <p class="text-xs md:text-sm text-slate-200 leading-relaxed">
                            If a customer collects a bike on a <strong>Tuesday</strong>, the rent paid covers the initial period up to the following <strong>Monday</strong>.
                        </p>
                        <p class="text-xs md:text-sm text-slate-200 leading-relaxed">
                            If the bike is kept into or returned on the subsequent <strong>Tuesday</strong> (entering a new Monday-to-Monday cycle), the customer is required to pay for <strong>another full week's rent</strong>. Rent is not pro-rated on a daily basis.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 5: Care of Vehicle & Customer Responsibilities -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">5</span>
                Care of E-Bike & Rider Responsibilities
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                The Customer has total custody of the vehicle during the rental period and is responsible for treating the bike with high care and diligence:
            </p>
            <ul class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                <li class="p-4 bg-[#f8fafc] rounded-2xl border border-slate-200 flex items-start gap-3">
                    <i class="fa-solid fa-shield-halved text-brandOrange-500 mt-1"></i>
                    <div>
                        <strong class="text-xs md:text-sm text-slate-900 block mb-0.5">Secure Locking Duty</strong>
                        <span class="text-xs text-slate-600">Must lock the e-bike through the frame to an immovable object using an eb4u-approved Gold-rated lock whenever left unattended.</span>
                    </div>
                </li>
                <li class="p-4 bg-[#f8fafc] rounded-2xl border border-slate-200 flex items-start gap-3">
                    <i class="fa-solid fa-battery-charging text-brandOrange-500 mt-1"></i>
                    <div>
                        <strong class="text-xs md:text-sm text-slate-900 block mb-0.5">Battery & Charging Care</strong>
                        <span class="text-xs text-slate-600">Only use the official eb4u charger provided. Protect battery packs from extreme moisture, submersion, or direct impact.</span>
                    </div>
                </li>
                <li class="p-4 bg-[#f8fafc] rounded-2xl border border-slate-200 flex items-start gap-3">
                    <i class="fa-solid fa-ban text-brandOrange-500 mt-1"></i>
                    <div>
                        <strong class="text-xs md:text-sm text-slate-900 block mb-0.5">Prohibited Modifications</strong>
                        <span class="text-xs text-slate-600">Tampering with speed limiters, controllers, motors, or adding unauthorized electronic accessories is strictly illegal and prohibited.</span>
                    </div>
                </li>
                <li class="p-4 bg-[#f8fafc] rounded-2xl border border-slate-200 flex items-start gap-3">
                    <i class="fa-solid fa-user-shield text-brandOrange-500 mt-1"></i>
                    <div>
                        <strong class="text-xs md:text-sm text-slate-900 block mb-0.5">Authorized Riding Only</strong>
                        <span class="text-xs text-slate-600">Only the registered customer who signed the hire agreement and submitted ID is permitted to operate the e-bike. Sub-letting is strictly forbidden.</span>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Section 6: Technical Problems vs. Accidents & Damage -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">6</span>
                Maintenance, Technical Faults vs. Accidental Damage
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                Our policy clearly separates manufacturing/mechanical faults from rider-caused damage:
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- eb4u Responsibility -->
                <div class="p-5 bg-emerald-50/70 rounded-2xl border border-emerald-200 space-y-3">
                    <div class="flex items-center gap-2 text-emerald-800 font-bold text-sm">
                        <i class="fa-solid fa-screwdriver-wrench text-emerald-600"></i>
                        <span>Inherent Technical Problems (Covered by eb4u)</span>
                    </div>
                    <p class="text-xs text-emerald-950 leading-relaxed">
                        If the bike develops a technical breakdown under normal riding conditions—such as motor failure, controller defects, electrical system malfunction, or internal mechanical failure not caused by external impact:
                    </p>
                    <ul class="list-disc list-inside text-xs text-emerald-900 space-y-1 font-medium">
                        <li><strong>eb4u is responsible</strong> for repairing the fault or providing a replacement vehicle.</li>
                        <li>Standard maintenance and routine wear-and-tear repairs are free of charge.</li>
                        <li>Renter must notify eb4u immediately upon discovering any technical issue.</li>
                    </ul>
                </div>

                <!-- Customer Responsibility -->
                <div class="p-5 bg-rose-50/70 rounded-2xl border border-rose-200 space-y-3">
                    <div class="flex items-center gap-2 text-rose-800 font-bold text-sm">
                        <i class="fa-solid fa-car-burst text-rose-600"></i>
                        <span>Accidents & User Damage (Customer Responsibility)</span>
                    </div>
                    <p class="text-xs text-rose-950 leading-relaxed">
                        If the bike suffers damage due to an accident, crash, fall, collision, water immersion, bad storage, or user misuse/negligence:
                    </p>
                    <ul class="list-disc list-inside text-xs text-rose-900 space-y-1 font-medium">
                        <li><strong>Customer is fully responsible</strong> to pay the complete cost of repairs and replacement parts.</li>
                        <li>Repair costs will be deducted from the £250 deposit or billed directly.</li>
                        <li>Includes damage to wheels, frame, brakes, battery casing, display screens, or lights.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Section 7: Stolen Bike & Police Custody Policy -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">7</span>
                Stolen E-Bikes & Police Custody / Seizure Policy
            </h2>
            
            <div class="p-6 bg-red-950 text-white rounded-3xl border border-red-800 space-y-4 shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center font-black text-lg">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h3 class="font-grotesk text-lg font-bold text-white">Full Financial Liability for Stolen or Confiscated Bikes</h3>
                        <p class="text-xs text-red-200">Customer Responsibility Clause under UK Hire Agreement</p>
                    </div>
                </div>

                <div class="space-y-3 text-xs md:text-sm text-red-100 leading-relaxed">
                    <p>
                        <strong>1. Stolen E-Bike Liability:</strong> If the e-bike is stolen during your rental period, <strong>the Customer has the sole legal and financial responsibility to pay the full value / full market cost of the e-bike to eb4u Ltd</strong>.
                    </p>
                    <p>
                        <strong>2. Police Custody / Impoundment / Seizure:</strong> If the e-bike is stopped, impounded, seized, or taken into police custody for any reason whatsoever (including traffic stops, rider inspection, lack of helmet/license where applicable, or police investigations), <strong>the Customer remains fully responsible to pay the full value of the bike to eb4u Ltd</strong> alongside any impound recovery fees.
                    </p>
                    <p>
                        <strong>3. Mandatory Theft Reporting Procedure:</strong> In the event of theft, the customer must report the incident to the UK Police immediately within 24 hours, obtain an official Crime Reference Number (CRN), and inform eb4u Support. Obtaining a CRN does not exempt the customer from paying the full value of the bike.
                    </p>
                    <p>
                        <strong>4. Deposit & Direct Invoicing:</strong> The £250 security deposit will be forfeited immediately and applied toward the balance of the bike cost. The customer will be invoiced for the remaining balance of the full bike value, payable within 7 business days.
                    </p>
                </div>
            </div>
        </div>

        <!-- Section 8: Overdue Payments & Default -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">8</span>
                Rental Payments, Late Fees & Default
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                Rent is due strictly every Monday in advance for the upcoming week. If rental payments fail or become overdue:
            </p>
            <ul class="list-disc list-inside text-xs md:text-sm text-textSec space-y-2 pl-2 font-medium">
                <li>A late payment administrative fee of £15 will be applied for payments delayed beyond 24 hours.</li>
                <li>eb4u reserves the right to remotely track, immobilize, or repossess the e-bike if rent remains unpaid for over 3 days.</li>
                <li>Unpaid balances will be referred to debt collection agencies and legal courts in England & Wales.</li>
            </ul>
        </div>

        <!-- Section 9: E-Bike Purchases & Sales Terms -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">9</span>
                E-Bike Purchase Terms & Consumer Rights
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                For customers purchasing new or refurbished e-bikes directly from eb4u:
            </p>
            <ul class="list-disc list-inside text-xs md:text-sm text-textSec space-y-2 pl-2 font-medium">
                <li><strong>UK EAPC Compliance:</strong> All sold e-bikes comply with British Electrically Assisted Pedal Cycles regulations (250W rating, maximum assisted speed of 15.5 mph / 25 km/h).</li>
                <li><strong>Manufacturer Warranty:</strong> New e-bikes include a 2-year warranty covering frame, motor, and battery manufacturing defects.</li>
                <li><strong>14-Day Consumer Right to Return:</strong> Under Consumer Contracts Regulations 2013, online sales can be returned within 14 days of receipt, provided the vehicle is unused and in original packaging.</li>
            </ul>
        </div>

        <!-- Section 10: Governing Law -->
        <div class="space-y-4">
            <h2 class="font-grotesk text-xl font-bold text-darkSlate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-brandOrange-50 text-brandOrange-500 flex items-center justify-center text-sm font-black border border-brandOrange-500/20 shadow-xs">10</span>
                Governing Law & Jurisdiction
            </h2>
            <p class="text-xs md:text-sm text-textSec leading-relaxed">
                These Terms & Conditions are governed by and construed in accordance with the laws of <strong>England & Wales</strong>. Any legal dispute or claim arising under or in connection with these Terms shall be subject to the exclusive jurisdiction of the Courts of England and Wales.
            </p>
        </div>

        <!-- Section 11: Customer Support Contact -->
        <div class="p-6 bg-[#f5f7fb] rounded-2xl border border-borderLight flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="font-grotesk text-sm font-bold text-darkSlate-900">Questions about our Terms or Rental Agreement?</h3>
                <p class="text-xs text-textSec mt-0.5">Our UK customer care team is available to assist you 7 days a week.</p>
            </div>
            <a href="/contact" class="px-6 py-3 bg-brandOrange-500 hover:bg-brandOrange-600 text-white font-bold text-xs rounded-xl shadow-sm whitespace-nowrap transition-colors flex items-center gap-2">
                <i class="fa-solid fa-headset"></i> Contact Customer Support
            </a>
        </div>

    </div>
</div>
HTML;

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
                'content' => $termsHtml,
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
