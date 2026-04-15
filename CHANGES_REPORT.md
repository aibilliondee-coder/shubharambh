# Shubharambh Infra Advisors — Project Changes Report

**Branch:** `uat`
**Prepared by:** Claude (AI Assistant)
**Last Updated:** April 2026
**Live Site:** https://shubharambhinfraadvisors.com/

---

## Overview

This report documents all changes, upgrades, and optimizations made to the project on the UAT (User Acceptance Testing) branch. Changes are safe, testable, and ready to be reviewed before deploying to production.

---

## Session 1 — SEO Image Optimization + Performance (April 2026)

### What Was Done
A full audit of every `<img>` tag across all PHP files was performed — both in the local codebase and on the live website. Two types of improvements were applied to every image:

1. **SEO-optimized `alt` attributes** — descriptive, keyword-rich text for Google Image Search and accessibility
2. **`loading="lazy"`** — defers off-screen images so the page loads faster (improves Core Web Vitals / PageSpeed score)

---

### Files Changed

#### 1. `includes/header.php` — Site Header Logo
| Attribute | Before | After |
|-----------|--------|-------|
| `alt` | `Shubharambh Infra Advisors` | `Shubharambh Infra Advisors — RERA-Registered Real Estate Consultancy in Delhi NCR` |
| `loading` | ❌ Missing | `lazy` |

---

#### 2. `includes/footer.php` — Footer Logo
| Attribute | Before | After |
|-----------|--------|-------|
| `alt` | `Shubharambh Infra Advisors` | `Shubharambh Infra Advisors — Luxury Residential & Commercial Real Estate Advisory, Delhi NCR` |
| `loading` | ❌ Missing | `lazy` |

---

#### 3. `public/about.php` — CEO / Founder Photo
| Attribute | Before | After |
|-----------|--------|-------|
| `alt` | `Mr. Mohit Khari` | `Mr. Mohit Khari — Founder, Shubharambh Infra Advisors, Real Estate Expert Delhi NCR` |
| `loading` | ❌ Missing | `lazy` |

> Note: The title (e.g. "Founder", "MD") is pulled dynamically from the database so it stays accurate automatically.

---

#### 4. `public/index.php` — 3 Images on Homepage

**Partner / Developer Logos (Trust Strip)**
| Attribute | Before | After |
|-----------|--------|-------|
| `alt` | `M3M Group` *(just the name)* | `M3M Group — Trusted Real Estate Developer Partner of Shubharambh Infra Advisors` |
| `loading` | ✅ Already present | ✅ Retained |

> Applies to all partner logos (M3M, SVG Group, Global Birth, Uniwest, Group 108, etc.) since it is a PHP loop.

**Featured Project Card Cover Images**
| Attribute | Before | After |
|-----------|--------|-------|
| `alt` | `M3M The Cullinan` *(just the name)* | `M3M The Cullinan by M3M Group — Residential in Noida, Delhi NCR \| Shubharambh Infra Advisors` |
| `loading` | ✅ Already present | ✅ Retained |

> Applies to all featured project cards dynamically. Builder, property type, and city are fetched from the database automatically.

**CEO Photo (About Section on Homepage)**
| Attribute | Before | After |
|-----------|--------|-------|
| `alt` | `Mr. Mohit Khari` | `Mr. Mohit Khari — Founder, Shubharambh Infra Advisors, Real Estate Expert Delhi NCR` |
| `loading` | ❌ Missing | `lazy` |

---

#### 5. `public/project.php` — Individual Project Detail Page

**Hero / Cover Image (Above the Fold)**
| Attribute | Before | After |
|-----------|--------|-------|
| `alt` | `M3M The Cullinan` | `M3M The Cullinan by M3M Group — Residential in Sector 150, Noida \| Buy Property Delhi NCR` |
| `loading` | `eager` ✅ | `eager` ✅ — **intentionally kept** |

> `loading="eager"` is correct here. This is the first visible (LCP) image on the page. Making it lazy would hurt Google PageSpeed / Core Web Vitals score.

**Related / Similar Project Cards**
| Attribute | Before | After |
|-----------|--------|-------|
| `alt` | `M3M The Line` *(just name)* | `M3M The Line by M3M Group — Commercial in Noida \| Shubharambh Infra Advisors` |
| `loading` | ✅ Already present | ✅ Retained |

---

#### 6. `public/projects.php` — All Projects Listing Page

**Project Card Cover Images**
| Attribute | Before | After |
|-----------|--------|-------|
| `alt` | `ONE FNG` *(just name)* | `ONE FNG by Paras Buildtech — Commercial in Noida, Delhi NCR \| Shubharambh Infra Advisors` |
| `loading` | ✅ Already present | ✅ Retained |

> Applies to all project cards (M3M The Cullinan, M3M The Line, ONE FNG, Eternia, Kutumbh City, SVG Town Square, Shubh Kadam, Corbett Eye, etc.) — all dynamic via PHP loop.

---

### Live Site vs UAT Gap

The live site (`shubharambhinfraadvisors.com`) was also audited. At time of audit, the production site was running the **old code** with short, non-optimized alt tags and no lazy loading on most images.

| Issue Found on Live Site | Fixed in UAT? |
|--------------------------|--------------|
| No `loading` attribute on 14 out of 16 homepage images | ✅ Fixed |
| Logo alt text too short — no keywords | ✅ Fixed |
| Partner logo alt text — no context | ✅ Fixed |
| Project card alt text — name only, no builder/city/type | ✅ Fixed |
| CEO photo alt text — name only, no role/brand | ✅ Fixed |
| Project detail hero alt text — too short | ✅ Fixed |

**Action Required:** Merge `uat` → `main` and deploy to make all fixes live.

---

### SEO Keywords Used in Alt Attributes

The following real-estate SEO keywords were woven into image alt text across the site:

- `RERA-Registered Real Estate Consultancy`
- `Delhi NCR`
- `Luxury Residential`
- `Commercial Real Estate Advisory`
- `Real Estate Expert Delhi NCR`
- `Trusted Real Estate Developer Partner`
- `Buy Property Delhi NCR`
- `Shubharambh Infra Advisors` *(brand reinforcement)*
- Builder names: `M3M Group`, `SVG Group`, `Paras Buildtech`, `Uniwest`, etc.
- City names: `Noida`, `Greater Noida`, `Gurgaon`

---

### No Breaking Changes

- No layout, design, or functionality was changed
- No scripts, classes, or IDs were removed
- `onerror` fallback handlers were preserved on all project images
- `width` and `height` attributes were preserved on all logo images
- `loading="eager"` preserved on the project detail page LCP image

---

---

## Session 2 — On-Page SEO Optimization (April 2026)

### What Was Done
A full on-page SEO audit was performed across all PHP files. Only files with genuine problems were changed — pages that were already well-optimized were left untouched (e.g. `about.php`, `emi-calculator.php`).

**Pages audited:** `index.php`, `about.php`, `projects.php`, `project.php`, `contact.php`, `emi-calculator.php`, `privacy-policy.php`, `terms.php`, `404.php`, `includes/header.php`

---

### File 1: `includes/header.php` — Global Template (affects ALL pages)

**Problems found:**
- No `<meta name="robots">` tag anywhere on the site — Google had no crawl directive
- No `<link rel="canonical">` — risk of duplicate content from URL variations (`?q=`, `?sort=`, etc.)

**Changes made:** Added two new variables + two new tags in `<head>`:

```php
// New variables (pages can override before including header)
$page_canonical = $page_canonical ?? url($_SERVER['REQUEST_URI'] ?? '/');
$page_robots    = $page_robots    ?? 'index, follow';
```

```html
<!-- Added to <head> -->
<meta name="robots" content="<?= e($page_robots) ?>">
<link rel="canonical" href="<?= e(strtok($page_canonical, '?')) ?>">
```

> `strtok($url, '?')` strips query strings from the canonical URL — so `/projects.php?sort=name` canonicalises to `/projects.php`. This prevents Google from treating filtered/sorted pages as separate URLs.

**Default behaviour:** `index, follow` for all pages unless a page explicitly overrides `$page_robots`.

---

### File 2: `public/index.php` — Homepage

**Problems found:**
- Title was `"{company_name} — {tagline}"` — both values are database-driven. If tagline is long, title could exceed 60 characters easily. Also had no SEO keywords.
- Meta description had no target keywords ("plots", "residential", "investment")

| | Before | After | Length |
|---|---|---|---|
| **Title** | `Shubharambh Infra Advisors — {tagline from DB}` | `Shubharambh Infra Advisors \| Real Estate Delhi NCR` | 52 chars ✅ |
| **Meta desc** | `...RERA-registered real estate consultancy in Noida. Luxury residential, commercial and investment properties...` | `RERA-registered real estate advisory in Delhi NCR. Residential plots, luxury homes & investment properties in Noida, Gurgaon & Uttarakhand. Free consultation.` | 158 chars ✅ |
| **Canonical** | ❌ Missing | `url('index.php')` | ✅ Added |

**Keywords now present in meta description:** `residential plots`, `investment properties`, `Delhi NCR`, `Noida`, `Gurgaon`

---

### File 3: `public/projects.php` — All Projects Listing

**Problems found:**
- Title was generic: `"Projects — {company}"` — no keywords
- Meta description used a dynamic `$count` variable (could render as "0+" if DB fails) and had no keyword targeting

| | Before | After | Length |
|---|---|---|---|
| **Title** | `Projects — Shubharambh Infra Advisors` | `Real Estate Projects in Delhi NCR \| Shubharambh Infra` | 55 chars ✅ |
| **Meta desc** | `Explore 0+ luxury residential, commercial and investment real estate projects...` | `Browse residential plots, luxury apartments & investment properties in Noida, Gurgaon & Uttarakhand. RERA-verified projects. Get a free site visit today.` | 153 chars ✅ |
| **Canonical** | ❌ Missing | `url('projects.php')` | ✅ Added |

**Keywords now present:** `residential plots`, `investment properties`, `Noida`, `Gurgaon`, `RERA-verified`

---

### File 4: `public/project.php` — Individual Project Detail Pages

**Problems found:**
- Title formula: `"{name} by {builder} | {location} — {company}"` — with a long project name + builder + location this regularly exceeds 70–80 characters, causing Google to rewrite it
- JSON-LD schema type was `Product` — incorrect for real estate. Google Search has a dedicated `RealEstateListing` type which gets better rich results
- No canonical (important since project pages are sometimes linked with tracking params)

| | Before | After |
|---|---|---|
| **Title** | `M3M The Cullinan by M3M Group \| Sector 150, Noida — Shubharambh Infra Advisors` (79 chars ❌) | `M3M The Cullinan by M3M Group \| Noida Real Estate` (50 chars ✅) |
| **JSON-LD type** | `Product` ❌ | `RealEstateListing` ✅ |
| **JSON-LD address** | Missing | Added `PostalAddress` with city, region, country ✅ |
| **Canonical** | ❌ Missing | `url('project.php?slug={slug}')` ✅ |

> Title uses `truncate()` (already in helpers) to cap `"{name} by {builder}"` at 35 chars before appending `"| {city} Real Estate"` — keeping total well under 60.

---

### File 5: `public/contact.php` — Contact Page

**Problems found:**
- Meta description was weak: `"Get in touch with Shubharambh Infra Advisors. Visit our office in Noida or reach out by phone, WhatsApp, or email."` — no property/investment keywords, no compelling action word beyond "get in touch"

| | Before | After | Length |
|---|---|---|---|
| **Title** | `Contact Us — Shubharambh Infra Advisors` | `Contact Us \| Shubharambh Infra Advisors, Noida` | 48 chars ✅ |
| **Meta desc** | `Get in touch... Visit our office in Noida or reach out by phone, WhatsApp, or email.` | `Contact our real estate experts in Noida for property advice, site visits & investment guidance across Delhi NCR. Call, WhatsApp or email — free consultation.` | 158 chars ✅ |
| **Canonical** | ❌ Missing | `url('contact.php')` ✅ |

---

### File 6: `public/privacy-policy.php`, `public/terms.php`, `public/404.php` — Legal & Error Pages

**Problems found:**
- None of these pages had a `robots` directive — Google was free to index them, wasting crawl budget on pages with zero ranking value and potentially creating legal/duplicate content issues.

**Change:** Added `$page_robots = 'noindex, nofollow';` to each file.

| File | Robots Before | Robots After |
|---|---|---|
| `privacy-policy.php` | `index, follow` (default) | `noindex, nofollow` ✅ |
| `terms.php` | `index, follow` (default) | `noindex, nofollow` ✅ |
| `404.php` | `index, follow` (default) | `noindex, nofollow` ✅ |

---

### Pages Left Unchanged (Already Correct)

| File | Reason Not Changed |
|---|---|
| `about.php` | Title 37 chars ✅, desc 157 chars ✅, H1 ✅, structure clean |
| `emi-calculator.php` | Title 55 chars ✅, desc 157 chars ✅, H1 ✅, structure clean |

---

### SEO Improvements Summary

| Metric | Before Session 2 | After Session 2 |
|---|---|---|
| Pages with `<meta name="robots">` | 0 / 9 | 9 / 9 ✅ |
| Pages with `<link rel="canonical">` | 0 / 9 | 9 / 9 ✅ |
| Titles within 60 chars | 6 / 9 | 9 / 9 ✅ |
| Meta descriptions with target keywords | 2 / 9 | 7 / 9 ✅ |
| Legal/error pages with `noindex` | 0 / 3 | 3 / 3 ✅ |
| JSON-LD type correct for project pages | ❌ `Product` | ✅ `RealEstateListing` |

---

### Target Keywords Now Covered

| Keyword | Present In |
|---|---|
| `residential plots` | Homepage meta, Projects meta |
| `investment properties` | Homepage meta, Projects meta |
| `Delhi NCR` | Homepage title + meta, Projects title, Contact title + meta |
| `Noida` | Homepage meta, Projects meta, Contact title + meta |
| `Gurgaon` | Homepage meta, Projects meta |
| `RERA-registered / RERA-verified` | Homepage meta, Projects meta |
| `real estate` | All major page titles |
| `free consultation` | Homepage meta, Contact meta |

---

---

## Session 3 — Schema.org Structured Data / JSON-LD (April 2026)

### What Was Done
Dynamic Schema.org JSON-LD markup was implemented for both the project detail page and the projects listing page. All values come 100% from PHP variables (database) — nothing is hardcoded.

The existing weak `Product` schema on `project.php` was replaced with a rich, properly typed `RealEstateListing` schema.

---

### File 1: `public/project.php` — Individual Project Detail Pages

**Before (old schema — problems):**
```json
{
  "@type": "Product",          ← Wrong type for real estate
  "brand": { ... },            ← Brand = product concept, not property
  "priceSpecification": { ... } ← Nested unnecessarily
  // Missing: address, amenities, breadcrumb, RERA, possession, sizes, keywords
}
```

**After (new schema — what was added):**

| Schema Field | PHP Variable Used | DB Column |
|---|---|---|
| `@type` | — | `RealEstateListing` (correct for property pages) |
| `name` | `$project['name']` | `projects.name` |
| `description` | `truncate($project['description'], 300)` | `projects.description` |
| `url` + `@id` | `url('project.php?slug=...')` | `projects.slug` |
| `image` | `$imgPath` | `projects.cover_image` |
| `offers.price` | `$project['price_display']` | `projects.price_display` |
| `offers.seller` | `$settings['company_name']` | `settings` table |
| `address.streetAddress` | `$project['location']` | `projects.location` |
| `address.addressLocality` | `$project['city']` | `projects.city` |
| `provider.name` | `$project['builder']` | `projects.builder` |
| `additionalProperty` → Configuration | `$project['configurations']` | `projects.configurations` |
| `additionalProperty` → Sizes | `$project['sizes']` | `projects.sizes` |
| `additionalProperty` → Possession | `$project['possession']` | `projects.possession` |
| `additionalProperty` → RERA ID | `$project['rera_id']` | `projects.rera_id` |
| `additionalProperty` → Property Type | `$project['property_type']` | `projects.property_type` |
| `amenityFeature[]` | `$amenities` (already parsed) | `projects.amenities` (JSON) |
| `keywords` | Compiled from type + config + city + builder + usps | multiple columns |
| `breadcrumb` | Dynamic: Home → Projects → `$project['name']` | `projects.name`, `projects.slug` |

**Smart handling:**
- `additionalProperty` items are only added if the column is not empty (`array_filter` with null)
- `amenityFeature` maps each amenity string to `LocationFeatureSpecification` with `value: true`
- `keywords` uses up to 5 USPs from the DB + location + builder for discoverability

---

### File 2: `public/projects.php` — Projects Listing Page

**Before:** No schema at all on this page.

**After:** Added `ItemList` schema — the correct Google-recommended type for listing/index pages.

| Schema Field | PHP Variable Used | Source |
|---|---|---|
| `@type` | — | `ItemList` |
| `name` | `'Real Estate Projects — ' . $settings['company_name']` | `settings` table |
| `numberOfItems` | `$count` | `count($allProjects)` |
| `itemListElement[].position` | `$i + 1` | Loop index |
| `itemListElement[].item.@type` | — | `RealEstateListing` |
| `itemListElement[].item.name` | `$p['name']` | `projects.name` |
| `itemListElement[].item.description` | `truncate($p['description'], 160)` | `projects.description` |
| `itemListElement[].item.url` + `@id` | `url('project.php?slug=...')` | `projects.slug` |
| `itemListElement[].item.image` | `upload_url($p['cover_image'])` | `projects.cover_image` |
| `itemListElement[].item.offers.price` | `$p['price_display']` | `projects.price_display` |
| `itemListElement[].item.address` | `$p['location']`, `$p['city']` | `projects.location`, `projects.city` |
| `itemListElement[].item.provider` | `$p['builder']` | `projects.builder` |

**Smart handling:**
- Cover image falls back to placeholder SVG if no image uploaded (same logic as the card template)
- Each `ListItem` has a unique `position` — Google requires this for ItemList rich results
- Schema only renders with real data — if `$allProjects` is empty, `itemListElement` is `[]`

---

### Where Is the JSON-LD Output?

Both schemas are passed via `$page_jsonld` to `includes/header.php` which already has this renderer (existing code, not changed):

```php
<?php if ($page_jsonld): ?>
<script type="application/ld+json">
<?= json_encode($page_jsonld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?php endif; ?>
```

So the JSON-LD appears inside `<head>` on every project page — exactly where Google expects it.

---

### How to Test
1. Open any project detail page, e.g. `/project.php?slug=m3m-the-cullinan`
2. View page source → look for `<script type="application/ld+json">` in `<head>`
3. Copy the JSON → paste into **Google Rich Results Test**: https://search.google.com/test/rich-results
4. Repeat for `/projects.php` — should show `ItemList` schema with all active projects

---

## Session 3 (Update) — Schema Fixed: @graph + Dynamic State + All 11 Projects (April 2026)

### Problem Found After Initial Implementation
The first version of the schema had two issues:
1. `addressRegion` was hardcoded as `'Uttar Pradesh'` — but Haridwar and Ramnagar projects are in **Uttarakhand**
2. Schema was a flat single object — not using `@graph`, which caused potential conflict with the `RealEstateAgent` schema already emitted globally by `header.php`

### Fix Applied — `public/project.php`

#### Change 1: Dynamic State Resolution
Added a lookup map — state is resolved from `$project['city']` (DB value), never hardcoded:

```php
$schemaStateMap = [
    'haridwar'  => 'Uttarakhand',
    'ramnagar'  => 'Uttarakhand',
    'dehradun'  => 'Uttarakhand',
    'rishikesh' => 'Uttarakhand',
    'gurgaon'   => 'Haryana',
    'gurugram'  => 'Haryana',
    'faridabad' => 'Haryana',
    'delhi'     => 'Delhi',
    'new delhi' => 'Delhi',
];
$cityLower   = mb_strtolower(trim($project['city'] ?? ''));
$schemaState = $schemaStateMap[$cityLower] ?? 'Uttar Pradesh'; // default UP
```

**Per-project state output (all 11 live projects):**

| Project | City (DB) | addressRegion (Schema) |
|---|---|---|
| M3M The Cullinan | Noida | Uttar Pradesh ✅ |
| M3M The Line | Noida | Uttar Pradesh ✅ |
| ONE FNG | Noida | Uttar Pradesh ✅ |
| Eternia | Greater Noida | Uttar Pradesh ✅ |
| SVG Town Square | Greater Noida | Uttar Pradesh ✅ |
| Uniwest Aero Hub | Greater Noida | Uttar Pradesh ✅ |
| Uniwest Hub | Greater Noida | Uttar Pradesh ✅ |
| Uniwest Arcade | Noida | Uttar Pradesh ✅ |
| Kutumbh City | Haridwar | **Uttarakhand** ✅ |
| Shubh Kadam | Ramnagar | **Uttarakhand** ✅ |
| Corbett Eye | Ramnagar | **Uttarakhand** ✅ |

#### Change 2: Switched to @graph
Schema now uses `@graph` so `RealEstateListing` and `BreadcrumbList` coexist cleanly alongside the global `RealEstateAgent` schema from `header.php`:

```json
{
  "@context": "https://schema.org",
  "@graph": [
    { "@type": "RealEstateListing", "@id": "...#listing", ... },
    { "@type": "BreadcrumbList",    "@id": "...#breadcrumb", ... }
  ]
}
```

#### How "One Template = All Projects" Works
`project.php` is a **single PHP template**. PHP reads `?slug=` from the URL, fetches that project's row from the DB, and all `$project['...']` variables change per request. So:

- `/project.php?slug=m3m-the-cullinan` → schema has M3M Cullinan's name, price, RERA, amenities
- `/project.php?slug=kutumbh-city` → schema has Kutumbh City's name, price, Haridwar address, Uttarakhand state
- `/project.php?slug=corbett-eye` → schema has Corbett Eye's details, Ramnagar, Uttarakhand

**No separate schema file needed per project. One template, infinite projects.**

---

*This report will be updated as more changes are made to the project.*
