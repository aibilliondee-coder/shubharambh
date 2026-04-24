<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/settings.php';

start_session_once();
$settings = load_settings();

// ---- Query string filters ----
$q        = trim((string)($_GET['q']        ?? ''));
$city     = trim((string)($_GET['city']     ?? ''));
$category = trim((string)($_GET['category'] ?? ''));
$builder  = trim((string)($_GET['builder']  ?? ''));
$sort     = (string)($_GET['sort'] ?? 'featured');

$allowedSort = ['featured', 'name', 'price_asc', 'price_desc'];
if (!in_array($sort, $allowedSort, true)) $sort = 'featured';

// Build WHERE
$conditions = ['is_active = 1'];
$params = [];

if ($q !== '') {
    $conditions[] = '(name LIKE :q OR builder LIKE :q OR location LIKE :q OR city LIKE :q)';
    $params[':q'] = '%' . $q . '%';
}
if ($city !== '') {
    $conditions[] = 'city = :city';
    $params[':city'] = $city;
}
if ($builder !== '') {
    $conditions[] = 'builder = :builder';
    $params[':builder'] = $builder;
}

// ORDER BY
switch ($sort) {
    case 'name':       $orderBy = 'name ASC'; break;
    case 'price_asc':  $orderBy = 'sort_order ASC, name ASC'; break;
    case 'price_desc': $orderBy = 'sort_order DESC, name ASC'; break;
    default:           $orderBy = 'is_featured DESC, sort_order DESC, id DESC';
}

try {
    $sql  = 'SELECT * FROM projects WHERE ' . implode(' AND ', $conditions) . ' ORDER BY ' . $orderBy;
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $allProjects = $stmt->fetchAll();

    // Client-side filter by broad category (since DB uses free-form types)
    if ($category !== '' && in_array($category, ['Residential', 'Commercial', 'Plots'], true)) {
        $allProjects = array_values(array_filter($allProjects, fn($p) => project_category($p['property_type'] ?? '') === $category));
    }

    // Distinct dropdown values
    $cities   = db()->query('SELECT DISTINCT city FROM projects WHERE is_active = 1 ORDER BY city')->fetchAll(PDO::FETCH_COLUMN);
    $builders = db()->query('SELECT DISTINCT builder FROM projects WHERE is_active = 1 ORDER BY builder')->fetchAll(PDO::FETCH_COLUMN);
} catch (Throwable $e) {
    $allProjects = [];
    $cities = $builders = [];
}

$count = count($allProjects);

$page_title       = 'Properties in Noida &amp; Delhi NCR | Shubharambh Infra Advisors — Best Property Advisor in Noida';
$page_description = 'Explore ' . $count . '+ residential, commercial & investment properties in Noida. Curated by Shubharambh Infra Advisors — best property advisor in Delhi NCR.';
$page_active      = 'projects';
$page_canonical   = url('projects.php');

// -----------------------------------------------------------------------
// JSON-LD — ItemList schema for the projects listing page.
// Each project becomes a ListItem pointing to its own detail page.
// All values are dynamic — pulled from $allProjects (DB query above).
// -----------------------------------------------------------------------
$listItems = [];
foreach ($allProjects as $i => $p) {
    $pImg = !empty($p['cover_image'])
        ? upload_url($p['cover_image'])
        : asset('img/placeholders/project.svg');

    $listItems[] = [
        '@type'    => 'ListItem',
        'position' => $i + 1,
        'item'     => [
            '@type'       => 'RealEstateListing',
            '@id'         => url('project.php?slug=' . urlencode($p['slug'])),
            'name'        => $p['name'],
            'description' => truncate($p['description'] ?? '', 160),
            'url'         => url('project.php?slug=' . urlencode($p['slug'])),
            'image'       => $pImg,
            'offers'      => [
                '@type'         => 'Offer',
                'priceCurrency' => 'INR',
                'price'         => $p['price_display'],
                'availability'  => 'https://schema.org/InStock',
            ],
            'address'  => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => $p['location'] ?? '',
                'addressLocality' => $p['city']     ?? '',
                'addressCountry'  => 'IN',
            ],
            'provider' => [
                '@type' => 'Organization',
                'name'  => $p['builder'] ?? '',
            ],
        ],
    ];
}

$page_jsonld = [
    '@context'       => 'https://schema.org',
    '@type'          => 'ItemList',
    'name'           => 'Real Estate Projects — ' . $settings['company_name'],
    'description'    => 'Browse residential plots, luxury apartments and investment properties across Delhi NCR and Uttarakhand.',
    'url'            => url('projects.php'),
    'numberOfItems'  => $count,
    'itemListElement' => $listItems,
];

include __DIR__ . '/../includes/header.php';
?>

<section class="page-banner">
  <div class="container">
    <span class="eyebrow">Shubharambh Infra Advisors — Best Property Advisor in Noida</span>
    <h1>Explore Properties in Noida &amp; Delhi NCR</h1>
    <nav class="crumbs" aria-label="Breadcrumb">
      <a href="<?= e(url('index.php')) ?>">Home</a><span class="sep">/</span>Projects
    </nav>
  </div>
</section>

<section class="section">
  <div class="container">

    <!-- Filter bar -->
    <form class="filter-bar" id="filter-form" action="<?= e(url('projects.php')) ?>" method="get">
      <div class="field field--search">
        <label for="f-q">Search</label>
        <input type="text" id="f-q" name="q" value="<?= e($q) ?>" placeholder="Project, builder, area…">
      </div>
      <div class="field">
        <label for="f-category">Category</label>
        <select id="f-category" name="category">
          <option value="">All</option>
          <?php foreach (['Residential', 'Commercial', 'Plots'] as $c): ?>
            <option value="<?= e($c) ?>" <?= $category === $c ? 'selected' : '' ?>><?= e($c) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field">
        <label for="f-city">City</label>
        <select id="f-city" name="city">
          <option value="">All Cities</option>
          <?php foreach ($cities as $c): ?>
            <option value="<?= e($c) ?>" <?= $city === $c ? 'selected' : '' ?>><?= e($c) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field">
        <label for="f-builder">Builder</label>
        <select id="f-builder" name="builder">
          <option value="">All Builders</option>
          <?php foreach ($builders as $b): ?>
            <option value="<?= e($b) ?>" <?= $builder === $b ? 'selected' : '' ?>><?= e($b) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-gold">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        Apply
      </button>
    </form>

    <div class="filter-meta">
      <div>Showing <strong><?= $count ?></strong> <?= $count === 1 ? 'property' : 'properties' ?><?php if ($q || $city || $category || $builder): ?> matching your filters<?php endif; ?></div>
      <div>
        <?php if ($q || $city || $category || $builder): ?>
          <a href="<?= e(url('projects.php')) ?>">Clear all filters</a>
        <?php endif; ?>
      </div>
    </div>

    <div class="projects-grid">
      <?php foreach ($allProjects as $p): ?>
        <?php
          $imgPath = !empty($p['cover_image'])
              ? upload_url($p['cover_image'])
              : asset('img/placeholders/project.svg');
          $waMsg = 'Hi, I am interested in ' . $p['name'] . ' by ' . $p['builder'] . '. Please share more details.';
          $cfg = short_config($p['configurations'] ?? '');
          $possession = short_possession($p['possession'] ?? '');
          $categoryLabel = project_category($p['property_type'] ?? '');
        ?>
        <article class="project-card reveal">
          <div class="media">
            <div class="badges">
              <span class="badge"><?= e($categoryLabel) ?></span>
              <?php if (!empty($p['rera_id'])): ?>
                <span class="badge badge--rera">RERA</span>
              <?php endif; ?>
            </div>
            <button class="fav" type="button"
                    data-fav="<?= e($p['id']) ?>"
                    data-name="<?= e($p['name']) ?>"
                    aria-label="Save <?= e($p['name']) ?>">
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

      <?php if (empty($allProjects)): ?>
        <div class="empty-state">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <h3 style="color:var(--c-white);font-size:1.25rem;">No properties found</h3>
          <p>Try adjusting your filters or <a href="<?= e(url('projects.php')) ?>">clear all filters</a> to see our full portfolio.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
