<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/settings.php';

start_session_once();
$settings = load_settings();
http_response_code(404);

$page_title       = 'Page Not Found — ' . $settings['company_name'];
$page_description = 'The page you are looking for does not exist.';
$page_active      = '';
$page_robots      = 'noindex, nofollow';
include __DIR__ . '/../includes/header.php';
?>

<section class="not-found">
  <div class="container">
    <div class="code">404</div>
    <h1 style="font-size:2rem;">Oops! Page not found.</h1>
    <div class="arch-divider"></div>
    <p style="max-width:480px;margin:1rem auto 2rem;">
      The page you're looking for doesn't exist or may have been moved.
      Let's get you back on track.
    </p>
    <div style="display:inline-flex;gap:1rem;flex-wrap:wrap;justify-content:center;">
      <a href="<?= e(url('index.php')) ?>" class="btn btn-gold">Back to Home</a>
      <a href="<?= e(url('projects.php')) ?>" class="btn btn-outline">View Projects</a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
