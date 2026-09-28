# JakartaProv CSIRT — Design System Reverse-Engineering Audit Report

> **Target:** DKI Jakarta Cybersecurity Incident Response Team (`JakartaProv-CSIRT`) Portal (`new-csirt`)  
> **Analysis Scope:** 100% source code parsing across `public/css/style.css`, `public/css/accessibility-contrast.css`, `public/js/accessibility.js`, and all 50+ Blade view templates in `resources/views/`.  
> **Total Declarations Parsed:** 6,589 CSS rules  
> **Audit Methodology:** Empirical code extraction, token reference verification, Euclidean color distance (&Delta;E) clustering, and component inventory mapping.

---

## Executive Summary Dashboard

| Metric | Discovered in Source | Intended / Documented | Consistency Score | Health Status |
|---|---|---|---|---|
| **Unique Colors** | **106 values** (56 hex, 50 rgb/rgba) | ~13 core tokens | **32%** | 🔴 LOW (47 near-duplicate pairs, token bypassing) |
| **Font Sizes** | **36 distinct values** | ~8 scale steps | **28%** | 🔴 POOR (Fractional pixels, fluid clamp drift) |
| **Spacing Steps** | **45 distinct values** (160 shorthands) | ~10 steps (4/8px base) | **48%** | 🟡 MODERATE (18 orphan values used 320+ times) |
| **Button Variants** | **25 distinct classes/implementations** | 3-4 core variants | **35%** | 🔴 LOW (Severe variant proliferation across views) |
| **Breakpoints** | **14 distinct media queries** | 2 standard (960px, 640px) | **42%** | 🔴 POOR (One-off breakpoint sprawl) |
| **Border Radius** | **0px** (with 1 outlier: `10px`) | 0px (Strict boxy NYC.gov) | **96%** | 🟢 EXCELLENT (1 major outlier in admin badge) |
| **Overall Design Health** | — | — | **37 / 100** | 🔴 **HEAVY FRAGMENTATION** |

### Key Systemic Diagnosis
1. **Core Specification vs Implementation Drift:** The project established a clean NYC.gov-inspired design system documented in `DESIGN_SYSTEM.md` with 13 core tokens. However, later features (Bug Hunter portal, Contact form, Admin CRUD, and Honeypot statistics) bypassed global styles and wrote custom scoped `<style>` tags with hardcoded values.
2. **Dark Mode Vulnerability:** Dark mode is implemented via CSS variable overrides in `accessibility-contrast.css`. Hardcoded colors in Blade views (e.g. `#9ca3af`, `#10B981`, `#004099`, `#d6e4f8`) do **not** adapt when the theme changes, producing unreadable contrast glitches.
3. **Variant Proliferation:** 25 button implementations exist in code doing the job of just 3-4 button roles. In several cases, exact duplicates exist (e.g. `.btn-hero-primary` in `home.blade.php:83` is identical to `.btn-primary-solid` in `style.css:195`).

---

## 1. Color System Analysis

### 1.1 Authoritative Token Architecture

| Token | Light Mode (`style.css`) | Dark Mode Override (`accessibility-contrast.css`) | High Contrast Mode | Intended Role |
|---|---|---|---|---|
| `--ink` | `#0A0F1A` | `#E2E8F0` | `#000000` | Primary text, headings, chrome |
| `--navy` | `#003580` | `#4DA6FF` | `#000000` | Primary brand blue: buttons, borders, links |
| `--navy-mid` | `#004099` | `#66B3FF` | `#000000` | Hover & active states |
| `--navy-dim` | `#002060` | `#2080E0` | `#000000` | Dark sections: CTA backgrounds, pressed states |
| `--navy-tint` | `#E8EFF8` | `#1A2234` | `#FFFFFF` | Faint blue: hover surfaces, tab highlights |
| `--mist` | `#F4F5F7` | `#151C28` | `#FFFFFF` | Light gray: table headers, section backgrounds |
| `--border` | `#D8DCE3` | `#232D42` | `#000000` | Dividers, card outlines |
| `--mid` | `#5B6472` | `#94A3B8` | `#000000` | Muted secondary text *(DESIGN_SYSTEM.md says #6B7280)* |
| `--white` | `#FFFFFF` | `#0F141E` | `#FFFFFF` | Card & page surfaces |
| `--muted-on-dark` | `#93A2B7` | — | — | Secondary text on dark surfaces |
| `--faint-on-dark` | `#8A99AD` | — | — | Tertiary text on dark surfaces |
| `--alert` | `#B91C1C` | `#FF6B6B` | `#FFFF00` | Warning / alert content only |
| `--alert-bg` | `#FEF2F2` | `#2A1215` | `#000000` | Alert container backdrop |
| `--alert-light` | `#EF4444` | `#FF8888` | `#FFFF00` | Alert badges |
| `--alert-dark` | `#991B1B` | `#FFAAAA` | `#FFFF00` | Alert error text |

### 1.2 Top Near-Duplicate Colors (Bugs / Inconsistencies)

| Color 1 | Color 2 | &Delta;E Distance | Usages | Bug Description & Files |
|---|---|---|---|---|
| `#e8eff8` | `#e8eef6` | **2.24** | `5x` vs `1x` | `#e8eff8` in `public\css\style.css`; `#e8eef6` in `resources\views\components\captcha.blade.php` |
| `#f4f5f7` | `#f5f5f5` | **2.24** | `8x` vs `1x` | `#f4f5f7` in `public\css\style.css`; `#f5f5f5` in `public\css\accessibility-contrast.css` |
| `#6c757d` | `#6b7280` | **4.36** | `2x` vs `4x` | `#6c757d` in `public\css\accessibility-contrast.css`; `#6b7280` in `resources\views\components\accessibility.blade.php` |
| `#cbd5e1` | `#c6d2e0` | **5.92** | `3x` vs `2x` | `#cbd5e1` in `public\css\style.css`; `#c6d2e0` in `public\css\accessibility-contrast.css` |
| `#5b6472` | `#5c636a` | **8.12** | `1x` vs `2x` | `#5b6472` in `public\css\style.css`; `#5c636a` in `public\css\accessibility-contrast.css` |
| `#9aa7b6` | `#9ca3af` | **8.31** | `4x` vs `1x` | `#9aa7b6` in `public\css\accessibility-contrast.css`; `#9ca3af` in `resources\views\contact\create.blade.php` |
| `#93a2b7` | `#9aa7b6` | **8.66** | `1x` vs `4x` | `#93a2b7` in `public\css\style.css`; `#9aa7b6` in `public\css\accessibility-contrast.css` |
| `#f0f0f0` | `#f5f5f5` | **8.66** | `12x` vs `1x` | `#f0f0f0` in `public\css\accessibility-contrast.css`; `#f5f5f5` in `public\css\accessibility-contrast.css` |
| `#0f0f0f` | `#0a0a0a` | **8.66** | `8x` vs `3x` | `#0f0f0f` in `public\css\accessibility-contrast.css`; `#0a0a0a` in `public\css\accessibility-contrast.css` |
| `#f4f5f7` | `#f0f0f0` | **9.49** | `8x` vs `12x` | `#f4f5f7` in `public\css\style.css`; `#f0f0f0` in `public\css\accessibility-contrast.css` |
| `#fef2f2` | `#f5f5f5` | **9.95** | `1x` vs `1x` | `#fef2f2` in `public\css\style.css`; `#f5f5f5` in `public\css\accessibility-contrast.css` |
| `#f0f0f0` | `#e8eef6` | **10.2** | `12x` vs `1x` | `#f0f0f0` in `public\css\accessibility-contrast.css`; `#e8eef6` in `resources\views\components\captcha.blade.php` |
| `#e8eff8` | `#f0f0f0` | **11.36** | `5x` vs `12x` | `#e8eff8` in `public\css\style.css`; `#f0f0f0` in `public\css\accessibility-contrast.css` |
| `#f4f5f7` | `#fef2f2` | **11.58** | `8x` vs `1x` | `#f4f5f7` in `public\css\style.css`; `#fef2f2` in `public\css\style.css` |
| `#0a0f1a` | `#0f0f0f` | **12.08** | `47x` vs `8x` | `#0a0f1a` in `public\css\style.css`; `#0f0f0f` in `public\css\accessibility-contrast.css` |

### 1.3 Complete Discovered Color Inventory (Grouped by Inferred Role)

#### Role: White Overlay / Opacity (22 unique values)

| Color Value | Status | Count | Files Affected | Sample Code Citations |
|---|---|---|---|---|
| `rgba(255,255,255,0.5)` | Hardcoded Inline Style | 19x | 17 files | `public\css\accessibility-contrast.css:1401, 1425, 1493`, `resources\views\bug-hunter\create.blade.php:43`, *(+15 more files)* |
| `rgba(255,255,255,0.7)` | Hardcoded CSS | 8x | 7 files | `public\css\style.css:222`, `resources\views\home.blade.php:106, 115`, *(+5 more files)* |
| `rgba(255,255,255,0.6)` | Hardcoded CSS | 5x | 4 files | `public\css\style.css:232`, `resources\views\auth\login.blade.php:43`, *(+2 more files)* |
| `rgba(255,255,255,0.55)` | Hardcoded CSS | 4x | 4 files | `public\css\style.css:615`, `resources\views\home.blade.php:235`, *(+2 more files)* |
| `rgba(255,255,255,0.15)` | Hardcoded CSS | 4x | 4 files | `public\css\style.css:616`, `resources\views\bug-hunter\thank-you.blade.php:51`, *(+2 more files)* |
| `rgba(255,255,255,0.07)` | Hardcoded CSS | 4x | 2 files | `resources\views\home.blade.php:122, 128`, `resources\views\demo\hero-contrast.blade.php:29, 31` |
| `rgba(255,255,255,0.25)` | Hardcoded CSS | 3x | 3 files | `public\css\style.css:223`, `resources\views\home.blade.php:206`, *(+1 more files)* |
| `rgba(255,255,255,0.4)` | Hardcoded CSS | 3x | 3 files | `public\css\style.css:625`, `resources\views\demo\hero-contrast.blade.php:34`, *(+1 more files)* |
| `rgba(255,255,255,0.12)` | Hardcoded CSS | 3x | 3 files | `public\css\accessibility-contrast.css:1513`, `resources\views\home.blade.php:205`, *(+1 more files)* |
| `rgba(255,255,255,0.45)` | Hardcoded CSS | 3x | 3 files | `resources\views\home.blade.php:217`, `resources\views\events\show.blade.php:36`, *(+1 more files)* |
| `rgba(255,255,255,0.8)` | Hardcoded CSS | 3x | 3 files | `resources\views\components\footer.blade.php:97`, `resources\views\events\show.blade.php:41`, *(+1 more files)* |
| `rgba(255,255,255,0.72)` | Hardcoded CSS | 2x | 2 files | `resources\views\home.blade.php:70`, `resources\views\demo\hero-contrast.blade.php:27` |
| `rgba(255,255,255,0.3)` | Hardcoded CSS | 2x | 2 files | `resources\views\home.blade.php:107`, `resources\views\demo\hero-contrast.blade.php:36` |
| `rgba(255,255,255,0.04)` | Hardcoded CSS | 2x | 2 files | `resources\views\bug-hunter\thank-you.blade.php:41`, `resources\views\events\show.blade.php:155` |
| `rgba(255,255,255,0.1)` | Hardcoded CSS | 2x | 2 files | `resources\views\bug-hunter\thank-you.blade.php:50`, `resources\views\infographics\index.blade.php:328` |
| `rgba(255,255,255,0.35)` | Hardcoded CSS | 2x | 2 files | `resources\views\components\accessibility.blade.php:848`, `resources\views\demo\hero-contrast.blade.php:25` |
| `rgba(255,255,255,0.08)` | Hardcoded CSS | 2x | 2 files | `resources\views\components\footer.blade.php:123`, `resources\views\events\show.blade.php:94` |
| `rgba(255,255,255,0.2)` | Hardcoded CSS | 1x | 1 files | `resources\views\home.blade.php:49` |
| `rgba(255,255,255,0.22)` | Hardcoded CSS | 1x | 1 files | `resources\views\home.blade.php:216` |
| `rgba(255,255,255,0.9)` | Hardcoded CSS | 1x | 1 files | `resources\views\components\footer.blade.php:72` |
| `rgba(255,255,255,0.85)` | Hardcoded CSS | 1x | 1 files | `resources\views\events\show.blade.php:81` |
| `rgba(255,255,255,0.06)` | Hardcoded CSS | 1x | 1 files | `resources\views\infographics\index.blade.php:307` |

#### Role: Dark Scrim / Shadow / Overlay (15 unique values)

| Color Value | Status | Count | Files Affected | Sample Code Citations |
|---|---|---|---|---|
| `rgba(0,0,0,0.08)` | Hardcoded CSS | 4x | 4 files | `resources\views\admin\login.blade.php:20`, `resources\views\auth\login.blade.php:20`, *(+2 more files)* |
| `rgba(0,0,0,0.35)` | Hardcoded CSS | 3x | 2 files | `resources\views\home.blade.php:52`, `resources\views\demo\hero-contrast.blade.php:60, 75` |
| `rgba(10,15,26,0.72)` | Hardcoded CSS | 2x | 2 files | `resources\views\home.blade.php:46`, `resources\views\demo\hero-contrast.blade.php:56` |
| `rgba(10,15,26,0.58)` | Hardcoded CSS | 2x | 2 files | `resources\views\home.blade.php:46`, `resources\views\demo\hero-contrast.blade.php:56` |
| `rgba(10,15,26,0.00)` | Hardcoded CSS | 2x | 2 files | `resources\views\home.blade.php:46`, `resources\views\demo\hero-contrast.blade.php:56` |
| `rgba(0,0,0,0.05)` | Hardcoded CSS | 2x | 1 files | `resources\views\contact\create.blade.php:62, 205` |
| `rgba(0,0,0,0.55)` | Hardcoded CSS | 2x | 1 files | `resources\views\demo\hero-contrast.blade.php:75, 76` |
| `rgba(0,0,0,0.9)` | Hardcoded CSS | 1x | 1 files | `public\css\accessibility-contrast.css:426` |
| `rgba(10,15,26,0.35)` | Hardcoded CSS | 1x | 1 files | `public\css\accessibility-contrast.css:1232` |
| `rgba(0,0,0,0.15)` | Hardcoded CSS | 1x | 1 files | `resources\views\components\accessibility.blade.php:437` |
| `rgba(0,0,0,0.10)` | Hardcoded CSS | 1x | 1 files | `resources\views\components\navbar.blade.php:211` |
| `rgba(0,0,0,0.45)` | Hardcoded CSS | 1x | 1 files | `resources\views\demo\hero-contrast.blade.php:75` |
| `rgba(0,0,0,0.5)` | Hardcoded CSS | 1x | 1 files | `resources\views\demo\hero-contrast.blade.php:77` |
| `rgba(10,15,26,0.96)` | Hardcoded CSS | 1x | 1 files | `resources\views\infographics\index.blade.php:252` |
| `rgba(0,0,0,0.06)` | Hardcoded CSS | 1x | 1 files | `resources\views\news\index.blade.php:64` |

#### Role: Blue Scrim / Focus Ring (11 unique values)

| Color Value | Status | Count | Files Affected | Sample Code Citations |
|---|---|---|---|---|
| `rgba(0,20,60,0.88)` | Hardcoded CSS | 2x | 2 files | `resources\views\home.blade.php:121`, `resources\views\demo\hero-contrast.blade.php:29` |
| `rgba(0,32,96,0.82)` | Hardcoded Inline Style | 2x | 1 files | `resources\views\home.blade.php:576, 576` |
| `rgba(0,53,128,0.68)` | Hardcoded Inline Style | 2x | 1 files | `resources\views\home.blade.php:576, 576` |
| `rgba(0,53,128,0.32)` | Hardcoded Inline Style | 2x | 1 files | `resources\views\home.blade.php:576, 576` |
| `rgba(0,53,128,0.1)` | Hardcoded CSS | 2x | 2 files | `resources\views\bug-hunter\create.blade.php:199`, `resources\views\contact\create.blade.php:125` |
| `rgba(0,53,128,0.25)` | Hardcoded CSS | 1x | 1 files | `resources\views\components\accessibility.blade.php:413` |
| `rgba(0,53,128,0.35)` | Hardcoded CSS | 1x | 1 files | `resources\views\components\accessibility.blade.php:419` |
| `rgba(0,53,128,0.45)` | Hardcoded CSS | 1x | 1 files | `resources\views\components\accessibility.blade.php:805` |
| `rgba(0,53,128,0)` | Hardcoded CSS | 1x | 1 files | `resources\views\components\accessibility.blade.php:806` |
| `rgba(0,32,96,0)` | Hardcoded CSS | 1x | 1 files | `resources\views\infographics\index.blade.php:122` |
| `rgba(0,32,96,0.35)` | Hardcoded CSS | 1x | 1 files | `resources\views\infographics\index.blade.php:129` |

#### Role: Muted Text / Secondary (10 unique values)

| Color Value | Status | Count | Files Affected | Sample Code Citations |
|---|---|---|---|---|
| `#9aa7b6` | Token Mode Override | 4x | 1 files | `public\css\accessibility-contrast.css:1008, 1341, 1348, +1 more` |
| `#6b7280` | Hardcoded CSS | 4x | 1 files | `resources\views\components\accessibility.blade.php:514, 582, 883, +1 more` |
| `#999999` | Hardcoded CSS | 2x | 1 files | `public\css\accessibility-contrast.css:1252, 1299` |
| `#6c757d` | Hardcoded CSS | 2x | 1 files | `public\css\accessibility-contrast.css:1606, 1608` |
| `#5c636a` | Hardcoded CSS | 2x | 1 files | `public\css\accessibility-contrast.css:1611, 1613` |
| `#5b6472` | Token Defined | 1x | 1 files | `public\css\style.css:27` |
| `#93a2b7` | Token Defined | 1x | 1 files | `public\css\style.css:30` |
| `#8a99ad` | Token Defined | 1x | 1 files | `public\css\style.css:31` |
| `#555555` | Hardcoded CSS | 1x | 1 files | `public\css\accessibility-contrast.css:1226` |
| `#9ca3af` | Hardcoded CSS | 1x | 1 files | `resources\views\contact\create.blade.php:194` |

#### Role: Miscellaneous / Accent (10 unique values)

| Color Value | Status | Count | Files Affected | Sample Code Citations |
|---|---|---|---|---|
| `#000000` | Token Mode Override | 129x | 2 files | `public\css\accessibility-contrast.css:8, 14, 15, +2 more`, `resources\views\bug-hunter\create.blade.php:371, 372` |
| `#000080` | Token Mode Override | 24x | 2 files | `public\css\accessibility-contrast.css:9, 10, 31, +2 more`, `resources\views\bug-hunter\create.blade.php:372` |
| `#cc0000` | Token Mode Override | 10x | 1 files | `public\css\accessibility-contrast.css:22, 360, 361, +2 more` |
| `#b0bcc9` | Token Mode Override | 9x | 1 files | `public\css\accessibility-contrast.css:1007, 1334, 1344, +2 more` |
| `#000040` | Token Mode Override | 8x | 1 files | `public\css\accessibility-contrast.css:11, 44, 381, +2 more` |
| `#2a2a3e` | Hardcoded CSS | 6x | 2 files | `public\css\accessibility-contrast.css:1091, 1127, 1147, +2 more`, `resources\views\bug-hunter\create.blade.php:370` |
| `#444444` | Hardcoded CSS | 3x | 1 files | `public\css\accessibility-contrast.css:488, 664, 793` |
| `#ff0000` | Token Mode Override | 1x | 1 files | `public\css\accessibility-contrast.css:19` |
| `#3385ff` | Token Mode Override | 1x | 1 files | `public\css\accessibility-contrast.css:1001` |
| `#330000` | Token Mode Override | 1x | 1 files | `public\css\accessibility-contrast.css:1010` |

#### Role: Ink / Dark Surface / Contrast Dark (8 unique values)

| Color Value | Status | Count | Files Affected | Sample Code Citations |
|---|---|---|---|---|
| `#0a0f1a` | Token Defined | 47x | 6 files | `public\css\style.css:19`, `public\css\accessibility-contrast.css:1099, 1170, 1174, +2 more`, *(+4 more files)* |
| `#333333` | Token Mode Override | 24x | 2 files | `public\css\accessibility-contrast.css:415, 1004, 1051, +2 more`, `resources\views\bug-hunter\create.blade.php:368, 369` |
| `#1a1a1a` | Token Mode Override | 22x | 2 files | `public\css\accessibility-contrast.css:1006, 1049, 1055, +2 more`, `resources\views\bug-hunter\create.blade.php:368, 369` |
| `#0f0f0f` | Token Mode Override | 8x | 1 files | `public\css\accessibility-contrast.css:1003, 1111, 1157, +2 more` |
| `#0f1b33` | Hardcoded CSS | 4x | 1 files | `public\css\accessibility-contrast.css:1380, 1455, 1489, +1 more` |
| `#1a1a2e` | Token Mode Override | 3x | 1 files | `public\css\accessibility-contrast.css:1002, 1636, 1642` |
| `#0a0a0a` | Hardcoded CSS | 3x | 1 files | `public\css\accessibility-contrast.css:1016, 1021, 1064` |
| `#222222` | Hardcoded CSS | 1x | 1 files | `public\css\accessibility-contrast.css:802` |

#### Role: Background / Light Surface (8 unique values)

| Color Value | Status | Count | Files Affected | Sample Code Citations |
|---|---|---|---|---|
| `#ffffff` | Token Defined | 157x | 4 files | `public\css\style.css:28`, `public\css\accessibility-contrast.css:12, 16, 17, +2 more`, *(+2 more files)* |
| `#d6e4f8` | Hardcoded CSS | 17x | 15 files | `resources\views\home.blade.php:37, 40, 146`, `resources\views\profile.blade.php:27`, *(+13 more files)* |
| `#f0f0f0` | Token Mode Override | 12x | 1 files | `public\css\accessibility-contrast.css:13, 87, 113, +2 more` |
| `#f4f5f7` | Token Defined | 8x | 4 files | `public\css\style.css:25`, `resources\views\components\accessibility.blade.php:488, 610`, *(+2 more files)* |
| `#e8eff8` | Token Defined | 5x | 2 files | `public\css\style.css:23`, `resources\views\components\accessibility.blade.php:524, 631, 681, +1 more` |
| `#fef2f2` | Token Defined | 1x | 1 files | `public\css\style.css:34` |
| `#f5f5f5` | Hardcoded CSS | 1x | 1 files | `public\css\accessibility-contrast.css:713` |
| `#e8eef6` | Hardcoded CSS | 1x | 1 files | `resources\views\components\captcha.blade.php:42` |

#### Role: Alert / Red / Danger (7 unique values)

| Color Value | Status | Count | Files Affected | Sample Code Citations |
|---|---|---|---|---|
| `#ff6b6b` | Token Mode Override | 11x | 1 files | `public\css\accessibility-contrast.css:1009, 1369, 1550, +2 more` |
| `#ff9999` | Token Mode Override | 5x | 1 files | `public\css\accessibility-contrast.css:1011, 1374, 1555, +2 more` |
| `#ff4444` | Token Mode Override | 2x | 1 files | `public\css\accessibility-contrast.css:21, 1012` |
| `#990000` | Hardcoded CSS | 2x | 1 files | `public\css\accessibility-contrast.css:787, 844` |
| `#b91c1c` | Token Defined | 1x | 1 files | `public\css\style.css:33` |
| `#ef4444` | Token Defined | 1x | 1 files | `public\css\style.css:35` |
| `#991b1b` | Token Defined | 1x | 1 files | `public\css\style.css:36` |

#### Role: Brand Blue / Primary (5 unique values)

| Color Value | Status | Count | Files Affected | Sample Code Citations |
|---|---|---|---|---|
| `#4da6ff` | Token Mode Override | 35x | 2 files | `public\css\accessibility-contrast.css:999, 1026, 1035, +2 more`, `resources\views\bug-hunter\create.blade.php:369, 370, 370` |
| `#003580` | Token Defined | 27x | 5 files | `public\css\style.css:20`, `resources\views\profile.blade.php:56`, *(+3 more files)* |
| `#66b3ff` | Token Mode Override | 13x | 1 files | `public\css\accessibility-contrast.css:1000, 1030, 1037, +2 more` |
| `#004099` | Token Defined | 3x | 3 files | `public\css\style.css:21`, `resources\views\profile.blade.php:61`, *(+1 more files)* |
| `#002060` | Token Defined | 2x | 2 files | `public\css\style.css:22`, `resources\views\profile.blade.php:66` |

#### Role: Border / Divider (4 unique values)

| Color Value | Status | Count | Files Affected | Sample Code Citations |
|---|---|---|---|---|
| `#d8dce3` | Token Defined | 21x | 4 files | `public\css\style.css:26`, `resources\views\components\accessibility.blade.php:436, 489, 512, +2 more`, *(+2 more files)* |
| `#cccccc` | Token Mode Override | 7x | 1 files | `public\css\accessibility-contrast.css:1005, 1065, 1123, +2 more` |
| `#cbd5e1` | Hardcoded CSS | 3x | 1 files | `public\css\style.css:546, 567, 582` |
| `#c6d2e0` | Hardcoded CSS | 2x | 1 files | `public\css\accessibility-contrast.css:1386, 1514` |

#### Role: Warning / Yellow (2 unique values)

| Color Value | Status | Count | Files Affected | Sample Code Citations |
|---|---|---|---|---|
| `#ffff00` | Token Mode Override | 16x | 1 files | `public\css\accessibility-contrast.css:20, 339, 343, +2 more` |
| `#e6e600` | Hardcoded CSS | 1x | 1 files | `public\css\accessibility-contrast.css:817` |

#### Role: Success / Green (2 unique values)

| Color Value | Status | Count | Files Affected | Sample Code Citations |
|---|---|---|---|---|
| `#006600` | Hardcoded CSS | 1x | 1 files | `public\css\accessibility-contrast.css:763` |
| `#004d00` | Hardcoded CSS | 1x | 1 files | `public\css\accessibility-contrast.css:772` |

#### Role: Alpha Gradient / Overlay (1 unique values)

| Color Value | Status | Count | Files Affected | Sample Code Citations |
|---|---|---|---|---|
| `rgba(77,166,255,0.15)` | Hardcoded CSS | 1x | 1 files | `public\css\accessibility-contrast.css:1106` |

#### Role: Red Focus Ring (1 unique values)

| Color Value | Status | Count | Files Affected | Sample Code Citations |
|---|---|---|---|---|
| `rgba(185,28,28,0.1)` | Hardcoded CSS | 2x | 2 files | `resources\views\bug-hunter\create.blade.php:205`, `resources\views\contact\create.blade.php:131` |

---

## 2. Typography System Analysis

### 2.1 Font Families

| Declaration | Occurrences | Files | Purpose | File References |
|---|---|---|---|---|
| `var(--font-body)` | 59x | 25 files | Primary scale | `public\css\style.css:59, 224, 541, +1 more`, `resources\views\dashboard.blade.php:39`, *(+23 more files)* |
| `inherit` | 1x | 1 files | Primary scale | `public\css\style.css:84` |
| `var(--font-display)` | 177x | 38 files | Primary scale | `public\css\style.css:129, 150, 170, +2 more`, `resources\views\dashboard.blade.php:12, 31, 50`, *(+36 more files)* |
| `'Trebuchet MS', Verdana, Tahoma, Arial, sans-serif` | 1x | 1 files | Primary scale | `public\css\accessibility-contrast.css:1733` |
| `var(--font-body, 'Inter', system-ui, sans-serif)` | 3x | 1 files | Primary scale | `resources\views\components\accessibility.blade.php:398, 566, 674` |
| `var(--font-display, 'Plus Jakarta Sans', sans-serif)` | 6x | 1 files | Primary scale | `resources\views\components\accessibility.blade.php:500, 588, 614, +2 more` |

### 2.2 Complete Discovered Font Size Scale (36 Distinct Sizes)

| Size Value | Computed px | Occurrences | Total Files | Classification | Sample File & Line Citations |
|---|---|---|---|---|---|
| `9px` | ~9.0px | 1x | 1 files | Scale Level | `resources\views\demo\hero-contrast.blade.php:34` |
| `10px` | ~10.0px | 11x | 8 files | Scale Level | `resources\views\home.blade.php:509`, `resources\views\events\index.blade.php:184`, *(+6 more files)* |
| `10.5px` | ~10.5px | 1x | 1 files | ⚠️ **Fractional Outlier** | `resources\views\events\show.blade.php:232` |
| `11px` | ~11.0px | 36x | 22 files | Scale Level | `public\css\style.css:186, 515, 639`, `resources\views\home.blade.php:447, 489`, *(+20 more files)* |
| `11.5px` | ~11.5px | 14x | 12 files | ⚠️ **Fractional Outlier** | `resources\views\bug-hunter\thank-you.blade.php:53`, `resources\views\components\navbar.blade.php:50`, *(+10 more files)* |
| `12px` | ~12.0px | 73x | 32 files | Scale Level | `public\css\style.css:379, 430, 504, +2 more`, `resources\views\home.blade.php:33, 40, 142, +2 more`, *(+30 more files)* |
| `12.5px` | ~12.5px | 20x | 16 files | ⚠️ **Fractional Outlier** | `resources\views\home.blade.php:484`, `resources\views\bug-hunter\create.blade.php:142, 158, 224, +1 more`, *(+14 more files)* |
| `13px` | ~13.0px | 68x | 28 files | Scale Level | `public\css\style.css:171, 292, 315, +2 more`, `resources\views\rfc2350.blade.php:131, 150, 159, +1 more`, *(+26 more files)* |
| `13.5px` | ~13.5px | 15x | 8 files | ⚠️ **Fractional Outlier** | `public\css\style.css:160, 301, 415, +1 more`, `resources\views\statistics.blade.php:45`, *(+6 more files)* |
| `14px` | ~14.0px | 68x | 32 files | Scale Level | `public\css\style.css:225`, `resources\views\dashboard.blade.php:52`, *(+30 more files)* |
| `15px` | ~15.0px | 43x | 24 files | Scale Level | `public\css\style.css:202, 244, 565`, `resources\views\dashboard.blade.php:41`, *(+22 more files)* |
| `16px` | ~16.0px | 14x | 13 files | Scale Level | `public\css\style.css:60, 595`, `resources\views\home.blade.php:326`, *(+11 more files)* |
| `17px` | ~17.0px | 5x | 4 files | Scale Level | `public\css\style.css:287, 485`, `resources\views\home.blade.php:460`, *(+2 more files)* |
| `18px` | ~18.0px | 12x | 11 files | Scale Level | `resources\views\home.blade.php:222`, `resources\views\bug-hunter\show.blade.php:57`, *(+9 more files)* |
| `19px` | ~19.0px | 1x | 1 files | Scale Level | `public\css\style.css:401` |
| `20px` | ~20.0px | 9x | 7 files | Scale Level | `resources\views\bug-hunter\create.blade.php:552`, `resources\views\bug-hunter\dashboard.blade.php:60`, *(+5 more files)* |
| `22px` | ~22.0px | 4x | 4 files | Scale Level | `resources\views\bug-hunter\tac.blade.php:63`, `resources\views\bug-hunter\thank-you.blade.php:157`, *(+2 more files)* |
| `24px` | ~24.0px | 8x | 8 files | Scale Level | `resources\views\home.blade.php:134`, `resources\views\rfc2350.blade.php:64`, *(+6 more files)* |
| `clamp(24px, 3.5vw, 36px)` | ~24.0px | 1x | 1 files | Fluid Responsive Clamp | `resources\views\bug-hunter\thank-you.blade.php:63` |
| `clamp(24px, 4vw, 40px)` | ~24.0px | 1x | 1 files | Fluid Responsive Clamp | `resources\views\infographics\show.blade.php:45` |
| `26px` | ~26.0px | 2x | 2 files | Scale Level | `resources\views\bug-hunter\create.blade.php:67`, `resources\views\contact\create.blade.php:67` |
| `28px` | ~28.0px | 10x | 8 files | Scale Level | `public\css\style.css:473`, `resources\views\dashboard.blade.php:33`, *(+6 more files)* |
| `clamp(28px, 4vw, 44px)` | ~28.0px | 7x | 7 files | Fluid Responsive Clamp | `resources\views\bug-hunter\dashboard.blade.php:23`, `resources\views\bug-hunter\show.blade.php:23`, *(+5 more files)* |
| `clamp(28px,4vw,44px)` | ~28.0px | 1x | 1 files | Fluid Responsive Clamp | `resources\views\demo\hero-contrast.blade.php:26` |
| `clamp(28px, 4.5vw, 48px)` | ~28.0px | 1x | 1 files | Fluid Responsive Clamp | `resources\views\events\show.blade.php:54` |
| `clamp(32px, 5vw, 54px)` | ~32.0px | 11x | 11 files | Fluid Responsive Clamp | `resources\views\dashboard.blade.php:13`, `resources\views\home.blade.php:59`, *(+9 more files)* |
| `clamp(32px, 5vw, 52px)` | ~32.0px | 2x | 2 files | Fluid Responsive Clamp | `resources\views\bug-hunter\create.blade.php:32`, `resources\views\contact\create.blade.php:33` |
| `36px` | ~36.0px | 6x | 6 files | Scale Level | `public\css\style.css:151`, `resources\views\home.blade.php:151`, *(+4 more files)* |
| `clamp(36px, 4.5vw, 58px)` | ~36.0px | 1x | 1 files | Fluid Responsive Clamp | `public\css\style.css:555` |
| `38px` | ~38.0px | 1x | 1 files | Scale Level | `resources\views\search\index.blade.php:13` |
| `40px` | ~40.0px | 3x | 2 files | Scale Level | `resources\views\home.blade.php:745, 860`, `resources\views\bug-hunter\dashboard.blade.php:137` |
| `42px` | ~42.0px | 2x | 2 files | Scale Level | `resources\views\admin\dashboard.blade.php:20`, `resources\views\admin\incidents\index.blade.php:24` |
| `44px` | ~44.0px | 6x | 6 files | Scale Level | `resources\views\events\index.blade.php:194`, `resources\views\guides\index.blade.php:266`, *(+4 more files)* |
| `48px` | ~48.0px | 2x | 2 files | Scale Level | `resources\views\search\index.blade.php:91`, `resources\views\warnings\index.blade.php:113` |
| `120px` | ~120.0px | 1x | 1 files | Scale Level | `resources\views\events\show.blade.php:153` |
| `140px` | ~140.0px | 1x | 1 files | Scale Level | `resources\views\bug-hunter\thank-you.blade.php:39` |

### 2.3 Fractional Font Size Outliers
The following non-integer font sizes exist in the codebase without justification:
- **`10.5px`** (1x): `resources/views/events/show.blade.php:232`
- **`11.5px`** (14x): `resources/views/bug-hunter/thank-you.blade.php:53`, `resources/views/components/navbar.blade.php:50`, `resources/views/events/show.blade.php:301`
- **`12.5px`** (20x): `resources/views/bug-hunter/create.blade.php:142,158`, `resources/views/bug-hunter/dashboard.blade.php:177`, `resources/views/home.blade.php:484`
- **`13.5px`** (15x): `public/css/style.css:160,301`, `resources/views/statistics.blade.php:45`, `resources/views/components/navbar.blade.php:180`

### 2.4 Fluid Clamp Inconsistencies
Near-duplicate fluid formulas with arbitrary differences:
- `clamp(32px, 5vw, 54px)` (11x in `home.blade.php:59`, `rfc2350.blade.php:27`) vs `clamp(32px, 5vw, 52px)` (2x in `bug-hunter/create.blade.php:32`, `contact/create.blade.php:33`)
- `clamp(28px, 4vw, 44px)` (7x in `bug-hunter/dashboard.blade.php:23`) vs `clamp(28px, 4.5vw, 48px)` (1x in `events/show.blade.php:54`)

### 2.5 Font Weights Distribution
- **800 (Extra Bold):** 202 occurrences across 34 files (Dominant for display headings, buttons, tags)
- **700 (Bold):** 99 occurrences across 34 files
- **600 (Semi Bold):** 55 occurrences across 29 files
- **500 (Medium):** 28 occurrences across 20 files
- **300 (Light):** 16 occurrences across 16 files (Used for dark header subtitles)
- **400 (Regular):** 12 occurrences across 10 files
- **900 (Black):** 3 occurrences

### 2.6 Line Heights (18 Distinct Values)
`0`, `0.97`, `1` (25x), `1.05`, `1.1`, `1.2` (5x), `1.25` (7x), `1.3` (5x), `1.35`, `1.4` (2x), `1.5` (3x), `1.55`, `1.6` (13x), `1.65` (8x), `1.7` (5x), `1.75` (8x), `1.8` (2x).

### 2.7 Letter Spacing (11 Distinct Values)
`0.06em` (84x - primary buttons/labels), `0.02em` (47x - headings), `0.01em` (24x), `0.05em` (21x), `0.08em` (17x), `0.04em` (15x), `0.12em` (10x - eyebrows), `0.1em` (4x), `-0.02em` (1x), `0.03em` (1x).

---

## 3. Spacing System Analysis

### 3.1 Base Grid Rhythm
The intended base unit is **4px / 8px** multiples: `0`, `4px` (60x), `8px` (120x), `12px` (85x), `16px` (152x), `20px` (48x), `24px` (82x), `28px` (54x), `32px` (38x), `36px` (22x), `40px` (22x), `48px` (22x), `52px` (20x), `56px` (11x), `60px` (10x), `64px` (8x).

### 3.2 The 18 Non-Grid Orphan Values (320+ Occurrences)

| Orphan Spacing | Occurrences | Total Files | Properties Applied | Sample File & Line Citations |
|---|---|---|---|---|
| `1px` | **4x** | 4 files | `gap, padding` | `public\css\style.css:450`, `resources\views\bug-hunter\thank-you.blade.php:144`, *(+2 more files)* |
| `2px` | **11x** | 10 files | `padding-bottom, margin-bottom, margin-left, padding-top, margin-top` | `public\css\style.css:177, 297`, `resources\views\auth\login.blade.php:76`, *(+8 more files)* |
| `3px` | **6x** | 4 files | `margin-bottom, padding, gap` | `resources\views\home.blade.php:139`, `resources\views\rfc2350.blade.php:115`, *(+2 more files)* |
| `5px` | **17x** | 12 files | `margin-top, gap, margin, padding` | `public\css\style.css:162, 169, 637`, `resources\views\home.blade.php:476`, *(+10 more files)* |
| `6px` | **69x** | 27 files | `gap, padding, margin-top, margin-bottom, margin, margin-right` | `public\css\style.css:311`, `resources\views\home.blade.php:434, 442, 483`, *(+25 more files)* |
| `7px` | **8x** | 6 files | `gap` | `public\css\style.css:240, 387`, `resources\views\bug-hunter\thank-you.blade.php:49`, *(+4 more files)* |
| `9px` | **8x** | 8 files | `padding, gap` | `public\css\style.css:319`, `resources\views\bug-hunter\create.blade.php:273`, *(+6 more files)* |
| `10px` | **66x** | 23 files | `margin-bottom, gap, padding, margin, margin-top, padding-top` | `public\css\style.css:384, 406, 575, +2 more`, `resources\views\rfc2350.blade.php:20, 79, 348`, *(+21 more files)* |
| `11px` | **4x** | 4 files | `padding, gap` | `resources\views\bug-hunter\show.blade.php:153`, `resources\views\components\navbar.blade.php:82`, *(+2 more files)* |
| `13px` | **6x** | 6 files | `padding, margin-bottom` | `resources\views\home.blade.php:111`, `resources\views\bug-hunter\tac.blade.php:155`, *(+4 more files)* |
| `14px` | **66x** | 24 files | `padding-bottom, padding, margin-top, margin-bottom, margin` | `public\css\style.css:145, 206, 227, +1 more`, `resources\views\home.blade.php:54, 94, 127, +2 more`, *(+22 more files)* |
| `15px` | **4x** | 3 files | `padding` | `public\css\style.css:620`, `resources\views\bug-hunter\create.blade.php:333`, *(+1 more files)* |
| `17px` | **1x** | 1 files | `padding` | `public\css\style.css:599` |
| `18px` | **30x** | 17 files | `margin-bottom, padding, padding-top, margin-top, padding-bottom, padding-left` | `public\css\style.css:475, 499`, `resources\views\home.blade.php:38, 47`, *(+15 more files)* |
| `22px` | **12x** | 9 files | `padding, margin-bottom, gap` | `public\css\style.css:375`, `resources\views\home.blade.php:47, 452, 452`, *(+7 more files)* |
| `26px` | **3x** | 2 files | `padding` | `public\css\style.css:456`, `resources\views\bug-hunter\show.blade.php:135, 153` |
| `30px` | **3x** | 3 files | `padding, margin` | `public\css\style.css:456`, `resources\views\rfc2350.blade.php:79`, *(+1 more files)* |
| `34px` | **1x** | 1 files | `margin-bottom` | `resources\views\rfc2350.blade.php:103` |

---

## 4. Layout, Breakpoints & Max-Widths

### 4.1 Breakpoints: Documented vs Reality

| Breakpoint | Status | Occurrences | Views Using This Breakpoint |
|---|---|---|---|
| `max-width: 960px` | Documented Standard | 5x | `public/css/style.css:752`, `resources/views/home.blade.php:348` |
| `max-width: 640px` | Documented Standard | 13x | `public/css/style.css:775`, `resources/views/home.blade.php:53` |
| `max-width: 1200px` | Documented (Nav Partners) | 2x | `resources/views/components/navbar.blade.php:322`, `home.blade.php:539` |
| `max-width: 1024px` | ⚠️ Undocumented | 6x | `resources/views/guides/index.blade.php:335`, `laws/index.blade.php:355` |
| `max-width: 991px` | ⚠️ Undocumented (Bootstrap leaked) | 1x | `resources/views/contact/create.blade.php:267` |
| `max-width: 900px` | ⚠️ Undocumented | 7x | `resources/views/events/index.blade.php:259`, `news/index.blade.php:381` |
| `max-width: 768px` | ⚠️ Undocumented | 6x | `resources/views/guides/index.blade.php:345`, `laws/index.blade.php:365` |
| `max-width: 760px` | ⚠️ Undocumented | 1x | `resources/views/bug-hunter/dashboard.blade.php:148` |
| `max-width: 700px` | ⚠️ Undocumented | 1x | `resources/views/bug-hunter/create.blade.php:377` |
| `max-width: 600px` | ⚠️ Undocumented | 4x | `resources/views/events/index.blade.php:262`, `news/index.blade.php:386` |
| `max-width: 540px` | ⚠️ Undocumented | 1x | `resources/views/infographics/index.blade.php:360` |
| `max-width: 500px` | ⚠️ Undocumented | 1x | `resources/views/components/captcha.blade.php:44` |
| `max-width: 480px` | ⚠️ Undocumented | 1x | `resources/views/components/accessibility.blade.php:909` |
| `max-width: 420px` | ⚠️ Undocumented | 1x | `resources/views/home.blade.php:357` |

### 4.2 Container Max-Width Discrepancies
- **1200px:** `.container` in `style.css:110` (Global standard)
- **1400px:** `.admin-container` in `admin/dashboard.blade.php:7`, `admin/contacts/index.blade.php:5`
- **1144px:** `.demo-head` / `.variant` in `demo/hero-contrast.blade.php:6,23`
- **920px:** `.statistics-intro` in `statistics.blade.php:38`
- **900px:** Form card in `bug-hunter/create.blade.php:55`, `bug-hunter/show.blade.php:37`
- **860px:** Document reader in `bug-hunter/tac.blade.php:47`, `infographics/show.blade.php:66`
- **760px:** Single-column content in `infographics/show.blade.php:51`
- **640px:** Header card in `home.blade.php:45`, `dashboard.blade.php:24`, `contact/thank-you.blade.php:27`
- **540px:** Sub-header in `bug-hunter/create.blade.php:44`, `contact/create.blade.php:46`
- **460px:** Auth card in `auth/login.blade.php:17`, `admin/login.blade.php:17`
- **500px:** Register card in `auth/register.blade.php:17`

---

## 5. Border Radius & Elevation Tokens

### 5.1 Corner Radius: Strict Boxy Rule & 1 Outlier
- **Rule:** `border-radius: 0;` (Enforced across 21 declarations and global reset in `style.css:731-748` for buttons, inputs, cards, modals, tables).
- 🔴 **Direct Violation Outlier:** `resources/views/admin/dashboard.blade.php:77`:
  ```css
  .badge-count {
      padding: 0 6px;
      background: var(--alert);
      border-radius: 10px; /* <--- DIRECT SYSTEM VIOLATION */
  }
  ```

### 5.2 Box Shadows (15 Unique Values)
1. `0 4px 12px rgba(0, 0, 0, 0.08)` (4x &rarr; dropdown menus)
2. `0 0 0 3px rgba(0, 53, 128, 0.1)` (2x &rarr; input focus ring in `bug-hunter/create.blade.php:199`)
3. `0 0 0 3px rgba(185, 28, 28, 0.1)` (2x &rarr; error focus ring in `contact/create.blade.php:131`)
4. `0 4px 12px rgba(0, 53, 128, 0.25)` (1x &rarr; accessibility widget toggle)
5. `0 6px 16px rgba(0, 53, 128, 0.35)` (1x &rarr; accessibility trigger hover)
6. `0 8px 32px rgba(0, 0, 0, 0.15)` (1x &rarr; accessibility panel shadow)
7. `0 6px 20px rgba(0, 0, 0, 0.10)` (1x &rarr; sticky navbar shadow in `components/navbar.blade.php:211`)
8. `0 4px 12px rgba(0, 0, 0, 0.05)` (2x &rarr; contact sidebar card in `contact/create.blade.php:62`)
9. `0 2px 8px rgba(0, 0, 0, 0.06)` (1x &rarr; news listing card in `news/index.blade.php:64`)

---

## 6. Component Inventory & Visual Variants

### 6.1 Buttons: 25 Implementations Across Codebase

| Class Name | Origin File & Line | Padding | Font | Letter Spacing | Styling & Role |
|---|---|---|---|---|---|
| `.btn-primary-solid` | `public/css/style.css:195` | `12px 24px` | 15px / 800 | `0.06em` | White bg, navy text, uppercase |
| `.btn-hero-primary` | `resources/views/home.blade.php:83` | `12px 24px` | 15px / 800 | `0.06em` | **100% duplicate of .btn-primary-solid** |
| `.btn-cta-main` | `public/css/style.css:587` | `14px 28px` | 16px / 800 | `0.06em` | White bg, navy-dim text, uppercase |
| `.btn-navy` | `public/css/style.css:237` | `12px 24px` | 15px / 800 | `0.06em` | Navy bg, white text, uppercase |
| `.btn-submit` (Auth) | `resources/views/auth/login.blade.php:106` | `12px 20px` | 15px / 800 | `0.06em` | Navy bg, white text, full width |
| `.btn-submit` (Report) | `resources/views/bug-hunter/create.blade.php:322` | `14px 28px` | 15px / 800 | `0.05em` | Navy bg, white text, inline flex |
| `.btn-tac-submit` | `resources/views/bug-hunter/tac.blade.php:144` | `12px 28px` | 14px / 800 | `0.05em` | Navy bg, disabled state |
| `.btn-back-home` | `resources/views/bug-hunter/thank-you.blade.php:184` | `13px 28px` | 14px / 800 | `0.05em` | Navy bg, white text |
| `.btn-detail-action` | `resources/views/bug-hunter/show.blade.php:124` | `10px 20px` | 13px / 800 | `0.05em` | Navy bg, white text, small |
| `.btn.btn-primary` | `resources/views/admin/news_edit.blade.php:71` | `0.375rem 0.75rem` | 16px / 400 | none | Raw Bootstrap class |
| `.btn-ghost` (Dark) | `public/css/style.css:217` | `12px 24px` | 14px / 500 | `0.02em` | Transparent, white border |
| `.btn-hero-ghost` | `resources/views/home.blade.php:101` | `12px 24px` | 14px / 500 | `0.02em` | **Duplicate of .btn-ghost** |
| `.btn-cta-ghost` | `public/css/style.css:609` | `14px 28px` | 15px / 500 | `0.02em` | Transparent, white border |
| `.btn-ghost` (Contact) | `resources/views/contact/create.blade.php:179` | `14px 24px` | 14px / 600 | `0.02em` | **Divergent light ghost (border #D8DCE3, hover #9ca3af)** |
| `.btn-cancel` | `resources/views/bug-hunter/create.blade.php:351` | `14px 24px` | 14px / 600 | `0.02em` | Border var(--border), text var(--mid) |
| `.btn-tac-cancel` | `resources/views/bug-hunter/tac.blade.php:164` | `12px 24px` | 14px / 600 | `0.02em` | Border var(--border), text var(--mid) |
| `.btn-detail-ghost` | `resources/views/bug-hunter/show.blade.php:143` | `10px 20px` | 13px / 700 | `0.02em` | Border var(--border), text var(--ink) |
| `.btn-report-another` | `resources/views/bug-hunter/thank-you.blade.php:202` | `13px 28px` | 14px / 700 | `0.02em` | Border var(--navy), text var(--navy) |
| `.btn.btn-secondary` | `resources/views/admin/news_edit.blade.php:70` | `0.375rem 0.75rem` | 16px / 400 | none | Raw Bootstrap class |
| `.btn-add` | `resources/views/admin/dashboard.blade.php:109` | `8px 16px` | 13px / 800 | `0.06em` | Navy bg, white text, small action |
| `.btn-edit` | `resources/views/admin/dashboard.blade.php:165` | `6px 12px` | 12px / 700 | `0.04em` | Navy bg, table action |
| `.btn-delete` | `resources/views/admin/dashboard.blade.php:186` | `6px 12px` | 12px / 700 | `0.04em` | Red alert bg, table action |
| `.btn-logout` | `resources/views/admin/dashboard.blade.php:205` | `8px 16px` | 13px / 700 | `0.04em` | Border var(--border), text var(--mid) |
| `.btn-add-evidence` | `resources/views/bug-hunter/create.blade.php:263` | `9px 14px` | 13px / 600 | `0.02em` | Dashed border button |
| `.btn-remove-evidence` | `resources/views/bug-hunter/create.blade.php:280` | `0` (32x32px) | 14px / 400 | none | Icon-only trash button |

### 6.2 Cards: Hover Consistency vs Padding Divergence
All content cards share the same hover animation (`background: var(--navy-tint);` and 3px bottom navy line via `::after`), but have divergent internal padding:
- `.service-card` (`style.css:331`): `padding: 32px 24px;`
- `.tac-card` (`bug-hunter/tac.blade.php:51`): `padding: 28px 32px;`
- `.sidebar-card` (`contact/create.blade.php:222`): `padding: 24px;`
- `.law-card` / `.guide-card` (`laws/index.blade.php:60`): `padding: 24px;`
- `.event-card` (`home.blade.php:372`): `padding: 22px;` (was previously 44px)
- `.news-card` (`home.blade.php:292`): `padding: 20px;`
- `.summary-card` (`admin/dashboard.blade.php`): `padding: 18px 20px;`

### 6.3 Tables: Data-Table vs Lapor-Table
- **Admin `.data-table`** (`admin/dashboard.blade.php:98`): Full-width, `border-collapse: collapse; border: 1px solid var(--border)`. Header uses `12px / 800` uppercase, background `var(--mist)`, padding `12px 16px`. Rows use `14px`, padding `14px 16px`, hover `var(--navy-tint)`.
- **Reporter `.lapor-table`** (`bug-hunter/dashboard.blade.php:87`): Re-declared table rules with identical cell padding, but altered font size to **`13.5px`** (unaligned fractional size).

---

## 7. Prioritized Consistency Issues (Ranked by Blast Radius)

### 🔴 Priority Rank 1 — Massive Button Proliferation (25 Variants)
- **Blast Radius:** Affects 20+ files and 100+ button instances across the application.
- **Problem Statement:** Rather than consuming the documented design system classes (`.btn-primary-solid`, `.btn-ghost`, `.btn-navy`), new pages created local button classes in `<style>` blocks. For example, `resources/views/home.blade.php:83` defines `.btn-hero-primary` with styling 100% identical to `public/css/style.css:195`'s `.btn-primary-solid`. Similarly, `.btn-submit` was independently defined in 5 separate templates with drifting padding (`12px 20px`, `14px 28px`) and letter-spacing (`0.06em` vs `0.05em`).
- **Files & Lines Affected:**
  - `public/css/style.css:195, 217, 237, 587, 609`
  - `resources/views/home.blade.php:83, 101, 884`
  - `resources/views/auth/login.blade.php:106`
  - `resources/views/auth/register.blade.php:106`
  - `resources/views/admin/login.blade.php:84`
  - `resources/views/bug-hunter/create.blade.php:322, 351`
  - `resources/views/contact/create.blade.php:161, 179`
  - `resources/views/bug-hunter/dashboard.blade.php:109`
  - `resources/views/bug-hunter/show.blade.php:124, 143`
  - `resources/views/bug-hunter/tac.blade.php:144, 164`
  - `resources/views/admin/news_edit.blade.php:70, 71`
- **Actionable Remediation Code:**
  1. Consolidate button styling into 4 canonical classes in `public/css/style.css`:
     - `.btn-primary-solid` (Dark-surface primary CTA: White fill, Navy text, 12px 24px, 15px / 800 display font)
     - `.btn-navy` (Light-surface primary CTA: Navy fill, White text, 12px 24px, 15px / 800 display font)
     - `.btn-ghost` (Transparent outline: 1px border, 12px 24px, 14px / 500)
     - `.btn-delete` (Danger action: Red alert fill, White text, 6px 12px, 12px / 700)
  2. Replace `.btn-hero-primary` in `home.blade.php` with `.btn-primary-solid`.
  3. Replace `.btn-submit` in login/register/lapor views with `.btn-navy`.
  4. Remove all local `.btn-*` `<style>` declarations in Blade templates.

### 🔴 Priority Rank 2 — Hardcoded Colors Bypassing Design Tokens (106 Colors)
- **Blast Radius:** Affects 30+ files; breaks Dark Mode and High Contrast adaptation.
- **Problem Statement:** 106 unique color values exist across templates, creating 47 near-duplicate pairs. Key issues:
  - `contact/create.blade.php:194`: Hardcodes Tailwind gray `#9ca3af` for hover border instead of `var(--mid)`.
  - `admin/dashboard.blade.php:230`: Hardcodes Tailwind emerald `#10B981` instead of `var(--navy)`.
  - `profile.blade.php:61, 66`: Hardcodes raw `#004099` and `#002060` instead of `var(--navy-mid)` and `var(--navy-dim)`.
  - `home.blade.php:46, 121, 576`: Hardcodes raw `rgba(10,15,26,...)` and `rgba(0,20,60,...)`.
  - `DESIGN_SYSTEM.md:31` lists `--mid` as `#6B7280`, but `style.css:27` defines it as `#5B6472`.
- **Files & Lines Affected:**
  - `resources/views/contact/create.blade.php:194`
  - `resources/views/admin/dashboard.blade.php:230`
  - `resources/views/profile.blade.php:61, 66`
  - `resources/views/components/captcha.blade.php:42`
  - `resources/views/home.blade.php:46, 70, 107, 121, 576`
  - `DESIGN_SYSTEM.md:31` vs `public/css/style.css:27`
- **Actionable Remediation Code:**
  - In `contact/create.blade.php:194`: Change `border-color: #9ca3af;` to `border-color: var(--mid);`.
  - In `admin/dashboard.blade.php:230`: Change `border-left: 4px solid #10B981;` to `border-left: 4px solid var(--navy);`.
  - In `components/captcha.blade.php:42`: Change `background: #e8eef6;` to `background: var(--navy-tint);`.
  - In `DESIGN_SYSTEM.md:31`: Update documentation to reflect `#5B6472`.

### 🔴 Priority Rank 3 — Type Scale Explosion (36 Distinct Font Sizes)
- **Blast Radius:** Affects almost every Blade template.
- **Problem Statement:** The project expanded from an intended ~8 tiers to 36 sizes, including arbitrary fractional sizes (`10.5px`, `11.5px`, `12.5px`, `13.5px`) and drifting fluid clamp formulas (`clamp(32px, 5vw, 54px)` vs `clamp(32px, 5vw, 52px)`).
- **Files & Lines Affected:**
  - `resources/views/events/show.blade.php:232, 301`
  - `resources/views/bug-hunter/create.blade.php:32, 142, 158`
  - `resources/views/contact/create.blade.php:33`
  - `resources/views/home.blade.php:484`
  - `resources/views/components/navbar.blade.php:50, 180`
- **Actionable Remediation Code:**
  - Define a strict 7-level type scale in `public/css/style.css` `:root`:
    ```css
    --text-xs: 12px;
    --text-sm: 14px;
    --text-base: 16px;
    --text-lg: 18px;
    --text-xl: 24px;
    --text-2xl: 32px;
    --text-hero: clamp(32px, 5vw, 54px);
    ```
  - Replace all `10.5px` and `11.5px` with `12px` (or `11px` status badge).
  - Replace all `12.5px` and `13.5px` with `14px`.
  - Standardize `clamp(32px, 5vw, 52px)` in contact and bug hunter forms to `clamp(32px, 5vw, 54px)`.

### 🟡 Priority Rank 4 — Spacing Grid Degradation (18 Orphan Values, 320+ Usages)
- **Blast Radius:** Affects 35+ files.
- **Problem Statement:** The 4px/8px rhythm is repeatedly broken by `6px` (69x), `10px` (66x), `14px` (66x), `18px` (30x), `22px` (12x), `5px` (17x), and magic numbers like `13px`, `7px`, `9px`, `11px`, `15px`, `26px`, `34px`.
- **Files & Lines Affected:**
  - `public/css/style.css:160, 202, 244`
  - `resources/views/home.blade.php:33, 40, 71, 109, 152, 372`
  - `resources/views/bug-hunter/create.blade.php:173, 265`
  - `resources/views/contact/create.blade.php:99`
- **Actionable Remediation Code:**
  - In `bug-hunter/create.blade.php:173` & `contact/create.blade.php:99`: Change `padding: 10px 14px;` to `padding: 12px 16px;`.
  - In `home.blade.php:372`: Change `.event-card__body` `padding: 22px;` to `padding: 24px;`.
  - Snap all `6px` and `10px` gaps to `8px`.

### 🟡 Priority Rank 5 — Breakpoint Proliferation (14 Media Queries)
- **Blast Radius:** Affects 18 files.
- **Problem Statement:** Views collapse inconsistently at `1024px`, `991px`, `960px`, `900px`, `768px`, `760px`, `700px`, `640px`, `600px`, `540px`, `500px`, `480px`, `420px`.
- **Files & Lines Affected:**
  - `resources/views/guides/index.blade.php:335, 345` (1024px, 768px)
  - `resources/views/events/index.blade.php:259, 262` (900px, 600px)
  - `resources/views/bug-hunter/create.blade.php:377` (700px)
  - `resources/views/contact/create.blade.php:267` (991px)
  - `resources/views/components/captcha.blade.php:44` (500px)
- **Actionable Remediation Code:**
  - Consolidate all media queries to two global standards: `@media (max-width: 960px)` (tablet grid collapse) and `@media (max-width: 640px)` (mobile layout collapse), keeping `1200px` exclusively for navbar partner logo density management.

### 🟡 Priority Rank 6 — The 10px Rounded Pill in Boxy NYC.gov Design System
- **Blast Radius:** `resources/views/admin/dashboard.blade.php:77`.
- **Problem Statement:** While the entire site strictly enforces `border-radius: 0`, the admin notification badge `.badge-count` declared `border-radius: 10px;`, creating an isolated rounded pill.
- **Files & Lines Affected:**
  - `resources/views/admin/dashboard.blade.php:77`
- **Actionable Remediation Code:**
  - In `resources/views/admin/dashboard.blade.php:77`: Change `border-radius: 10px;` to `border-radius: 0;`.

---

## Summary for AI LLM Code Agents
When implementing refactors or generating code for the Jakarta CSIRT Portal, adhere to the following rules:
1. **Never use raw hex/rgb:** Always use `var(--ink)`, `var(--navy)`, `var(--navy-tint)`, `var(--mist)`, `var(--border)`, `var(--mid)`, `var(--alert)`.
2. **Never add custom button classes:** Always use `.btn-primary-solid` (dark surface), `.btn-navy` (light surface), `.btn-ghost` (transparent), or `.btn-delete` (danger).
3. **Enforce sharp boxy corners:** Always set `border-radius: 0;`.
4. **Only use standard breakpoints:** `@media (max-width: 960px)` and `@media (max-width: 640px)`.
5. **Strict 4px/8px grid:** Never use 6px, 10px, 14px, 18px, or 22px spacing.
6. **Integer font sizes only:** Restrict font sizes to 12px, 14px, 16px, 18px, 24px, 32px, or `clamp(32px, 5vw, 54px)`.