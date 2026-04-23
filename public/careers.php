<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/settings.php';

start_session_once();
$settings = load_settings();

// PRG: show flash message after redirect
$flash_success = false;
$flash_error   = '';
if (isset($_SESSION['career_flash'])) {
    $flash = $_SESSION['career_flash'];
    unset($_SESSION['career_flash']);
    if ($flash === 'success') $flash_success = true;
    else $flash_error = $flash;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $redirect_base = url('careers.php') . '#apply-form';

    // CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $_SESSION['career_flash'] = 'Invalid request. Please try again.';
        header('Location: ' . $redirect_base); exit;
    }

    // Honeypot
    if (!empty($_POST['honeypot'])) {
        $_SESSION['career_flash'] = 'success';
        header('Location: ' . $redirect_base); exit;
    }

    // Rate limit: 1 submission per IP per 24 hours
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $recent = db()->prepare(
        "SELECT COUNT(*) FROM job_applications WHERE ip_address = ? AND created_at >= datetime('now', '-24 hours')"
    );
    $recent->execute([$ip]);
    if ((int)$recent->fetchColumn() >= 1) {
        $_SESSION['career_flash'] = 'You have already submitted an application in the last 24 hours. Please try again tomorrow.';
        header('Location: ' . $redirect_base); exit;
    }

    $name     = trim($_POST['full_name']   ?? '');
    $email    = trim($_POST['email']        ?? '');
    $phone    = trim($_POST['phone']        ?? '');
    $position = trim($_POST['position']     ?? '');
    $exp      = trim($_POST['experience']   ?? '');
    $message  = trim($_POST['cover_letter'] ?? '');

    if (!$name || !$email || !$phone || !$position) {
        $_SESSION['career_flash'] = 'Please fill in all required fields.';
        header('Location: ' . $redirect_base); exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['career_flash'] = 'Please enter a valid email address.';
        header('Location: ' . $redirect_base); exit;
    }

    // CV upload
    $cv_path = null;
    if (!empty($_FILES['cv']['name'])) {
        $file     = $_FILES['cv'];
        $allowed  = ['pdf' => 'application/pdf', 'doc' => 'application/msword', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $max_size = 5 * 1024 * 1024; // 5 MB

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['career_flash'] = 'CV upload failed. Please try again.';
            header('Location: ' . $redirect_base); exit;
        }
        if (!array_key_exists($ext, $allowed)) {
            $_SESSION['career_flash'] = 'Only PDF, DOC, or DOCX files are accepted for CV upload.';
            header('Location: ' . $redirect_base); exit;
        }
        if ($file['size'] > $max_size) {
            $_SESSION['career_flash'] = 'CV file size must be under 5 MB.';
            header('Location: ' . $redirect_base); exit;
        }
        // Validate MIME server-side
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, array_values($allowed))) {
            $_SESSION['career_flash'] = 'Invalid file type. Only PDF, DOC, or DOCX accepted.';
            header('Location: ' . $redirect_base); exit;
        }

        $filename = 'cv_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest     = __DIR__ . '/uploads/cv/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            $_SESSION['career_flash'] = 'Failed to save CV. Please try again.';
            header('Location: ' . $redirect_base); exit;
        }
        $cv_path = 'cv/' . $filename;
    }

    try {
        db()->prepare(
            'INSERT INTO job_applications (full_name, email, phone, position, experience, cover_letter, cv_path, ip_address, user_agent, status, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, datetime("now"))'
        )->execute([
            $name, $email, $phone, $position, $exp, $message,
            $cv_path, $ip, $_SERVER['HTTP_USER_AGENT'] ?? '', 'new',
        ]);
        $_SESSION['career_flash'] = 'success';
    } catch (Throwable $e) {
        $_SESSION['career_flash'] = 'Something went wrong. Please try again or call us directly.';
    }

    header('Location: ' . $redirect_base); exit;
}

// Regenerate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$page_title       = 'Careers at Shubharambh Infra Advisors — Best Property Advisor in Noida';
$page_description = 'Join Shubharambh Infra Advisors — the best property advisor in Noida. Explore exciting careers in real estate sales, advisory, marketing and operations. Build your future with Delhi NCR\'s most trusted property consultancy.';
$page_active      = 'careers';
include __DIR__ . '/../includes/header.php';
?>

<!-- PAGE BANNER -->
<section class="page-banner">
  <div class="container">
    <span class="eyebrow">Best Property Advisor in Noida</span>
    <h1>Careers at Shubharambh Infra Advisors</h1>
    <nav class="crumbs" aria-label="Breadcrumb">
      <a href="<?= e(url('index.php')) ?>">Home</a><span class="sep">/</span>Careers
    </nav>
  </div>
</section>

<!-- WHY JOIN US -->
<section class="section" id="why-join">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Why Work With Us</span>
      <h2>Build Your Career with Noida's Best Property Advisor</h2>
      <div class="arch-divider" aria-hidden="true"></div>
      <p>At Shubharambh Infra Advisors, we believe great people build great companies. Join a team that's reshaping real estate advisory across Delhi NCR.</p>
    </div>

    <div class="careers-perks reveal">
      <div class="careers-perk">
        <div class="careers-perk__icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        </div>
        <h3>High Earning Potential</h3>
        <p>Industry-leading incentives, performance bonuses and one of the best commission structures in Delhi NCR real estate.</p>
      </div>
      <div class="careers-perk">
        <div class="careers-perk__icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <h3>Expert Mentorship</h3>
        <p>Learn from 10+ year veterans of the real estate industry. Our senior advisors guide you at every step of your growth journey.</p>
      </div>
      <div class="careers-perk">
        <div class="careers-perk__icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </div>
        <h3>Fast Growth</h3>
        <p>Merit-based promotions, rapid career progression and real ownership of your work — no unnecessary bureaucracy.</p>
      </div>
      <div class="careers-perk">
        <div class="careers-perk__icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
        </div>
        <h3>Premium Projects</h3>
        <p>Work with India's top builders — M3M, SVG, Group 108, Uniwest and more. Represent luxury and high-value properties across Noida, Gurgaon & Greater Noida.</p>
      </div>
      <div class="careers-perk">
        <div class="careers-perk__icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <h3>RERA-Registered Firm</h3>
        <p>Join a fully compliant, RERA-registered consultancy with a reputation built on transparency, integrity and client trust since 2014.</p>
      </div>
      <div class="careers-perk">
        <div class="careers-perk__icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
        <h3>Flexible & Supportive Culture</h3>
        <p>A collaborative, inclusive workplace with modern tools, team outings, and a management team that truly values your contribution.</p>
      </div>
    </div>
  </div>
</section>

<!-- OPEN POSITIONS -->
<section class="section section--soft" id="open-positions">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Current Openings</span>
      <h2>Open Positions — Join Our Growing Team</h2>
      <div class="arch-divider" aria-hidden="true"></div>
    </div>

    <div class="careers-jobs reveal">

      <div class="careers-job">
        <div class="careers-job__top">
          <div>
            <span class="careers-job__tag">Sales</span>
            <h3 class="careers-job__title">Senior Property Advisor</h3>
            <p class="careers-job__meta"><span>📍 Noida, Sector 132</span> <span>💼 Full-Time</span> <span>💰 ₹4–10 LPA + Commission</span></p>
          </div>
        </div>
        <p>We're looking for an experienced Senior Property Advisor to manage client relationships and close high-value residential and commercial deals across Delhi NCR. You'll represent top-tier projects from India's leading developers.</p>
        <ul class="careers-job__req">
          <li>2+ years of experience in real estate sales</li>
          <li>Strong knowledge of Noida, Greater Noida & Gurgaon markets</li>
          <li>Excellent communication and negotiation skills</li>
          <li>Own vehicle preferred</li>
        </ul>
        <a href="#apply-form" class="btn btn-gold careers-apply-btn">Apply Now</a>
      </div>

      <div class="careers-job">
        <div class="careers-job__top">
          <div>
            <span class="careers-job__tag">Marketing</span>
            <h3 class="careers-job__title">Digital Marketing Executive</h3>
            <p class="careers-job__meta"><span>📍 Noida, Sector 132</span> <span>💼 Full-Time</span> <span>💰 ₹3–6 LPA</span></p>
          </div>
        </div>
        <p>Drive our online presence as the best property advisor in Noida. Manage SEO, Google Ads, Meta campaigns, content strategy and lead generation for our real estate brand across digital platforms.</p>
        <ul class="careers-job__req">
          <li>1–3 years of digital marketing experience</li>
          <li>Hands-on with Google Ads, Meta Ads & SEO tools</li>
          <li>Real estate or luxury brand experience is a plus</li>
          <li>Strong analytical and reporting skills</li>
        </ul>
        <a href="#apply-form" class="btn btn-gold careers-apply-btn">Apply Now</a>
      </div>

      <div class="careers-job">
        <div class="careers-job__top">
          <div>
            <span class="careers-job__tag">Advisory</span>
            <h3 class="careers-job__title">Property Consultant (Fresher)</h3>
            <p class="careers-job__meta"><span>📍 Noida, Sector 132</span> <span>💼 Full-Time</span> <span>💰 ₹2.5–4 LPA + Incentives</span></p>
          </div>
        </div>
        <p>No experience? No problem. If you're passionate about real estate and driven to succeed, we'll train you from scratch. Learn from the best property advisor team in Noida and kickstart a rewarding career.</p>
        <ul class="careers-job__req">
          <li>Graduate (any stream)</li>
          <li>Excellent communication skills in Hindi & English</li>
          <li>Hunger to learn and grow</li>
          <li>Smartphone & basic computer literacy</li>
        </ul>
        <a href="#apply-form" class="btn btn-gold careers-apply-btn">Apply Now</a>
      </div>

      <div class="careers-job">
        <div class="careers-job__top">
          <div>
            <span class="careers-job__tag">Operations</span>
            <h3 class="careers-job__title">CRM & Operations Executive</h3>
            <p class="careers-job__meta"><span>📍 Noida, Sector 132</span> <span>💼 Full-Time</span> <span>💰 ₹3–5 LPA</span></p>
          </div>
        </div>
        <p>Manage our client database, follow-up workflows and internal processes. You'll be the backbone of our operations — ensuring every lead, inquiry and client interaction is tracked and actioned seamlessly.</p>
        <ul class="careers-job__req">
          <li>1+ year experience in CRM or operations role</li>
          <li>Proficiency in Excel, Google Sheets and CRM tools</li>
          <li>Detail-oriented with strong organisational skills</li>
          <li>Real estate background preferred but not mandatory</li>
        </ul>
        <a href="#apply-form" class="btn btn-gold careers-apply-btn">Apply Now</a>
      </div>

    </div>
  </div>
</section>

<!-- CULTURE STATS STRIP -->
<section class="section section--plain careers-stats-strip" aria-label="Company stats">
  <div class="container">
    <div class="counters reveal">
      <div class="counter">
        <span class="num" data-counter="10" data-suffix="+">10+</span>
        <span class="lbl">Years in Business</span>
      </div>
      <div class="counter">
        <span class="num" data-counter="500" data-suffix="+">500+</span>
        <span class="lbl">Happy Clients</span>
      </div>
      <div class="counter">
        <span class="num" data-counter="50" data-suffix="+">50+</span>
        <span class="lbl">Premium Projects</span>
      </div>
      <div class="counter">
        <span class="num" data-counter="30" data-suffix="+">30+</span>
        <span class="lbl">Team Members</span>
      </div>
    </div>
  </div>
</section>

<!-- APPLY FORM -->
<section class="section" id="apply-form">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Apply Now</span>
      <h2>Send Us Your Application</h2>
      <div class="arch-divider" aria-hidden="true"></div>
      <p>Fill the form below and our HR team will reach out within 2 business days.</p>
    </div>

    <div class="careers-form-wrap reveal">

      <?php if ($flash_success): ?>
        <div class="careers-success">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="48" height="48"><circle cx="12" cy="12" r="10"/><polyline points="9 12 11 14 15 10"/></svg>
          <h3>Application Submitted!</h3>
          <p>Thank you for your interest in joining Shubharambh Infra Advisors — the best property advisor in Noida. Our HR team will review your application and contact you within 2 business days.</p>
          <a href="<?= e(url('index.php')) ?>" class="btn btn-gold">Back to Home</a>
        </div>
      <?php else: ?>

        <?php if ($flash_error): ?>
          <div class="form-error-banner"><?= e($flash_error) ?></div>
        <?php endif; ?>

        <form class="careers-form" method="POST" action="<?= e(url('careers.php')) ?>#apply-form"
              enctype="multipart/form-data" novalidate>
          <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
          <input type="text" name="honeypot" class="honeypot" tabindex="-1" autocomplete="off">

          <div class="careers-form__grid">
            <div class="form-field">
              <label for="cf-name">Full Name <span class="req">*</span></label>
              <input type="text" id="cf-name" name="full_name" placeholder="Your full name" required>
            </div>
            <div class="form-field">
              <label for="cf-email">Email Address <span class="req">*</span></label>
              <input type="email" id="cf-email" name="email" placeholder="you@example.com" required>
            </div>
            <div class="form-field">
              <label for="cf-phone">Phone Number <span class="req">*</span></label>
              <input type="tel" id="cf-phone" name="phone" placeholder="+91 9XXXXXXXXX" required>
            </div>
            <div class="form-field">
              <label for="cf-position">Position Applying For <span class="req">*</span></label>
              <select id="cf-position" name="position" required>
                <option value="" disabled selected>Select a position</option>
                <option value="Senior Property Advisor">Senior Property Advisor</option>
                <option value="Digital Marketing Executive">Digital Marketing Executive</option>
                <option value="Property Consultant (Fresher)">Property Consultant (Fresher)</option>
                <option value="CRM & Operations Executive">CRM &amp; Operations Executive</option>
                <option value="Other">Other / Open Application</option>
              </select>
            </div>
            <div class="form-field">
              <label for="cf-exp">Years of Experience</label>
              <select id="cf-exp" name="experience">
                <option value="Fresher">Fresher (0 years)</option>
                <option value="1-2 years">1–2 Years</option>
                <option value="3-5 years">3–5 Years</option>
                <option value="5+ years">5+ Years</option>
              </select>
            </div>
            <div class="form-field">
              <label for="cf-cv">Upload CV / Resume <span class="careers-cv-hint">(PDF, DOC or DOCX — max 5 MB)</span></label>
              <div class="cv-upload-wrap">
                <input type="file" id="cf-cv" name="cv" accept=".pdf,.doc,.docx" class="cv-file-input">
                <label for="cf-cv" class="cv-upload-label">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                  <span id="cv-label-text">Choose file or drag & drop</span>
                </label>
              </div>
            </div>
            <div class="form-field form-field--full">
              <label for="cf-cover">Cover Letter / Why do you want to join us?</label>
              <textarea id="cf-cover" name="cover_letter" rows="5"
                placeholder="Tell us a little about yourself and why you'd be a great fit for the best property advisory team in Noida..."></textarea>
            </div>
          </div>

          <div style="text-align:center;margin-top:2rem;">
            <button type="submit" class="btn btn-gold" style="min-width:220px;min-height:52px;font-size:1rem;">
              Submit Application
            </button>
          </div>
        </form>

      <?php endif; ?>
    </div>
  </div>
</section>

<script>
// Show selected filename in CV upload label
document.getElementById('cf-cv')?.addEventListener('change', function() {
  const label = document.getElementById('cv-label-text');
  if (this.files && this.files[0]) {
    label.textContent = this.files[0].name;
  } else {
    label.textContent = 'Choose file or drag & drop';
  }
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
