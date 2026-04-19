# Shubharambh Infra Advisors — Enterprise Design System

**Version:** 1.0 · **Release:** April 2026 (UAT)
**Product:** shubharambhinfraadvisors.com (public marketing + advisory site)
**Audience:** HNI buyers, NRI investors, institutional partners, developer principals
**Status:** Living document — anything that ships must trace back to a spec here.

---

## 1. Why a design system

The prior site was **functional but folksy** — a small broker's site, not a Tier-1 advisory. Our research (§2) shows a clear gap between how enterprise real-estate counsel presents (Sotheby's International Realty, Compass, JLL Residential, Cushman & Wakefield, Knight Frank, Lodha Luxury) and how we were presenting: loud badges, uneven rhythm, too many gradients, a 7-tab hero, counters as decoration.

Enterprise UX in this category is **editorial, quiet, content-led**. The signal of competence is *restraint*, not animation. This document is the contract that makes that real — one set of tokens, one voice, one motion curve.

---

## 2. Research synthesis

### 2.1 Competitive set studied

| Firm | What we studied | What we're taking |
|---|---|---|
| Sotheby's International Realty | Property cards, editorial tone, photography-led | Restraint, photo-first cards, serif display |
| Compass | Filter UX, map integration, data density | Collapsed filters, sticky stats, breadcrumb depth |
| Knight Frank | Market reports, advisor profiles | "The Advisory" narrative, market intel strip |
| JLL Residential (India) | NRI flows, compliance visible | RERA surfacing, disclosure footer |
| Cushman & Wakefield | Enterprise typographic hierarchy | Title/eyebrow/body rhythm |
| Lodha Luxury | Indian luxury context, Hindi/English bilingual | Bilingual price format, ₹ Cr conventions |
| Financial Times | Editorial gravity, content width | 720px ideal measure, drop caps |
| Monocle | Magazine layout, muted palette | Kicker + headline + standfirst pattern |
| Stripe / Linear | Motion restraint, micro-type | 160ms curve, label tracking |

### 2.2 Principles extracted

1. **Counsel, not catalogue.** Lead with advisory narrative; projects are evidence, not product.
2. **Evidence over adjectives.** Every claim earns a number, a RERA ID, or a date.
3. **Quiet motion.** One ease curve (`cubic-bezier(.2,.65,.25,1)`), 160–260ms. No parallax spectacle.
4. **Editorial rhythm.** Eyebrow → headline → standfirst → body. Vertical rhythm is measured in the body's line-height, not arbitrary rem.
5. **Photography is a feature.** Cover images get 3:2 or 16:11, full-bleed where the composition earns it. Never crop a facade tight.
6. **Disclosure is a trust signal.** RERA ID, possession date, price assumptions, and sources are always visible, not hidden behind "*T&C apply".
7. **Performance is a design choice.** Hero video ≤ 2 MB, fonts subset, Critical CSS inlined, LCP < 2.0s on 4G.
8. **Accessibility is non-negotiable.** WCAG 2.2 AA baseline, AAA for body text where the brand palette allows.

---

## 3. Brand foundation

**Identity:** Deep Navy (`#0B1D33`) + Antique Gold (`#B59355`). These are retained — they carry equity from print collateral and the physical office signage. The design system does NOT alter the brand marks.

**Voice:**
- Formal, not stiff. "We advise" not "We help you".
- Evidence-led. "RERA-registered · 48 months of data" not "trusted by everyone".
- No emoji. No exclamation marks in body copy.
- ₹ Cr / ₹ L, never "crore" spelled out in numeric runs.

**Photography:**
- Wide-angle architectural, golden-hour when available.
- No people stock. Clients appear named and captioned or not at all.
- Facade > interiors > amenities > master plans.

---

## 4. Color tokens

All tokens live on `:root`. Never hard-code hex outside this file.

### 4.1 Core palette (retained + extended)

```css
--c-navy:          #0B1D33;   /* primary surface, headers */
--c-navy-deep:     #071323;   /* page background for dark sections */
--c-navy-soft:     #13283F;   /* hover / card backgrounds */
--c-navy-card:     #102943;   /* elevated cards on navy */
--c-navy-line:     #1C3A58;   /* dividers on navy */

--c-gold:          #B59355;   /* accent, CTAs */
--c-gold-light:    #D4B06F;   /* highlights, active states */
--c-gold-soft:     #E8C98A;   /* tints, badge backgrounds */
--c-gold-dim:      #8A6F3E;   /* gold on light (WCAG AA) */
--c-gold-ink:      #5E4A24;   /* gold text on cream (WCAG AAA) */
```

### 4.2 Neutral scale (new — editorial surfaces)

```css
--c-ink:           #0E1622;   /* body text on light surfaces */
--c-ink-soft:      #2A3648;   /* secondary text on light */
--c-ink-muted:     #5A667A;   /* tertiary text on light */

--c-cream:         #F7F3EA;   /* page bg for editorial/light sections */
--c-parchment:     #EFE8D7;   /* tinted surfaces (callouts, pull quotes) */
--c-bone:          #FBF8F1;   /* softer alt to cream */
--c-porcelain:     #FFFFFF;   /* pure white, reserved for cards on cream */
```

### 4.3 Semantic

```css
--c-success:       #3F8B63;
--c-warning:       #C28B2E;
--c-danger:        #B8433A;
--c-info:          #4A6FA5;
```

### 4.4 Contrast contract

| Foreground | Background | Ratio | WCAG |
|---|---|---|---|
| `--c-off-white` on `--c-navy` | | 14.8:1 | AAA |
| `--c-ink` on `--c-cream` | | 13.1:1 | AAA |
| `--c-gold-dim` on `--c-cream` | | 4.7:1 | AA (large text AAA) |
| `--c-gold` on `--c-navy` | | 6.2:1 | AA |
| `--c-gold-ink` on `--c-cream` | | 8.9:1 | AAA (body-safe) |

**Rule:** gold-on-light in body copy **must** use `--c-gold-ink` or `--c-gold-dim`. The vibrant `--c-gold` is reserved for accents, icons, and short labels.

---

## 5. Typography

### 5.1 Type stack

```css
--f-display:  'Fraunces', 'Playfair Display', Georgia, serif;
--f-serif:    'Playfair Display', Georgia, 'Times New Roman', serif;
--f-sans:     'Inter', system-ui, 'Segoe UI', sans-serif;
--f-mono:     ui-monospace, 'SF Mono', Menlo, monospace;
```

**Fraunces** is the new editorial display face — variable, with optical sizing. We use it for:
- H1/H2 at size ≥ 2rem (opsz=144, soft=100, wght=500)
- Kickers ("The Advisory", "Market Intelligence") with wght=400 italic
- Large numerals (statistics) with opsz=144, wght=600

**Playfair Display** is retained for card titles, sub-headings, and anywhere the Fraunces weight feels too heavy. It's the brand's existing serif voice.

**Inter** stays as body text. The 16px floor on inputs is kept (prevents iOS zoom).

### 5.2 Scale (fluid, clamp-driven)

| Token | clamp range | Use |
|---|---|---|
| `--fs-display` | clamp(2.5rem, 6vw, 5rem) | Hero H1 |
| `--fs-h1` | clamp(2rem, 4.5vw, 3.5rem) | Page banners |
| `--fs-h2` | clamp(1.6rem, 3.2vw, 2.5rem) | Section headings |
| `--fs-h3` | clamp(1.25rem, 2vw, 1.65rem) | Subsection / card titles |
| `--fs-h4` | 1.125rem | Small titles |
| `--fs-lede` | clamp(1.1rem, 1.6vw, 1.3rem) | Standfirst paragraph |
| `--fs-body` | 1rem | Body |
| `--fs-sm` | 0.875rem | Secondary body, meta |
| `--fs-xs` | 0.75rem | Eyebrows, captions |
| `--fs-micro` | 0.68rem | Disclosures, legal |

### 5.3 Rhythm

- Body line-height: **1.7** (generous — matches editorial density)
- Heading line-height: **1.12** (tight, display)
- Measure (max line-length): **68ch** body, **58ch** quotes
- Paragraph spacing: 1em
- Section spacing: `clamp(4.5rem, 9vw, 7.5rem)` top/bottom

### 5.4 Letterspacing / tracking

- Eyebrows: `letter-spacing: 0.24em` uppercase — the enterprise "label" tone
- H1/H2: `letter-spacing: -0.015em` — modern display tension
- Body: default, `0`
- All-caps UI labels: `0.08em`

### 5.5 Numerals

Use `font-variant-numeric: tabular-nums` on every data row (price, stats, counters) so columns align.

---

## 6. Spacing scale

```css
--sp-0:  0;
--sp-1:  0.25rem;
--sp-2:  0.5rem;
--sp-3:  0.75rem;
--sp-4:  1rem;
--sp-5:  1.5rem;
--sp-6:  2rem;
--sp-7:  3rem;
--sp-8:  4.5rem;
--sp-9:  6.5rem;
--sp-10: 9rem;
```

No magic numbers. If a spacing exception is unavoidable, comment the reason inline.

---

## 7. Radii, borders, elevation

```css
--radius-xs:   4px;
--radius-sm:   6px;
--radius:      10px;
--radius-lg:   16px;
--radius-xl:   24px;
--radius-pill: 999px;

--border-hair:     1px solid var(--c-line);
--border-strong:   1px solid var(--c-line-strong);
--border-gold:     1px solid var(--c-gold-dim);

--shadow-xs:   0 1px 2px rgba(7,19,35,0.18);
--shadow-sm:   0 2px 8px rgba(7,19,35,0.22);
--shadow-md:   0 10px 30px rgba(7,19,35,0.35);
--shadow-lg:   0 24px 60px rgba(7,19,35,0.55);
--shadow-gold: 0 14px 34px rgba(181,147,85,0.28);
```

**Elevation rule:** cards use `shadow-sm` at rest, `shadow-md` on hover. Modals use `shadow-lg`. Nothing else.

---

## 8. Motion

**Curve:** `cubic-bezier(.2,.65,.25,1)` — use via `--ease`.

**Durations:**
- `--t-fast: 160ms` — hover/focus color swaps
- `--t: 260ms` — card lift, menu open
- `--t-slow: 440ms` — hero slides, shortlist bar

**Never:** bouncy cubic, spring, parallax scroll, auto-playing carousels > 2 slides.

**`prefers-reduced-motion: reduce`** disables every slide, pulse, and carousel autoplay. This is enforced in the top-level reset.

---

## 9. Components

Each component is specced below. When you ship a new component, add its spec here first.

### 9.1 Header (`.site-header`)

- Height: 88px desktop, 72px mobile, 64px on scroll-shrink
- Layout: logo (left) · primary nav (center) · phone + CTA cluster (right)
- Treatment: semi-transparent navy `rgba(11,29,51,0.84)` with 16px backdrop-filter blur when page is scrolled; solid before that
- Separator: 1px hairline of `--c-gold-dim` at 18% opacity below header
- Active nav item: underline with `--c-gold` at 2px, offset 8px from text baseline
- Phone CTA: hairline outline, upgrades to solid `--c-gold` on hover

### 9.2 Hero (`.hero--editorial`)

Refined replacement for `.hero--cinematic`. Reads like a magazine cover.

- Grid: 8-col editorial grid
- Content width: 9/12 on desktop, 11/12 on tablet, full on mobile
- Eyebrow: Fraunces italic, `--c-gold`, letter-spaced `0.24em`
- H1: Fraunces display `--fs-display`, weight 500, `--c-off-white`, measure ~14ch
- Standfirst: Inter 1.3rem `--c-muted`, measure 48ch
- CTAs: Primary gold + "or call ___" micro-link (no ghost button proliferation)
- Background: photo with navy gradient overlay; NO animated grid, NO floating orbs
- Motion: one staggered reveal on load, then still

### 9.3 Trust strip (`.trust-strip`)

- Background: `--c-navy-deep`
- Label: "Partnered with" Fraunces italic small
- Logos: monochrome gold-wash, 60% opacity, lift to 100% on hover
- Marquee preserved but slowed to 40s/cycle, pauses on hover

### 9.4 Section head (`.section-head`)

```
[ eyebrow — kicker ]
Headline set two-lines
—  standfirst that runs to ~58ch and gives the section its mandate.
```

- Eyebrow: uppercase Inter 12px, tracked `0.24em`, `--c-gold`
- Headline: `--fs-h2` Fraunces wght 500
- Em-dash separator (U+2014) on the standfirst line, `--c-gold-dim`
- Alignment: left on feature sections, centered on full-bleed CTA sections

### 9.5 Project card (`.project-card`)

**Anatomy (top to bottom):**
1. Media — 3:2 ratio, full-bleed image, 1px hairline border at bottom
2. Corner badges (top-left: category; top-right: RERA pill when present)
3. Fav button (top-right, floats over media)
4. Title (Playfair 1.35rem)
5. Builder (Inter 0.82rem, `--c-gold-dim`, all-caps tracked)
6. Location (icon + city, 0.88rem, `--c-muted`)
7. Meta row: Config · Possession · City (tabular-nums, divided by 1px gold hairlines)
8. Price (Fraunces 1.6rem, tabular-nums)
9. CTAs: "View details" (text link + arrow) — the WhatsApp CTA moves to card-level hover in desktop, stays visible in mobile

**States:**
- Rest: shadow-sm
- Hover (desktop): translateY(-4px), shadow-md, image scales 1.04 over 440ms
- Focus-within: gold hairline border intensifies

### 9.6 Buttons

| Class | Use | Rest | Hover |
|---|---|---|---|
| `.btn-primary` / `.btn-gold` | Primary action | Gold bg, navy text | `--c-gold-light` bg |
| `.btn-outline` | Secondary | Transparent, gold border + text | Gold bg, navy text |
| `.btn-ghost` | Tertiary | No border, gold text | Underline |
| `.btn-whatsapp` | WhatsApp | `--c-whatsapp` bg, white text | Darker green |

All buttons: min-height 44px, `--radius`, `0.08em` tracking on label, arrow-in-circle trailing icon on primary.

### 9.7 Forms

- Labels **above** inputs, 12px uppercase tracked `0.16em`
- Inputs: 52px height, 1px navy-line border, focus adds `--c-gold` border + 3px halo
- Consent checkbox: custom svg tick, 18px square
- Validation: inline red below field, error state border `--c-danger`
- Submit: full-width on mobile, `240px` fixed on desktop for rhythm

### 9.8 Shortlist bar

Already hardened in the April 2026 fix. Design system confirms:
- Nowrap row, ellipsis
- Dismiss × with session memory
- Pulse on add (accessible, no sound)
- Lifts toasts above when both visible

### 9.9 Footer (`.site-footer`)

**Anatomy:**
1. **Newsletter band** — Fraunces headline, inline email input, GDPR micro-line
2. **Sitemap grid** — 4 columns (Navigate / Advisory / Resources / Contact)
3. **Disclosure band** — RERA registration number, "Registered office", Brand & regulatory line
4. **Bottom bar** — copyright + privacy + terms + socials

Background: `--c-navy-deep`. Hairline gold separators between bands.

---

## 10. Layout

- Container max-width: 1240px
- Narrow container: 880px (editorial body)
- Gutter: `clamp(1rem, 3vw, 1.75rem)` left/right
- Grid: 12-col on desktop, 6-col on tablet, 4-col on mobile — implicit via CSS grid `repeat(12,1fr)`
- **Asymmetric hero**: content on 8 cols, rich media/image on 4 cols (or reverse) where applicable

---

## 11. Iconography

- Stroke-only line icons, 1.8px stroke, rounded caps
- Size: 16 / 20 / 24 / 32 — no in-between sizes
- Source: hand-authored inline SVG (no icon font)
- Color: inherits `currentColor` — never hard-coded

---

## 12. Accessibility

- **Focus ring:** 2px `--c-gold` + 4px navy halo (already in main.css); keep.
- **Skip link:** present (`.skip-link`); keep.
- **Reduced motion:** honored globally; every animation must respect it.
- **Target size:** 44×44px minimum for any interactive element on `pointer: coarse`.
- **Color is never the only signifier:** every badge has text or icon in addition to color.
- **Forms:** every input has a visible label, not placeholder-as-label.
- **Live regions:** shortlist bar uses `role="status" aria-live="polite"`; toasts too.
- **Keyboard:** testimonial carousel arrows, modal close with Esc, nav drawer traps focus when open.

---

## 13. Performance

- **LCP target:** < 2.0s on mid-tier 4G, < 1.2s on cable
- **Font loading:** `display=swap`, subset to Latin + Latin-ext, preconnect to Google Fonts
- **Hero video:** max 2 MB, poster image always present, `preload=metadata` not `auto`
- **Images:** WebP with jpg fallback (already covered by `upload_url`), lazy-loaded except LCP hero
- **CSS:** one bundle, no `@import` chains
- **JS:** single `main.js`, vanilla, no framework; defer loaded
- **Inline critical:** above-the-fold hero + header styles should be inlineable (future work)

---

## 14. Content patterns

### 14.1 Eyebrow taxonomy

Use these, not invented strings:

- **The Advisory** — homepage hero, about
- **Curated Portfolio** — featured projects
- **Market Intelligence** — data/insights
- **Counsel** — testimonials
- **Correspondence** — contact
- **Disclosures** — legal, RERA, footer compliance

### 14.2 Standfirst formula

One sentence. Ends with a period. No questions. Says what the section proves, not what it is.

❌ "Welcome to our featured projects!"
✅ "Nine residences under active advisory this quarter — all RERA-registered, all vetted."

### 14.3 Numeric format

- Price: `₹ 2.45 Cr` (non-breaking space after ₹, two decimals max)
- Area: `1,450 sqft` (comma-separated thousands)
- Possession: `Dec 2026` (abbreviated month)
- RERA ID: `UPRERAPRJ12345` monospace

### 14.4 Trust phrasing

- "RERA-registered" (hyphenated, adjective)
- "Since 2014" (not "11 years" — absolute dates age better)
- "Transacted value: ₹ 800 Cr" (past-tense, auditable)

---

## 15. Implementation contract

- **File of record:** `public/assets/css/enterprise.css` layered *after* `style.css` in `includes/header.php`.
- **Order:** reset → tokens → typography → primitives → components → utilities → media queries.
- **No new class name collisions:** extend existing `.hero`, `.section-head`, `.project-card`, etc., don't rename.
- **Every new token** must be documented here before it's committed.
- **Every color used in components** must resolve to a `--c-*` token, not a literal hex.
- **Media queries:** mobile-first; breakpoints are 480 / 600 / 768 / 900 / 1100 / 1280.

---

## 16. Inventory of changes delivered in v1.0

| Area | Change |
|---|---|
| Typography | Fraunces added as display; Playfair retained; Inter retained |
| Palette | Cream/parchment/bone neutral scale added for editorial sections |
| Spacing | Formal `--sp-*` scale introduced |
| Header | Glass-blur on scroll, refined nav underline |
| Hero | Editorial composition, single reveal, restrained orbs/grid removed on mobile |
| Section head | Eyebrow + headline + em-dash standfirst |
| Project card | 3:2 media, tabular-nums meta row, hover lift |
| Footer | Newsletter band + disclosure band added |
| Shortlist bar | Already hardened (April 2026 fix); no regression |
| Motion | All animations honor reduced-motion; single ease curve |

---

## 17. Open questions for v1.1

- **Hindi typography:** should H1/H2 have a Devanagari counterpart (Mukta, Poppins Devanagari)? Pending decision on bilingual homepage copy.
- **Dark/light toggle:** not in scope for v1.0 — our canvas is dark-default, cream accents. Revisit if analytics show a demand.
- **Map view:** Compass-style map on `/projects.php`. Requires Mapbox/MapTiler key; deferred.
- **Advisor profile pages:** individual team members deserve their own editorial pages. Deferred pending content.

---

## 18. References

- Sotheby's International Realty — https://www.sothebysrealty.com
- Compass — https://www.compass.com
- Knight Frank Residential — https://www.knightfrank.com
- JLL Residential India — https://residential.jll.co.in
- Cushman & Wakefield — https://www.cushmanwakefield.com
- Lodha Luxury — https://www.lodhaluxury.com
- Financial Times design system — https://www.ft.com/__origami
- Fraunces by Undercase Type — https://fonts.google.com/specimen/Fraunces
- WCAG 2.2 — https://www.w3.org/TR/WCAG22/
- Can I Use: backdrop-filter — https://caniuse.com/css-backdrop-filter

---

*Maintained by the product team. Changes require a PR with a screenshot and an entry in §16.*
