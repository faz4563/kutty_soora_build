-- ============================================================================
--  kuttysoora seafoods — Complete Database Creation Script
-- ============================================================================
--  Creates the full database + all tables used by the PHP backend.
--
--  HOW TO USE
--  ----------
--  Option A (command line):
--      mysql -u root -p < backend/database.sql
--
--  Option B (phpMyAdmin / Adminer):
--      Import  →  choose backend/database.sql  →  Go
--
--  The script is idempotent for a fresh install: it drops existing tables
--  (in foreign-key-safe order) and recreates everything from scratch.
--
--  After import:
--    1. Create an admin user + set its password via:
--       backend/admin/set_admin_password.php
--    2. Add products through the admin panel or
--       backend/import_products.php
-- ============================================================================

-- ----------------------------------------------------------------------------
--  1. CREATE DATABASE
-- ----------------------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `kuttysoora_seafood`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `kuttysoora_seafood`;

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------------------------------
--  2. DROP TABLES (fresh install — child tables first for FK safety)
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `payment_logs`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `cart`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `users`;

-- ----------------------------------------------------------------------------
--  3. USERS
--     Customer + admin accounts. Auth is mobile-first (JWT); admins also
--     carry a bcrypt `password_hash` verified in backend/login.php.
-- ----------------------------------------------------------------------------
CREATE TABLE `users` (
  `id`               INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `name`             VARCHAR(100)    NOT NULL DEFAULT '',
  `email`            VARCHAR(255)    NOT NULL DEFAULT '',
  `mobile`           VARCHAR(20)     NOT NULL,
  `password_hash`    VARCHAR(255)             DEFAULT NULL,  -- bcrypt, admins only
  `role`             ENUM('user','admin')     NOT NULL DEFAULT 'user',
  `is_admin`         TINYINT(1)      NOT NULL DEFAULT 0,
  `email_verified`   TINYINT(1)      NOT NULL DEFAULT 0,
  `mobile_verified`  TINYINT(1)      NOT NULL DEFAULT 0,
  `status`           ENUM('active','inactive','blocked') NOT NULL DEFAULT 'active',
  `last_login`       DATETIME                 DEFAULT NULL,
  `login_attempts`   INT             NOT NULL DEFAULT 0,
  `address`          TEXT                     DEFAULT NULL,
  `house`            VARCHAR(255)             DEFAULT '',
  `street`           VARCHAR(255)             DEFAULT '',
  `area`             VARCHAR(255)             DEFAULT '',
  `city`             VARCHAR(100)             DEFAULT '',
  `pin_code`         VARCHAR(10)              DEFAULT '',
  `landmark`         VARCHAR(255)             DEFAULT '',
  `referral`         VARCHAR(255)             DEFAULT '',
  `created_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                                       ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_mobile` (`mobile`),
  KEY `idx_users_role` (`role`),
  KEY `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
--  4. PRODUCTS
--     Catalog with searchable name/description/category (FULLTEXT used by
--     backend/products.php MATCH ... AGAINST). `health_benefits`,
--     `nutritional_info`, `product_uses`, `images` are JSON-encoded strings.
-- ----------------------------------------------------------------------------
CREATE TABLE `products` (
  `id`                INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `sku`               VARCHAR(50)              DEFAULT NULL,
  `name`              VARCHAR(255)    NOT NULL,
  `category`          VARCHAR(100)             DEFAULT '',
  `description`       TEXT                     DEFAULT NULL,
  `price`             DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
  `stock`             INT             NOT NULL DEFAULT 0,
  `image_url`         VARCHAR(500)             DEFAULT '',
  `brand`             VARCHAR(100)             DEFAULT 'Kutty Soora',
  `availability`      ENUM('in_stock','out_of_stock','pre_order')
                                       NOT NULL DEFAULT 'in_stock',
  `minimum_quantity`  VARCHAR(50)              DEFAULT 'per_kg',
  `price_unit`        VARCHAR(20)              DEFAULT 'per_kg',
  `health_benefits`   TEXT                     DEFAULT NULL,  -- JSON array
  `nutritional_info`  TEXT                     DEFAULT NULL,  -- JSON array
  `product_uses`      TEXT                     DEFAULT NULL,  -- JSON array
  `is_special`        TINYINT(1)      NOT NULL DEFAULT 0,
  `is_dry`            TINYINT(1)      NOT NULL DEFAULT 0,
  `weight`            VARCHAR(50)              DEFAULT NULL,
  `dimensions`        VARCHAR(50)              DEFAULT NULL,
  `material`          VARCHAR(100)             DEFAULT NULL,
  `color`             VARCHAR(50)              DEFAULT NULL,
  `images`            TEXT                     DEFAULT NULL,  -- JSON array of URLs
  `tags`              VARCHAR(500)             DEFAULT '',
  `created_date`      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated`      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                                       ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_products_sku` (`sku`),
  KEY `idx_products_category` (`category`),
  KEY `idx_products_availability` (`availability`),
  FULLTEXT KEY `ft_products_search` (`name`, `description`, `category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
--  5. CART
--     One row per (user, product). Quantity is DECIMAL so weight-based items
--     (e.g. 0.25 kg) work — see backend/admin/update_quantity_to_decimal.php.
-- ----------------------------------------------------------------------------
CREATE TABLE `cart` (
  `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `user_id`    INT UNSIGNED  NOT NULL,
  `product_id` INT UNSIGNED  NOT NULL,
  `quantity`   DECIMAL(10,2) NOT NULL DEFAULT 1.00,
  `created_at` DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
                                 ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cart_user_product` (`user_id`, `product_id`),
  KEY `idx_cart_product` (`product_id`),
  CONSTRAINT `fk_cart_user`    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)    ON DELETE CASCADE,
  CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`)
    REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
--  6. ORDERS
--     Order header. Total is always computed server-side (no client price
--     tampering). Razorpay fields are filled by create_razorpay_order.php
--     and verify_razorpay_payment.php.
-- ----------------------------------------------------------------------------
CREATE TABLE `orders` (
  `id`                   INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `user_id`              INT UNSIGNED  NOT NULL,
  `order_number`         VARCHAR(50)            DEFAULT NULL,
  `subtotal`             DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `total_amount`         DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `customer_name`        VARCHAR(100)           DEFAULT '',
  `customer_phone`       VARCHAR(20)            DEFAULT '',
  `delivery_address`     TEXT                   DEFAULT NULL,
  `payment_method`       VARCHAR(50)            DEFAULT 'razorpay',
  `payment_status`       ENUM('pending','initiated','paid','failed','refunded')
                                          NOT NULL DEFAULT 'pending',
  `status`               ENUM('pending','confirmed','processing','shipped',
                              'delivered','cancelled')
                                          NOT NULL DEFAULT 'pending',
  `razorpay_order_id`    VARCHAR(100)           DEFAULT NULL,
  `razorpay_payment_id`  VARCHAR(100)           DEFAULT NULL,
  `razorpay_signature`   VARCHAR(255)           DEFAULT NULL,
  `created_at`           DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`           DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
                                         ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_orders_order_number` (`order_number`),
  KEY `idx_orders_user` (`user_id`),
  KEY `idx_orders_status` (`status`),
  KEY `idx_orders_payment_status` (`payment_status`),
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
--  7. ORDER ITEMS
--     Snapshot of the product at purchase time (name/image/price are copied
--     so orders survive product edits/deletes).
-- ----------------------------------------------------------------------------
CREATE TABLE `order_items` (
  `id`                 INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `order_id`           INT UNSIGNED  NOT NULL,
  `product_id`         INT UNSIGNED           DEFAULT NULL,  -- NULL if product deleted
  `product_name`       VARCHAR(255)           DEFAULT '',
  `product_image_url`  VARCHAR(500)           DEFAULT '',
  `quantity`           DECIMAL(10,2) NOT NULL DEFAULT 1.00,
  `unit_price`         DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `total_price`        DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount`    DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount`         DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `product_sku`        VARCHAR(50)            DEFAULT '',
  `product_category`   VARCHAR(100)           DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_order_items_order` (`order_id`),
  KEY `idx_order_items_product` (`product_id`),
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`)
    REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`)
    REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
--  8. PAYMENT LOGS
--     Audit trail for every Razorpay interaction (initiated / success /
--     signature_failed), written by create_razorpay_order.php and
--     verify_razorpay_payment.php.
-- ----------------------------------------------------------------------------
CREATE TABLE `payment_logs` (
  `id`                  INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `order_id`            INT UNSIGNED  NOT NULL,
  `razorpay_order_id`   VARCHAR(100)           DEFAULT NULL,
  `razorpay_payment_id` VARCHAR(100)           DEFAULT NULL,
  `amount`              DECIMAL(10,2)          DEFAULT NULL,
  `currency`            VARCHAR(10)            DEFAULT 'INR',
  `status`              VARCHAR(50)            DEFAULT 'initiated',
  `payment_method`      VARCHAR(50)            DEFAULT NULL,
  `error_message`       TEXT                   DEFAULT NULL,
  `created_at`          DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_payment_logs_order` (`order_id`),
  KEY `idx_payment_logs_status` (`status`),
  CONSTRAINT `fk_payment_logs_order` FOREIGN KEY (`order_id`)
    REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
--  9. SEED DATA (optional — uncomment what you need)
-- ============================================================================

-- ----------------------------------------------------------------------------
--  9a. Default admin account
--      mobile : 9999999999   (no password_hash yet)
--      Set the bcrypt password afterwards via:
--          backend/admin/set_admin_password.php
-- ----------------------------------------------------------------------------
-- INSERT INTO `users` (`name`, `email`, `mobile`, `role`, `is_admin`, `status`)
-- VALUES ('Administrator', 'admin@kuttysoora.com', '9999999999', 'admin', 1, 'active');

-- ----------------------------------------------------------------------------
--  9b. Sample categories / products
--      (Prices are in INR; adjust freely.)
-- ----------------------------------------------------------------------------
-- INSERT INTO `products`
--   (`sku`, `name`, `category`, `description`, `price`, `stock`, `image_url`,
--    `availability`, `minimum_quantity`, `price_unit`, `tags`)
-- VALUES
--   ('KS00001', 'Vanjaram (Seer Fish) Steak', 'Fish',
--    'Fresh-cut seer fish steaks, cleaned and ready to cook.', 650.00, 20,
--    '', 'in_stock', 'per_kg', 'per_kg', 'vanjaram,seer fish,fresh'),
--   ('KS00002', 'Tiger Prawns (Jumbo)', 'Prawns',
--    'Large, sweet tiger prawns — ideal for grilling and curries.', 850.00, 15,
--    '', 'in_stock', 'per_kg', 'per_kg', 'prawns,tiger,fresh'),
--   ('KS00003', 'Crab (Live, Medium)', 'Crab',
--    'Live medium-size crabs, packed with care for delivery.', 550.00, 10,
--    '', 'in_stock', 'per_kg', 'per_kg', 'crab,live,fresh');

-- ============================================================================
--  END OF SCRIPT
-- ============================================================================
