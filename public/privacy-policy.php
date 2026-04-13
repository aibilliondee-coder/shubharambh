<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/settings.php';

start_session_once();
$settings = load_settings();

$page_title       = 'Privacy Policy — ' . $settings['company_name'];
$page_description = 'How Shubharambh Infra Advisors collects, uses and protects your personal information.';
$page_active      = '';
include __DIR__ . '/../includes/header.php';
?>

<section class="page-banner">
  <div class="container">
    <span class="eyebrow">Legal</span>
    <h1>Privacy Policy</h1>
    <nav class="crumbs" aria-label="Breadcrumb">
      <a href="<?= e(url('index.php')) ?>">Home</a> &nbsp;/&nbsp; Privacy Policy
    </nav>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="legal-body">
      <p><em>Effective <?= date('F Y') ?> and onwards.</em></p>

      <p>
        <?= e($settings['company_name']) ?> ("we", "us", "our") respects your
        privacy. This Privacy Policy explains what personal information we
        collect, how we use it, and the choices you have regarding that
        information when you interact with our website, office or services.
      </p>

      <h2>1. Information We Collect</h2>
      <ul>
        <li><strong>Contact details</strong> — name, phone number, email address, and city when you submit a form or enquiry.</li>
        <li><strong>Enquiry details</strong> — the project you are interested in and any message you share with us.</li>
        <li><strong>Technical data</strong> — IP address, browser type and general usage data for security and analytics.</li>
        <li><strong>Communication records</strong> — call logs, WhatsApp messages and emails exchanged during consultation.</li>
      </ul>

      <h2>2. How We Use Your Information</h2>
      <ul>
        <li>To respond to your enquiries and provide real estate advisory services.</li>
        <li>To share property updates, listings and market insights (only with your consent).</li>
        <li>To coordinate site visits, documentation and transactions with developers.</li>
        <li>To comply with legal and regulatory obligations under Indian law.</li>
        <li>To improve our website performance, security and user experience.</li>
      </ul>

      <h2>3. Sharing of Information</h2>
      <p>
        We do not sell your personal information. We may share it only with:
      </p>
      <ul>
        <li>Developers and property owners relevant to the project you are enquiring about.</li>
        <li>Regulatory and government authorities when legally required.</li>
        <li>Trusted service providers (hosting, analytics) bound by confidentiality.</li>
      </ul>

      <h2>4. Data Security</h2>
      <p>
        We use reasonable technical and organisational measures to protect
        your information, including encrypted storage, secure servers and
        access controls. No method of transmission over the internet is
        completely secure, however, and we cannot guarantee absolute security.
      </p>

      <h2>5. Data Retention</h2>
      <p>
        We retain your personal information only for as long as necessary to
        provide the services you requested or to comply with applicable laws.
      </p>

      <h2>6. Your Rights</h2>
      <ul>
        <li>You may request a copy of the personal information we hold about you.</li>
        <li>You may ask us to correct or delete inaccurate information.</li>
        <li>You may opt out of marketing communications at any time by contacting us.</li>
      </ul>

      <h2>7. Cookies</h2>
      <p>
        Our website uses essential cookies to run core functionality and may
        use analytics cookies to understand visitor behaviour in aggregate.
        You can control cookies through your browser settings.
      </p>

      <h2>8. Updates to this Policy</h2>
      <p>
        We may update this Privacy Policy from time to time. The latest
        version will always be available on this page with a revised
        effective date.
      </p>

      <h2>9. Contact Us</h2>
      <p>
        If you have any questions about this Privacy Policy or your personal
        data, please contact us at
        <a href="mailto:<?= e($settings['email_primary']) ?>"><?= e($settings['email_primary']) ?></a>
        or
        <a href="tel:<?= e(preg_replace('/\s+/', '', $settings['phone_primary'])) ?>"><?= e($settings['phone_primary']) ?></a>.
      </p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
