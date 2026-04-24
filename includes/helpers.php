<?php
/**
 * Shared helper functions: escaping, URL building, session + CSRF, redirect.
 */

if (!defined('APP_ROOT')) {
    require_once __DIR__ . '/../config/config.php';
}

// ---------------------------------------------------------------------------
// Session bootstrap — secure cookie params, started once per request.
// ---------------------------------------------------------------------------
function start_session_once(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $secure = (
        (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') ||
        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    );
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_name('SIAS_SESS');
    session_start();
}

// ---------------------------------------------------------------------------
// Output escaping
// ---------------------------------------------------------------------------
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// ---------------------------------------------------------------------------
// URL / asset helpers
// ---------------------------------------------------------------------------
function url(string $path = ''): string
{
    $clean = ltrim($path, '/');
    // Strip .php from page paths so all links use extensionless URLs.
    // Leave api/* alone — those endpoints keep their .php extension.
    if (!str_starts_with($clean, 'api/')) {
        $clean = preg_replace('/\.php(?=$|[?#])/', '', $clean);
    }
    return rtrim(SITE_URL, '/') . '/' . $clean;
}

function asset(string $path): string
{
    return rtrim(ASSET_URL, '/') . '/' . ltrim($path, '/');
}

/**
 * Return the URL for the brand logo.
 * Prefers a real transparent PNG at public/assets/img/logo.png (drop-in override),
 * otherwise falls back to the SVG variant suitable for the given background.
 */
function logo_url(string $variant = 'light'): string
{
    // Drop-in PNG override: if you save the official transparent logo as
    // public/assets/img/logo.png it will automatically be used everywhere.
    $pngPath = APP_ROOT . '/public/assets/img/logo.png';
    if (is_file($pngPath)) {
        return asset('img/logo.png');
    }

    // SVG fallback: 'light' = white text (for dark navy backgrounds),
    //               'dark'  = navy text (for light backgrounds)
    return asset($variant === 'light' ? 'img/logo-white.svg' : 'img/logo.svg');
}

function upload_url(string $relative): string
{
    return rtrim(UPLOAD_URL, '/') . '/' . ltrim($relative, '/');
}

// ---------------------------------------------------------------------------
// Redirect helper (sends Location header and exits)
// ---------------------------------------------------------------------------
function redirect(string $path): void
{
    $target = (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0)
        ? $path
        : url($path);
    header('Location: ' . $target);
    exit;
}

// ---------------------------------------------------------------------------
// CSRF
// ---------------------------------------------------------------------------
function csrf_token(): string
{
    start_session_once();
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_verify(?string $token): bool
{
    start_session_once();
    if (empty($_SESSION['_csrf']) || !is_string($token) || $token === '') {
        return false;
    }
    return hash_equals($_SESSION['_csrf'], $token);
}

// ---------------------------------------------------------------------------
// Client IP
// ---------------------------------------------------------------------------
function client_ip(): string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $parts = array_map('trim', explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
        if (!empty($parts[0]) && filter_var($parts[0], FILTER_VALIDATE_IP)) {
            $ip = $parts[0];
        }
    }
    return $ip;
}

// ---------------------------------------------------------------------------
// Slugify
// ---------------------------------------------------------------------------
function slugify(string $text): string
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text ?: 'n-a';
}

// ---------------------------------------------------------------------------
// WhatsApp click-to-chat URL builder
// ---------------------------------------------------------------------------
function whatsapp_url(string $number, string $message = ''): string
{
    $num = preg_replace('/\D+/', '', $number);
    return 'https://wa.me/' . $num . ($message ? '?text=' . rawurlencode($message) : '');
}

// ---------------------------------------------------------------------------
// JSON response helper
// ---------------------------------------------------------------------------
function json_response(int $status, array $payload): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// ---------------------------------------------------------------------------
// Truncate with ellipsis
// ---------------------------------------------------------------------------
function truncate(?string $str, int $limit = 140): string
{
    if ($str === null) return '';
    $str = trim(strip_tags($str));
    if (mb_strlen($str) <= $limit) return $str;
    return rtrim(mb_substr($str, 0, $limit)) . '…';
}

// ---------------------------------------------------------------------------
// Parse a JSON field (amenities / connectivity / usps) into an array.
// Falls back to line-split if it's not JSON. Returns [] on empty.
// ---------------------------------------------------------------------------
function parse_list_field($raw): array
{
    if ($raw === null || $raw === '') return [];
    if (is_array($raw)) return array_values(array_filter(array_map('strval', $raw)));

    $raw = trim((string)$raw);
    if ($raw === '') return [];

    // Try JSON first
    if ($raw[0] === '[' || $raw[0] === '{') {
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            return array_values(array_filter(array_map('strval', $decoded), fn($s) => $s !== ''));
        }
    }

    // Fallback: split by newline or pipe
    $parts = preg_split('/\r\n|\n|\|/', $raw) ?: [];
    return array_values(array_filter(array_map('trim', $parts), fn($s) => $s !== ''));
}

// ---------------------------------------------------------------------------
// Derive a broad property category ("Residential" / "Commercial" / "Plots")
// from the free-form property_type column so filters can group nicely.
// ---------------------------------------------------------------------------
function project_category(?string $type): string
{
    $t = mb_strtolower((string)$type);
    if ($t === '') return 'Residential';
    if (str_contains($t, 'plot')) return 'Plots';
    if (str_contains($t, 'office') || str_contains($t, 'retail') ||
        str_contains($t, 'shop')  || str_contains($t, 'commercial') ||
        str_contains($t, 'food court') || str_contains($t, 'pentsuite')) {
        return 'Commercial';
    }
    return 'Residential';
}

// ---------------------------------------------------------------------------
// Short BHK / configuration label for project cards (e.g. "3/4/5 BHK")
// ---------------------------------------------------------------------------
function short_config(?string $raw): string
{
    if (!$raw) return '—';
    // Pull the first "N BHK" range if present
    if (preg_match('/(\d+(?:\s*[,\/&\-]\s*\d+)*)\s*BHK/i', $raw, $m)) {
        $nums = preg_replace('/\s+/', '', $m[1]);
        return $nums . ' BHK';
    }
    // Otherwise take the first 2 words
    $words = preg_split('/\s+/', trim($raw));
    return mb_strimwidth(implode(' ', array_slice($words, 0, 3)), 0, 26, '…');
}

// ---------------------------------------------------------------------------
// Short "possession" label ("Apr 2028" → "2028")
// ---------------------------------------------------------------------------
function short_possession(?string $raw): string
{
    if (!$raw) return 'TBA';
    if (preg_match('/\b(20\d{2})\b/', $raw, $m)) return $m[1];
    return mb_strimwidth($raw, 0, 12, '…');
}
