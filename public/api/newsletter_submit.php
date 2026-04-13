<?php
/**
 * Newsletter signup endpoint.
 * POST-only. Stores as a row in `inquiries` with source='newsletter'.
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/rate_limit.php';

start_session_once();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    json_response(405, ['ok' => false, 'message' => 'Method not allowed.']);
}

if (!csrf_verify($_POST['csrf'] ?? null)) {
    json_response(403, ['ok' => false, 'message' => 'Invalid session. Please refresh the page and try again.']);
}

// Honeypot — silent drop
if (!empty($_POST['website'])) {
    json_response(200, ['ok' => true, 'message' => 'Thank you for subscribing!']);
}

$ip = client_ip();
if (check_rate_limit($ip, 60, 3)) {
    json_response(429, ['ok' => false, 'message' => 'Too many requests. Please try again in a minute.']);
}

$email = trim((string)($_POST['email'] ?? ''));
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_response(422, ['ok' => false, 'message' => 'Please enter a valid email address.']);
}

try {
    // The inquiries table has a CHECK constraint that only allows
    // ('contact','popup','project'). We reuse the 'contact' source and
    // tag the message so it's findable, without schema migration.
    $stmt = db()->prepare(
        'INSERT INTO inquiries
          (source, full_name, email, phone, message, ip_address, user_agent, status)
         VALUES
          (:source, :name, :email, :phone, :message, :ip, :ua, "new")'
    );
    $stmt->execute([
        ':source'  => 'contact',
        ':name'    => 'Newsletter Subscriber',
        ':email'   => $email,
        ':phone'   => 'N/A',
        ':message' => '[Newsletter signup] ' . $email,
        ':ip'      => $ip,
        ':ua'      => mb_substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
    ]);
} catch (Throwable $e) {
    error_log('newsletter insert failed: ' . $e->getMessage());
    json_response(500, ['ok' => false, 'message' => 'Could not save your request. Please try again.']);
}

json_response(200, [
    'ok'      => true,
    'message' => 'Thank you for subscribing! We will send updates to ' . $email,
]);
