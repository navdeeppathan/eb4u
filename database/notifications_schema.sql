-- ==============================================================================
-- Notifications Table Schema for eb4u UK Platform
-- Manual Execution SQL (As requested: NO artisan migrate ran)
-- ==============================================================================

CREATE TABLE IF NOT EXISTS `notifications` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `type` VARCHAR(100) NOT NULL COMMENT 'e.g. order_placed, rental_booked, rental_expiring, rental_expired, rental_extended, return_requested, review_approved, order_status_update, unit_assigned',
    `title` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `action_url` VARCHAR(255) NULL,
    `icon` VARCHAR(50) DEFAULT 'fa-bell',
    `is_read` TINYINT(1) DEFAULT 0,
    `data` JSON NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `notifications_user_id_is_read_idx` (`user_id`, `is_read`),
    CONSTRAINT `fk_notifications_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
