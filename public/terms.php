<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/settings.php';

start_session_once();
$settings = load_settings();

$page_title       = 'Terms & Conditions — ' . $settings['company_name'];
$page_description = 'Terms and conditions governing use of the Shubharambh Infra Advisors website and services.';
$page_active      = '';
include __DIR__ . '/../includes/header.php';
?>

<section class="page-banner">
  <div class="container">
    <span class="eyebrow">Legal</span>
    <h1>Terms &amp; Conditions</h1>
    <nav class="crumbs" aria-label="Breadcrumb">
      <a href="<?= e(url('index.php')) ?>">Home</a> &nbsp;/&nbsp; Terms &amp; Conditions
    </nav>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="legal-body">
      <p><em>Effective <?= date('F Y') ?> and onwards.</em></p>

      <p>
        These Terms and Conditions ("Terms") govern your access to and use of
        the <?= e($settings['company_name']) ?> website and our real estate
        advisory services. By using this site you agree to these Terms.
      </p>

      <h2>1. Our Services</h2>
      <p>
        We provide real estate advisory, listing, consultation and client
        support across residential and commercial properties in Delhi NCR,
        Uttarakhand and adjacent markets. We act as intermediaries between
        buyers and RERA-registered developers.
      </p>

      <h2>2. Property Listings</h2>
      <ul>
        <li>Property details, pricing and availability are provided by developers and are subject to change without notice.</li>
        <li>All buyers are encouraged to independently verify details, documentation and legal status before committing to any transaction.</li>
        <li>We make reasonable efforts to keep listings accurate but disclaim liability for typographical errors, omissions or changes made by property owners.</li>
      </ul>

      <h2>3. Bookings &amp; Payments</h2>
      <ul>
        <li>All pricing is in Indian Rupees (INR) unless stated otherwise.</li>
        <li>A booking is considered effective only after the agreed amount and required documentation are received by the developer.</li>
        <li>Cancellations must be requested in writing. Refunds are subject to deductions for administrative, legal and developer-retention charges where applicable.</li>
      </ul>

      <h2>4. Due Diligence</h2>
      <p>
        Buyers are solely responsible for conducting their own legal,
        financial and technical due-diligence before purchase. We encourage
        engaging independent legal counsel for every transaction.
      </p>

      <h2>5. Limitation of Liability</h2>
      <p>
        To the extent permitted by Indian law, our liability is limited to
        the fees paid to us for the disputed service. We are not liable for
        indirect, incidental or consequential losses arising out of your use
        of this site or our services.
      </p>

      <h2>6. Intellectual Property</h2>
      <p>
        All content on this website, including text, images, logos and
        branding elements, is the property of <?= e($settings['company_name']) ?>
        or its licensors and is protected by applicable Indian and
        international copyright laws. Unauthorised use is prohibited.
      </p>

      <h2>7. User Conduct</h2>
      <p>
        You agree not to use this website for any unlawful purpose, to
        attempt to gain unauthorised access, or to disrupt its operation.
      </p>

      <h2>8. Governing Law &amp; Jurisdiction</h2>
      <p>
        These Terms are governed by the laws of India. Any dispute arising
        from these Terms or the use of this site will be subject to the
        exclusive jurisdiction of the courts in Noida, Uttar Pradesh. We
        encourage mediation before formal proceedings.
      </p>

      <h2>9. Contact</h2>
      <p>
        For any questions regarding these Terms, please contact us at
        <a href="mailto:<?= e($settings['email_primary']) ?>"><?= e($settings['email_primary']) ?></a>.
      </p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
