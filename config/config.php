<?php
/**
 * Shubharambh Infra Advisors — Site Configuration
 *
 * Copy this file and fill in your local/production credentials.
 * This file MUST NOT be web-accessible. The /config/.htaccess blocks direct access.
 */

// ---------------------------------------------------------------------------
// Environment
// ---------------------------------------------------------------------------
define('APP_ENV', 'local'); // 'local' or 'production'

// ---------------------------------------------------------------------------
// Paths & URLs
// ---------------------------------------------------------------------------
define('APP_ROOT', dirname(__DIR__));                 // absolute path to project root
define('SITE_URL', 'http://localhost:8000');          // no trailing slash — PHP built-in dev server
define('ASSET_URL', SITE_URL . '/assets');
define('UPLOAD_URL', SITE_URL . '/uploads');

// ---------------------------------------------------------------------------
// Database
// DB_DRIVER: 'sqlite' (no server, file-based) or 'mysql'
// ---------------------------------------------------------------------------
define('DB_DRIVER', 'sqlite');                 // 'sqlite' or 'mysql'

// SQLite (used when DB_DRIVER === 'sqlite')
define('DB_SQLITE_PATH', APP_ROOT . '/storage/shubharambh.sqlite');

// MySQL (used when DB_DRIVER === 'mysql')
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'shubharambh_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// ---------------------------------------------------------------------------
// Google reCAPTCHA v3
// Register at https://www.google.com/recaptcha/admin and paste keys here.
// Leave blank to disable reCAPTCHA verification during local development.
// ---------------------------------------------------------------------------
define('RECAPTCHA_SITE_KEY', '');
define('RECAPTCHA_SECRET', '');
define('RECAPTCHA_MIN_SCORE', 0.5);

// ---------------------------------------------------------------------------
// Company contact defaults (overridden by site_settings table if row exists)
// ---------------------------------------------------------------------------
define('DEFAULT_COMPANY_NAME', 'Shubharambh Infra Advisors');
define('DEFAULT_PHONE', '+91 9911600100');
define('DEFAULT_WHATSAPP', '919911600100');
define('DEFAULT_EMAIL', 'company@shubharambhinfraadvisors.com');

// ---------------------------------------------------------------------------
// Error reporting
// ---------------------------------------------------------------------------
if (APP_ENV === 'local') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}

// ---------------------------------------------------------------------------
// Timezone
// ---------------------------------------------------------------------------
date_default_timezone_set('Asia/Kolkata');
