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
    } catch (Throwable $e) { $project = null; }
}

if (!$project) {
    http_response_code(404);
    include __DIR__ . '/404.php';
    exit;
}

try {
    $related = db()->query(
        'SELECT * FROM projects WHERE is_active = 1 AND id != ' . (int)$project['id'] .
        ' ORDER BY is_featured DESC, sort_order DESC LIMIT 3'
    )->fetchAll();
} catch (Throwable $e) { $related = []; }

$amenities    = parse_list_field($project['amenities']    ?? '');
$connectivity = parse_list_field($project['connectivity'] ?? '');
$usps         = parse_list_field($project['usps']         ?? '');
$gallery      = json_decode($project['gallery'] ?? '[]', true) ?: [];

$category   = project_category($project['property_type'] ?? '');
$cfg        = short_config($project['configurations'] ?? '');
$possession = $project['possession'] ?? 'TBA';

// Main image = first gallery or cover
$mainImg = !empty($gallery[0]) ? upload_url($gallery[0]) : (!empty($project['cover_image']) ? upload_url($project['cover_image']) : asset('img/placeholders/project.svg'));

// All gallery images
$galleryImgs = !empty($gallery) ? array_map('upload_url', $gallery) : [$mainImg];

$waMsg = 'Hi, I am interested in ' . $project['name'] . ' by ' . $project['builder'] . '. Please share more details.';

$page_title       = $project['name'] . ' by ' . $project['builder'] . ' | ' . $project['location'] . ' — ' . $settings['company_name'];
$page_description = $project['name'] . ' by ' . $project['builder'] . ' at ' . $project['location'] . '. ' . truncate($project['description'] ?? '', 140);
$page_active      = 'projects';
$page_ogimage     = $mainImg;
$page_canonical   = url('project.php?slug=' . urlencode($project['slug']));

$schemaStateMap = ['haridwar'=>'Uttarakhand','ramnagar'=>'Uttarakhand','dehradun'=>'Uttarakhand','rishikesh'=>'Uttarakhand','gurgaon'=>'Haryana','gurugram'=>'Haryana','faridabad'=>'Haryana','delhi'=>'Delhi','new delhi'=>'Delhi'];
$cityLower   = mb_strtolower(trim($project['city'] ?? ''));
$schemaState = $schemaStateMap[$cityLower] ?? 'Uttar Pradesh';
$schemaAdditional = array_values(array_filter([
    !empty($project['property_type'])   ? ['@type'=>'PropertyValue','name'=>'Property Type','value'=>$project['property_type']] : null,
    !empty($project['configurations'])  ? ['@type'=>'PropertyValue','name'=>'Configuration','value'=>$project['configurations']] : null,
    !empty($project['sizes'])           ? ['@type'=>'PropertyValue','name'=>'Sizes','value'=>$project['sizes']] : null,
    !empty($project['possession'])      ? ['@type'=>'PropertyValue','name'=>'Possession','value'=>$project['possession']] : null,
    !empty($project['rera_id'])         ? ['@type'=>'PropertyValue','name'=>'RERA Registration ID','value'=>$project['rera_id']] : null,
]));
$page_jsonld = ['@context'=>'https://schema.org','@graph'=>[
    ['@type'=>'RealEstateListing','@id'=>url('project.php?slug='.urlencode($project['slug'])).'#listing','name'=>$project['name'],'description'=>truncate($project['description']??'',300),'url'=>url('project.php?slug='.urlencode($project['slug'])),'image'=>$mainImg,'offers'=>['@type'=>'Offer','priceCurrency'=>'INR','price'=>$project['price_display'],'availability'=>'https://schema.org/InStock','seller'=>['@type'=>'RealEstateAgent','name'=>$settings['company_name'],'url'=>url('index.php')]],'address'=>['@type'=>'PostalAddress','streetAddress'=>$project['location']??'','addressLocality'=>$project['city']??'','addressRegion'=>$schemaState,'addressCountry'=>'IN'],'provider'=>['@type'=>'Organization','name'=>$project['builder']??''],'additionalProperty'=>$schemaAdditional,'amenityFeature'=>array_map(fn($a)=>['@type'=>'LocationFeatureSpecification','name'=>$a,'value'=>true],$amenities)],
    ['@type'=>'BreadcrumbList','@id'=>url('project.php?slug='.urlencode($project['slug'])).'#breadcrumb','itemListElement'=>[['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>url('index.php')],['@type'=>'ListItem','position'=>2,'name'=>'Projects','item'=>url('projects.php')],['@type'=>'ListItem','position'=>3,'name'=>$project['name'],'item'=>url('project.php?slug='.urlencode($project['slug']))]]],
]];

include __DIR__ . '/../includes/header.php';
?>

<!-- ===================== HERO SLIDER ===================== -->
<section class="pd-hero" aria-label="<?= e($project['name']) ?> Gallery">
  <div class="pd-slider" id="pdSlider">
    <?php foreach ($galleryImgs as $i => $gImg): ?>
    <div class="pd-slide <?= $i === 0 ? 'active' : '' ?>">
      <img src="<?= e($gImg) ?>" alt="<?= e($project['name']) ?> by <?= e($project['builder']) ?> — <?= e($project['property_type']) ?> in <?= e($project['location']) ?> | Gallery Image <?= $i+1 ?>"
           loading="<?= $i === 0 ? 'eager' : 'lazy' ?>">
    </div>
    <?php endforeach; ?>

    <?php if (count($galleryImgs) > 1): ?>
    <button class="pd-slide-btn pd-slide-btn--prev" id="pdPrev" aria-label="Previous image">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <button class="pd-slide-btn pd-slide-btn--next" id="pdNext" aria-label="Next image">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    <div class="pd-dots" id="pdDots">
      <?php foreach ($galleryImgs as $i => $_): ?>
        <button class="pd-dot <?= $i === 0 ? 'active' : '' ?>" data-slide="<?= $i ?>" aria-label="Image <?= $i+1 ?>"></button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Overlay badges -->
    <div class="pd-hero-badges">
      <span class="badge badge--glass"><?= e($category) ?></span>
      <?php if (!empty($project['rera_id'])): ?>
        <span class="badge badge--glass">RERA Approved</span>
      <?php endif; ?>
    </div>

    <!-- Thumbnail strip -->
    <?php if (count($galleryImgs) > 1): ?>
    <div class="pd-thumbs" id="pdThumbs">
      <?php foreach ($galleryImgs as $i => $gImg): ?>
      <button class="pd-thumb <?= $i === 0 ? 'active' : '' ?>" data-slide="<?= $i ?>">
        <img src="<?= e($gImg) ?>" alt="<?= e($project['name']) ?> thumbnail <?= $i+1 ?>" loading="lazy">
      </button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- ===================== BREADCRUMB ===================== -->
<div class="pd-crumb-bar">
  <div class="container">
    <nav class="crumbs" aria-label="Breadcrumb">
      <a href="<?= e(url('index.php')) ?>">Home</a><span class="sep">/</span>
      <a href="<?= e(url('projects.php')) ?>">Projects</a><span class="sep">/</span>
      <span><?= e($project['name']) ?></span>
    </nav>
  </div>
</div>

<!-- ===================== MAIN LAYOUT ===================== -->
<section class="section pd-layout-section">
  <div class="container">
    <div class="pd-layout">

      <!-- ===== LEFT: content ===== -->
      <div class="pd-content">

        <!-- Title block -->
        <div class="pd-title-block">
          <h1 class="pd-title"><?= e($project['name']) ?></h1>
          <p class="pd-by">by <strong><?= e($project['builder']) ?></strong></p>
          <p class="pd-loc">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="14" height="14"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <?= e($project['location']) ?>, <?= e($project['city']) ?>
          </p>
        </div>

        <!-- Quick stats bar -->
        <div class="pd-stats-bar">
          <div class="pd-stat">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            <div><span><?= e($cfg ?: $project['configurations']) ?></span><small>Configuration</small></div>
          </div>
          <div class="pd-stat">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
            <div><span><?= e($project['sizes'] ?: 'On Request') ?></span><small>Sizes</small></div>
          </div>
          <div class="pd-stat">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <div><span><?= e($possession) ?></span><small>Possession</small></div>
          </div>
          <div class="pd-stat">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <div><span><?= e($project['city']) ?></span><small>City</small></div>
          </div>
        </div>

        <!-- About -->
        <?php if (!empty($project['description'])): ?>
        <div class="pd-section">
          <h2 class="pd-section-title">About <?= e($project['name']) ?></h2>
          <?php foreach (preg_split('/\n\s*\n/', trim($project['description'])) as $para): ?>
            <?php if (trim($para) === '') continue; ?>
            <p><?= e($para) ?></p>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Key Highlights / USPs -->
        <?php if (!empty($usps)): ?>
        <div class="pd-section">
          <h2 class="pd-section-title">Key Highlights</h2>
          <ul class="pd-usp-list">
            <?php foreach ($usps as $u): ?>
            <li>
              <span class="pd-usp-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
              <?= e($u) ?>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <!-- Amenities -->
        <?php if (!empty($amenities)): ?>
        <div class="pd-section">
          <h2 class="pd-section-title">Amenities &amp; Lifestyle</h2>
          <div class="pd-amenities">
            <?php foreach ($amenities as $a): ?>
            <div class="pd-amenity-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              <span><?= e($a) ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Connectivity -->
        <?php if (!empty($connectivity)): ?>
        <div class="pd-section">
          <h2 class="pd-section-title">Connectivity &amp; Location</h2>
          <ul class="pd-conn-list">
            <?php foreach ($connectivity as $c): ?>
            <li>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <?= e($c) ?>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <!-- EMI Calculator -->
        <div class="pd-section">
          <h2 class="pd-section-title">Plan Your EMI</h2>
          <p style="color:var(--c-muted);margin-bottom:1.25rem;font-size:0.9rem;">Estimate your monthly home loan instalment for this property.</p>
          <?php include __DIR__ . '/../includes/emi_widget.php'; ?>
        </div>

      </div><!-- /pd-content -->

      <!-- ===== RIGHT: sticky sidebar ===== -->
      <aside class="pd-sidebar" id="pdSidebar">
        <div class="pd-sidebar-card">
          <div class="pd-price-block">
            <small>Starting Price</small>
            <strong class="pd-price"><?= e($project['price_display']) ?></strong>
          </div>

          <dl class="pd-details-list">
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

          <div class="pd-cta-stack">
            <a href="#" class="btn btn-gold"
               data-modal-open
               data-project-id="<?= e($project['id']) ?>"
               data-project-name="<?= e($project['name']) ?>">
              Enquire Now
            </a>
            <a href="<?= e(whatsapp_url($settings['phone_whatsapp'], $waMsg)) ?>"
               class="btn btn-whatsapp" target="_blank" rel="noopener">
              <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M17.5 14.3c-.3-.1-1.7-.8-1.9-.9-.3-.1-.5-.1-.7.1-.2.3-.8.9-1 1.1-.2.2-.4.2-.6.1a7.6 7.6 0 0 1-3.7-3.2c-.3-.5.3-.4.8-1.3.1-.2 0-.4 0-.5s-.7-1.6-.9-2.2c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4s-1 1-1 2.4 1 2.8 1.2 3c.2.3 2 3 4.8 4.2l1.6.6c.7.2 1.3.2 1.8.1.5-.1 1.7-.7 1.9-1.3.2-.7.2-1.2.2-1.3zM12 2a10 10 0 0 0-8.5 15.2L2 22l4.9-1.3A10 10 0 1 0 12 2z"/></svg>
              WhatsApp Us
            </a>
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
        </div>
      </aside>

    </div><!-- /pd-layout -->
  </div>
</section>

<!-- ===================== RELATED PROJECTS ===================== -->
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
          $rGallery = json_decode($p['gallery'] ?? '[]', true) ?: [];
          $rImg = !empty($rGallery[0]) ? upload_url($rGallery[0]) : (!empty($p['cover_image']) ? upload_url($p['cover_image']) : asset('img/placeholders/project.svg'));
          $rCfg  = short_config($p['configurations'] ?? '');
          $rPoss = short_possession($p['possession'] ?? '');
          $rCat  = project_category($p['property_type'] ?? '');
        ?>
        <article class="project-card">
          <div class="media">
            <div class="badges"><span class="badge"><?= e($rCat) ?></span></div>
            <img src="<?= e($rImg) ?>" alt="<?= e($p['name']) ?> by <?= e($p['builder']) ?>" loading="lazy">
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
            <div class="price"><small>Starting From</small><?= e($p['price_display']) ?></div>
            <div class="cta-row">
              <a href="<?= e(url('project.php?slug='.urlencode($p['slug']))) ?>" class="btn btn-outline btn-sm">View Details</a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===================== SLIDER JS ===================== -->
<script>
(function() {
  const slider  = document.getElementById('pdSlider');
  if (!slider) return;
  const slides  = slider.querySelectorAll('.pd-slide');
  const dots    = slider.querySelectorAll('.pd-dot');
  const thumbs  = slider.querySelectorAll('.pd-thumb');
  const total   = slides.length;
  if (total <= 1) return;

  let current = 0, timer;

  function goTo(n) {
    slides[current].classList.remove('active');
    dots[current]?.classList.remove('active');
    thumbs[current]?.classList.remove('active');
    current = (n + total) % total;
    slides[current].classList.add('active');
    dots[current]?.classList.add('active');
    thumbs[current]?.classList.add('active');
  }

  function startAuto() {
    clearInterval(timer);
    timer = setInterval(() => goTo(current + 1), 3000);
  }

  document.getElementById('pdNext')?.addEventListener('click', () => { goTo(current + 1); startAuto(); });
  document.getElementById('pdPrev')?.addEventListener('click', () => { goTo(current - 1); startAuto(); });
  dots.forEach(d => d.addEventListener('click', () => { goTo(+d.dataset.slide); startAuto(); }));
  thumbs.forEach(t => t.addEventListener('click', () => { goTo(+t.dataset.slide); startAuto(); }));

  startAuto();
})();
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
