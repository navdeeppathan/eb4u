-- SQL Schema for Rental Products Logs & Damage/Repair Management
-- Project: E-Bike 4 U (UK) Admin Panel

CREATE TABLE IF NOT EXISTS `product_damage_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `ebike_unit_id` BIGINT UNSIGNED NULL,
  `user_id` BIGINT UNSIGNED NULL COMMENT 'Customer who damaged the product',
  `order_id` BIGINT UNSIGNED NULL COMMENT 'Associated rental order',
  `damage_type` VARCHAR(100) NOT NULL DEFAULT 'Accident',
  `incident_date` DATE NOT NULL,
  `damage_description` TEXT NOT NULL,
  `repair_cost` DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT 'Shop expense to repair',
  `user_charge_amount` DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT 'Amount to recover from customer',
  `user_payment_status` ENUM('pending', 'paid', 'waived') NOT NULL DEFAULT 'pending',
  `repair_status` ENUM('under_repair', 'repaired', 'written_off') NOT NULL DEFAULT 'under_repair',
  `repair_start_date` DATE NULL,
  `repair_completion_date` DATE NULL COMMENT 'Date returned from repair / maintenance',
  `admin_notes` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_pdl_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pdl_ebike_unit` FOREIGN KEY (`ebike_unit_id`) REFERENCES `ebike_units` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_pdl_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_pdl_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample Data Insertion
INSERT INTO `product_damage_logs` 
(`product_id`, `ebike_unit_id`, `user_id`, `order_id`, `damage_type`, `incident_date`, `damage_description`, `repair_cost`, `user_charge_amount`, `user_payment_status`, `repair_status`, `repair_start_date`, `repair_completion_date`, `admin_notes`)
VALUES
(1, 4, 2, 1, 'Accident', '2026-09-10', 'Left handlebar cracked and disc brake bent during rental period.', 120.00, 150.00, 'pending', 'under_repair', '2026-09-11', '2026-09-18', 'Parts ordered from Shimano UK supplier.'),
(2, 8, 2, 1, 'Battery Damage', '2026-09-01', 'Battery lock casing damaged due to rough handling.', 85.00, 85.00, 'paid', 'repaired', '2026-09-02', '2026-09-05', 'Casing replaced and tested successfully.');
