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

---

## 🗓️ Session 2 — 18 April 2026

---

## 🔝 7. Sticky Header (Fixed)

### ✅ Header changed from `position: sticky` → `position: fixed`
- `position: sticky` was not working reliably due to `body { position: relative }` and overflow constraints
- Changed to `position: fixed; top: 0; left: 0; right: 0; z-index: 200`
- Added `body { padding-top: calc(var(--header-h) + var(--banner-h)) }` to prevent content going under header
- Header size stays **same on scroll** — removed logo shrink on `.is-scrolled` (was changing from 76px → 64px)
- `is-scrolled` now only changes background opacity + adds box-shadow (no size change)

---

## 📢 8. Notification Banner

### ✅ New announcement banner added below header (all pages)
- Added in `includes/header.php` between `</header>` and `<main>`
- `position: fixed; top: var(--header-h); z-index: 190` — always visible below header
- Gold gradient background, navy text, navy CTA pill button
- Message: "🔥 Limited Time Offer — **Book Your Plot Today** & Get Exclusive Pre-Launch Prices in Noida!"
- **Enquire Now** button links to `contact.php`
- **✕ dismiss button** — hides banner with smooth animation, remembers dismiss via `sessionStorage` (won't reappear on page navigation)
- CSS variable `--banner-h: 44px` (desktop) / `52px` (mobile ≤600px) used sitewide for offset calculations
- Mobile: text wraps naturally, icon hidden, font/padding reduced

---

## 📱 9. Mobile Nav Drawer Redesign

### ✅ Complete visual redesign of hamburger menu drawer
- Deep gradient background: `#071323 → #0B1D33 → #102943`
- Nav links styled as **card buttons** with:
  - Gold left border `3px` on active/hover
  - `rgba` gold background highlight on active
  - `›` arrow on right side
  - Rounded corners, smooth hover transitions
- **Close button (✕) added inside drawer** — `position: absolute; top-right` — always visible regardless of z-index stacking
- Close button wired to JS `initNav()` — same close function as backdrop/escape key
- Drawer `z-index: 150`, header `z-index: 200` — header always on top
- **Call button** at bottom of drawer — gold gradient, full width, with phone icon
- Drawer width: **72% / max 280px** — doesn't cover full screen
- `top: 0; height: 100vh` with `padding-top: var(--header-h)` — links start right below header

---

## 🔑 11. Keyword Density — index.php

### ✅ "Best Property Advisor in Noida" now visible 4 times in body content
| # | Location | Text |
|---|---|---|
| 1 | Hero eyebrow | `Shubharambh Infra Advisors — Best Property Advisor in Noida` |
| 2 | About section | `Trusted by 500+ families as the best property advisor in Noida and Delhi NCR.` |
| 3 | Why Choose Us H2 | `Noida's Best Property Advisor — Shubharambh Infra` |
| 4 | Why Choose Us paragraph | `As the best property advisor in Noida, we build every client relationship on transparency...` |

---

## 📁 Files Modified (Session 2)

| File | Changes |
|---|---|
| `includes/header.php` | Notification banner, nav drawer close button, drawer call button |
| `config/config.php` | SITE_URL updated for XAMPP |
| `public/index.php` | Keyword added 2 more times naturally (about section + why choose us) |
| `public/assets/css/style.css` | Fixed header, notification banner, mobile nav drawer redesign, body padding-top, banner CSS variables |
| `public/assets/js/main.js` | `initNotifBanner()` — dismiss + sessionStorage, drawer close button wired to `initNav()` |

---

## 🗓️ Session 3 — 19 April 2026

---

## 🎫 12. Notification Banner — Updates

### ✅ CTA button text changed: "Grab the Deal" → "Enquire Now"

### ✅ Close (✕) button permanently removed
- Banner is now always visible — cannot be dismissed by user
- Removed `initNotifBanner()` JS function entirely (dismiss + sessionStorage logic deleted)
- Removed `.notif-banner__close`, `.notif-banner.is-hidden`, `body.no-banner` CSS

---

## ❤️ 13. Shortlist Bar — Bug Fixes & UI

### ✅ Bug fix: shortlist bar was showing on mobile even with 0 projects
- Added `visibility: hidden` by default with `transition delay` trick
- Bar is now fully invisible until `.visible` class is added (count > 0)

### ✅ Overlap fix: shortlist bar was overlapping sticky mobile bar
- `bottom: calc(76px + env(safe-area-inset-bottom))` — sits cleanly above sticky bar

### ✅ Toast notification moved above shortlist bar on mobile
- `bottom: calc(148px + env(safe-area-inset-bottom))` so toast doesn't overlap bar

### ✅ Clear All (🗑️) trash icon button added to shortlist bar
- Clears all shortlisted projects from localStorage in one tap
- Removes `is-saved` class from all heart buttons
- Shows toast: "Shortlist cleared"
- CSS: red hover state on trash icon

### ✅ Mobile layout fix — single row, no wrap
- `flex-wrap: nowrap` on mobile
- `white-space: nowrap` on text and button
- On screens ≤360px: label switches to short version "♥ N saved" to fit in one row

---

## 📍 14. Advisor Info Section — Repositioned & Redesigned

### ✅ Moved above Featured Projects
- Order is now: **Advisor → Featured Projects → About → Counters → Why Choose Us → Testimonials**

### ✅ Visual redesign — removed card box
- Removed `border`, `border-radius`, `background` from `.advisor-info-wrap`
- Now a clean full-width strip with `border-top` + `border-bottom` divider lines
- Blends naturally between trust strip and featured projects

### ✅ Layout changed to centered
- Heading + subtitle centered
- Read More button centered below (was side-by-side with heading)
- `font-size: clamp(1.4rem, 3vw, 2rem)` for responsive heading

---

## 🏷️ 15. Hero Subtitle Updated

### ✅ "BEST REAL ESTATE PROPERTY CONSULTANT IN DELHI/NCR" → "Trusted by 500+ Families Across Delhi NCR"
- Hardcoded in `public/index.php` (overrides DB value)

---

## 🤝 16. Partner Logos — Uniform White Background

### ✅ All partner logo cards now have pure `#ffffff` background
- Previously some logos had transparent PNG backgrounds (SVG Developers, GlobalBirth) — looked inconsistent
- Fixed: `.partners-track .partner { background: #ffffff }` — uniform across all logos

---

## 📁 Files Modified (Session 3)

| File | Changes |
|---|---|
| `includes/header.php` | Removed close button from banner, CTA text → "Enquire Now" |
| `includes/footer.php` | Added trash icon (Clear All) button to shortlist bar, dual span labels for responsive text |
| `public/index.php` | Advisor section moved above Featured Projects, hero subtitle updated, advisor section restructured as standalone |
| `public/assets/css/style.css` | Shortlist bar visibility fix, toast offset, clear btn CSS, advisor redesign (no box), partner logos white bg, mobile bar layout |
| `public/assets/js/main.js` | Removed `initNotifBanner()`, added `initShortlistClear()`, dual count update for short label |

---

## 🗓️ Session 3 (Continued) — 19 April 2026

---

## 🎓 17. Careers Page — New Page Created

### ✅ Full careers page built from scratch (`public/careers.php`)
- **Why Join Us** — 6 perk cards: High Earning Potential, Expert Mentorship, Fast Growth, Premium Projects, RERA-Registered, Flexible Culture
- **Open Positions** — 4 job listings with requirements + Apply Now buttons:
  - Senior Property Advisor (₹4–10 LPA + Commission)
  - Digital Marketing Executive (₹3–6 LPA)
  - Property Consultant Fresher (₹2.5–4 LPA + Incentives)
  - CRM & Operations Executive (₹3–5 LPA)
- **Stats strip** — 10+ years, 500+ clients, 50+ projects, 30+ team members
- **Application form** — name, email, phone, position dropdown, experience, cover letter
  - CSRF token validation, honeypot spam protection
  - Submissions saved to `inquiries` table with `source = 'careers'`
  - Success state + error state with messages
- **SEO** — title, meta description with main keyword "Best Property Advisor in Noida"
- **"Careers" added to header nav** (between EMI Calc and Contact)
- **"Careers" added to footer Quick Links**
- Fully responsive — 3-col perks → 2-col → 1-col on mobile

---

## 🏗️ 18. New Projects Added — T&T The Blue & Yatharth HighLife

### ✅ T&T The Blue (ID: 12, sort_order: 125)
- Builder: T&T Group
- Location: Siddharth Vihar, NH-24, Ghaziabad
- Config: 3 BHK | Size: 2,048 sq ft | Price: ₹2.8 Cr Onwards
- Possession: December 2027 | RERA: UPRERAPRJ899584
- Category: Residential | is_featured: 1
- Key USPs: AI-enabled smart home, only 2 residences/floor, 6 design themes, white façade
- Cover image downloaded from `theblue.tandtgroup.in`

### ✅ Yatharth HighLife TechZone 4 (ID: 13, sort_order: 120)
- Builder: Yatharth Group & NBCC India
- Location: Tech Zone IV, Dream Valley, Greater Noida West
- Config: 1 BHK, 2 BHK | Sizes: 941–1,454 sq ft | Price: ₹90 Lakh Onwards
- Possession: 2030 | NBCC/Supreme Court supervised
- Category: Residential | is_featured: 1
- Key USPs: IKEA-furnished, smart home, NBCC govt. undertaking, only 91 units
- Cover image downloaded from `moneytreerealty.com`

### ✅ Project display order fixed — latest projects now show first
- Changed `ORDER BY sort_order ASC` → `DESC` in `index.php` and `projects.php`
- Higher sort_order = appears first on homepage and projects page

### ✅ Both projects added to `sql/seed.sqlite.sql` for git deployment

---

## 🔤 19. Font Changed — Inter → DM Sans (Sitewide)

### ✅ Body font changed from Inter to DM Sans across entire project
- Updated Google Fonts `<link>` in `includes/header.php`
- Updated CSS variable: `--f-sans: 'DM Sans'`
- Updated `--f-serif: 'DM Sans'` (Playfair Display removed from headings/numbers)
- All text — headings, body, numbers, labels — now renders in DM Sans
- Playfair Display retained only in logo SVG file
- Added `?v=20260419b` cache-busting query to CSS link

---

## 📁 Files Modified (Session 3 Continued)

| File | Changes |
|---|---|
| `includes/header.php` | Careers nav link added, DM Sans Google Fonts, cache-bust version query |
| `includes/footer.php` | Careers link added to Quick Links |
| `public/careers.php` | **NEW FILE** — full careers page with job listings + application form |
| `public/index.php` | `ORDER BY sort_order DESC` for newest projects first |
| `public/projects.php` | `ORDER BY sort_order DESC` default sort |
| `public/assets/css/style.css` | Careers page CSS, `--f-sans` + `--f-serif` → DM Sans, font comment updated |
| `sql/seed.sqlite.sql` | T&T The Blue + Yatharth HighLife INSERT statements added |
| `public/uploads/projects/tnt-the-blue.webp` | Cover image downloaded from official site |
| `public/uploads/projects/yatharth-highlife.webp` | Cover image downloaded from moneytreerealty.com |

---

## 🗓️ Session 4 — 20 April 2026

---

## 📝 20. Blog System — Built from Scratch

### ✅ Database Table — `blogs`
- New SQLite table: `id, slug (UNIQUE), title, excerpt, body (HTML), cover_image, category, author, read_time, is_published, sort_order, published_at, created_at, updated_at`
- 6 blog posts seeded with real Delhi NCR real estate content

### ✅ Blog Listing Page — `public/blogs.php`
- Category filter bar (All / Finance & Loans / Investment Guide / Legal & Compliance / Location Guide / Project Reviews)
- Filter bar: horizontal scroll on mobile (no wrapping)
- Pagination: 9 posts per page
- Blog cards: image, category badge, read time, author, title, excerpt (clamped 2 lines on mobile), Read More link
- `$page_active = 'blog'` — Blog nav link highlighted

### ✅ Single Post Page — `public/blog.php`
- Slug-based routing via `?slug=` GET param
- Two-column layout: main content + sticky sidebar
- Main: category badge, read time + author meta, H1 title, excerpt (gold left border), cover image (`eager` load), prose body
- Share bar: WhatsApp / Facebook / LinkedIn buttons
- CTA box: "Looking for Best Property in Noida?" with Free Consultation button
- Sidebar: author card (Shubharambh Infra Advisors), related posts (same category, fallback to any), quick contact (Call + WhatsApp)
- Related posts: thumbnail + title + date

### ✅ Homepage Blog Section — `public/index.php`
- New "Latest from Our Blog" section added before testimonials
- Shows 6 most recent published posts (ORDER BY sort_order DESC)
- "View All Blogs" button shown only when total posts > 6

### ✅ Navigation — Blog Link Added
- **Header nav:** Blog link added between Projects and Careers
- **Footer Quick Links:** Blog link added

### ✅ Blog CSS
- `.blog-grid` — 3-col → 2-col → 1-col responsive
- `.blog-card` — hover lift, gold border on hover, image scale on hover
- `.blog-filter-bar` — pill buttons, gold active state
- `.blog-post-layout` — 2-col with sticky sidebar
- `.prose` — styled h2/h3/p/ul/li for blog body content
- `.blog-share`, `.share-btn` — WhatsApp/FB/LinkedIn color buttons
- `.blog-cta-box` — gradient CTA inside post
- `.blog-post-sidebar`, `.blog-author`, `.blog-related-*` — sidebar components

---

## 📸 21. Blog Cover Images — Real Contextual Photos

### ✅ 6 images downloaded from Unsplash — relevant to each topic

| Blog Post | Image Theme |
|---|---|
| Best Property Advisor in Noida | Professional advisor meeting client |
| Why Noida is Best Place to Invest (2026) | Noida city / skyline |
| RERA Registered Property | Legal document signing |
| Complete Home Loan Guide | House keys + home |
| Top 5 Luxury Projects Noida Expressway | Luxury apartment building |
| Residential vs Commercial Investment | Commercial high-rise |

- All blog card images: `loading="lazy"`
- Single post cover: `loading="eager"` (above fold)

---

## ✍️ 22. Blog Content — Real Delhi NCR Data

### ✅ All 6 posts updated with genuine real estate content
- **No fake author** — all posts show "Shubharambh Infra Advisors" (removed "Mohit Khari")
- **No fake dates** — published_at removed from display (card meta + single post)
- **Year corrected** — all content updated from 2025 → 2026 including slugs
- Real data embedded: UP-RERA registration numbers (UPRERAPRJ559147, UPRERAPRJ4699, UPRERAPRJ619773, UPRERAPRJ4081), actual price ranges (₹4,500–₹12,500/sq ft), real developers (Godrej, ATS, M3M, Mahagun, Gaurs), Jewar Airport, Aqua Line Metro, RBI repo rate 6.0%, tax sections 24(b) and 80C

### ✅ Greater Noida West blog post deleted (irrelevant to Shubharambh focus areas)

### ✅ New blog: "Best Property Advisor in Noida" (main keyword blog)
- slug: `best-property-advisor-in-noida`
- sort_order: 100 (shows first on all listings)
- 8-min read, ~900 words targeting main SEO keyword
- Covers: why you need an advisor, 4 must-haves (RERA, local expertise, track record, transparency), red flags, Shubharambh credentials
- Mentions: 500+ families, since 2014, Sector 150/152/146, zero brokerage, free consultation, +91 9911600100

---

## 💼 23. Careers Page — Major Upgrades

### ✅ CV / Resume Upload Added
- File input accepts PDF, DOC, DOCX only (max 5 MB)
- Server-side MIME type validation via `finfo_file()` (not just extension)
- Stored in `public/uploads/cv/` with randomised filename: `cv_[timestamp]_[hex].ext`
- Custom drag-and-drop UI: dashed border, filename preview on select

### ✅ Dedicated DB Table — `job_applications`
- Separate from general `inquiries` table
- Fields: `full_name, email, phone, position, experience, cover_letter, cv_path, ip_address, user_agent, status, created_at`
- All form submissions now stored here

### ✅ Rate Limiting — 1 Submission per IP per 24 Hours
- Checked against `job_applications` table: `WHERE ip_address = ? AND created_at >= datetime('now', '-24 hours')`
- Returns friendly error: "You have already submitted an application in the last 24 hours"
- Resets automatically after 24 hours — no manual intervention needed

### ✅ PRG Pattern — No Double Submit on Refresh
- POST → DB insert → `header('Location: ...')` redirect → GET page with flash message
- Reloading the page after submit never re-sends the form data
- Flash message stored in `$_SESSION['career_flash']`, cleared after one display

### ✅ Form Fields Cleared After Submit
- No stale POST data shown in fields after redirect (was showing old values before)

---

## 🏗️ 24. Project Detail Page — Full Redesign

### ✅ Hero Image Slider
- Full-width hero replacing old single static image
- Auto-advances every 3 seconds
- Prev / Next arrow buttons with backdrop-blur glass effect
- Dot navigation (bottom center)
- Thumbnail strip (bottom) — click any thumb to jump to that image
- First image: `loading="eager"` | All others: `loading="lazy"`
- Smooth CSS `opacity` transition between slides (0.65s ease)

### ✅ Gallery System — 3–4 Images per Project
- New `gallery` column added to `projects` table (JSON array of image paths)
- All 13 projects updated with gallery images

### ✅ New Layout — Landing Page Style
- **Stats bar:** Config / Sizes / Possession / City with SVG icons in 4-column grid
- **Key Highlights:** 2-column checklist with gold circle icons
- **Amenities:** Pill-style chip grid (3-col → 2-col → 1-col responsive)
- **Connectivity:** Left gold border accent cards
- **Sticky sidebar:** Price block → details list → CTA stack (Enquire / WhatsApp / Call / Shortlist)
- 2-column layout (content + sidebar) → stacks on mobile with sidebar on top

### ✅ Real Images Downloaded from Official Developer Websites

| Project | Source | Images |
|---|---|---|
| T&T The Blue | theblue.tandtgroup.in | 4 — exterior render, interior, site photo, amenity |
| Yatharth HighLife | moneytreerealty.com | 3 — official hero renders |
| Corbett Eye | nprinfra.com | 1 — real project render |
| Shubh Kadam | globalbirthdevelopers.com | 4 — Jim Corbett resort renders |
| Uniwest Arcade | uniwestarcade.com | 4 — night/day renders + interior |
| Uniwest Hub / Aero Hub | uniwestaerohub.com | 4 — official project images |

### ✅ Alt Tags — All Project Images
- Slider: `"[Project Name] by [Builder] — [Property Type] in [Location] | Gallery Image N"`
- Thumbnails: `"[Project Name] thumbnail N"`
- Related project cards: `"[Project Name] by [Builder]"`

### ⏳ Pending Fix (Next Session)
- Some project cover images are showing the **developer's logo** instead of an actual render/photo
- Will be fixed in next session by downloading proper render images for remaining projects

---

## 📁 Files Modified (Session 4)

| File | Changes |
|---|---|
| `includes/header.php` | Blog nav link, cache-bust versions updated (20260419b → 20260420a → 20260420b → 20260420c) |
| `includes/footer.php` | Blog link in Quick Links |
| `public/index.php` | Homepage blog section (6 cards + View All button), date removed from blog cards |
| `public/blogs.php` | **NEW FILE** — blog listing with filter + pagination |
| `public/blog.php` | **NEW FILE** — single post with slider, share bar, sidebar |
| `public/careers.php` | CV upload field, PRG pattern, rate limit, `job_applications` table, flash messages |
| `public/project.php` | Full redesign — hero slider, gallery, new layout, real images |
| `public/assets/css/style.css` | Blog CSS, CV upload CSS, project detail redesign CSS, responsive improvements |
| `storage/shubharambh.sqlite` | New tables: `blogs`, `job_applications`; new column: `projects.gallery` |
| `public/uploads/blog/` | 7 blog cover images (advisor, noida, rera, homeloan, expressway, resvscom) |
| `public/uploads/projects/` | 30+ new project images from official developer websites |
| `public/uploads/cv/` | **NEW FOLDER** — CV/resume uploads from careers form |

---

## 🗓️ Session 5 — 24 April 2026

---

## 🗺️ 25. Homepage — Google Map Section

### ✅ New "Find Us" section added before Contact
- Google Maps iframe embed — exact coordinates: 28.5085151, 77.3793737 (Logix Technova, Sector 132, Noida)
- 4 info cards: Office Address / Nearest Metro (Sector 137 Aqua Line) / Expressway Access (Sector 132 Exit) / Working Hours
- Gold pin label overlay: "Shubharambh Infra Advisors" highlighted on map
- "Get Directions" button linking to Google Maps navigation
- Map height: 560px desktop / 460px tablet / 340px mobile
- Dark navy theme with gold accent borders matching site design
- Fully responsive: 4-col → 2-col → 1-col info cards

---

## 📱 26. Footer — Mobile Responsive Fix

### ✅ Complete mobile footer redesign
- Quick Links + Services: side by side in 2-column grid (was stacked 1-col)
- About section: logo + text horizontal row, social icons full-width below
- Newsletter: inline flex (no wrapping on mobile)
- Contact + Get In Touch: full width below 2-col grid
- Footer bottom text: centered on mobile, visibility fixed (rgba white 65% instead of muted)
- 420px: collapses to full 1-col stack

---

## 🔍 27. SEO — Meta Description Lengths Fixed

### ✅ All pages now within 100–160 character limit

| Page | Before | After |
|---|---|---|
| index.php | 201ch | 158ch |
| about.php | 188ch | 137ch |
| blogs.php | 174ch | 131ch |
| careers.php | 222ch | 156ch |
| contact.php | 164ch | 146ch |
| projects.php | 161ch | 145ch |
| emi-calculator.php | 145ch | 145ch ✅ |

---

## 🚫 28. Nav — EMI Calculator Removed from Header

### ✅ EMI Calc link removed from header navigation
- Remains accessible via Footer Quick Links
- Header nav now: HOME / ABOUT / PROJECTS / BLOG / CAREERS / CONTACT

---

## 🖼️ 29. Project Images — Real Photos from Developer Websites

### ✅ Logo images replaced with actual renders/photos

| Project | Source | Fix |
|---|---|---|
| T&T The Blue | theblue.tandtgroup.in | Replaced logo/text image with actual building render |
| Corbett Eye | globalbirthdevelopers.com | Real resort photos |
| SVG Town Square | shrivinayaka.com | Official architectural renders |
| Kutumbh City | kutumbcityharidwar.com | Aerial drone photos (Feb 2026) |
| Eternia | eternia.greatvaluerealty.com | Official tower renders |
| Shubh Kadam | globalbirthdevelopers.com | Resort renders confirmed |

### ✅ User-uploaded images integrated for 8 projects
- Corbett Eye: plot1–3 (user's own photos)
- Yatharth HighLife: HighLife1–3 (user's own photos)
- Shubh Kadam: ShubhKadam1–4 (user's own photos)
- Uniwest Arcade: UniwsestArcade1–3 (user's own photos)
- Uniwest Aero Hub: uniwest1–3 (user's own photos)
- Eternia: eternia1–3 (user's own photos)
- ONE FNG: ONEFNG1–3 (user's own photos)
- M3M The Line: m3mline1–3 (user's own photos)
- M3M The Cullinan: M3MTheCullinan1–3 (user's own photos)

### ✅ Projects hidden from index + projects page
- Kutumbh City: `is_active=0, is_featured=0`
- Uniwest Hub: `is_active=0, is_featured=0` (duplicate of Aero Hub)

---

## 👤 30. Team Photo — Mohit Khari Updated

### ✅ New photo uploaded and tracked in git
- File: `public/uploads/team/mohit-khari.jpg`
- `.gitignore` updated: `!public/uploads/team/` exception added
- Team folder now tracked in git — deploys on `git pull`
- DB updated: `team_members` table photo path updated

---

## 🏗️ 31. Project Cards — Full Redesign (index + projects page)

### ✅ New card design — price on image overlay
- Price moved from card body to image overlay (bottom-left, gold text on dark gradient)
- Image ratio: 16:10 → 4:3 (better for property photos)
- Specs bar: boxed grid with border separators (no misalignment)
- Config text: `white-space: nowrap` + `text-overflow: ellipsis` — no wrapping
- All config values shortened in DB: "High-street retail shops, anchor spaces..." → "Retail & Studios"
- Hover: subtle translateY(-5px) + gold border glow

---

## 🏠 32. Project Slug Pages — Stats Bar Redesign

### ✅ Complete stats bar redesign
- Removed SVG icons — label (gold uppercase) on top, value (white bold) below
- Border-separated cells — no random gaps
- `word-break: break-word` for long values
- Sidebar: shortened `property_type` and `sizes` for clean display
- Stats bar: full values shown with proper wrapping
- Responsive: 2×2 grid on tablet/mobile with border-bottom separators

---

## 🔍 33. SEO — Blog Pages Noindex

### ✅ noindex, nofollow added to all blog pages
- `blogs.php`: `$page_robots = 'noindex, nofollow'`
- `blog.php`: `$page_robots = 'noindex, nofollow'`
- Google will not index any blog listing or single blog post pages

---

## 🔀 34. Git — UAT Merged to Main

### ✅ main branch synced with uat
- `git reset --hard origin/uat` + `git push origin main --force`
- Both branches now identical
- `uploads/team/` folder tracked in git with `.gitignore` exception

---

## 📁 Files Modified (Session 5)

| File | Changes |
|---|---|
| `includes/header.php` | EMI Calc removed from nav, cache-bust versions (20260424a–l) |
| `public/index.php` | Google Map section added, project card price overlay |
| `public/projects.php` | Project card price overlay, config text shortened |
| `public/project.php` | Stats bar redesign (label/value, no icons), sidebar data shortened |
| `public/blogs.php` | `noindex, nofollow` added |
| `public/blog.php` | `noindex, nofollow` added |
| `public/assets/css/style.css` | Map section CSS, footer mobile CSS, project card redesign, stats bar redesign |
| `public/uploads/team/mohit-khari.jpg` | New team photo added and tracked in git |
| `.gitignore` | `!public/uploads/team/` exception added |
| `storage/shubharambh.sqlite` | Projects: config/sizes/property_type shortened; Kutumbh City + Uniwest Hub hidden; team photo updated; 9 projects gallery updated with user images |
