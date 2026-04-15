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
         ORDER BY sort_order ASC, id DESC LIMIT 9'
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

$page_title       = $settings['company_name'] . ' — ' . $settings['tagline'];
$page_description = 'Shubharambh Infra Advisors — RERA-registered real estate consultancy in Noida. Luxury residential, commercial and investment properties across Delhi NCR, Gurgaon and Uttarakhand.';
$page_active      = 'home';
$page_canonical   = url('index.php');
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
    <span class="eyebrow">Shubharambh Infra Advisors</span>
    <h1><?= e($settings['hero_title']) ?> <span class="accent">in Delhi NCR</span></h1>
    <p class="sub"><?= e($settings['hero_subtitle']) ?></p>

    <div class="hero-search" role="search">
      <div class="hero-search-tabs" role="tablist" aria-label="Property type">
        <button type="button" class="active" data-type="Residential" role="tab" aria-selected="true">Residential</button>
        <button type="button" data-type="Commercial" role="tab" aria-selected="false">Commercial</button>
        <button type="button" data-type="Plots" role="tab" aria-selected="false">Plots</button>
      </div>

      <form class="hero-search-form" action="<?= e(url('projects.php')) ?>" method="get">
        <input type="hidden" name="category" id="hero-search-type" value="Residential">

        <div class="field field--search">
          <label for="hero-search-input">Project / Builder / Location</label>
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
    <h4>Partnered With India's Most Trusted Developers</h4>
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

        <div style="margin-top:1.75rem;display:flex;gap:0.75rem;flex-wrap:wrap;">
          <a href="<?= e(url('about.php')) ?>" class="btn btn-gold">Learn More</a>
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
      <h2>Why Choose Shubharambh Infra</h2>
      <div class="arch-divider" aria-hidden="true"></div>
      <p>A partnership built on transparency, expertise and a client-first mindset.</p>
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
        <div><h4>10+ Years of Expertise</h4><p>Deep market insight across Delhi NCR's most sought-after micro-markets.</p></div>
      </div>
      <div class="feature reveal delay-1">
        <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v6M12 17v6M4.22 4.22l4.24 4.24M15.54 15.54l4.24 4.24M1 12h6M17 12h6M4.22 19.78l4.24-4.24M15.54 8.46l4.24-4.24"/></svg></div>
        <div><h4>Best Price Negotiation</h4><p>Developer relationships that unlock preferred pricing and payment plans.</p></div>
      </div>
      <div class="feature reveal delay-2">
        <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="M22 6l-10 7L2 6"/></svg></div>
        <div><h4>Transparent Communication</h4><p>No surprises — clear pricing, honest advice and regular updates.</p></div>
      </div>
      <div class="feature reveal delay-3">
        <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
        <div><h4>Home Loan Assistance</h4><p>Partnered with leading banks for faster approvals and competitive rates.</p></div>
      </div>
    </div>
  </div>
</section>

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
      <div class="testimonial-dots" role="tablist" aria-label="Choose testimonial"></div>
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

<!-- ==========  CONTACT  ========== -->
<section class="section" id="contact">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Get In Touch</span>
      <h2>Let's Find Your Next Property</h2>
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
              <h4>Office Address</h4>
              <div class="val"><?= e($settings['address_line']) ?></div>
            </div>
          </li>
          <li>
            <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
            <div>
              <h4>Phone / WhatsApp</h4>
              <div class="val"><a href="tel:<?= e(preg_replace('/\s+/', '', $settings['phone_primary'])) ?>"><?= e($settings['phone_primary']) ?></a></div>
            </div>
          </li>
          <li>
            <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="M22 6l-10 7L2 6"/></svg></div>
            <div>
              <h4>Email</h4>
              <div class="val"><a href="mailto:<?= e($settings['email_primary']) ?>"><?= e($settings['email_primary']) ?></a></div>
            </div>
          </li>
          <li>
            <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
            <div>
              <h4>Working Hours</h4>
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
