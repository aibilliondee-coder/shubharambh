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

// Canonical URL — pages can override by setting $page_canonical before including header
$page_canonical   = $page_canonical ?? url($_SERVER['REQUEST_URI'] ?? '/');

// Robots — pages can override by setting $page_robots (e.g. 'noindex, nofollow')
$page_robots      = $page_robots ?? 'index, follow';
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<title><?= e($page_title) ?></title>
<meta name="description" content="<?= e($page_description) ?>">
<meta name="robots" content="<?= e($page_robots) ?>">
<link rel="canonical" href="<?= e(strtok($page_canonical, '?')) ?>">
<meta name="theme-color" content="#0B1D33">
<meta name="format-detection" content="telephone=yes">

<!-- Geo meta tags — office: B-220, Logix Technova, Sector 132, Noida, UP -->
<meta name="geo.region" content="IN-UP">
<meta name="geo.placename" content="B-220, Logix Technova, Sector 132, Noida – 201304, Uttar Pradesh, India">
<meta name="geo.position" content="28.5085151;77.3793737">
<meta name="ICBM" content="28.5085151, 77.3793737">

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

<!-- Fonts — Playfair Display (serif headings) + DM Sans (body) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap">

<!-- Stylesheet -->
<link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>?v=20260424e">

<!-- JSON-LD — @graph: Organization + RealEstateAgent (sitewide, all pages) -->
<?php
  // Build sameAs array from settings — only non-empty social URLs
  $schemaSocials = array_values(array_filter([
    $settings['facebook_url']  ?? '',
    $settings['instagram_url'] ?? '',
    $settings['linkedin_url']  ?? '',
    $settings['youtube_url']   ?? '',
    $settings['twitter_url']   ?? '',
  ]));

  // Logo — prefer PNG if it exists (same logic as logo_url())
  $schemaLogoUrl = file_exists(APP_ROOT . '/public/assets/img/logo.png')
      ? asset('img/logo.png')
      : asset('img/logo.svg');

  // Build the @graph payload as a PHP array so json_encode handles
  // all escaping safely — no risk of broken JSON from special chars
  $baseSchema = [
    '@context' => 'https://schema.org',
    '@graph'   => [

      // ── 1. Organization ───────────────────────────────────────────
      [
        '@type'       => 'Organization',
        '@id'         => rtrim(SITE_URL, '/') . '/#organization',
        'name'        => $settings['company_name']  ?? '',
        'alternateName' => 'Shubharambh Infra',
        'description' => 'Best property advisor in Noida — RERA-registered real estate consultancy offering residential plots, luxury apartments and commercial properties across Delhi NCR and Uttarakhand.',
        'url'         => rtrim(SITE_URL, '/') . '/',
        'foundingDate'=> '2014',

        // Logo as ImageObject (Google preferred format)
        'logo' => [
          '@type'  => 'ImageObject',
          '@id'    => rtrim(SITE_URL, '/') . '/#logo',
          'url'    => $schemaLogoUrl,
          'contentUrl' => $schemaLogoUrl,
          'caption'=> $settings['company_name'] ?? '',
        ],

        // Primary image same as logo
        'image' => [
          '@id' => rtrim(SITE_URL, '/') . '/#logo',
        ],

        // Contact point — phone + WhatsApp
        'contactPoint' => [
          [
            '@type'             => 'ContactPoint',
            'telephone'         => $settings['phone_primary']   ?? '',
            'contactType'       => 'customer service',
            'availableLanguage' => ['English', 'Hindi'],
            'areaServed'        => 'IN',
            'hoursAvailable'    => [
              '@type'     => 'OpeningHoursSpecification',
              'dayOfWeek' => [
                'https://schema.org/Monday',
                'https://schema.org/Tuesday',
                'https://schema.org/Wednesday',
                'https://schema.org/Thursday',
                'https://schema.org/Friday',
                'https://schema.org/Saturday',
              ],
              'opens'  => '10:00',
              'closes' => '19:00',
            ],
          ],
          [
            '@type'       => 'ContactPoint',
            'email'       => $settings['email_primary'] ?? '',
            'contactType' => 'customer service',
          ],
        ],

        // Address — fully structured PostalAddress
        'address' => [
          '@type'           => 'PostalAddress',
          'streetAddress'   => $settings['address_line'] ?? '',
          'addressLocality' => 'Noida',
          'addressRegion'   => 'Uttar Pradesh',
          'postalCode'      => '201304',
          'addressCountry'  => 'IN',
        ],

        // Areas served — structured as AdministrativeArea
        'areaServed' => [
          ['@type' => 'AdministrativeArea', 'name' => 'Delhi NCR'],
          ['@type' => 'AdministrativeArea', 'name' => 'Noida'],
          ['@type' => 'AdministrativeArea', 'name' => 'Greater Noida'],
          ['@type' => 'AdministrativeArea', 'name' => 'Gurgaon'],
          ['@type' => 'AdministrativeArea', 'name' => 'Haridwar'],
          ['@type' => 'AdministrativeArea', 'name' => 'Uttarakhand'],
        ],

        // Social profiles
        'sameAs' => $schemaSocials,
      ],

      // ── 2. RealEstateAgent (extends Organization) ──────────────────
      [
        '@type'       => ['RealEstateAgent', 'LocalBusiness'],
        '@id'         => rtrim(SITE_URL, '/') . '/#realestate-agent',
        'name'        => $settings['company_name'] ?? '',
        'url'         => rtrim(SITE_URL, '/') . '/',
        'slogan'      => $settings['tagline']      ?? '',
        'telephone'   => $settings['phone_primary']  ?? '',
        'email'       => $settings['email_primary']  ?? '',
        'priceRange'  => '₹₹₹',

        // Geo coordinates — verified from Google Maps
        // Shubharambh Infra Advisors, B-220, Logix Technova, Sector 132, Noida
        'geo' => [
          '@type'     => 'GeoCoordinates',
          'latitude'  => '28.5085151',
          'longitude' => '77.3793737',
        ],

        // Opening hours
        'openingHoursSpecification' => [
          [
            '@type'     => 'OpeningHoursSpecification',
            'dayOfWeek' => [
              'https://schema.org/Monday',
              'https://schema.org/Tuesday',
              'https://schema.org/Wednesday',
              'https://schema.org/Thursday',
              'https://schema.org/Friday',
              'https://schema.org/Saturday',
            ],
            'opens'  => '10:00',
            'closes' => '19:00',
          ],
        ],

        'logo'    => ['@id' => rtrim(SITE_URL, '/') . '/#logo'],
        'image'   => ['@id' => rtrim(SITE_URL, '/') . '/#logo'],
        'address' => [
          '@type'           => 'PostalAddress',
          'streetAddress'   => $settings['address_line'] ?? '',
          'addressLocality' => 'Noida',
          'addressRegion'   => 'Uttar Pradesh',
          'postalCode'      => '201304',
          'addressCountry'  => 'IN',
        ],
        'areaServed' => [
          ['@type' => 'AdministrativeArea', 'name' => 'Delhi NCR'],
          ['@type' => 'AdministrativeArea', 'name' => 'Noida'],
          ['@type' => 'AdministrativeArea', 'name' => 'Greater Noida'],
          ['@type' => 'AdministrativeArea', 'name' => 'Gurgaon'],
          ['@type' => 'AdministrativeArea', 'name' => 'Haridwar'],
          ['@type' => 'AdministrativeArea', 'name' => 'Uttarakhand'],
        ],
        'sameAs'  => $schemaSocials,
      ],

    ], // end @graph
  ];
?>
<script type="application/ld+json">
<?= json_encode($baseSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>

<?php if ($page_jsonld): ?>
<script type="application/ld+json">
<?= is_string($page_jsonld) ? $page_jsonld : json_encode($page_jsonld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?php endif; ?>

<?php if (!empty(RECAPTCHA_SITE_KEY)): ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?= e(RECAPTCHA_SITE_KEY) ?>" async defer></script>
<?php endif; ?>
<?php if (!empty($page_extra_head)) echo $page_extra_head . "\n"; ?>
</head>
<body>

<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header">
  <div class="container inner">
    <a href="<?= e(url('index.php')) ?>" class="site-logo" aria-label="<?= e($settings['company_name']) ?> — Home">
      <img src="<?= e(logo_url('light')) ?>" alt="<?= e($settings['company_name']) ?> — RERA-Registered Real Estate Consultancy in Delhi NCR" width="180" height="100" loading="eager" fetchpriority="high">
    </a>

    <nav aria-label="Primary">
      <ul class="nav-list" id="primary-nav">
        <button class="nav-drawer-close" aria-label="Close menu" type="button">&#10005;</button>
        <li><a href="<?= e(url('index.php'))    ?>" class="<?= $page_active === 'home'     ? 'active' : '' ?>">Home</a></li>
        <li><a href="<?= e(url('about.php'))    ?>" class="<?= $page_active === 'about'    ? 'active' : '' ?>">About</a></li>
        <li><a href="<?= e(url('projects.php')) ?>" class="<?= $page_active === 'projects' ? 'active' : '' ?>">Projects</a></li>
        <li><a href="<?= e(url('emi-calculator.php')) ?>" class="<?= $page_active === 'emi' ? 'active' : '' ?>">EMI Calc</a></li>
        <li><a href="<?= e(url('blogs.php'))    ?>" class="<?= $page_active === 'blog'     ? 'active' : '' ?>">Blog</a></li>
        <li><a href="<?= e(url('careers.php'))  ?>" class="<?= $page_active === 'careers'  ? 'active' : '' ?>">Careers</a></li>
        <li><a href="<?= e(url('contact.php'))  ?>" class="<?= $page_active === 'contact'  ? 'active' : '' ?>">Contact</a></li>
        <li class="nav-drawer-footer" aria-hidden="true">
          <a href="tel:<?= e(preg_replace('/\s+/', '', $settings['phone_primary'])) ?>" class="nav-drawer-call">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            <?= e($settings['phone_primary']) ?>
          </a>
        </li>
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

<!-- Notification Ticker Banner -->
<div class="notif-banner" role="marquee" aria-live="off">
  <div class="notif-ticker-track">
    <div class="notif-ticker-inner">
      <span>&#x1F525; Limited Time Offer — <strong>Book Your Plot Today</strong> &amp; Get Exclusive Pre-Launch Prices in Noida!</span>
      <span class="notif-ticker-sep">&#9670;</span>
      <span>&#x1F3E1; Premium Residential &amp; Commercial Properties in <strong>Noida, Greater Noida &amp; Gurgaon</strong></span>
      <span class="notif-ticker-sep">&#9670;</span>
      <span>&#x2714;&#xFE0F; RERA-Registered Consultancy — <strong>500+ Happy Families</strong> Served Since 2014</span>
      <span class="notif-ticker-sep">&#9670;</span>
      <span>&#x1F4DE; Call Now: <strong><?= e($settings['phone_primary']) ?></strong> — Free Property Consultation!</span>
      <span class="notif-ticker-sep">&#9670;</span>
      <!-- Duplicate for seamless loop -->
      <span>&#x1F525; Limited Time Offer — <strong>Book Your Plot Today</strong> &amp; Get Exclusive Pre-Launch Prices in Noida!</span>
      <span class="notif-ticker-sep">&#9670;</span>
      <span>&#x1F3E1; Premium Residential &amp; Commercial Properties in <strong>Noida, Greater Noida &amp; Gurgaon</strong></span>
      <span class="notif-ticker-sep">&#9670;</span>
      <span>&#x2714;&#xFE0F; RERA-Registered Consultancy — <strong>500+ Happy Families</strong> Served Since 2014</span>
      <span class="notif-ticker-sep">&#9670;</span>
      <span>&#x1F4DE; Call Now: <strong><?= e($settings['phone_primary']) ?></strong> — Free Property Consultation!</span>
      <span class="notif-ticker-sep">&#9670;</span>
    </div>
  </div>
  <a href="<?= e(url('contact.php')) ?>" class="notif-banner__cta">Enquire Now</a>
</div>

<main id="main">
