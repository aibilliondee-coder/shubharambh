<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/settings.php';

start_session_once();
$settings = load_settings();

// ---- Fetch dynamic content ----
try {
    $projects = db()->query(
        'SELECT * FROM projects WHERE is_featured = 1 AND is_active = 1
         ORDER BY sort_order DESC, id DESC LIMIT 9'
    )->fetchAll();

    $testimonials = db()->query(
        'SELECT * FROM testimonials WHERE is_active = 1
         ORDER BY sort_order ASC, id DESC'
    )->fetchAll();

    $partners = db()->query(
        'SELECT * FROM partners WHERE is_active = 1
         ORDER BY sort_order ASC, id DESC'
    )->fetchAll();

    $ceo = db()->query(
        "SELECT * FROM team_members WHERE is_active = 1
         ORDER BY sort_order ASC, id ASC LIMIT 1"
    )->fetch();

    // Distinct cities for hero search dropdown
    $cities = db()->query(
        'SELECT DISTINCT city FROM projects WHERE is_active = 1 ORDER BY city'
    )->fetchAll(PDO::FETCH_COLUMN);
} catch (Throwable $e) {
    $projects = $testimonials = $partners = $cities = [];
    $ceo = null;
    if (APP_ENV === 'local') {
        echo '<pre style="color:#ee6055;background:#081527;padding:1rem;font-family:monospace;">';
        echo 'DB error: ' . e($e->getMessage());
        echo '</pre>';
    }
}

$page_title       = 'Best Property Advisor in Noida — ' . $settings['company_name'];
$page_description = 'Best property advisor in Noida — Shubharambh Infra Advisors. RERA-registered consultancy for residential, commercial & investment properties across Delhi NCR.';
$page_active      = 'home';
$page_canonical   = url('index.php');

// Preload first hero slide so LCP image is discovered early
$heroSlidesPreload = [
    'projects/m3mcullinan-1.webp',
    'projects/elanimperial1.webp',
    'projects/godrejriverine1.webp',
    'projects/mahindracodenamegreenlife1.webp',
];
$firstHeroSlide = null;
foreach ($heroSlidesPreload as $p) {
    if (file_exists(APP_ROOT . '/public/uploads/' . $p)) {
        $firstHeroSlide = upload_url($p);
        break;
    }
}

// Inject preload link into <head> before header renders
$page_extra_head = $firstHeroSlide
    ? '<link rel="preload" as="image" href="' . e($firstHeroSlide) . '" fetchpriority="high">'
    : '';

include __DIR__ . '/../includes/header.php';
?>

<!-- ==========  HERO  ========== -->
<?php
  // Rotating showcase images for hero background (uses real project covers).
  // Drop an MP4 at public/assets/video/hero.mp4 and it will auto-enable.
  $heroSlides = [
      'projects/m3mcullinan-1.webp',
      'projects/elanimperial1.webp',
      'projects/godrejriverine1.webp',
      'projects/mahindracodenamegreenlife1.webp',
  ];
  $heroSlides = array_values(array_filter($heroSlides, fn($p) => file_exists(APP_ROOT . '/public/uploads/' . $p)));
  $heroVideo  = APP_ROOT . '/public/assets/video/hero.mp4';
  $hasHeroVideo = is_file($heroVideo);
?>
<section class="hero">
  <!-- Cinematic media stack: video (if available) + rotating image slides + overlays -->
  <div class="hero-media" aria-hidden="true">
    <?php if ($hasHeroVideo): ?>
      <video class="hero-video" autoplay muted loop playsinline preload="auto"
             poster="<?= e(upload_url($heroSlides[0] ?? 'projects/m3mcullinan-1.webp')) ?>">
        <source src="<?= e(asset('video/hero.mp4')) ?>" type="video/mp4">
      </video>
    <?php endif; ?>

    <div class="hero-slides" id="hero-slides">
      <?php foreach ($heroSlides as $i => $slide): ?>
        <div class="hero-slide<?= $i === 0 ? ' is-active' : '' ?>"
             style="background-image:url('<?= e(upload_url($slide)) ?>');"></div>
      <?php endforeach; ?>
    </div>

    <div class="hero-overlay"></div>
    <div class="hero-grid"></div>
    <div class="hero-glow"></div>
    <div class="hero-orb hero-orb--1"></div>
    <div class="hero-orb hero-orb--2"></div>
  </div>

  <div class="container hero-inner">
    <span class="eyebrow">Shubharambh Infra Advisors — Best Property Advisor in Noida</span>
    <h1 style="font-size:clamp(1.6rem,7vw,3.25rem);">Best Property Advisor <span class="accent">in Noida</span></h1>
    <p class="sub">Trusted by 500+ Families Across Delhi NCR</p>

    <div class="hero-search" role="search">
      <div class="hero-search-tabs" role="tablist" aria-label="Property type">
        <button type="button" class="active" data-type="Residential" role="tab" aria-selected="true">Residential</button>
        <button type="button" data-type="Commercial" role="tab" aria-selected="false">Commercial</button>
        <button type="button" data-type="Plots" role="tab" aria-selected="false">Plots</button>
      </div>

      <form class="hero-search-form" action="<?= e(url('projects.php')) ?>" method="get">
        <input type="hidden" name="category" id="hero-search-type" value="Residential">

        <div class="field field--search">
          <label for="hero-search-input">Search</label>
          <input type="text" id="hero-search-input" name="q"
                 placeholder="Try M3M, Godrej, Sector 94…" autocomplete="off">
          <div class="hero-search-results" id="hero-search-results" role="listbox"></div>
        </div>

        <div class="field">
          <label for="hero-search-city">City</label>
          <select id="hero-search-city" name="city">
            <option value="">All cities</option>
            <?php foreach ($cities as $c): ?>
              <option value="<?= e($c) ?>"><?= e($c) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field">
          <label for="hero-search-budget">Budget</label>
          <select id="hero-search-budget" name="budget">
            <option value="">Any</option>
            <option value="under-1">Under &#8377;1 Cr</option>
            <option value="1-2">&#8377;1 &ndash; 2 Cr</option>
            <option value="2-5">&#8377;2 &ndash; 5 Cr</option>
            <option value="5-10">&#8377;5 &ndash; 10 Cr</option>
            <option value="10plus">&#8377;10 Cr+</option>
          </select>
        </div>

        <button type="submit" class="btn btn-gold" aria-label="Search projects">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          Search
        </button>
      </form>
    </div>

    <div class="hero-stats reveal delay-1">
      <div class="stat"><span class="num" data-counter="10" data-suffix="+">10+</span><span class="lbl">Years Experience</span></div>
      <div class="stat"><span class="num" data-counter="500" data-suffix="+">500+</span><span class="lbl">Happy Clients</span></div>
      <div class="stat"><span class="num" data-counter="50" data-suffix="+">50+</span><span class="lbl">Premium Projects</span></div>
      <div class="stat"><span class="num">RERA</span><span class="lbl">Registered</span></div>
    </div>
  </div>

  <div class="hero-slide-dots" id="hero-slide-dots" aria-label="Showcase pagination"></div>

  <div class="hero-scroll-hint" aria-hidden="true">
    Scroll
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
  </div>
</section>

<!-- ==========  TRUST / PARTNERS STRIP  ========== -->
<?php if (!empty($partners)): ?>
<section class="trust-strip" aria-label="Developer partners">
  <div class="container">
    <p class="trust-strip-label">Partnered With India's Most Trusted Developers</p>
  </div>
  <div class="partners-track">
    <?php foreach (array_merge($partners, $partners) as $pt): ?>
      <div class="partner">
        <?php if (!empty($pt['logo']) && file_exists(APP_ROOT . '/public/uploads/' . $pt['logo'])): ?>
          <img src="<?= e(upload_url($pt['logo'])) ?>" alt="<?= e($pt['name']) ?> — Trusted Real Estate Developer Partner of Shubharambh Infra Advisors" loading="lazy">
        <?php else: ?>
          <span><?= e($pt['name']) ?></span>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- ==========  ADVISOR INFO EXPAND  ========== -->
<section class="section section--plain advisor-info-section" id="advisor-info">
  <div class="container">
    <div class="advisor-info-wrap reveal">
      <div class="advisor-info-header">
        <div class="advisor-info-title">
          <h2>Your Trusted Property Investment Partner <span class="accent">in Noida</span></h2>
          <p>Shubharambh Infra Advisors — helping families find the right property since 2014.</p>
        </div>
        <button class="advisor-read-more-btn" aria-expanded="false" aria-controls="advisor-info-body" type="button">
          <span class="btn-label">Read More</span>
          <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
      </div>
    </div>
  </div>
</section>

<!-- Advisor side panel — outside all sections so position:fixed works correctly -->
<div class="advisor-panel-backdrop" id="advisor-panel-backdrop" aria-hidden="true"></div>
<div class="advisor-info-body" id="advisor-info-body" aria-hidden="true">
  <div class="advisor-panel-close">
    <span class="advisor-panel-close-title">About Us</span>
    <button class="advisor-panel-close-btn" aria-label="Close panel" type="button">&#10005;</button>
  </div>
  <div class="advisor-info-grid">
    <div class="advisor-info-card">
      <div class="advisor-info-card__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      </div>
      <h3>Best Property Advisor in Noida for Buyers &amp; Investors</h3>
      <p>Shubharambh Infra Advisors is recognized as the best property advisor in Noida — with a strong focus on transparency, RERA compliance and client satisfaction. We help buyers and investors make confident, informed property decisions across Delhi NCR.</p>
    </div>
    <div class="advisor-info-card">
      <div class="advisor-info-card__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
      <h3>Expert Guidance for Residential, Commercial &amp; Investment Properties</h3>
      <p>As a leading property advisor in Noida, we provide expert guidance on residential apartments, luxury plots and commercial spaces. Our experienced consultants analyze market trends, project reliability and growth potential to ensure maximum ROI for every client.</p>
    </div>
    <div class="advisor-info-card">
      <div class="advisor-info-card__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      </div>
      <h3>RERA-Registered &amp; Trusted Real Estate Consultancy in Delhi NCR</h3>
      <p>With 10+ years of experience and 500+ families served, we have earned a reputation as the most trusted real estate consultancy in Noida and Delhi NCR. Every project in our portfolio is RERA-approved and due-diligence verified — so you invest with total confidence.</p>
    </div>
  </div>
</div>

<!-- ==========  FEATURED PROJECTS  ========== -->
<section class="section" id="projects">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Curated Portfolio</span>
      <h2>Featured Properties</h2>
      <div class="arch-divider" aria-hidden="true"></div>
      <p>
        Hand-picked luxury residences, commercial spaces and investment
        opportunities from India's most trusted developers.
      </p>
    </div>

    <div class="projects-grid">
      <?php foreach ($projects as $p): ?>
        <?php
          $imgPath = !empty($p['cover_image'])
              ? upload_url($p['cover_image'])
              : asset('img/placeholders/project.svg');
          $waMsg = 'Hi, I am interested in ' . $p['name'] . ' by ' . $p['builder'] . '. Please share more details.';
          $cfg = short_config($p['configurations'] ?? '');
          $possession = short_possession($p['possession'] ?? '');
          $category = project_category($p['property_type'] ?? '');
        ?>
        <article class="project-card reveal">
          <div class="media">
            <div class="badges">
              <span class="badge"><?= e($category) ?></span>
              <?php if (!empty($p['rera_id'])): ?>
                <span class="badge badge--rera">RERA</span>
              <?php endif; ?>
            </div>
            <button class="fav" type="button"
                    data-fav="<?= e($p['id']) ?>"
                    data-name="<?= e($p['name']) ?>"
                    aria-label="Save <?= e($p['name']) ?> to shortlist">
              <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </button>
            <img src="<?= e($imgPath) ?>" alt="<?= e($p['name']) ?> by <?= e($p['builder']) ?> — <?= e($p['property_type']) ?> in <?= e($p['city']) ?>, Delhi NCR | Shubharambh Infra Advisors" loading="lazy"
                 onerror="this.style.display='none'">
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
              <div><span class="v"><?= e($cfg) ?></span><span class="l">Config</span></div>
              <div><span class="v"><?= e($possession) ?></span><span class="l">Possession</span></div>
              <div><span class="v"><?= e($p['city']) ?></span><span class="l">City</span></div>
            </div>
            <div class="price">
              <small>Starting From</small>
              <?= e($p['price_display']) ?>
            </div>
            <div class="cta-row">
              <a href="<?= e(url('project.php?slug=' . urlencode($p['slug']))) ?>" class="btn btn-outline btn-sm">View Details</a>
              <a href="<?= e(whatsapp_url($settings['phone_whatsapp'], $waMsg)) ?>"
                 class="btn btn-whatsapp btn-sm" target="_blank" rel="noopener">WhatsApp</a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>

      <?php if (empty($projects)): ?>
        <div class="empty-state">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
          <p>No featured projects yet. Please check back soon.</p>
        </div>
      <?php endif; ?>
    </div>

    <div style="text-align:center;margin-top:3rem;">
      <a href="<?= e(url('projects.php')) ?>" class="btn btn-outline">View All Projects</a>
    </div>
  </div>
</section>

<!-- ==========  ABOUT  ========== -->
<section class="section section--soft" id="about">
  <div class="container">
    <div class="about-grid">
      <div class="about-img reveal">
        <div class="img-wrap">
          <?php if ($ceo && !empty($ceo['photo']) && file_exists(APP_ROOT . '/public/uploads/' . $ceo['photo'])): ?>
            <img src="<?= e(upload_url($ceo['photo'])) ?>" alt="<?= e($ceo['full_name']) ?> — <?= e($ceo['title'] ?? 'Founder') ?>, Shubharambh Infra Advisors, Real Estate Expert Delhi NCR" loading="lazy">
          <?php else: ?>
            <div style="height:100%;display:flex;align-items:center;justify-content:center;font-family:var(--f-serif);color:var(--c-gold);font-size:2rem;padding:2rem;text-align:center;">
              <?= e($ceo['full_name'] ?? 'Mr. Mohit Khari') ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="badge-float">
          <strong>10+</strong>
          <span>Years of Trust</span>
        </div>
      </div>

      <div class="about-body reveal delay-1">
        <span class="eyebrow">About Us</span>
        <h2><?= e($settings['about_heading']) ?></h2>
        <?php
          $aboutBody = $settings['about_body'] ?? '';
          foreach (preg_split('/\n\s*\n/', trim($aboutBody)) as $para):
            if (trim($para) === '') continue;
        ?>
          <p><?= e($para) ?></p>
        <?php endforeach; ?>

        <?php if ($ceo): ?>
          <div class="sig">
            <strong><?= e($ceo['full_name']) ?></strong>
            <span><?= e($ceo['title']) ?></span>
          </div>
        <?php endif; ?>

        <p style="color:var(--c-muted);font-size:0.9rem;margin-top:0.75rem;">Trusted by 500+ families as the best property advisor in Noida and Delhi NCR.</p>

        <div style="margin-top:1.75rem;display:flex;gap:0.75rem;flex-wrap:wrap;">
          <a href="<?= e(url('about.php')) ?>" class="btn btn-gold">About Shubharambh</a>
          <a href="<?= e(url('contact.php')) ?>" class="btn btn-ghost">Contact Team</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========  COUNTERS  ========== -->
<section class="section section--plain" aria-label="Our numbers">
  <div class="container">
    <div class="counters reveal">
      <div class="counter">
        <span class="num" data-counter="10" data-suffix="+">10+</span>
        <span class="lbl">Years in Business</span>
      </div>
      <div class="counter">
        <span class="num" data-counter="500" data-suffix="+">500+</span>
        <span class="lbl">Families Served</span>
      </div>
      <div class="counter">
        <span class="num" data-counter="50" data-suffix="+">50+</span>
        <span class="lbl">Curated Projects</span>
      </div>
      <div class="counter">
        <span class="num" data-counter="800" data-suffix="Cr+">800Cr+</span>
        <span class="lbl">Transacted Value</span>
      </div>
    </div>
  </div>
</section>

<!-- ==========  WHY CHOOSE US  ========== -->
<section class="section section--soft">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Our Advantage</span>
      <h2>Noida's Best Property Advisor — Shubharambh Infra</h2>
      <div class="arch-divider" aria-hidden="true"></div>
      <p>As the best property advisor in Noida, we build every client relationship on transparency, expertise and a client-first mindset.</p>
    </div>

    <div class="pillars">
      <div class="pillar reveal">
        <div class="icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
        </div>
        <h3>RERA-Verified Projects</h3>
        <p>Every property in our portfolio is RERA-approved, due-diligence verified and legally sound — so you invest with total confidence.</p>
      </div>

      <div class="pillar reveal delay-1">
        <div class="icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </div>
        <h3>Personalised Advisory</h3>
        <p>Every recommendation is tailored to your lifestyle, budget and investment goals — never a one-size-fits-all listing dump.</p>
      </div>

      <div class="pillar reveal delay-2">
        <div class="icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
        </div>
        <h3>End-to-End Support</h3>
        <p>From site visits and loan tie-ups to registration and handover — we stay by your side through every step of the journey.</p>
      </div>
    </div>

    <!-- Secondary benefits -->
    <div class="feature-grid" style="margin-top:2.5rem;">
      <div class="feature reveal">
        <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/></svg></div>
        <div><h3 class="feature-title">10+ Years of Expertise</h3><p>Deep market insight across Delhi NCR's most sought-after micro-markets.</p></div>
      </div>
      <div class="feature reveal delay-1">
        <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v6M12 17v6M4.22 4.22l4.24 4.24M15.54 15.54l4.24 4.24M1 12h6M17 12h6M4.22 19.78l4.24-4.24M15.54 8.46l4.24-4.24"/></svg></div>
        <div><h3 class="feature-title">Best Price Negotiation</h3><p>Developer relationships that unlock preferred pricing and payment plans.</p></div>
      </div>
      <div class="feature reveal delay-2">
        <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="M22 6l-10 7L2 6"/></svg></div>
        <div><h3 class="feature-title">Transparent Communication</h3><p>No surprises — clear pricing, honest advice and regular updates.</p></div>
      </div>
      <div class="feature reveal delay-3">
        <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
        <div><h3 class="feature-title">Home Loan Assistance</h3><p>Partnered with leading banks for faster approvals and competitive rates.</p></div>
      </div>
    </div>
  </div>
</section>

<!-- ==========  BLOG SECTION  ========== -->
<?php
try {
    $recentBlogs = db()->query("SELECT id, slug, title, excerpt, category, author, read_time, cover_image, published_at FROM blogs WHERE is_published = 1 ORDER BY sort_order DESC, published_at DESC LIMIT 6")->fetchAll();
    $blogTotal   = (int)db()->query("SELECT COUNT(*) FROM blogs WHERE is_published = 1")->fetchColumn();
} catch (Throwable $e) { $recentBlogs = []; $blogTotal = 0; }
?>
<?php if (!empty($recentBlogs)): ?>
<section class="section section--soft" id="blog">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Expert Insights</span>
      <h2>Real Estate Blog & <span class="accent">Property Guides</span></h2>
      <div class="arch-divider" aria-hidden="true"></div>
      <p>Investment tips, market updates and property guides from the best property advisor in Noida</p>
    </div>

    <div class="blog-grid blog-grid--home reveal">
      <?php foreach ($recentBlogs as $b):
        $bimg  = !empty($b['cover_image']) ? upload_url($b['cover_image']) : asset('img/placeholders/blog.jpg');
      ?>
      <article class="blog-card">
        <a href="<?= e(url('blog.php')) ?>?slug=<?= urlencode($b['slug']) ?>" class="blog-card__img-wrap">
          <img src="<?= e($bimg) ?>" alt="<?= e($b['title']) ?>" loading="lazy">
          <span class="blog-card__cat"><?= e($b['category']) ?></span>
        </a>
        <div class="blog-card__body">
          <div class="blog-card__meta">
            <span><?= (int)$b['read_time'] ?> min read</span>
            <span class="blog-card__dot">·</span>
            <span>Shubharambh Infra Advisors</span>
          </div>
          <h3 class="blog-card__title">
            <a href="<?= e(url('blog.php')) ?>?slug=<?= urlencode($b['slug']) ?>"><?= e($b['title']) ?></a>
          </h3>
          <p class="blog-card__excerpt"><?= e(mb_substr($b['excerpt'], 0, 100)) ?>…</p>
          <a href="<?= e(url('blog.php')) ?>?slug=<?= urlencode($b['slug']) ?>" class="blog-card__read">
            Read More <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="13" height="13"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>

    <?php if ($blogTotal > 6): ?>
    <div style="text-align:center;margin-top:2.5rem;">
      <a href="<?= e(url('blogs.php')) ?>" class="btn btn-outline">View All Blogs</a>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<!-- ==========  TESTIMONIALS  ========== -->
<?php if (!empty($testimonials)): ?>
<section class="section" id="testimonials">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Client Love</span>
      <h2>What Our Clients Say</h2>
      <div class="arch-divider" aria-hidden="true"></div>
      <p>Real stories from families who found their dream homes with us.</p>
    </div>

    <div class="testimonials reveal">
      <button type="button" class="testimonial-nav prev" aria-label="Previous testimonial">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      </button>
      <div class="testimonial-viewport">
        <div class="testimonial-track">
          <?php foreach ($testimonials as $t): ?>
            <div class="testimonial">
              <div class="stars"><?= str_repeat('&#9733; ', (int)$t['rating']) ?></div>
              <blockquote><?= e($t['quote']) ?></blockquote>
              <div class="author"><?= e($t['client_name']) ?></div>
              <?php if (!empty($t['city'])): ?>
                <div class="city"><?= e($t['city']) ?></div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <button type="button" class="testimonial-nav next" aria-label="Next testimonial">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      </button>
      <div class="testimonial-dots" role="tablist" aria-label="Choose testimonial">
        <?php foreach ($testimonials as $ti => $t): ?>
          <button type="button" role="tab" aria-label="Testimonial <?= $ti + 1 ?>" <?= $ti === 0 ? 'aria-selected="true" class="active"' : 'aria-selected="false"' ?>></button>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ==========  FAQ  ========== -->
<section class="section section--soft">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">FAQs</span>
      <h2>Frequently Asked Questions</h2>
      <div class="arch-divider" aria-hidden="true"></div>
      <p>Answers to the questions we hear most often from first-time and seasoned property buyers.</p>
    </div>

    <div class="faq-wrap reveal">
      <details class="faq">
        <summary>Are all your projects RERA-registered?</summary>
        <div class="content">
          <p>Yes. Every property we advise on is a RERA-registered project from a reputable, compliant developer. We display the RERA ID on every project detail page and can share the public registration link on request.</p>
        </div>
      </details>

      <details class="faq">
        <summary>Do you charge any fees from buyers?</summary>
        <div class="content">
          <p>No. Buyers pay nothing to Shubharambh Infra Advisors. Our brokerage is paid by the developer at the time of booking, so you receive neutral advisory, site visits and documentation support at zero cost.</p>
        </div>
      </details>

      <details class="faq">
        <summary>Can you help with home loans?</summary>
        <div class="content">
          <p>Absolutely. We work with leading banks (HDFC, SBI, ICICI, Axis, LIC Housing) to get you competitive interest rates and faster approvals. Use our <a href="<?= e(url('emi-calculator.php')) ?>">EMI calculator</a> to plan your budget first.</p>
        </div>
      </details>

      <details class="faq">
        <summary>Which locations do you cover?</summary>
        <div class="content">
          <p>We actively cover Delhi NCR (Noida, Greater Noida, Gurgaon, Faridabad) and Uttarakhand (Haridwar, Ramnagar). We also assist NRI clients with investments across these regions.</p>
        </div>
      </details>

      <details class="faq">
        <summary>How do I book a site visit?</summary>
        <div class="content">
          <p>Click "Enquire" on any project page or fill out our <a href="<?= e(url('contact.php')) ?>">contact form</a>. Our team will call you within 24 hours to schedule a free, guided site visit with transport arrangement if needed.</p>
        </div>
      </details>

      <details class="faq">
        <summary>Do you help NRIs buy property in India?</summary>
        <div class="content">
          <p>Yes. We have dedicated NRI services covering remote site visits (video walkthrough), FEMA-compliant documentation, power-of-attorney guidance, and end-to-end purchase support — without requiring you to fly down.</p>
        </div>
      </details>
    </div>
  </div>
</section>

<!-- ==========  FIND US  ========== -->
<section class="section section--soft" id="find-us">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Our Location</span>
      <h2>Find Us at <span class="accent">Logix Technova, Noida</span></h2>
      <div class="arch-divider" aria-hidden="true"></div>
      <p>We are centrally located in Sector 132, Noida — just off the Noida–Greater Noida Expressway with easy metro connectivity.</p>
    </div>

    <div class="map-section reveal">

      <!-- Info cards row -->
      <div class="map-info-cards">
        <div class="map-info-card">
          <div class="map-info-card__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          </div>
          <div>
            <strong>Office Address</strong>
            <span>B-220, Logix Technova, Sector 132<br>Noida, Uttar Pradesh – 201304</span>
          </div>
        </div>
        <div class="map-info-card">
          <div class="map-info-card__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
          </div>
          <div>
            <strong>Nearest Metro</strong>
            <span>Sector 137 Metro Station<br>Aqua Line — 5 min drive</span>
          </div>
        </div>
        <div class="map-info-card">
          <div class="map-info-card__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
          </div>
          <div>
            <strong>Expressway Access</strong>
            <span>Noida–Greater Noida Expressway<br>Sector 132 Exit — 2 min</span>
          </div>
        </div>
        <div class="map-info-card">
          <div class="map-info-card__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
          </div>
          <div>
            <strong>Working Hours</strong>
            <span>Mon – Sat: 10:00 AM – 7:00 PM<br>Sunday: By Appointment</span>
          </div>
        </div>
      </div>

      <!-- Map embed -->
      <div class="map-embed-wrap">
        <div class="map-embed-overlay">
          <div class="map-pin-label">
            <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
            Shubharambh Infra Advisors
          </div>
        </div>
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d875.3!2d77.3769383!3d28.5084976!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce99f97f93a17%3A0x1ef37cdad52ed565!2sShubharambh%20Infra%20Advisors%20Pvt.%20Ltd.!5e0!3m2!1sen!2sin!4v1713600000000!5m2!1sen!2sin"
          width="100%"
          height="560"
          style="border:0;"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          title="Shubharambh Infra Advisors Location — Logix Technova, Sector 132, Noida"
          aria-label="Google Map showing Shubharambh Infra Advisors office location">
        </iframe>
      </div>

      <!-- Direction button -->
      <div style="text-align:center;margin-top:1.5rem;">
        <a href="https://www.google.com/maps/dir//Shubharambh+Infra+Advisors+Pvt.+Ltd.,+LOGIX+TECHNOVA,+B-220,+Block+B,+Sector+132,+Noida,+Uttar+Pradesh+201304/@28.5085151,77.3793737,17z"
           target="_blank" rel="noopener" class="btn btn-gold">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16" style="margin-right:0.4rem;"><path d="M3 12h18M3 6l9-4 9 4M3 18l9 4 9-4"/></svg>
          Get Directions on Google Maps
        </a>
      </div>

    </div>
  </div>
</section>

<!-- ==========  CONTACT  ========== -->
<section class="section" id="contact">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Get In Touch</span>
      <h2>Talk to the Best Property Advisor in Noida</h2>
      <div class="arch-divider" aria-hidden="true"></div>
    </div>

    <div class="contact-grid">
      <div class="contact-info reveal">
        <h2 style="font-size:1.6rem;">We'd Love to Hear From You</h2>
        <p>Reach out for a free consultation &mdash; our team will help you discover properties that match your goals and budget.</p>
        <ul>
          <li>
            <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
            <div>
              <h3 class="contact-label">Office Address</h3>
              <div class="val"><?= e($settings['address_line']) ?></div>
            </div>
          </li>
          <li>
            <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
            <div>
              <h3 class="contact-label">Phone / WhatsApp</h3>
              <div class="val"><a href="tel:<?= e(preg_replace('/\s+/', '', $settings['phone_primary'])) ?>"><?= e($settings['phone_primary']) ?></a></div>
            </div>
          </li>
          <li>
            <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="M22 6l-10 7L2 6"/></svg></div>
            <div>
              <h3 class="contact-label">Email</h3>
              <div class="val"><a href="mailto:<?= e($settings['email_primary']) ?>"><?= e($settings['email_primary']) ?></a></div>
            </div>
          </li>
          <li>
            <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
            <div>
              <h3 class="contact-label">Working Hours</h3>
              <div class="val">Monday &ndash; Saturday &middot; 10:00 AM &ndash; 7:00 PM</div>
            </div>
          </li>
        </ul>
      </div>

      <form class="contact-form reveal delay-1" action="<?= e(url('api/contact_submit.php')) ?>" method="post" data-ajax-form>
        <h3>Send us a Message</h3>
        <p class="subtitle">We'll reply within 24 business hours.</p>
        <?= csrf_field() ?>
        <input type="hidden" name="source" value="contact">
        <input type="text" name="website" class="honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">

        <div class="form-grid">
          <div class="form-field">
            <label for="cf-name">Full Name *</label>
            <input type="text" id="cf-name" name="full_name" required minlength="2" maxlength="150" autocomplete="name">
          </div>
          <div class="form-field">
            <label for="cf-phone">Phone *</label>
            <input type="tel" id="cf-phone" name="phone" required pattern="[0-9+\- ]{10,15}" autocomplete="tel">
          </div>
          <div class="form-field">
            <label for="cf-email">Email</label>
            <input type="email" id="cf-email" name="email" maxlength="150" autocomplete="email">
          </div>
          <div class="form-field">
            <label for="cf-city">City</label>
            <input type="text" id="cf-city" name="city" maxlength="100" placeholder="e.g. Noida" autocomplete="address-level2">
          </div>
          <div class="form-field full">
            <label for="cf-project">Project of Interest</label>
            <select id="cf-project" name="project_name">
              <option value="">-- Select a project --</option>
              <?php foreach ($projects as $p): ?>
                <option value="<?= e($p['name']) ?>"><?= e($p['name']) ?> &mdash; <?= e($p['builder']) ?></option>
              <?php endforeach; ?>
              <option value="General Enquiry">General Enquiry</option>
            </select>
          </div>
          <div class="form-field full">
            <label for="cf-message">Message</label>
            <textarea id="cf-message" name="message" maxlength="2000" placeholder="Tell us about what you're looking for…"></textarea>
          </div>
          <label class="form-consent full">
            <input type="checkbox" required>
            I agree to be contacted about my enquiry and accept the
            <a href="<?= e(url('privacy-policy.php')) ?>">privacy policy</a>.
          </label>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-gold">Send Enquiry</button>
        </div>
        <div class="form-msg" role="status" aria-live="polite"></div>
      </form>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
