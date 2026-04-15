<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/settings.php';

start_session_once();
$settings = load_settings();

try {
    $projects = db()->query(
        'SELECT id, name, builder FROM projects WHERE is_active = 1
         ORDER BY sort_order ASC, id DESC'
    )->fetchAll();
} catch (Throwable $e) {
    $projects = [];
}

$page_title       = 'Contact Shubharambh Infra Advisors — Best Property Advisor in Noida';
$page_description = 'Contact Shubharambh Infra Advisors — the best property advisor in Noida. Visit our office or reach out by phone, WhatsApp, or email for expert real estate guidance.';
$page_active      = 'contact';
$page_canonical   = url('contact.php');
include __DIR__ . '/../includes/header.php';
?>

<section class="page-banner">
  <div class="container">
    <span class="eyebrow">Best Property Advisor in Noida</span>
    <h1>Contact Shubharambh Infra Advisors</h1>
    <nav class="crumbs" aria-label="Breadcrumb">
      <a href="<?= e(url('index.php')) ?>">Home</a> &nbsp;/&nbsp; Contact
    </nav>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="contact-grid">
      <div class="contact-info">
        <h2 style="font-size:1.75rem;">We'd Love to Hear From You</h2>
        <p>
          Whether you're ready to buy, want to sell, or just need expert
          guidance on a property — our advisors are here to help. Reach out
          and we'll be in touch within 24 hours.
        </p>

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
              <div class="val">
                <a href="tel:<?= e(preg_replace('/\s+/', '', $settings['phone_primary'])) ?>"><?= e($settings['phone_primary']) ?></a>
              </div>
            </div>
          </li>
          <li>
            <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="M22 6l-10 7L2 6"/></svg></div>
            <div>
              <h4>Email</h4>
              <div class="val">
                <a href="mailto:<?= e($settings['email_primary']) ?>"><?= e($settings['email_primary']) ?></a><br>
                <?php if (!empty($settings['email_secondary'])): ?>
                  <a href="mailto:<?= e($settings['email_secondary']) ?>"><?= e($settings['email_secondary']) ?></a>
                <?php endif; ?>
              </div>
            </div>
          </li>
          <li>
            <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
            <div>
              <h4>Working Hours</h4>
              <div class="val">Monday – Saturday &nbsp;·&nbsp; 10:00 AM – 7:00 PM</div>
            </div>
          </li>
        </ul>
      </div>

      <form class="contact-form" action="<?= e(url('api/contact_submit.php')) ?>" method="post" data-ajax-form>
        <h3>Send us a Message</h3>
        <?= csrf_field() ?>
        <input type="hidden" name="source" value="contact">
        <input type="text" name="website" class="honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">

        <div class="form-grid">
          <div class="form-field">
            <label for="cf-name">Full Name *</label>
            <input type="text" id="cf-name" name="full_name" required minlength="2" maxlength="150">
          </div>
          <div class="form-field">
            <label for="cf-phone">Phone *</label>
            <input type="tel" id="cf-phone" name="phone" required pattern="[0-9+\- ]{10,15}">
          </div>
          <div class="form-field">
            <label for="cf-email">Email</label>
            <input type="email" id="cf-email" name="email" maxlength="150">
          </div>
          <div class="form-field">
            <label for="cf-city">City</label>
            <input type="text" id="cf-city" name="city" maxlength="100" placeholder="e.g. Noida">
          </div>
          <div class="form-field full">
            <label for="cf-project">Project of Interest</label>
            <select id="cf-project" name="project_name">
              <option value="">-- Select a project --</option>
              <?php foreach ($projects as $p): ?>
                <option value="<?= e($p['name']) ?>"><?= e($p['name']) ?> — <?= e($p['builder']) ?></option>
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

<?php if (!empty($settings['map_embed_url'])): ?>
<section class="section section--soft" style="padding-top:0;">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Visit Us</span>
      <h2>Our Office Location</h2>
      <div class="arch-divider"></div>
    </div>
    <div style="border-radius:var(--radius-lg);overflow:hidden;border:1px solid var(--c-line);">
      <iframe src="<?= e($settings['map_embed_url']) ?>"
              width="100%" height="420" style="border:0;display:block;"
              loading="lazy" referrerpolicy="no-referrer-when-downgrade"
              title="Office location"></iframe>
    </div>
  </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
