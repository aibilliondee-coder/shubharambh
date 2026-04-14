<?php
/**
 * Public header — include at top of every public page.
 * Expects $settings (from load_settings()) and optional $page_* variables:
 *   $page_title       — <title> content
 *   $page_description — meta description
 *   $page_active      — nav key: home|projects|about|contact|emi
 *   $page_ogimage     — social share image
 *   $page_jsonld      — array or string of extra JSON-LD to emit in <head>
 */

if (!isset($settings)) {
    $settings = load_settings();
}
$page_title       = $page_title ?? ($settings['company_name'] . ' — ' . $settings['tagline']);
$page_description = $page_description ?? 'Shubharambh Infra Advisors — RERA-registered real estate consultancy in Delhi NCR. Luxury residential, commercial and investment properties across Noida, Gurgaon and Uttarakhand.';
$page_active      = $page_active ?? 'home';
$page_ogimage     = $page_ogimage ?? asset('img/logo.svg');

// Optional extra JSON-LD payload (string or array — we'll render it raw)
$page_jsonld      = $page_jsonld ?? null;
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<title><?= e($page_title) ?></title>
<meta name="description" content="<?= e($page_description) ?>">
<meta name="theme-color" content="#0B1D33">
<meta name="format-detection" content="telephone=yes">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:title" content="<?= e($page_title) ?>">
<meta property="og:description" content="<?= e($page_description) ?>">
<meta property="og:image" content="<?= e($page_ogimage) ?>">
<meta property="og:url" content="<?= e(url($_SERVER['REQUEST_URI'] ?? '/')) ?>">
<meta property="og:site_name" content="<?= e($settings['company_name']) ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($page_title) ?>">
<meta name="twitter:description" content="<?= e($page_description) ?>">
<meta name="twitter:image" content="<?= e($page_ogimage) ?>">

<!-- Favicon -->
<link rel="icon" type="image/svg+xml" href="<?= e(logo_url('light')) ?>">

<!-- Fonts — Fraunces (editorial display) + Playfair Display (serif) + Inter (body) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght,SOFT@0,9..144,400..700,30..100;1,9..144,400..500,30..100&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap">

<!-- Base stylesheet (tokens + legacy components) -->
<link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
<!-- Enterprise skin v1.0 — implements docs/DESIGN_SYSTEM.md -->
<link rel="stylesheet" href="<?= e(asset('css/enterprise.css')) ?>">

<!-- JSON-LD — base RealEstateAgent schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "RealEstateAgent",
  "@id": "<?= e(SITE_URL) ?>/#organization",
  "name": "<?= e($settings['company_name']) ?>",
  "slogan": "<?= e($settings['tagline']) ?>",
  "url": "<?= e(SITE_URL) ?>",
  "logo": "<?= e(asset('img/logo.svg')) ?>",
  "telephone": "<?= e($settings['phone_primary']) ?>",
  "email": "<?= e($settings['email_primary']) ?>",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "<?= e($settings['address_line']) ?>",
    "addressRegion": "Uttar Pradesh",
    "addressCountry": "IN"
  },
  "areaServed": ["Delhi NCR", "Noida", "Greater Noida", "Gurgaon", "Haridwar", "Uttarakhand"],
  "sameAs": [
    <?php
      $socials = array_filter([
        $settings['facebook_url']  ?? '',
        $settings['instagram_url'] ?? '',
        $settings['linkedin_url']  ?? '',
        $settings['youtube_url']   ?? '',
        $settings['twitter_url']   ?? '',
      ]);
      echo implode(',', array_map(fn($u) => json_encode($u), $socials));
    ?>
  ]
}
</script>

<?php if ($page_jsonld): ?>
<script type="application/ld+json">
<?= is_string($page_jsonld) ? $page_jsonld : json_encode($page_jsonld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?php endif; ?>

<?php if (!empty(RECAPTCHA_SITE_KEY)): ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?= e(RECAPTCHA_SITE_KEY) ?>" async defer></script>
<?php endif; ?>
</head>
<body>

<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header">
  <div class="container inner">
    <a href="<?= e(url('index.php')) ?>" class="site-logo" aria-label="<?= e($settings['company_name']) ?> — Home">
      <img src="<?= e(logo_url('light')) ?>" alt="<?= e($settings['company_name']) ?>" width="180" height="100">
    </a>

    <nav aria-label="Primary">
      <ul class="nav-list" id="primary-nav">
        <li><a href="<?= e(url('index.php'))    ?>" class="<?= $page_active === 'home'     ? 'active' : '' ?>">Home</a></li>
        <li><a href="<?= e(url('about.php'))    ?>" class="<?= $page_active === 'about'    ? 'active' : '' ?>">About</a></li>
        <li><a href="<?= e(url('projects.php')) ?>" class="<?= $page_active === 'projects' ? 'active' : '' ?>">Projects</a></li>
        <li><a href="<?= e(url('emi-calculator.php')) ?>" class="<?= $page_active === 'emi' ? 'active' : '' ?>">EMI Calc</a></li>
        <li><a href="<?= e(url('contact.php'))  ?>" class="<?= $page_active === 'contact'  ? 'active' : '' ?>">Contact</a></li>
      </ul>
    </nav>

    <div class="header-cta">
      <a href="tel:<?= e(preg_replace('/\s+/', '', $settings['phone_primary'])) ?>" class="header-phone" aria-label="Call us">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        <span><?= e($settings['phone_primary']) ?></span>
      </a>
      <button class="nav-toggle" aria-label="Toggle navigation" aria-controls="primary-nav" aria-expanded="false" type="button"><span></span></button>
    </div>
  </div>
</header>

<div class="nav-backdrop" aria-hidden="true"></div>

<main id="main">
