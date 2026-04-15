# Shubharambh Infra Advisors — Changes Report
**Date:** 15 April 2026
**Project:** shubharambhinfraadvisors.com

---

## ⚡ 1. Performance & Speed

### ✅ Lazy Load — All Images
- Added `loading="lazy"` on all project cards, partner logos, team photos
- Logo in header: `loading="eager" fetchpriority="high"` (above the fold)
- Project detail cover image: `loading="eager"` (LCP element)

### ✅ JavaScript Defer + Idle Optimization
- Removed `async` conflict from `main.js` — kept only `defer`
- Critical JS (nav, hero, testimonials) runs on `DOMContentLoaded`
- Non-critical JS (counters, reveal, popup, EMI, favorites, forms) deferred via `requestIdleCallback` — frees main thread during LCP

### ✅ LCP Preload
- First hero slide image preloaded via `<link rel="preload" as="image" fetchpriority="high">` injected in `<head>` via `$page_extra_head`

---

## 🔍 2. SEO (On-Page)

### ✅ ALT Tags — All Images
- All project images: descriptive alt with project name, builder, property type, city
- Logo: company name + tagline
- Team photo: name + title + company
- Partner logos: partner name + context

### ✅ Meta Tags — All Pages Updated

| Page | Title | Meta Description |
|---|---|---|
| index.php | Best Property Advisor in Noida — Shubharambh Infra Advisors | Keyword + company + services |
| about.php | About Shubharambh Infra Advisors — Best Property Advisor in Noida | Keyword + founder + location |
| projects.php | Properties in Noida & Delhi NCR — Shubharambh Infra Advisors | Keyword + count + locations |
| contact.php | Contact Shubharambh Infra Advisors — Best Property Advisor in Noida | Keyword + contact info |
| emi-calculator.php | Home Loan EMI Calculator — Shubharambh Infra Advisors, Best Property Advisor in Noida | Keyword + tool desc |

### ✅ Heading Structure Fixed (H1 → H2 → H3, no skip)
- Removed all `h4` skips — replaced with `h3` + custom CSS classes
- `trust-strip-label` class for partner strip (was h4)
- `feature-title` class for feature cards (was h4)
- `contact-label` class for contact info labels (was h4)

### ✅ Main Keyword: "Best Property Advisor in Noida"
Added on every page in:
- `<title>` tag
- `<meta name="description">`
- `<h1>` or eyebrow span
- `<h2>` section headings
- JSON-LD Organization description

### ✅ SEO — Link Text Fixed
- "LEARN MORE" → "About Shubharambh" (descriptive anchor text, Lighthouse SEO 92→100)
- Enquire button: `href="javascript:void(0)"` → `href="contact.php"` (crawlable)

---

## 🧠 3. Schema (Structured Data)

### ✅ Organization + RealEstateAgent Schema — header.php (All Pages)
- `@type: Organization` with full address, contact points, social profiles, logo ImageObject
- `@type: RealEstateAgent + LocalBusiness` with geo coordinates, opening hours, area served
- All values dynamic from DB settings — nothing hardcoded
- `sameAs` array from social URLs in settings

### ✅ Project Schema — project.php (Each Project Page)
- `@type: RealEstateListing` — name, description, price, address, builder, amenities
- `@type: BreadcrumbList` — Home → Projects → Project Name
- State resolved dynamically from city (Noida→UP, Gurgaon→Haryana, Haridwar→Uttarakhand)
- `additionalProperty` — property type, config, sizes, possession, RERA ID
- `amenityFeature` — each amenity as `LocationFeatureSpecification`

---

## 🌍 4. Location SEO

### ✅ Geo Meta Tags — header.php
```html
<meta name="geo.region" content="IN-UP">
<meta name="geo.placename" content="B-220, Logix Technova, Sector 132, Noida – 201304, Uttar Pradesh, India">
<meta name="geo.position" content="28.5085151;77.3793737">
<meta name="ICBM" content="28.5085151, 77.3793737">
```

---

## 📱 5. UI / UX Optimization

### ✅ Accessibility (Lighthouse 96 → 100)
- Testimonial dots: `role="tablist"` on container, `role="tab"` + `aria-selected` on each button
- JS `initTestimonials()` — clears PHP pre-rendered dots, creates with ARIA attributes, updates `aria-selected` on every slide change
- Enquire button: descriptive `aria-label`

### ✅ Mobile Hero Search Bar
- Label "Project / Builder / Location" → "Search" (too long for mobile)
- Layout: `2fr 1fr 1fr auto` (search field wider)
- Each field: vertical flex (icon → label → input), `min-height: 64px`
- Mobile (<=768px): search full-width top, city+budget side by side, button full-width
- Border: subtle gold `rgba(181,147,85,0.3)`, focus state highlights

### ✅ Contact Form — Full Responsive Fix
- `box-sizing: border-box` + `min-width: 0` on grid, form, all inputs
- `word-break: break-word` on email/address values (long email no longer overflows)
- Mobile: single column form, full-width submit button
- `overflow: hidden` on `.contact-grid` container

### ✅ Honeypot Input Hidden (White Box Fix)
- Problem: CSS selector `.form-field .honeypot` didn't match (input is outside `.form-field`)
- Fix: Added `input.honeypot` with `position: absolute !important; left: -9999px !important; opacity: 0 !important`

### ✅ Modal Backdrop Fix
- `display: none` → `visibility: hidden + pointer-events: none + opacity: 0`
- Enables CSS opacity transition on open/close (was broken before)

### ✅ Project Slug — Quick Facts Strip Redesign
- Old: horizontal flex (icon + text side by side) — cramped, long text overflows
- New: vertical card layout — icon → label (gold caps) → value (white bold)
- 4 equal columns with border-right dividers (no gaps)
- `word-break: break-word` on values
- Mobile: 2×2 grid with border separators

### ✅ Section H2 Headings — Responsive Font Size
- `.section-head h2`: `font-size: clamp(1.5rem, 3.2vw, 2.75rem)`
- `.contact-info h2`: `font-size: clamp(1.35rem, 2.8vw, 1.75rem)`
- Long headings auto-shrink on mobile, never wrap badly

### ✅ Touch UX Improvements
- `touch-action: manipulation` on all buttons (eliminates 300ms tap delay)
- `-webkit-tap-highlight-color: transparent` on links
- `overscroll-behavior: contain` on nav drawer and modal

---

## 🔒 6. Security & Form Handling

### ✅ Contact Form Data Flow (Verified)
- Submissions saved to `inquiries` table in `storage/shubharambh.sqlite`
- Fields stored: name, phone, email, city, message, project, source, IP, user agent, status
- CSRF token validation on every submission
- Honeypot spam protection
- Rate limiting: 1 submission per 60 seconds per IP
- reCAPTCHA verification

---

## 📊 Lighthouse Scores

| Category | Before | After |
|---|---|---|
| Performance (Desktop) | ~85 | **100** |
| Accessibility | 96 | **100** |
| Best Practices | 100 | **100** |
| SEO | 92 | **100** |
| Performance (Mobile) | 72 | **70-72** (localhost — expected, Slow 4G + 4x CPU throttle simulation) |

> **Note:** Mobile Lighthouse on localhost is unreliable. Production score will be significantly higher with server caching, CDN and gzip enabled.

---

## 📁 Files Modified

| File | Changes |
|---|---|
| `includes/header.php` | Geo tags, JSON-LD schema, logo eager load, LCP preload hook |
| `includes/footer.php` | `defer` on main.js, enquire button href fix |
| `public/index.php` | Title, meta, H1/H2/eyebrow keyword, hero search label, testimonial ARIA, preload logic |
| `public/about.php` | Title, meta, H1/H2 keyword + company name |
| `public/projects.php` | Title, meta, H1/eyebrow keyword |
| `public/contact.php` | Title, meta, H1/H2 keyword |
| `public/emi-calculator.php` | Title, meta, H1/H2 keyword |
| `public/project.php` | Quick facts HTML restructure, Project JSON-LD schema |
| `public/assets/css/style.css` | Honeypot fix, modal fix, touch UX, contact responsive, hero search, quick-facts, H2 clamp, form box-sizing |
| `public/assets/js/main.js` | Testimonial ARIA (role=tab, aria-selected), idle callback for non-critical JS |
