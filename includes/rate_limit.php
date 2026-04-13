<?php
/**
 * Simple IP-based rate limit backed by the inquiries table.
 *
 * check_rate_limit('1.2.3.4', 60, 1) returns TRUE when the IP has submitted
 * more than $max inquiries in the last $seconds (i.e. request should be blocked).
 */

if (!function_exists('db')) {
    require_once __DIR__ . '/db.php';
}

function check_rate_limit(string $ip, int $seconds = 60, int $max = 1): bool
{
    if ($ip === '') {
        return false;
    }
    try {
        // Portable between SQLite and MySQL: compute the cutoff in PHP.
        $cutoff = gmdate('Y-m-d H:i:s', time() - $seconds);
        $stmt = db()->prepare(
            'SELECT COUNT(*) FROM inquiries
             WHERE ip_address = :ip
               AND created_at > :cutoff'
        );
        $stmt->bindValue(':ip', $ip, PDO::PARAM_STR);
        $stmt->bindValue(':cutoff', $cutoff, PDO::PARAM_STR);
        $stmt->execute();
        $count = (int) $stmt->fetchColumn();
        return $count >= $max;
    } catch (Throwable $e) {
        error_log('rate_limit error: ' . $e->getMessage());
        return false;
    }
}
