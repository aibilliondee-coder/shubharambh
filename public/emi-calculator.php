<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/settings.php';

start_session_once();
$settings = load_settings();

$page_title       = 'Home Loan EMI Calculator — ' . $settings['company_name'];
$page_description = 'Calculate your monthly home loan EMI instantly. Plan your dream home purchase in Noida, Gurgaon or Delhi NCR with the Shubharambh Infra Advisors EMI calculator.';
$page_active      = 'emi';
include __DIR__ . '/../includes/header.php';
?>

<section class="page-banner">
  <div class="container">
    <span class="eyebrow">Plan Smart</span>
    <h1>Home Loan EMI Calculator</h1>
    <nav class="crumbs" aria-label="Breadcrumb">
      <a href="<?= e(url('index.php')) ?>">Home</a><span class="sep">/</span>EMI Calculator
    </nav>
  </div>
</section>

<section class="section">
  <div class="container container-narrow">
    <div class="section-head reveal">
      <span class="eyebrow">Financial Planning</span>
      <h2>Estimate Your Monthly Instalment</h2>
      <div class="arch-divider" aria-hidden="true"></div>
      <p>Use the sliders below to estimate your EMI for any property. We'll walk you through the full loan process once you're ready to move forward.</p>
    </div>

    <div class="reveal">
      <?php include __DIR__ . '/../includes/emi_widget.php'; ?>
    </div>

    <!-- Info cards -->
    <div class="feature-grid reveal" style="margin-top:2.5rem;">
      <div class="feature">
        <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/></svg></div>
        <div><h4>Best Rates</h4><p>Partnered with HDFC, SBI, ICICI, Axis, LIC &amp; Bajaj Housing Finance.</p></div>
      </div>
      <div class="feature">
        <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
        <div><h4>Zero Paperwork Hassle</h4><p>We coordinate docs, valuations and disbursals end-to-end.</p></div>
      </div>
      <div class="feature">
        <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
        <div><h4>Fast Approvals</h4><p>Pre-approved options in as little as 48 hours for eligible buyers.</p></div>
      </div>
    </div>

    <div style="text-align:center;margin-top:2.5rem;">
      <a href="<?= e(url('contact.php')) ?>" class="btn btn-gold">Talk to a Loan Advisor</a>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="section section--soft">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Know More</span>
      <h2>EMI &amp; Home Loan FAQs</h2>
      <div class="arch-divider" aria-hidden="true"></div>
    </div>

    <div class="faq-wrap reveal">
      <details class="faq">
        <summary>How is EMI calculated?</summary>
        <div class="content">
          <p>EMI uses the standard reducing-balance formula: <strong>EMI = P &times; r &times; (1+r)<sup>n</sup> / ((1+r)<sup>n</sup> &ndash; 1)</strong> where P is your loan amount, r is the monthly interest rate (annual rate &divide; 12 &divide; 100), and n is the number of monthly instalments (years &times; 12).</p>
        </div>
      </details>
      <details class="faq">
        <summary>What's a typical down payment in India?</summary>
        <div class="content">
          <p>Most banks finance up to 80&ndash;85% of the property value, so you'll need 15&ndash;20% upfront plus stamp duty and registration (typically another 6&ndash;8% on top). Luxury properties over &#8377;75 lakh may require a higher down payment of 25%.</p>
        </div>
      </details>
      <details class="faq">
        <summary>Should I choose a longer or shorter tenure?</summary>
        <div class="content">
          <p>Longer tenure lowers monthly EMI but increases total interest paid. Shorter tenure means higher EMI but much lower total cost. A good rule of thumb: keep EMI under 40% of your monthly take-home income for comfort.</p>
        </div>
      </details>
      <details class="faq">
        <summary>Are interest rates fixed or floating?</summary>
        <div class="content">
          <p>Most home loans in India are floating (linked to RBI's repo rate or MCLR). Fixed-rate options exist but are usually 0.5&ndash;1% higher. Floating rates benefit you when RBI cuts rates; fixed gives predictability during rising-rate cycles.</p>
        </div>
      </details>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
