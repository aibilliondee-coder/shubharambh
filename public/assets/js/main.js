/**
 * Shubharambh Infra Advisors — Public JS
 * Vanilla, no dependencies. Mobile-first, progressive enhancement.
 *
 * Features:
 *  - Sticky header shrink
 *  - Mobile nav (slide-in drawer + backdrop)
 *  - Tabbed hero search (Buy / Rent / Commercial)
 *  - Hero autocomplete (debounced)
 *  - Testimonial carousel (dots + prev/next + autoplay + swipe)
 *  - Scroll reveal (IntersectionObserver)
 *  - Animated counters
 *  - EMI calculator (sliders + live compute)
 *  - Filter bar (auto-submit on change)
 *  - Favorites via localStorage + shortlist bar
 *  - Exit-intent / idle popup (once per session)
 *  - Scroll-to-top button
 *  - AJAX form submissions with toast feedback
 *  - Smooth-scroll anchors with header offset
 */
(function () {
  'use strict';

  const CFG = window.SIA || {};

  // ------------------------------------------------------------------
  // DOM helpers
  // ------------------------------------------------------------------
  const $  = (sel, ctx) => (ctx || document).querySelector(sel);
  const $$ = (sel, ctx) => Array.from((ctx || document).querySelectorAll(sel));

  function debounce(fn, wait) {
    let t;
    return function () {
      clearTimeout(t);
      const args = arguments, ctx = this;
      t = setTimeout(() => fn.apply(ctx, args), wait);
    };
  }

  function escapeHtml(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, c => (
      { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]
    ));
  }

  function formatINR(n) {
    if (!isFinite(n)) return '\u20B9 0';
    const fixed = Math.round(n);
    return '\u20B9 ' + fixed.toLocaleString('en-IN');
  }

  // ------------------------------------------------------------------
  // Toast
  // ------------------------------------------------------------------
  function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = 'toast ' + (type || 'success');
    const icon = type === 'error'
      ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>'
      : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
    toast.innerHTML = icon + '<span>' + escapeHtml(message) + '</span>';
    document.body.appendChild(toast);
    requestAnimationFrame(() => toast.classList.add('show'));
    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 450);
    }, 4400);
  }

  // ------------------------------------------------------------------
  // Sticky header shrink on scroll
  // ------------------------------------------------------------------
  function initStickyHeader() {
    const header = $('.site-header');
    if (!header) return;
    const onScroll = () => {
      header.classList.toggle('is-scrolled', window.scrollY > 24);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  // ------------------------------------------------------------------
  // Mobile navigation drawer
  // ------------------------------------------------------------------
  function initNav() {
    const toggle = $('.nav-toggle');
    const list   = $('.nav-list');
    const backdrop = $('.nav-backdrop');
    if (!toggle || !list) return;

    const close = () => {
      list.classList.remove('open');
      toggle.setAttribute('aria-expanded', 'false');
      if (backdrop) backdrop.classList.remove('open');
      document.body.style.overflow = '';
    };
    const open = () => {
      list.classList.add('open');
      toggle.setAttribute('aria-expanded', 'true');
      if (backdrop) backdrop.classList.add('open');
      document.body.style.overflow = 'hidden';
    };
    toggle.addEventListener('click', () => {
      list.classList.contains('open') ? close() : open();
    });
    if (backdrop) backdrop.addEventListener('click', close);
    $$('a', list).forEach(a => a.addEventListener('click', close));
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && list.classList.contains('open')) close();
    });
  }

  // ------------------------------------------------------------------
  // Hero background slideshow (Ken Burns rotator)
  // ------------------------------------------------------------------
  function initHeroSlides() {
    const wrap = $('#hero-slides');
    if (!wrap) return;
    const slides = $$('.hero-slide', wrap);
    if (slides.length < 2) return;

    // Build pagination dots
    const dotsBox = $('#hero-slide-dots');
    const dots = [];
    if (dotsBox) {
      slides.forEach((_, i) => {
        const b = document.createElement('button');
        b.type = 'button';
        b.setAttribute('aria-label', 'Show slide ' + (i + 1));
        if (i === 0) b.classList.add('active');
        b.addEventListener('click', () => { goTo(i); restart(); });
        dotsBox.appendChild(b);
        dots.push(b);
      });
    }

    // If a <video> is present and can play, skip the slideshow and dots
    const video = $('.hero-video');
    if (video) {
      video.addEventListener('playing', () => {
        slides.forEach(s => s.classList.remove('is-active'));
        if (dotsBox) dotsBox.style.display = 'none';
      }, { once: true });
      video.addEventListener('error', () => { video.classList.add('is-missing'); });
    }

    let idx = 0, timer = null;
    function goTo(i) {
      idx = (i + slides.length) % slides.length;
      slides.forEach((s, si) => s.classList.toggle('is-active', si === idx));
      dots.forEach((d, di) => d.classList.toggle('active', di === idx));
    }
    function next() { goTo(idx + 1); }
    function restart() {
      if (timer) clearInterval(timer);
      timer = setInterval(next, 6500);
    }
    restart();
  }

  // ------------------------------------------------------------------
  // Hero tabbed search
  // ------------------------------------------------------------------
  function initHeroTabs() {
    const tabsBox = $('.hero-search-tabs');
    if (!tabsBox) return;
    const tabs = $$('button', tabsBox);
    const target = $('#hero-search-type');
    tabs.forEach(btn => {
      btn.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        if (target) target.value = btn.getAttribute('data-type') || '';
      });
    });
  }

  // ------------------------------------------------------------------
  // Hero project autocomplete
  // ------------------------------------------------------------------
  function initHeroSearch() {
    const input   = $('#hero-search-input');
    const results = $('#hero-search-results');
    if (!input || !results) return;

    const onQuery = debounce(async function () {
      const q = input.value.trim();
      if (q.length < 2) {
        results.classList.remove('open');
        results.innerHTML = '';
        return;
      }
      try {
        const r = await fetch(CFG.baseUrl + '/api/project_search.php?q=' + encodeURIComponent(q), {
          headers: { 'Accept': 'application/json' }
        });
        if (!r.ok) throw new Error('search');
        const data = await r.json();
        if (!data.items || !data.items.length) {
          results.innerHTML = '<a style="cursor:default;opacity:.6;justify-content:center">No projects found</a>';
        } else {
          results.innerHTML = data.items.map(p =>
            '<a href="' + CFG.baseUrl + '/project.php?slug=' + encodeURIComponent(p.slug) + '">' +
              '<div class="r-main">' +
                '<strong>' + escapeHtml(p.name) + '</strong>' +
                '<small>' + escapeHtml(p.builder || '') + (p.location ? ' \u00B7 ' + escapeHtml(p.location) : '') + '</small>' +
              '</div>' +
              (p.price ? '<span class="r-price">' + escapeHtml(p.price) + '</span>' : '') +
            '</a>'
          ).join('');
        }
        results.classList.add('open');
      } catch (err) {
        results.classList.remove('open');
      }
    }, 220);

    input.addEventListener('input', onQuery);
    input.addEventListener('focus', onQuery);
    document.addEventListener('click', (e) => {
      if (!results.contains(e.target) && e.target !== input) {
        results.classList.remove('open');
      }
    });
  }

  // ------------------------------------------------------------------
  // Testimonial carousel (dots, prev/next, autoplay, swipe)
  // ------------------------------------------------------------------
  function initTestimonials() {
    const track = $('.testimonial-track');
    const dotsBox = $('.testimonial-dots');
    const root = $('.testimonials');
    if (!track || !dotsBox || !root) return;

    const items = $$('.testimonial', track);
    if (items.length < 2) return;

    let idx = 0, timer = null;
    items.forEach((_, i) => {
      const b = document.createElement('button');
      b.type = 'button';
      b.setAttribute('aria-label', 'Go to testimonial ' + (i + 1));
      b.addEventListener('click', () => { goTo(i); restart(); });
      dotsBox.appendChild(b);
    });
    const dots = $$('button', dotsBox);

    function goTo(i) {
      idx = (i + items.length) % items.length;
      track.style.transform = 'translateX(-' + (idx * 100) + '%)';
      dots.forEach((d, di) => d.classList.toggle('active', di === idx));
    }
    function next() { goTo(idx + 1); }
    function prev() { goTo(idx - 1); }
    function restart() {
      if (timer) clearInterval(timer);
      timer = setInterval(next, 6000);
    }
    goTo(0);
    restart();

    // prev/next buttons
    const prevBtn = $('.testimonial-nav.prev', root);
    const nextBtn = $('.testimonial-nav.next', root);
    if (prevBtn) prevBtn.addEventListener('click', () => { prev(); restart(); });
    if (nextBtn) nextBtn.addEventListener('click', () => { next(); restart(); });

    // Swipe
    let tx = null;
    track.addEventListener('touchstart', e => { tx = e.touches[0].clientX; }, { passive: true });
    track.addEventListener('touchend', e => {
      if (tx == null) return;
      const dx = e.changedTouches[0].clientX - tx;
      if (Math.abs(dx) > 40) { dx < 0 ? next() : prev(); restart(); }
      tx = null;
    });

    // Pause on hover
    root.addEventListener('mouseenter', () => { if (timer) clearInterval(timer); });
    root.addEventListener('mouseleave', restart);
  }

  // ------------------------------------------------------------------
  // Scroll reveal (IntersectionObserver)
  // ------------------------------------------------------------------
  function initReveal() {
    const els = $$('.reveal');
    if (!('IntersectionObserver' in window) || !els.length) {
      els.forEach(el => el.classList.add('reveal-in'));
      return;
    }
    const io = new IntersectionObserver(entries => {
      entries.forEach(en => {
        if (en.isIntersecting) {
          en.target.classList.add('reveal-in');
          io.unobserve(en.target);
        }
      });
    }, { threshold: 0.14, rootMargin: '0px 0px -60px 0px' });
    els.forEach(el => io.observe(el));
  }

  // ------------------------------------------------------------------
  // Animated counters
  // ------------------------------------------------------------------
  function initCounters() {
    const els = $$('[data-counter]');
    if (!els.length) return;

    const animate = (el) => {
      const target = parseFloat(el.getAttribute('data-counter')) || 0;
      const suffix = el.getAttribute('data-suffix') || '';
      const duration = 1400;
      const start = performance.now();
      const step = (now) => {
        const p = Math.min(1, (now - start) / duration);
        // easeOutQuad
        const eased = 1 - (1 - p) * (1 - p);
        const v = Math.round(target * eased);
        el.innerHTML = v + (suffix ? '<sup>' + escapeHtml(suffix) + '</sup>' : '');
        if (p < 1) requestAnimationFrame(step);
      };
      requestAnimationFrame(step);
    };

    if (!('IntersectionObserver' in window)) {
      els.forEach(animate);
      return;
    }
    const io = new IntersectionObserver(entries => {
      entries.forEach(en => {
        if (en.isIntersecting) {
          animate(en.target);
          io.unobserve(en.target);
        }
      });
    }, { threshold: 0.4 });
    els.forEach(el => io.observe(el));
  }

  // ------------------------------------------------------------------
  // EMI calculator
  // ------------------------------------------------------------------
  function initEmiCalc() {
    const calc = $('.emi-calc');
    if (!calc) return;

    const price  = $('#emi-price',  calc);
    const down   = $('#emi-down',   calc);
    const rate   = $('#emi-rate',   calc);
    const tenure = $('#emi-tenure', calc);

    const priceOut  = $('#emi-price-v',  calc);
    const downOut   = $('#emi-down-v',   calc);
    const rateOut   = $('#emi-rate-v',   calc);
    const tenureOut = $('#emi-tenure-v', calc);

    const emiOut      = $('#emi-out',       calc);
    const loanOut     = $('#emi-loan',      calc);
    const interestOut = $('#emi-interest',  calc);
    const totalOut    = $('#emi-total',     calc);

    if (!price || !down || !rate || !tenure || !emiOut) return;

    function compute() {
      const P = parseFloat(price.value)  || 0;
      const D = parseFloat(down.value)   || 0;
      const R = parseFloat(rate.value)   || 0;
      const N = parseFloat(tenure.value) || 0;

      const loan = Math.max(0, P - D);
      const monthlyRate = (R / 100) / 12;
      const months = N * 12;

      let emi = 0;
      if (loan > 0 && monthlyRate > 0 && months > 0) {
        emi = (loan * monthlyRate * Math.pow(1 + monthlyRate, months)) /
              (Math.pow(1 + monthlyRate, months) - 1);
      } else if (loan > 0 && months > 0) {
        emi = loan / months;
      }
      const total = emi * months;
      const interest = total - loan;

      if (priceOut)  priceOut.textContent  = formatINR(P);
      if (downOut)   downOut.textContent   = formatINR(D);
      if (rateOut)   rateOut.textContent   = R.toFixed(2) + ' %';
      if (tenureOut) tenureOut.textContent = N + ' Years';

      emiOut.textContent      = formatINR(emi);
      if (loanOut)     loanOut.textContent     = formatINR(loan);
      if (interestOut) interestOut.textContent = formatINR(interest);
      if (totalOut)    totalOut.textContent    = formatINR(total);
    }

    [price, down, rate, tenure].forEach(el => {
      el.addEventListener('input', compute);
      el.addEventListener('change', compute);
    });
    compute();
  }

  // ------------------------------------------------------------------
  // Favorites (localStorage) + shortlist bar
  // ------------------------------------------------------------------
  const FAV_KEY = 'sia_fav_projects';
  function getFavs() {
    try { return JSON.parse(localStorage.getItem(FAV_KEY) || '[]'); }
    catch (e) { return []; }
  }
  function setFavs(list) {
    try { localStorage.setItem(FAV_KEY, JSON.stringify(list)); } catch (e) {}
  }
  function initFavorites() {
    const buttons = $$('[data-fav]');
    const favs = getFavs();

    // mark already-saved on page load
    buttons.forEach(btn => {
      const id = btn.getAttribute('data-fav');
      if (favs.indexOf(id) !== -1) btn.classList.add('is-saved');
    });
    updateShortlistBar();

    buttons.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        const id = btn.getAttribute('data-fav');
        const name = btn.getAttribute('data-name') || 'Project';
        const list = getFavs();
        const i = list.indexOf(id);
        if (i === -1) {
          list.push(id);
          btn.classList.add('is-saved');
          showToast('Added ' + name + ' to your shortlist', 'success');
        } else {
          list.splice(i, 1);
          btn.classList.remove('is-saved');
          showToast('Removed ' + name + ' from your shortlist', 'success');
        }
        setFavs(list);
        updateShortlistBar();
      });
    });
  }
  function updateShortlistBar() {
    const bar = $('#shortlist-bar');
    if (!bar) return;
    const count = getFavs().length;
    const numEl = $('#shortlist-count', bar);
    if (numEl) numEl.textContent = count;
    bar.classList.toggle('visible', count > 0);
    document.body.classList.toggle('has-shortlist', count > 0);
  }

  // ------------------------------------------------------------------
  // Exit-intent / idle popup modal
  // ------------------------------------------------------------------
  function initPopup() {
    const modal = $('#popup-modal');
    if (!modal) return;

    let shown = false;
    try {
      if (sessionStorage.getItem('sia_popup_shown') === '1') shown = true;
    } catch (e) {}

    const open = () => modal.classList.add('open');
    const close = () => modal.classList.remove('open');

    const showOnce = () => {
      if (shown) return;
      shown = true;
      try { sessionStorage.setItem('sia_popup_shown', '1'); } catch (e) {}
      open();
    };

    // Idle trigger: show after 25 seconds of activity
    const idleTimer = setTimeout(showOnce, 25000);

    // Scroll trigger: show when user has scrolled 55% of page
    let scrollHandled = false;
    const onScroll = () => {
      if (scrollHandled || shown) return;
      const pct = (window.scrollY + window.innerHeight) / document.documentElement.scrollHeight;
      if (pct > 0.55) {
        scrollHandled = true;
        showOnce();
      }
    };
    window.addEventListener('scroll', onScroll, { passive: true });

    // Exit-intent trigger on desktop (mouse leaves top of window)
    if (!('ontouchstart' in window)) {
      document.addEventListener('mouseout', (e) => {
        if (!e.relatedTarget && e.clientY < 10) showOnce();
      });
    }

    // Close handlers
    $$('[data-modal-close]', modal).forEach(el => el.addEventListener('click', close));
    modal.addEventListener('click', (e) => { if (e.target === modal) close(); });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') close();
    });

    // Manual trigger handlers (project cards, enquire buttons)
    $$('[data-modal-open]').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const projectName = btn.getAttribute('data-project-name') || '';
        const pid = btn.getAttribute('data-project-id') || '';
        const nameField = $('#popup-project-name');
        const idField = $('#popup-project-id');
        if (nameField) nameField.value = projectName;
        if (idField) idField.value = pid;
        const header = $('#popup-subtitle');
        if (header) {
          header.textContent = projectName
            ? 'Enquire about ' + projectName
            : 'Our expert will reach out within 24 hours';
        }
        shown = true;
        try { sessionStorage.setItem('sia_popup_shown', '1'); } catch (e) {}
        clearTimeout(idleTimer);
        open();
      });
    });
  }

  // ------------------------------------------------------------------
  // Scroll-to-top button
  // ------------------------------------------------------------------
  function initScrollTop() {
    const btn = $('#scroll-top');
    if (!btn) return;
    const onScroll = () => {
      btn.classList.toggle('visible', window.scrollY > 520);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    btn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    onScroll();
  }

  // ------------------------------------------------------------------
  // Filter bar auto-submit on change
  // ------------------------------------------------------------------
  function initFilterBar() {
    const form = $('#filter-form');
    if (!form) return;
    $$('select', form).forEach(sel => {
      sel.addEventListener('change', () => form.submit());
    });
  }

  // ------------------------------------------------------------------
  // Smooth anchor offset for sticky header
  // ------------------------------------------------------------------
  function initAnchors() {
    $$('a[href^="#"]').forEach(a => {
      a.addEventListener('click', (e) => {
        const href = a.getAttribute('href');
        if (!href || href === '#' || href.length < 2) return;
        const target = document.querySelector(href);
        if (!target) return;
        e.preventDefault();
        const headerOffset = window.innerWidth <= 768 ? 72 : 86;
        const top = target.getBoundingClientRect().top + window.scrollY - (headerOffset + 12);
        window.scrollTo({ top, behavior: 'smooth' });
      });
    });
  }

  // ------------------------------------------------------------------
  // AJAX form submissions
  // ------------------------------------------------------------------
  function initForms() {
    $$('form[data-ajax-form]').forEach(form => {
      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const endpoint = form.getAttribute('action');
        const msgBox = form.querySelector('.form-msg');
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalLabel = submitBtn ? submitBtn.textContent : '';

        if (msgBox) { msgBox.className = 'form-msg'; msgBox.textContent = ''; }
        if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Submitting\u2026'; }

        try {
          const formData = new FormData(form);

          // reCAPTCHA v3
          if (CFG.recaptchaKey && window.grecaptcha) {
            await new Promise(resolve => grecaptcha.ready(resolve));
            const token = await grecaptcha.execute(CFG.recaptchaKey, { action: 'contact' });
            formData.set('recaptcha_token', token);
          }

          const r = await fetch(endpoint, { method: 'POST', body: formData });
          const data = await r.json().catch(() => ({}));

          if (r.ok && data.ok) {
            form.reset();
            const successMsg = data.message || 'Thank you! We will be in touch shortly.';
            showToast(successMsg, 'success');
            if (msgBox) {
              msgBox.className = 'form-msg success';
              msgBox.textContent = successMsg;
            }
            const modal = form.closest('.modal-backdrop');
            if (modal) setTimeout(() => modal.classList.remove('open'), 1800);
          } else {
            const err = (data && data.message) || 'Something went wrong. Please try again.';
            showToast(err, 'error');
            if (msgBox) {
              msgBox.className = 'form-msg error';
              msgBox.textContent = err;
            }
          }
        } catch (err) {
          showToast('Network error. Please check your connection.', 'error');
          if (msgBox) {
            msgBox.className = 'form-msg error';
            msgBox.textContent = 'Network error. Please try again.';
          }
        } finally {
          if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = originalLabel; }
        }
      });
    });
  }

  // ------------------------------------------------------------------
  // Boot
  // ------------------------------------------------------------------
  document.addEventListener('DOMContentLoaded', () => {
    initStickyHeader();
    initNav();
    initHeroSlides();
    initHeroTabs();
    initHeroSearch();
    initTestimonials();
    initReveal();
    initCounters();
    initEmiCalc();
    initFavorites();
    initPopup();
    initScrollTop();
    initFilterBar();
    initAnchors();
    initForms();
  });
})();
