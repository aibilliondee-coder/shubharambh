<?php
/**
 * Public footer — include at bottom of every public page.
 * Expects $settings in scope.
 */
if (!isset($settings)) {
    $settings = load_settings();
}
$whatsappMsg = 'Hello, I am interested in exploring properties with Shubharambh Infra Advisors.';
$whatsappHref = whatsapp_url($settings['phone_whatsapp'], $whatsappMsg);
?>
</main>

<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-about">
        <img src="<?= e(logo_url('light')) ?>" alt="<?= e($settings['company_name']) ?> — Luxury Residential &amp; Commercial Real Estate Advisory, Delhi NCR" width="260" height="110" loading="lazy">
        <p>
          A RERA-registered real estate consultancy based in Noida. We help clients
          buy, sell and invest in premium residential and commercial properties
          across Delhi NCR and Uttarakhand.
        </p>
        <div class="footer-socials" aria-label="Social links">
          <?php if (!empty($settings['facebook_url'])): ?>
          <a href="<?= e($settings['facebook_url']) ?>" aria-label="Facebook" target="_blank" rel="noopener">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.51 1.49-3.9 3.77-3.9 1.1 0 2.24.2 2.24.2v2.47h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12z"/></svg>
          </a>
          <?php endif; ?>
          <?php if (!empty($settings['instagram_url'])): ?>
          <a href="<?= e($settings['instagram_url']) ?>" aria-label="Instagram" target="_blank" rel="noopener">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="18" cy="6" r="1" fill="currentColor"/></svg>
          </a>
          <?php endif; ?>
          <?php if (!empty($settings['linkedin_url'])): ?>
          <a href="<?= e($settings['linkedin_url']) ?>" aria-label="LinkedIn" target="_blank" rel="noopener">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4 4h4v16H4zM6 2a2 2 0 1 1 0 4 2 2 0 0 1 0-4zM10 8h4v2a4 4 0 0 1 7 2.8V20h-4v-6a2 2 0 0 0-4 0v6h-4z"/></svg>
          </a>
          <?php endif; ?>
          <?php if (!empty($settings['youtube_url'])): ?>
          <a href="<?= e($settings['youtube_url']) ?>" aria-label="YouTube" target="_blank" rel="noopener">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23 6.2a3 3 0 0 0-2.1-2.1C19 3.5 12 3.5 12 3.5s-7 0-8.9.6A3 3 0 0 0 1 6.2 31 31 0 0 0 .5 12 31 31 0 0 0 1 17.8a3 3 0 0 0 2.1 2.1c1.9.6 8.9.6 8.9.6s7 0 8.9-.6a3 3 0 0 0 2.1-2.1c.4-1.9.5-3.8.5-5.8s-.1-3.9-.5-5.8zM10 15.5v-7l6 3.5z"/></svg>
          </a>
          <?php endif; ?>
        </div>
      </div>

      <div class="footer-col">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="<?= e(url('index.php'))           ?>">Home</a></li>
          <li><a href="<?= e(url('about.php'))           ?>">About Us</a></li>
          <li><a href="<?= e(url('projects.php'))        ?>">All Projects</a></li>
          <li><a href="<?= e(url('emi-calculator.php'))  ?>">EMI Calculator</a></li>
          <li><a href="<?= e(url('contact.php'))         ?>">Contact</a></li>
          <li><a href="<?= e(url('privacy-policy.php'))  ?>">Privacy Policy</a></li>
          <li><a href="<?= e(url('terms.php'))           ?>">Terms &amp; Conditions</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Services</h4>
        <ul>
          <li><a href="<?= e(url('projects.php?category=Residential')) ?>">Luxury Residential</a></li>
          <li><a href="<?= e(url('projects.php?category=Commercial'))  ?>">Commercial Spaces</a></li>
          <li><a href="<?= e(url('projects.php?category=Plots'))       ?>">Investment Plots</a></li>
          <li><a href="<?= e(url('projects.php?city=Ramnagar'))        ?>">Holiday Homes</a></li>
          <li><a href="<?= e(url('contact.php'))                        ?>">Property Advisory</a></li>
          <li><a href="<?= e(url('contact.php'))                        ?>">NRI Services</a></li>
        </ul>
      </div>

      <div class="footer-col newsletter-col">
        <h4>Get In Touch</h4>
        <ul class="footer-contact">
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <span><?= e($settings['address_line']) ?></span>
          </li>
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            <a href="tel:<?= e(preg_replace('/\s+/', '', $settings['phone_primary'])) ?>"><?= e($settings['phone_primary']) ?></a>
          </li>
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="M22 6l-10 7L2 6"/></svg>
            <a href="mailto:<?= e($settings['email_primary']) ?>"><?= e($settings['email_primary']) ?></a>
          </li>
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            <span>Mon – Sat &nbsp;·&nbsp; 10:00 AM – 7:00 PM</span>
          </li>
        </ul>

        <div style="margin-top:1.5rem;">
          <h4 style="border:none;padding-bottom:0;margin-bottom:0.75rem;">Newsletter</h4>
          <form class="newsletter-form" action="<?= e(url('api/newsletter_submit.php')) ?>" method="post" data-ajax-form>
            <?= csrf_field() ?>
            <input type="text" name="website" class="honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">
            <label class="sr-only" for="nl-email">Email address</label>
            <input id="nl-email" type="email" name="email" placeholder="Your email address" required>
            <button type="submit">Join</button>
          </form>
        </div>
      </div>

      <?php if (!empty($settings['rera_notice'])): ?>
      <p class="rera-notice">
        <strong><?= e($settings['rera_number']) ?></strong> &nbsp;·&nbsp; <?= e($settings['rera_notice']) ?>
      </p>
      <?php endif; ?>
    </div>

    <div class="footer-bottom">
      <div>&copy; <?= date('Y') ?> <?= e($settings['company_name']) ?>. All rights reserved.</div>
      <div>
        Crafted with care &nbsp;&middot;&nbsp;
        <a href="<?= e(url('privacy-policy.php')) ?>">Privacy</a>
        &nbsp;&middot;&nbsp;
        <a href="<?= e(url('terms.php')) ?>">Terms</a>
      </div>
    </div>
  </div>
</footer>

<!-- Sticky mobile bottom bar (hidden on desktop) -->
<nav class="sticky-mobile-bar" aria-label="Mobile actions">
  <a href="tel:<?= e(preg_replace('/\s+/', '', $settings['phone_primary'])) ?>" aria-label="Call">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
    Call
  </a>
  <a href="#" data-modal-open data-project-name="" class="enquire" aria-label="Enquire">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="M22 6l-10 7L2 6"/></svg>
    Enquire
  </a>
  <a class="whatsapp" href="<?= e($whatsappHref) ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 14.3c-.3-.1-1.7-.8-1.9-.9-.3-.1-.5-.1-.7.1-.2.3-.8.9-1 1.1-.2.2-.4.2-.6.1a7.6 7.6 0 0 1-3.7-3.2c-.3-.5.3-.4.8-1.3.1-.2 0-.4 0-.5s-.7-1.6-.9-2.2c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4s-1 1-1 2.4 1 2.8 1.2 3c.2.3 2 3 4.8 4.2l1.6.6c.7.2 1.3.2 1.8.1.5-.1 1.7-.7 1.9-1.3.2-.7.2-1.2.2-1.3zM12 2a10 10 0 0 0-8.5 15.2L2 22l4.9-1.3A10 10 0 1 0 12 2z"/></svg>
    WhatsApp
  </a>
</nav>

<!-- Floating WhatsApp FAB (desktop) -->
<a class="float-wa" href="<?= e($whatsappHref) ?>" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
  <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.5 14.3c-.3-.1-1.7-.8-1.9-.9-.3-.1-.5-.1-.7.1-.2.3-.8.9-1 1.1-.2.2-.4.2-.6.1a7.6 7.6 0 0 1-3.7-3.2c-.3-.5.3-.4.8-1.3.1-.2 0-.4 0-.5s-.7-1.6-.9-2.2c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4s-1 1-1 2.4 1 2.8 1.2 3c.2.3 2 3 4.8 4.2l1.6.6c.7.2 1.3.2 1.8.1.5-.1 1.7-.7 1.9-1.3.2-.7.2-1.2.2-1.3zM12 2a10 10 0 0 0-8.5 15.2L2 22l4.9-1.3A10 10 0 1 0 12 2z"/></svg>
</a>

<!-- Scroll to top -->
<button class="scroll-top" id="scroll-top" aria-label="Scroll to top" type="button">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
</button>

<!-- Shortlist bar (favorites counter) -->
<div class="shortlist-bar" id="shortlist-bar" role="status" aria-live="polite">
  <span>Shortlisted <strong id="shortlist-count">0</strong> projects</span>
  <a href="<?= e(url('contact.php')) ?>" class="btn btn-gold btn-sm">Get Quotes</a>
</div>

<!-- Enquiry modal (triggered manually or via idle/exit-intent) -->
<div class="modal-backdrop" id="popup-modal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="popup-title">
    <button class="close" data-modal-close aria-label="Close" type="button">&times;</button>
    <div class="arch-divider" aria-hidden="true"></div>
    <h3 id="popup-title">Book Your Interest</h3>
    <p class="subtitle" id="popup-subtitle">Our expert will reach out within 24 hours</p>

    <form action="<?= e(url('api/popup_submit.php')) ?>" method="post" data-ajax-form>
      <?= csrf_field() ?>
      <input type="hidden" name="source" value="popup">
      <input type="hidden" name="project_id" id="popup-project-id" value="">
      <input type="hidden" name="project_name" id="popup-project-name" value="">
      <input type="text" name="website" class="honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">

      <div class="form-grid">
        <div class="form-field full">
          <label for="popup-name">Full Name</label>
          <input type="text" id="popup-name" name="full_name" required minlength="2" maxlength="150" autocomplete="name">
        </div>
        <div class="form-field full">
          <label for="popup-phone">Phone</label>
          <input type="tel" id="popup-phone" name="phone" required pattern="[0-9+\- ]{10,15}" autocomplete="tel">
        </div>
        <div class="form-field full">
          <label for="popup-city">City</label>
          <input type="text" id="popup-city" name="city" maxlength="100" placeholder="e.g. Noida" autocomplete="address-level2">
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-gold btn-block">Request a Callback</button>
      </div>
      <div class="form-msg" role="status" aria-live="polite"></div>
    </form>
  </div>
</div>

<script>
  window.SIA = {
    baseUrl: <?= json_encode(rtrim(SITE_URL, '/')) ?>,
    recaptchaKey: <?= json_encode((string)RECAPTCHA_SITE_KEY) ?>
  };
</script>
<script src="<?= e(asset('js/main.js')) ?>" defer async></script>
</body>
</html>
