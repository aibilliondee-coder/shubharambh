<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/settings.php';

start_session_once();
$settings = load_settings();

$slug = isset($_GET['slug']) ? trim((string)$_GET['slug']) : '';

$project = null;
if ($slug !== '') {
    try {
        $stmt = db()->prepare('SELECT * FROM projects WHERE slug = :slug AND is_active = 1 LIMIT 1');
        $stmt->execute([':slug' => $slug]);
        $project = $stmt->fetch();
    } catch (Throwable $e) {
        $project = null;
    }
}

if (!$project) {
    http_response_code(404);
    include __DIR__ . '/404.php';
    exit;
}

try {
    $related = db()->query(
        'SELECT * FROM projects WHERE is_active = 1 AND id != ' . (int)$project['id'] .
        ' ORDER BY is_featured DESC, sort_order ASC LIMIT 3'
    )->fetchAll();
} catch (Throwable $e) {
    $related = [];
}

// Parse list fields (amenities/connectivity/usps may be JSON or newline-separated)
$amenities    = parse_list_field($project['amenities']    ?? '');
$connectivity = parse_list_field($project['connectivity'] ?? '');
$usps         = parse_list_field($project['usps']         ?? '');

$category = project_category($project['property_type'] ?? '');
$cfg      = short_config($project['configurations']     ?? '');
$possession = $project['possession'] ?? 'TBA';

$imgPath = !empty($project['cover_image'])
    ? upload_url($project['cover_image'])
    : asset('img/placeholders/project.svg');
$waMsg = 'Hi, I am interested in ' . $project['name'] . ' by ' . $project['builder'] . '. Please share more details.';

$page_title       = $project['name'] . ' by ' . $project['builder'] . ' | ' . $project['location'] . ' — ' . $settings['company_name'];
$page_description = $project['name'] . ' by ' . $project['builder'] . ' at ' . $project['location'] . '. ' . truncate($project['description'] ?? '', 140);
$page_active      = 'projects';
$page_ogimage     = $imgPath;

// JSON-LD Product schema for this project
$page_jsonld = [
    '@context'    => 'https://schema.org',
    '@type'       => 'Product',
    'name'        => $project['name'],
    'description' => truncate($project['description'] ?? '', 300),
    'brand'       => ['@type' => 'Organization', 'name' => $project['builder']],
    'category'    => $project['property_type'],
    'image'       => $imgPath,
    'offers'      => [
        '@type'         => 'Offer',
        'priceCurrency' => 'INR',
        'priceSpecification' => [
            '@type' => 'PriceSpecification',
            'price' => $project['price_display'],
        ],
        'availability'  => 'https://schema.org/InStock',
        'seller'        => ['@type' => 'Organization', 'name' => $settings['company_name']],
    ],
];

include __DIR__ . '/../includes/header.php';
?>

<section class="page-banner">
  <div class="container">
    <span class="eyebrow"><?= e($project['property_type']) ?></span>
    <h1><?= e($project['name']) ?></h1>
    <?php if (!empty($project['tagline'])): ?>
      <p style="color:var(--c-muted);max-width:620px;margin:0.25rem auto 0.75rem;font-size:1rem;"><?= e($project['tagline']) ?></p>
    <?php endif; ?>
    <nav class="crumbs" aria-label="Breadcrumb">
      <a href="<?= e(url('index.php')) ?>">Home</a><span class="sep">/</span>
      <a href="<?= e(url('projects.php')) ?>">Projects</a><span class="sep">/</span>
      <?= e($project['name']) ?>
    </nav>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="project-detail">
      <div>
        <!-- Cover image -->
        <div class="cover">
          <div class="cover-badges">
            <span class="badge"><?= e($category) ?></span>
            <?php if (!empty($project['rera_id'])): ?>
              <span class="badge">RERA Approved</span>
            <?php endif; ?>
          </div>
          <img src="<?= e($imgPath) ?>" alt="<?= e($project['name']) ?> by <?= e($project['builder']) ?> — <?= e($project['property_type']) ?> in <?= e($project['location']) ?>, <?= e($project['city']) ?> | Buy Property Delhi NCR" loading="eager">
        </div>

        <!-- Quick facts strip -->
        <div class="quick-facts">
          <div class="qf">
            <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg></div>
            <div><strong><?= e($cfg) ?></strong><span>Configuration</span></div>
          </div>
          <div class="qf">
            <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></div>
            <div><strong><?= e($project['sizes'] ?: 'On Request') ?></strong><span>Sizes</span></div>
          </div>
          <div class="qf">
            <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
            <div><strong><?= e($possession) ?></strong><span>Possession</span></div>
          </div>
          <div class="qf">
            <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
            <div><strong><?= e($project['city']) ?></strong><span>Location</span></div>
          </div>
        </div>

        <!-- Description -->
        <?php if (!empty($project['description'])): ?>
        <div class="pd-block">
          <h2>About This Project</h2>
          <?php foreach (preg_split('/\n\s*\n/', trim($project['description'])) as $para): ?>
            <?php if (trim($para) === '') continue; ?>
            <p><?= e($para) ?></p>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- USPs / Highlights -->
        <?php if (!empty($usps)): ?>
        <div class="pd-block">
          <h2>Key Highlights</h2>
          <ul class="feature-list">
            <?php foreach ($usps as $u): ?>
              <li>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                <span><?= e($u) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <!-- Amenities grid -->
        <?php if (!empty($amenities)): ?>
        <div class="pd-block">
          <h2>Amenities &amp; Lifestyle</h2>
          <div class="amenities-grid">
            <?php foreach ($amenities as $a): ?>
              <div class="item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                <span><?= e($a) ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Connectivity -->
        <?php if (!empty($connectivity)): ?>
        <div class="pd-block">
          <h2>Connectivity &amp; Location</h2>
          <ul class="feature-list">
            <?php foreach ($connectivity as $c): ?>
              <li>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span><?= e($c) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <!-- EMI calculator for this project -->
        <div class="pd-block">
          <h2>Plan Your EMI</h2>
          <p style="color:var(--c-muted);margin-bottom:1.25rem;">Estimate your monthly home loan instalment for this property. Slide to adjust price, down payment, interest rate and tenure.</p>
          <?php include __DIR__ . '/../includes/emi_widget.php'; ?>
        </div>
      </div>

      <!-- Sticky sidebar -->
      <aside class="meta">
        <div class="price-head">
          <small>Starting Price</small>
          <strong><?= e($project['price_display']) ?></strong>
        </div>

        <dl>
          <div><dt>Builder</dt><dd><?= e($project['builder']) ?></dd></div>
          <div><dt>Location</dt><dd><?= e($project['location']) ?></dd></div>
          <div><dt>Property Type</dt><dd><?= e($project['property_type']) ?></dd></div>
          <?php if (!empty($project['configurations'])): ?>
            <div><dt>Config</dt><dd><?= e($project['configurations']) ?></dd></div>
          <?php endif; ?>
          <?php if (!empty($project['possession'])): ?>
            <div><dt>Possession</dt><dd><?= e($project['possession']) ?></dd></div>
          <?php endif; ?>
          <?php if (!empty($project['rera_id'])): ?>
            <div><dt>RERA ID</dt><dd><?= e($project['rera_id']) ?></dd></div>
          <?php endif; ?>
        </dl>

        <div class="cta-stack">
          <a href="#" class="btn btn-gold"
             data-modal-open
             data-project-id="<?= e($project['id']) ?>"
             data-project-name="<?= e($project['name']) ?>">Enquire Now</a>
          <a href="<?= e(whatsapp_url($settings['phone_whatsapp'], $waMsg)) ?>"
             class="btn btn-whatsapp" target="_blank" rel="noopener">WhatsApp Us</a>
          <a href="tel:<?= e(preg_replace('/\s+/', '', $settings['phone_primary'])) ?>" class="btn btn-outline">
            Call <?= e($settings['phone_primary']) ?>
          </a>
          <button class="btn btn-ghost" type="button"
                  data-fav="<?= e($project['id']) ?>"
                  data-name="<?= e($project['name']) ?>"
                  style="width:100%;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            Save to Shortlist
          </button>
        </div>
      </aside>
    </div>
  </div>
</section>

<?php if (!empty($related)): ?>
<section class="section section--soft">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">You May Also Like</span>
      <h2>Similar Projects</h2>
      <div class="arch-divider" aria-hidden="true"></div>
    </div>
    <div class="projects-grid">
      <?php foreach ($related as $p): ?>
        <?php
          $rImg = !empty($p['cover_image'])
              ? upload_url($p['cover_image'])
              : asset('img/placeholders/project.svg');
          $rCfg = short_config($p['configurations'] ?? '');
          $rPoss = short_possession($p['possession'] ?? '');
          $rCat = project_category($p['property_type'] ?? '');
        ?>
        <article class="project-card">
          <div class="media">
            <div class="badges"><span class="badge"><?= e($rCat) ?></span></div>
            <img src="<?= e($rImg) ?>" alt="<?= e($p['name']) ?> by <?= e($p['builder']) ?> — <?= e($p['property_type']) ?> in <?= e($p['city']) ?> | Shubharambh Infra Advisors" loading="lazy" onerror="this.style.display='none'">
          </div>
          <div class="body">
            <h3><?= e($p['name']) ?></h3>
            <div class="builder"><?= e($p['builder']) ?></div>
            <div class="location">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <?= e($p['location']) ?>
            </div>
            <div class="specs">
              <div><span class="v"><?= e($rCfg) ?></span><span class="l">Config</span></div>
              <div><span class="v"><?= e($rPoss) ?></span><span class="l">Possession</span></div>
              <div><span class="v"><?= e($p['city']) ?></span><span class="l">City</span></div>
            </div>
            <div class="price">
              <small>Starting From</small>
              <?= e($p['price_display']) ?>
            </div>
            <div class="cta-row">
              <a href="<?= e(url('project.php?slug=' . urlencode($p['slug']))) ?>" class="btn btn-outline btn-sm">View Details</a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
