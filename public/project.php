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
    $featured = db()->query(
        'SELECT * FROM projects WHERE is_active = 1 AND id != ' . (int)$project['id'] .
        ' ORDER BY is_featured DESC, sort_order DESC LIMIT 8'
    )->fetchAll();
} catch (Throwable $e) { $featured = []; }

$amenities    = parse_list_field($project['amenities']    ?? '');
$connectivity = parse_list_field($project['connectivity'] ?? '');
$usps         = parse_list_field($project['usps']         ?? '');
$gallery      = json_decode($project['gallery'] ?? '[]', true) ?: [];

$category   = project_category($project['property_type'] ?? '');
$cfg        = short_config($project['configurations'] ?? '');
$possession = $project['possession'] ?? 'TBA';

$mainImg = !empty($gallery[0]) ? upload_url($gallery[0]) : (!empty($project['cover_image']) ? upload_url($project['cover_image']) : asset('img/placeholders/project.svg'));
$galleryImgs = !empty($gallery) ? array_map('upload_url', $gallery) : [$mainImg];

$waMsg = 'Hi, I am interested in ' . $project['name'] . ' by ' . $project['builder'] . '. Please share more details.';
$waLink = whatsapp_url($settings['phone_whatsapp'], $waMsg);

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

<!-- ========== SUB-NAV ========== -->
<nav class="sp-subnav" aria-label="Project sections">
  <div class="container">
    <ul>
      <li><a href="#overview">Overview</a></li>
      <li><a href="#amenities">Amenities</a></li>
      <li><a href="#floorplan">Plans &amp; Price</a></li>
      <li><a href="#gallery">Gallery</a></li>
      <li><a href="#location">Location</a></li>
    </ul>
  </div>
</nav>

<!-- ========== HERO BANNER + SLIDER ========== -->
<section class="sp-hero" id="sp-hero">
  <div class="sp-hero-slider" id="spHeroSlider">
    <?php foreach ($galleryImgs as $i => $gImg): ?>
      <div class="sp-hero-slide <?= $i === 0 ? 'active' : '' ?>"
           style="background-image:url('<?= e($gImg) ?>')"></div>
    <?php endforeach; ?>
    <div class="sp-hero-overlay"></div>
  </div>

  <div class="container sp-hero-content">
    <div class="sp-hero-text">
      <span class="sp-hero-cat"><?= e($category) ?></span>
      <h1><?= e($project['name']) ?></h1>
      <p class="sp-hero-loc">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        <?= e($project['location']) ?>, <?= e($project['city']) ?>
      </p>
      <ul class="sp-hero-pointers">
        <li><strong>Price:</strong> <?= e($project['price_display']) ?></li>
        <li><?= e($cfg ?: $project['configurations']) ?></li>
        <?php if (!empty($project['rera_id'])): ?>
          <li>RERA Approved</li>
        <?php endif; ?>
      </ul>
      <a href="<?= e($waLink) ?>" target="_blank" rel="noopener" class="sp-wa-btn">
        <strong>Get details on</strong>
        <svg viewBox="0 0 24 24" fill="currentColor" width="22" height="22"><path d="M17.5 14.3c-.3-.1-1.7-.8-1.9-.9-.3-.1-.5-.1-.7.1-.2.3-.8.9-1 1.1-.2.2-.4.2-.6.1a7.6 7.6 0 0 1-3.7-3.2c-.3-.5.3-.4.8-1.3.1-.2 0-.4 0-.5s-.7-1.6-.9-2.2c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4s-1 1-1 2.4 1 2.8 1.2 3c.2.3 2 3 4.8 4.2l1.6.6c.7.2 1.3.2 1.8.1.5-.1 1.7-.7 1.9-1.3.2-.7.2-1.2.2-1.3zM12 2a10 10 0 0 0-8.5 15.2L2 22l4.9-1.3A10 10 0 1 0 12 2z"/></svg>
      </a>
    </div>

    <!-- Floating form -->
    <aside class="sp-hero-form" id="sp-form">
      <?php if (!empty($project['rera_id'])): ?>
      <div class="sp-rera-strip">
        <div>
          <small><strong>RERA No:</strong> <?= e($project['rera_id']) ?></small>
        </div>
      </div>
      <?php endif; ?>

      <h3>Enquire Now</h3>
      <p>Get the best deal &amp; site visit</p>
      <form action="<?= e(url('api/contact_submit.php')) ?>" method="post" data-ajax-form>
        <?= csrf_field() ?>
        <input type="hidden" name="source" value="project_page">
        <input type="hidden" name="project_id" value="<?= e($project['id']) ?>">
        <input type="hidden" name="project_name" value="<?= e($project['name']) ?>">
        <input type="text" name="website" class="honeypot" tabindex="-1" autocomplete="off">

        <div class="form-field">
          <input type="text" name="full_name" placeholder="Your Name *" required>
        </div>
        <div class="form-field">
          <input type="email" name="email" placeholder="Your Email *" required>
        </div>
        <div class="form-field">
          <input type="tel" name="phone" placeholder="Your Phone *" required pattern="[0-9+\- ]{10,15}">
        </div>
        <button type="submit" class="btn btn-gold btn-block">Send Message</button>
        <div class="form-msg" role="status" aria-live="polite"></div>
      </form>
    </aside>
  </div>
</section>

<!-- ========== OVERVIEW ========== -->
<?php if (!empty($project['description'])): ?>
<section class="sp-section sp-overview" id="overview">
  <div class="container">
    <div class="sp-section-head">
      <h2>About <?= e($project['name']) ?></h2>
      <div class="sp-arch" aria-hidden="true"></div>
    </div>
    <div class="sp-overview-text">
      <?php foreach (preg_split('/\n\s*\n/', trim($project['description'])) as $para): ?>
        <?php if (trim($para) === '') continue; ?>
        <p><?= e($para) ?></p>
      <?php endforeach; ?>
    </div>
    <div class="sp-cta-row">
      <a href="<?= e($waLink) ?>" target="_blank" rel="noopener" class="btn btn-outline">Download Brochure</a>
      <a href="<?= e($waLink) ?>" target="_blank" rel="noopener" class="btn btn-gold">Schedule Site Visit</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ========== STATS STRIP ========== -->
<section class="sp-stats-strip">
  <div class="container">
    <div class="sp-stats-grid">
      <div class="sp-stat-item">
        <small>Configuration</small>
        <strong><?= e($cfg ?: $project['configurations']) ?></strong>
      </div>
      <div class="sp-stat-item">
        <small>Sizes</small>
        <strong><?= e($project['sizes'] ?: 'On Request') ?></strong>
      </div>
      <div class="sp-stat-item">
        <small>Possession</small>
        <strong><?= e($possession) ?></strong>
      </div>
      <div class="sp-stat-item">
        <small>Starting Price</small>
        <strong class="sp-price"><?= e($project['price_display']) ?></strong>
      </div>
    </div>
  </div>
</section>

<!-- ========== AMENITIES ========== -->
<?php if (!empty($amenities)): ?>
<section class="sp-section sp-amenities" id="amenities"
         style="background-image: linear-gradient(rgba(5,12,28,0.92), rgba(5,12,28,0.92)), url('<?= e($mainImg) ?>')">
  <div class="container">
    <div class="sp-section-head sp-section-head--light">
      <h2>Amenities &amp; Lifestyle</h2>
      <div class="sp-arch" aria-hidden="true"></div>
      <p>Premium amenities crafted for a refined lifestyle at <?= e($project['name']) ?>.</p>
    </div>

    <div class="sp-amenities-grid">
      <?php foreach ($amenities as $a): ?>
      <div class="sp-amenity-box">
        <div class="sp-amenity-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <p><?= e($a) ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="sp-cta-row">
      <a href="<?= e($waLink) ?>" target="_blank" rel="noopener" class="btn btn-gold">View All Amenities</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ========== KEY HIGHLIGHTS / FLOOR PLANS ========== -->
<?php if (!empty($usps)): ?>
<section class="sp-section sp-floorplan" id="floorplan">
  <div class="container">
    <div class="sp-section-head">
      <h2>Key Highlights &amp; Plans</h2>
      <div class="sp-arch" aria-hidden="true"></div>
      <p>Discover what makes <?= e($project['name']) ?> a premium investment opportunity in <?= e($project['city']) ?>.</p>
    </div>

    <div class="sp-fp-grid">
      <?php foreach ($usps as $i => $u): ?>
      <div class="sp-fp-card">
        <div class="sp-fp-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
        </div>
        <h4>Highlight <?= $i + 1 ?></h4>
        <p><?= e($u) ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="sp-cta-row">
      <a href="<?= e($waLink) ?>" target="_blank" rel="noopener" class="btn btn-outline">Get Floor Plans</a>
      <a href="<?= e($waLink) ?>" target="_blank" rel="noopener" class="btn btn-gold">Price on Request</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ========== GALLERY ========== -->
<?php if (count($galleryImgs) > 0): ?>
<section class="sp-section sp-gallery" id="gallery">
  <div class="container">
    <div class="sp-section-head sp-section-head--light">
      <h2>Project Gallery</h2>
      <div class="sp-arch" aria-hidden="true"></div>
    </div>

    <div class="sp-gallery-grid">
      <?php foreach ($galleryImgs as $i => $gImg): ?>
      <div class="sp-gallery-item">
        <img src="<?= e($gImg) ?>" alt="<?= e($project['name']) ?> Gallery <?= $i + 1 ?>" loading="lazy">
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ========== LOCATION ========== -->
<section class="sp-section sp-location" id="location">
  <div class="container">
    <div class="sp-section-head">
      <h2>Location Advantages</h2>
      <div class="sp-arch" aria-hidden="true"></div>
      <p>Located at <strong><?= e($project['location']) ?></strong>, <?= e($project['city']) ?> — with seamless connectivity to major business and lifestyle destinations.</p>
    </div>

    <div class="sp-loc-grid">
      <div class="sp-loc-map">
        <iframe
          src="https://www.google.com/maps?q=<?= urlencode($project['location'] . ', ' . $project['city']) ?>&output=embed"
          width="100%" height="100%" style="border:0; border-radius: var(--radius-lg);"
          allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
          title="<?= e($project['name']) ?> Location Map"></iframe>
      </div>

      <div class="sp-loc-list">
        <h3>Connectivity &amp; Nearby</h3>
        <?php if (!empty($connectivity)): ?>
        <ul>
          <?php foreach ($connectivity as $c): ?>
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <span><?= e($c) ?></span>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <div class="sp-loc-address">
          <div><strong>Address:</strong> <?= e($project['location']) ?></div>
          <div><strong>City:</strong> <?= e($project['city']) ?></div>
          <div><strong>State:</strong> <?= e($schemaState) ?></div>
          <div><strong>Country:</strong> India</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========== GET IN TOUCH ========== -->
<section class="sp-section sp-touch">
  <div class="container">
    <div class="sp-section-head">
      <h2>Get in Touch</h2>
      <div class="sp-arch" aria-hidden="true"></div>
      <p>Want to know more about <?= e($project['name']) ?>? Our property advisor will call you back within 24 hours.</p>
    </div>

    <form class="sp-touch-form" action="<?= e(url('api/contact_submit.php')) ?>" method="post" data-ajax-form>
      <?= csrf_field() ?>
      <input type="hidden" name="source" value="project_page_bottom">
      <input type="hidden" name="project_id" value="<?= e($project['id']) ?>">
      <input type="hidden" name="project_name" value="<?= e($project['name']) ?>">
      <input type="text" name="website" class="honeypot" tabindex="-1" autocomplete="off">

      <div class="sp-touch-grid">
        <input type="text" name="full_name" placeholder="Your Name *" required>
        <input type="tel" name="phone" placeholder="Phone *" required pattern="[0-9+\- ]{10,15}">
        <input type="email" name="email" placeholder="Email *" required>
      </div>
      <div class="sp-touch-submit">
        <button type="submit" class="btn btn-gold">Send Message</button>
      </div>
      <div class="form-msg" role="status" aria-live="polite"></div>
    </form>
  </div>
</section>

<!-- ========== FAQs ========== -->
<section class="sp-section sp-faq">
  <div class="container">
    <div class="sp-section-head">
      <h2>Frequently Asked Questions</h2>
      <div class="sp-arch" aria-hidden="true"></div>
    </div>

    <div class="sp-faq-list">
      <details class="sp-faq-item">
        <summary>
          <span><strong>Q1:</strong> Where is <?= e($project['name']) ?> located?</span>
          <span class="sp-faq-icon">+</span>
        </summary>
        <div class="sp-faq-body">
          <?= e($project['name']) ?> is located at <?= e($project['location']) ?>, <?= e($project['city']) ?> — one of the most sought-after destinations in <?= e($schemaState) ?>.
        </div>
      </details>

      <details class="sp-faq-item">
        <summary>
          <span><strong>Q2:</strong> What configurations are available?</span>
          <span class="sp-faq-icon">+</span>
        </summary>
        <div class="sp-faq-body">
          <?= e($project['name']) ?> offers <?= e($project['configurations']) ?> with sizes ranging <?= e($project['sizes'] ?: 'on request') ?>.
        </div>
      </details>

      <details class="sp-faq-item">
        <summary>
          <span><strong>Q3:</strong> What is the possession date?</span>
          <span class="sp-faq-icon">+</span>
        </summary>
        <div class="sp-faq-body">
          Possession is targeted for <?= e($possession) ?>. Please contact us for the latest construction status updates.
        </div>
      </details>

      <?php if (!empty($project['rera_id'])): ?>
      <details class="sp-faq-item">
        <summary>
          <span><strong>Q4:</strong> Is the project RERA-registered?</span>
          <span class="sp-faq-icon">+</span>
        </summary>
        <div class="sp-faq-body">
          Yes, <?= e($project['name']) ?> is fully RERA-registered. RERA No: <strong><?= e($project['rera_id']) ?></strong>.
        </div>
      </details>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ========== FEATURED PROJECTS ========== -->
<?php if (!empty($featured)): ?>
<section class="sp-section sp-featured">
  <div class="container">
    <div class="sp-section-head">
      <h2>Featured Projects</h2>
      <div class="sp-arch" aria-hidden="true"></div>
    </div>

    <div class="projects-grid">
      <?php foreach (array_slice($featured, 0, 3) as $p):
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
          <div class="media-price">
            <small>Starting From</small>
            <strong><?= e($p['price_display']) ?></strong>
          </div>
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
          <div class="cta-row">
            <a href="<?= e(url('project.php?slug=' . urlencode($p['slug']))) ?>" class="btn btn-outline btn-sm">View Details</a>
            <a href="<?= e(whatsapp_url($settings['phone_whatsapp'], 'Hi, I want details about ' . $p['name'])) ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-sm">WhatsApp</a>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<script>
(function(){
  // Hero slider auto
  const slides = document.querySelectorAll('#spHeroSlider .sp-hero-slide');
  if (slides.length > 1) {
    let cur = 0;
    setInterval(() => {
      slides[cur].classList.remove('active');
      cur = (cur + 1) % slides.length;
      slides[cur].classList.add('active');
    }, 4000);
  }

  // Sub-nav smooth scroll
  document.querySelectorAll('.sp-subnav a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const id = a.getAttribute('href');
      const target = document.querySelector(id);
      if (target) {
        e.preventDefault();
        const offset = 140;
        window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - offset, behavior: 'smooth' });
      }
    });
  });

  // Sub-nav active state on scroll
  const sections = ['overview','amenities','floorplan','gallery','location'].map(id => document.getElementById(id)).filter(Boolean);
  const navLinks = document.querySelectorAll('.sp-subnav a');
  window.addEventListener('scroll', () => {
    let curId = '';
    sections.forEach(sec => {
      if (sec.getBoundingClientRect().top <= 200) curId = sec.id;
    });
    navLinks.forEach(a => {
      a.classList.toggle('active', a.getAttribute('href') === '#' + curId);
    });
  }, { passive: true });
})();
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
