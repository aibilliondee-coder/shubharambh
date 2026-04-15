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

*This report will be updated as more changes are made to the project.*
