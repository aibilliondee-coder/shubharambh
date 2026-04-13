<?php
/**
 * Google reCAPTCHA v3 server-side verification.
 * Returns true when verification passes OR when keys are not configured
 * (so local development without keys can still accept form submissions).
 */

function verify_recaptcha(?string $token, string $expectedAction = 'contact'): bool
{
    $secret = defined('RECAPTCHA_SECRET') ? RECAPTCHA_SECRET : '';
    if ($secret === '') {
        // Not configured — skip check (useful for local dev).
        return true;
    }

    if (!is_string($token) || $token === '') {
        return false;
    }

    $minScore = defined('RECAPTCHA_MIN_SCORE') ? (float) RECAPTCHA_MIN_SCORE : 0.5;
    $body = http_build_query([
        'secret'   => $secret,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);

    $ctx = stream_context_create([
        'http' => [
            'method'        => 'POST',
            'header'        => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content'       => $body,
            'timeout'       => 5,
            'ignore_errors' => true,
        ],
    ]);

    $raw = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $ctx);
    if ($raw === false) {
        error_log('reCAPTCHA request failed.');
        return false;
    }

    $data = json_decode($raw, true);
    if (!is_array($data) || empty($data['success'])) {
        return false;
    }

    if (isset($data['action']) && $data['action'] !== $expectedAction) {
        return false;
    }

    if (isset($data['score']) && (float) $data['score'] < $minScore) {
        return false;
    }

    return true;
}
