-- =============================================================================
-- Shubharambh Infra Advisors — Database Schema
-- MySQL 8 / MariaDB — run once on a fresh database.
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `inquiries`;
DROP TABLE IF EXISTS `testimonials`;
DROP TABLE IF EXISTS `partners`;
DROP TABLE IF EXISTS `team_members`;
DROP TABLE IF EXISTS `projects`;
DROP TABLE IF EXISTS `site_settings`;

SET FOREIGN_KEY_CHECKS = 1;

-- -----------------------------------------------------------------------------
-- Site-wide settings (single row, id = 1)
-- -----------------------------------------------------------------------------
CREATE TABLE `site_settings` (
  `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_name`   VARCHAR(150) NOT NULL,
  `tagline`        VARCHAR(255) NOT NULL,
  `phone_primary`  VARCHAR(30)  NOT NULL,
  `phone_whatsapp` VARCHAR(30)  NOT NULL,
  `email_primary`  VARCHAR(150) NOT NULL,
  `email_secondary` VARCHAR(150) NULL,
  `address_line`   VARCHAR(255) NOT NULL,
  `map_embed_url`  TEXT NULL,
  `rera_number`    VARCHAR(100) NULL,
  `rera_notice`    TEXT NULL,
  `facebook_url`   VARCHAR(255) NULL,
  `instagram_url`  VARCHAR(255) NULL,
  `linkedin_url`   VARCHAR(255) NULL,
  `youtube_url`    VARCHAR(255) NULL,
  `twitter_url`    VARCHAR(255) NULL,
  `hero_title`     VARCHAR(150) NOT NULL DEFAULT 'Find Your Luxury Home',
  `hero_subtitle`  VARCHAR(255) NOT NULL DEFAULT 'BEST REAL ESTATE PROPERTY CONSULTANT IN DELHI/NCR',
  `about_heading`  VARCHAR(150) NOT NULL DEFAULT 'Get To Know About Shubharambh Infra',
  `about_body`     MEDIUMTEXT NULL,
  `updated_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Projects (properties the firm is marketing)
-- -----------------------------------------------------------------------------
CREATE TABLE `projects` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug`          VARCHAR(180) NOT NULL,
  `name`          VARCHAR(180) NOT NULL,
  `builder`       VARCHAR(150) NOT NULL,
  `location`      VARCHAR(180) NOT NULL,
  `city`          VARCHAR(100) NOT NULL DEFAULT 'Noida',
  `price_display` VARCHAR(100) NOT NULL,
  `property_type` VARCHAR(120) NOT NULL,
  `description`   MEDIUMTEXT NULL,
  `cover_image`   VARCHAR(255) NULL,
  `rera_id`       VARCHAR(100) NULL,
  `is_featured`   TINYINT(1) NOT NULL DEFAULT 0,
  `is_active`     TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order`    INT NOT NULL DEFAULT 0,
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_projects_slug` (`slug`),
  KEY `idx_featured_active_sort` (`is_featured`, `is_active`, `sort_order`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Testimonials
-- -----------------------------------------------------------------------------
CREATE TABLE `testimonials` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_name` VARCHAR(120) NOT NULL,
  `city`        VARCHAR(100) NULL,
  `rating`      TINYINT UNSIGNED NOT NULL DEFAULT 5,
  `quote`       TEXT NOT NULL,
  `is_active`   TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order`  INT NOT NULL DEFAULT 0,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_active_sort` (`is_active`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Developer partner logos
-- -----------------------------------------------------------------------------
CREATE TABLE `partners` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(150) NOT NULL,
  `logo`       VARCHAR(255) NULL,
  `website`    VARCHAR(255) NULL,
  `is_active`  TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_active_sort` (`is_active`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Team members
-- -----------------------------------------------------------------------------
CREATE TABLE `team_members` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name`  VARCHAR(120) NOT NULL,
  `title`      VARCHAR(150) NOT NULL,
  `bio`        TEXT NULL,
  `photo`      VARCHAR(255) NULL,
  `linkedin`   VARCHAR(255) NULL,
  `twitter`    VARCHAR(255) NULL,
  `email`      VARCHAR(150) NULL,
  `is_active`  TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_active_sort` (`is_active`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Inquiries (form submissions)
-- -----------------------------------------------------------------------------
CREATE TABLE `inquiries` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `source`       ENUM('contact','popup','project') NOT NULL DEFAULT 'contact',
  `full_name`    VARCHAR(150) NOT NULL,
  `email`        VARCHAR(150) NULL,
  `phone`        VARCHAR(30) NOT NULL,
  `city`         VARCHAR(100) NULL,
  `message`      TEXT NULL,
  `project_id`   INT UNSIGNED NULL,
  `project_name` VARCHAR(180) NULL,
  `ip_address`   VARCHAR(45) NOT NULL,
  `user_agent`   VARCHAR(255) NULL,
  `status`       ENUM('new','read','archived') NOT NULL DEFAULT 'new',
  `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status_created` (`status`, `created_at`),
  KEY `idx_ip_created` (`ip_address`, `created_at`),
  KEY `fk_inquiries_project` (`project_id`),
  CONSTRAINT `fk_inquiries_project`
    FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
