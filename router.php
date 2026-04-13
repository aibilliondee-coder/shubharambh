<?php
/**
 * Router for PHP's built-in dev server.
 *   Usage:  php -S localhost:8000 -t public router.php
 *
 * Because `-t public` sets the doc-root to /public, config/, includes/, sql/
 * are already outside the doc-root and return 404 automatically. This router
 * mainly handles:
 *   - Forwarding "/" to index.php
 *   - Letting static files (css, js, images) pass through
 *   - Denying PHP execution under /uploads/
 *   - Rendering the branded 404.php for missing pages
 */

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

// Deny PHP execution inside /uploads/ (mirrors public/uploads/.htaccess)
if (preg_match('#^/uploads/.*\.(php|phtml|php3|php4|php5|pl|py|cgi|asp|jsp|sh)$#i', $uri)) {
    http_response_code(403);
    exit('Forbidden');
}

// Let the built-in server serve any real file on disk (css, js, images, etc.)
$file = __DIR__ . '/public' . $uri;
if ($uri !== '/' && is_file($file)) {
    return false;
}

// Render branded 404 page for anything else that isn't a known PHP entry point
if ($uri !== '/' && !is_file(__DIR__ . '/public' . $uri)) {
    http_response_code(404);
    require __DIR__ . '/public/404.php';
    return true;
}

// Fall through: serve index.php for "/"
return false;
