<?php
/**
 * Hero project search autocomplete.
 * GET /api/project_search.php?q=keyword
 * Returns JSON { items: [{id, name, slug, builder}, ...] }
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$q = trim((string)($_GET['q'] ?? ''));
if (mb_strlen($q) < 2) {
    echo json_encode(['items' => []]);
    exit;
}

try {
    $like = '%' . $q . '%';
    $stmt = db()->prepare(
        'SELECT id, name, slug, builder, location, price_display
         FROM projects
         WHERE is_active = 1
           AND (name LIKE :q OR builder LIKE :q OR location LIKE :q)
         ORDER BY is_featured DESC, sort_order ASC
         LIMIT 8'
    );
    $stmt->bindValue(':q', $like, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll();
} catch (Throwable $e) {
    error_log('project_search error: ' . $e->getMessage());
    echo json_encode(['items' => []]);
    exit;
}

echo json_encode([
    'items' => array_map(static function ($r) {
        return [
            'id'       => (int)$r['id'],
            'name'     => $r['name'],
            'slug'     => $r['slug'],
            'builder'  => $r['builder'],
            'location' => $r['location'],
            'price'    => $r['price_display'],
        ];
    }, $rows),
]);
