<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/settings.php';

start_session_once();
$settings = load_settings();

$page   = max(1, (int)($_GET['page'] ?? 1));
$limit  = 9;
$offset = ($page - 1) * $limit;
$cat    = trim($_GET['cat'] ?? '');

try {
    $where  = 'WHERE is_published = 1';
    $params = [];
    if ($cat !== '') {
        $where  .= ' AND category = :cat';
        $params[':cat'] = $cat;
    }
    $countStmt = db()->prepare("SELECT COUNT(*) FROM blogs $where");
    $countStmt->execute($params);
    $total = (int)$countStmt->fetchColumn();
    $pages = max(1, (int)ceil($total / $limit));

    $stmt = db()->prepare("SELECT id, slug, title, excerpt, category, author, read_time, cover_image, published_at FROM blogs $where ORDER BY sort_order DESC, published_at DESC LIMIT :lim OFFSET :off");
    $stmt->execute($params + [':lim' => $limit, ':off' => $offset]);
    $blogs = $stmt->fetchAll();

    $cats = db()->query("SELECT DISTINCT category FROM blogs WHERE is_published = 1 ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
} catch (Throwable $e) {
    $blogs = []; $cats = []; $total = 0; $pages = 1;
}

$page_title       = 'Real Estate Blog — Best Property Advisor in Noida | Shubharambh Infra Advisors';
$page_description = 'Real estate insights, investment guides & RERA updates from Shubharambh Infra Advisors — best property advisor in Noida, Delhi NCR.';
$page_robots      = 'noindex, nofollow';
$page_active      = 'blog';
include __DIR__ . '/../includes/header.php';
?>

<section class="page-banner">
  <div class="container">
    <span class="eyebrow">Expert Insights</span>
    <h1>Real Estate Blog — <span class="accent">Noida & Delhi NCR</span></h1>
    <p style="color:var(--c-muted);margin-top:0.5rem;font-size:0.95rem;">Investment guides, market updates & property tips from the best property advisor in Noida</p>
    <nav class="crumbs" aria-label="Breadcrumb">
      <a href="<?= e(url('index.php')) ?>">Home</a><span class="sep">/</span>Blog
    </nav>
  </div>
</section>

<section class="section">
  <div class="container">

    <!-- Category filter -->
    <?php if (!empty($cats)): ?>
    <div class="blog-filter-bar">
      <a href="<?= e(url('blogs.php')) ?>" class="blog-filter-btn <?= $cat === '' ? 'active' : '' ?>">All</a>
      <?php foreach ($cats as $c): ?>
        <a href="<?= e(url('blogs.php')) ?>?cat=<?= urlencode($c) ?>" class="blog-filter-btn <?= $cat === $c ? 'active' : '' ?>"><?= e($c) ?></a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Blog grid -->
    <?php if (empty($blogs)): ?>
      <div class="empty-state">
        <p>No blog posts found. Check back soon.</p>
      </div>
    <?php else: ?>
    <div class="blog-grid">
      <?php foreach ($blogs as $b):
        $img  = !empty($b['cover_image']) ? upload_url($b['cover_image']) : asset('img/placeholders/blog.jpg');
      ?>
      <article class="blog-card reveal">
        <a href="<?= e(url('blog.php')) ?>?slug=<?= urlencode($b['slug']) ?>" class="blog-card__img-wrap">
          <img src="<?= e($img) ?>" alt="<?= e($b['title']) ?>" loading="lazy">
          <span class="blog-card__cat"><?= e($b['category']) ?></span>
        </a>
        <div class="blog-card__body">
          <div class="blog-card__meta">
            <span><?= (int)$b['read_time'] ?> min read</span>
            <span class="blog-card__dot">·</span>
            <span>Shubharambh Infra Advisors</span>
          </div>
          <h2 class="blog-card__title">
            <a href="<?= e(url('blog.php')) ?>?slug=<?= urlencode($b['slug']) ?>"><?= e($b['title']) ?></a>
          </h2>
          <p class="blog-card__excerpt"><?= e($b['excerpt']) ?></p>
          <a href="<?= e(url('blog.php')) ?>?slug=<?= urlencode($b['slug']) ?>" class="blog-card__read">
            Read Article <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="14" height="14"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($pages > 1): ?>
    <nav class="blog-pagination" aria-label="Blog pagination">
      <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?><?= $cat ? '&cat='.urlencode($cat) : '' ?>" class="blog-page-btn">&larr; Prev</a>
      <?php endif; ?>
      <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a href="?page=<?= $i ?><?= $cat ? '&cat='.urlencode($cat) : '' ?>" class="blog-page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
      <?php endfor; ?>
      <?php if ($page < $pages): ?>
        <a href="?page=<?= $page + 1 ?><?= $cat ? '&cat='.urlencode($cat) : '' ?>" class="blog-page-btn">Next &rarr;</a>
      <?php endif; ?>
    </nav>
    <?php endif; ?>
    <?php endif; ?>

  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
