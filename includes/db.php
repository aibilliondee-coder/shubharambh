<?php
/**
 * PDO database singleton.
 * Supports both SQLite (default, file-based, no server) and MySQL.
 * Usage: $pdo = db();
 */

if (!defined('DB_DRIVER')) {
    require_once __DIR__ . '/../config/config.php';
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    try {
        if (DB_DRIVER === 'sqlite') {
            $dsn = 'sqlite:' . DB_SQLITE_PATH;
            $pdo = new PDO($dsn, null, null, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
            $pdo->exec('PRAGMA foreign_keys = ON');
        } else {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
            );
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . DB_CHARSET,
            ]);
        }
    } catch (PDOException $e) {
        if (APP_ENV === 'local') {
            die('Database connection failed: ' . $e->getMessage());
        }
        error_log('DB connection error: ' . $e->getMessage());
        http_response_code(500);
        die('Service temporarily unavailable. Please try again later.');
    }

    return $pdo;
}
