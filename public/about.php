<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/settings.php';

start_session_once();
$settings = load_settings();

try {
    $team = db()->query(
        'SELECT * FROM team_members WHERE is_active = 1 ORDER BY sort_order ASC, id ASC'
    )->fetchAll();
} catch (Throwable $e) {
    $team = [];
}

$page_title       = 'About Shubharambh Infra Advisors — Best Property Advisor in Noida';
$page_description = 'Meet Shubharambh Infra Advisors — best property advisor in Noida. RERA-registered consultancy serving Delhi NCR & Uttarakhand since 2014.';
$page_active      = 'about';
include __DIR__ . '/../includes/header.php';
?>

<section class="page-banner">
  <div class="container">
    <span class="eyebrow">Best Property Advisor in Noida</span>
    <h1>Shubharambh Infra Advisors — Noida's Best Property Advisory Firm</h1>
    <nav class="crumbs" aria-label="Breadcrumb">
      <a href="<?= e(url('index.php')) ?>">Home</a><span class="sep">/</span>About
    </nav>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="about-grid">
      <div class="about-img reveal">
        <div class="img-wrap">
          <?php
            $ceo = $team[0] ?? null;
            if ($ceo && !empty($ceo['photo']) && file_exists(APP_ROOT . '/public/uploads/' . $ceo['photo'])):
          ?>
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
        <span class="eyebrow">Our Story</span>
        <h2><?= e($settings['about_heading']) ?></h2>
        <?php foreach (preg_split('/\n\s*\n/', trim($settings['about_body'] ?? '')) as $para): ?>
          <?php if (trim($para) === '') continue; ?>
          <p><?= e($para) ?></p>
        <?php endforeach; ?>

        <?php if ($ceo): ?>
          <div class="sig">
            <strong><?= e($ceo['full_name']) ?></strong>
            <span><?= e($ceo['title']) ?></span>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- Mission / Vision / Values -->
<section class="section section--soft">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">What Drives Us</span>
      <h2>Mission, Vision &amp; Values</h2>
      <div class="arch-divider" aria-hidden="true"></div>
    </div>
    <div class="pillars">
      <div class="pillar reveal">
        <div class="icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><circle cx="12" cy="12" r="1" fill="currentColor"/></svg>
        </div>
        <h3>Mission</h3>
        <p>To simplify property buying, selling and investing while ensuring transparency, integrity and trust at every step of the client journey.</p>
      </div>
      <div class="pillar reveal delay-1">
        <div class="icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
        <h3>Vision</h3>
        <p>To be India's most trusted real estate advisory, recognised for deep market expertise, client-first solutions and long-term relationships.</p>
      </div>
      <div class="pillar reveal delay-2">
        <div class="icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </div>
        <h3>Values</h3>
        <p>Transparency, integrity, client-first thinking, deep market knowledge and a relentless commitment to delivering measurable value.</p>
      </div>
    </div>
  </div>
</section>

<!-- Journey Timeline -->
<section class="section">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Our Journey</span>
      <h2>Milestones Along the Way</h2>
      <div class="arch-divider" aria-hidden="true"></div>
      <p>A decade of partnering with families, investors and businesses to build legacies through real estate.</p>
    </div>

    <div class="timeline reveal">
      <div class="tl-item">
        <div class="year">2014</div>
        <h3>The Beginning</h3>
        <p>Shubharambh Infra Advisors was founded in Noida with a simple promise &mdash; put the client first, always.</p>
      </div>
      <div class="tl-item">
        <div class="year">2017</div>
        <h3>Expanded to Delhi NCR</h3>
        <p>Deep partnerships with leading developers across Gurgaon, Greater Noida and Faridabad &mdash; crossing 100+ successful transactions.</p>
      </div>
      <div class="tl-item">
        <div class="year">2020</div>
        <h3>RERA Registered</h3>
        <p>Formally registered under the UP Real Estate Regulatory Authority, reinforcing our commitment to transparency and compliance.</p>
      </div>
      <div class="tl-item">
        <div class="year">2022</div>
        <h3>Uttarakhand Portfolio</h3>
        <p>Expanded into Haridwar and Ramnagar with curated holiday-home and investment plot offerings for NCR buyers.</p>
      </div>
      <div class="tl-item">
        <div class="year">2024</div>
        <h3>500+ Happy Families</h3>
        <p>Crossed the 500-family milestone with &#8377;800 Cr+ in cumulative transacted value across luxury residential and commercial segments.</p>
      </div>
      <div class="tl-item">
        <div class="year">Today</div>
        <h3>Building the Future</h3>
        <p>A modern, digital-first advisory with deep market expertise, transparent processes and a growing portfolio of India's most sought-after properties.</p>
      </div>
    </div>
  </div>
</section>

<!-- Call to action strip -->
<section class="section">
  <div class="container">
    <div class="counters reveal" style="text-align:center;">
      <div style="grid-column:1 / -1;">
        <h2 style="margin-bottom:0.5rem;">Work with Noida's Best Property Advisor</h2>
        <p style="color:var(--c-muted);max-width:580px;margin:0 auto 1.5rem;">Let Shubharambh Infra Advisors guide you to the perfect investment, home or commercial space across Noida, Delhi NCR and Uttarakhand.</p>
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;justify-content:center;">
          <a href="<?= e(url('projects.php')) ?>" class="btn btn-gold">Browse Projects</a>
          <a href="<?= e(url('contact.php')) ?>" class="btn btn-outline">Talk to an Expert</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
