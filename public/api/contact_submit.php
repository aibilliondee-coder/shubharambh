<?php
/**
 * Contact form submission handler (main site contact form).
 * POST-only. Returns JSON.
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/recaptcha.php';
require_once __DIR__ . '/../../includes/rate_limit.php';

start_session_once();

// ---- 1. Method guard -----------------------------------------------------
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    json_response(405, ['ok' => false, 'message' => 'Method not allowed.']);
}

// ---- 2. CSRF -------------------------------------------------------------
if (!csrf_verify($_POST['csrf'] ?? null)) {
    json_response(403, ['ok' => false, 'message' => 'Invalid session. Please refresh the page and try again.']);
}

// ---- 3. Honeypot (silent drop) -------------------------------------------
if (!empty($_POST['website'])) {
    // Return fake success so bots don't learn they were caught.
    json_response(200, ['ok' => true, 'message' => 'Thank you! We will be in touch shortly.']);
}

// ---- 4. Rate limit (1 submission per 60s per IP) -------------------------
$ip = client_ip();
if (check_rate_limit($ip, 60, 1)) {
    json_response(429, ['ok' => false, 'message' => 'Please wait a minute before sending another enquiry.']);
}

// ---- 5. Validate fields --------------------------------------------------
$name    = trim((string)($_POST['full_name'] ?? ''));
$email   = trim((string)($_POST['email'] ?? ''));
$phone   = trim((string)($_POST['phone'] ?? ''));
$city    = trim((string)($_POST['city'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));
$source  = in_array(($_POST['source'] ?? 'contact'), ['contact','popup','project'], true)
           ? $_POST['source'] : 'contact';
$projectName = trim((string)($_POST['project_name'] ?? ''));
$projectId   = isset($_POST['project_id']) && $_POST['project_id'] !== ''
               ? (int)$_POST['project_id'] : null;

$errors = [];

if (mb_strlen($name) < 2 || mb_strlen($name) > 150) {
    $errors[] = 'Please enter your full name.';
}

// Phone: allow digits, +, -, space; 10-15 digit count
$phoneDigits = preg_replace('/\D+/', '', $phone);
if (strlen($phoneDigits) < 10 || strlen($phoneDigits) > 15) {
    $errors[] = 'Please enter a valid phone number.';
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}

if (mb_strlen($message) > 2000) {
    $errors[] = 'Message is too long (max 2000 characters).';
}

if (!empty($errors)) {
    json_response(422, ['ok' => false, 'message' => implode(' ', $errors)]);
}

// ---- 6. reCAPTCHA --------------------------------------------------------
if (!verify_recaptcha($_POST['recaptcha_token'] ?? null, 'contact')) {
    json_response(403, ['ok' => false, 'message' => 'Verification failed. Please refresh the page and try again.']);
}

// ---- 7. Ensure project_id is valid if provided ---------------------------
if ($projectId !== null) {
    try {
        $stmt = db()->prepare('SELECT id, name FROM projects WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $projectId]);
        $row = $stmt->fetch();
        if (!$row) {
            $projectId = null;
        } elseif ($projectName === '') {
            $projectName = $row['name'];
        }
    } catch (Throwable $e) {
        $projectId = null;
    }
}

// ---- 8. Insert inquiry ---------------------------------------------------
try {
    $stmt = db()->prepare(
        'INSERT INTO inquiries
          (source, full_name, email, phone, city, message, project_id, project_name,
           ip_address, user_agent, status)
         VALUES
          (:source, :name, :email, :phone, :city, :message, :pid, :pname,
           :ip, :ua, "new")'
    );
    $stmt->execute([
        ':source'  => $source,
        ':name'    => $name,
        ':email'   => $email !== '' ? $email : null,
        ':phone'   => $phone,
        ':city'    => $city !== '' ? $city : null,
        ':message' => $message !== '' ? $message : null,
        ':pid'     => $projectId,
        ':pname'   => $projectName !== '' ? $projectName : null,
        ':ip'      => $ip,
        ':ua'      => mb_substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
    ]);
} catch (Throwable $e) {
    error_log('inquiry insert failed: ' . $e->getMessage());
    json_response(500, ['ok' => false, 'message' => 'We could not save your request. Please try again.']);
}

// ---- 9. Success ----------------------------------------------------------
json_response(200, [
    'ok'      => true,
    'message' => 'Thank you! Your enquiry has been received. Our team will contact you within 24 hours.',
]);
