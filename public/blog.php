<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/settings.php';

start_session_once();
$settings = load_settings();

$slug = trim($_GET['slug'] ?? '');
if ($slug === '') { header('Location: ' . url('blogs.php')); exit; }

try {
    $post = db()->prepare("SELECT * FROM blogs WHERE slug = ? AND is_published = 1 LIMIT 1");
    $post->execute([$slug]);
    $post = $post->fetch();

    if (!$post) { header('Location: ' . url('blogs.php')); exit; }

    // Related posts (same category, exclude current)
    $related = db()->prepare("SELECT id, slug, title, excerpt, category, author, read_time, cover_image, published_at FROM blogs WHERE is_published = 1 AND slug != ? AND category = ? ORDER BY sort_order DESC LIMIT 3");
    $related->execute([$slug, $post['category']]);
    $related = $related->fetchAll();
    if (count($related) < 2) {
        $rel2 = db()->prepare("SELECT id, slug, title, excerpt, category, author, read_time, cover_image, published_at FROM blogs WHERE is_published = 1 AND slug != ? ORDER BY sort_order DESC LIMIT 3");
        $rel2->execute([$slug]);
        $related = $rel2->fetchAll();
    }
} catch (Throwable $e) {
    header('Location: ' . url('blogs.php')); exit;
}

$img  = !empty($post['cover_image']) ? upload_url($post['cover_image']) : asset('img/placeholders/blog.jpg');

$page_title       = e($post['title']) . ' — Best Property Advisor in Noida | Shubharambh';
$page_description = e(mb_substr(strip_tags($post['excerpt']), 0, 160));
$page_robots      = 'noindex, nofollow';
$page_active      = 'blog';
$page_canonical   = url('blog.php') . '?slug=' . urlencode($slug);
include __DIR__ . '/../includes/header.php';
?>

<!-- Breadcrumb banner -->
<section class="page-banner page-banner--sm">
  <div class="container">
    <nav class="crumbs" aria-label="Breadcrumb">
      <a href="<?= e(url('index.php')) ?>">Home</a>
      <span class="sep">/</span>
      <a href="<?= e(url('blogs.php')) ?>">Blog</a>
      <span class="sep">/</span>
      <span><?= e(mb_substr($post['title'], 0, 40)) ?>…</span>
    </nav>
  </div>
</section>

<article class="section blog-post-wrap">
  <div class="container blog-post-layout">

    <!-- Main content -->
    <div class="blog-post-main">

      <!-- Category + meta -->
      <div class="blog-post-top">
        <span class="blog-post-cat"><?= e($post['category']) ?></span>
        <span class="blog-post-meta"><?= (int)$post['read_time'] ?> min read &nbsp;·&nbsp; Shubharambh Infra Advisors</span>
      </div>

      <h1 class="blog-post-title"><?= e($post['title']) ?></h1>
      <p class="blog-post-excerpt"><?= e($post['excerpt']) ?></p>

      <!-- Cover image -->
      <div class="blog-post-cover">
        <img src="<?= e($img) ?>" alt="<?= e($post['title']) ?>" loading="eager">
      </div>

      <!-- Body content -->
      <div class="blog-post-body prose">
        <?= $post['body'] /* trusted admin-authored HTML — intentionally not escaped */ ?>
      </div>

      <!-- Share bar -->
      <div class="blog-share">
        <span>Share this article:</span>
        <a href="https://wa.me/?text=<?= urlencode($post['title'] . ' — ' . url('blog.php') . '?slug=' . $slug) ?>" target="_blank" rel="noopener" class="share-btn share-btn--wa">
          <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M17.5 14.3c-.3-.1-1.7-.8-1.9-.9-.3-.1-.5-.1-.7.1-.2.3-.8.9-1 1.1-.2.2-.4.2-.6.1a7.6 7.6 0 0 1-3.7-3.2c-.3-.5.3-.4.8-1.3.1-.2 0-.4 0-.5s-.7-1.6-.9-2.2c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4s-1 1-1 2.4 1 2.8 1.2 3c.2.3 2 3 4.8 4.2l1.6.6c.7.2 1.3.2 1.8.1.5-.1 1.7-.7 1.9-1.3.2-.7.2-1.2.2-1.3zM12 2a10 10 0 0 0-8.5 15.2L2 22l4.9-1.3A10 10 0 1 0 12 2z"/></svg>
          WhatsApp
        </a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(url('blog.php') . '?slug=' . $slug) ?>" target="_blank" rel="noopener" class="share-btn share-btn--fb">
          <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          Facebook
        </a>
        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode(url('blog.php') . '?slug=' . $slug) ?>&title=<?= urlencode($post['title']) ?>" target="_blank" rel="noopener" class="share-btn share-btn--li">
          <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
          LinkedIn
        </a>
      </div>

      <!-- CTA box -->
      <div class="blog-cta-box">
        <div class="blog-cta-box__text">
          <h3>Looking for the Best Property in Noida?</h3>
          <p>Talk to Shubharambh Infra Advisors — Noida's most trusted RERA-registered property consultancy. Free consultation, zero brokerage on select projects.</p>
        </div>
        <a href="<?= e(url('contact.php')) ?>" class="btn btn-gold">Free Consultation</a>
      </div>

    </div>

    <!-- Sidebar -->
    <aside class="blog-post-sidebar">

      <!-- Author card -->
      <div class="blog-sidebar-card">
        <div class="blog-author">
          <div class="blog-author__avatar">S</div>
          <div>
            <strong>Shubharambh Infra Advisors</strong>
            <span>Shubharambh Infra Advisors</span>
          </div>
        </div>
        <p style="font-size:0.85rem;color:var(--c-muted);margin:0.75rem 0 0;">Real estate experts helping buyers, sellers and investors across Noida and Delhi NCR since 2014.</p>
      </div>

      <!-- Related posts -->
      <?php if (!empty($related)): ?>
      <div class="blog-sidebar-card">
        <h3 class="blog-sidebar-title">Related Articles</h3>
        <ul class="blog-related-list">
          <?php foreach ($related as $r):
            $rdate = date('d M Y', strtotime($r['published_at']));
            $rimg  = !empty($r['cover_image']) ? upload_url($r['cover_image']) : asset('img/placeholders/blog.jpg');
          ?>
          <li class="blog-related-item">
            <a href="<?= e(url('blog.php')) ?>?slug=<?= urlencode($r['slug']) ?>" class="blog-related-img">
              <img src="<?= e($rimg) ?>" alt="<?= e($r['title']) ?>" loading="lazy">
            </a>
            <div>
              <a href="<?= e(url('blog.php')) ?>?slug=<?= urlencode($r['slug']) ?>" class="blog-related-title"><?= e($r['title']) ?></a>
              <span class="blog-related-date"><?= e($rdate) ?></span>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <!-- Quick contact -->
      <div class="blog-sidebar-card blog-sidebar-card--cta">
        <h3 class="blog-sidebar-title">Need Expert Advice?</h3>
        <p>Our property advisors are available 6 days a week. Call us or WhatsApp for a free property consultation.</p>
        <a href="tel:<?= e($settings['phone_primary'] ?? '+919911600100') ?>" class="btn btn-gold" style="width:100%;text-align:center;margin-bottom:0.5rem;">
          Call Now
        </a>
        <a href="https://wa.me/<?= e(preg_replace('/[^0-9]/', '', $settings['phone_whatsapp'] ?? '919911600100')) ?>" target="_blank" rel="noopener" class="btn btn-ghost" style="width:100%;text-align:center;">
          WhatsApp Us
        </a>
      </div>

    </aside>
  </div>
</article>

<?php include __DIR__ . '/../includes/footer.php'; ?>
