# FEATURES.md — Jakarta Prov CSIRT Portal

> Complete feature inventory for the DKI Jakarta Cybersecurity Portal (csirt.jakarta.go.id).
> For developers taking over this project: this file tells you **what exists and why**.

---

## Overview

JakartaProv-CSIRT is a public-facing portal for the Computer Security Incident Response Team of DKI Jakarta Provincial Government. It serves two audiences:

1. **Public users** — citizens and government employees who need to report incidents, read security news, or access cybersecurity resources
2. **Admin users** — CSIRT staff who manage content and review incident reports

---

## Public Pages

### Home (`/`)
- **Purpose:** Landing page, main entry point
- **Features:**
  - Hero section with "Lapor Insiden" CTA
  - Alert strip for active security warnings
  - Latest news cards (3 most recent)
  - Services grid linking to warnings, infographics, laws, guides
  - Footer CTA section with 24/7 hotline info
- **Route:** `home` → `HomeController@index`

### News (`/news`, `/news/{id}`)
- **Purpose:** Cybersecurity news articles
- **Features:**
  - Paginated listing (6 per page) with year filter
  - Individual article view with sidebar
- **Routes:** `news.index`, `news.show`

### Events (`/events`, `/events/{id}`)
- **Purpose:** Security awareness events, webinars, sosialisasi
- **Features:**
  - Paginated listing (6 per page) sorted by date
  - Individual event view with registration link, capacity, related events
- **Routes:** `events.index`, `events.show`

### Warnings (`/warnings`, `/warnings/{id}`)
- **Purpose:** Active security warnings and advisories
- **Features:**
  - Paginated listing (12 per page)
  - Individual warning view with severity indicators
- **Routes:** `warnings.index`, `warnings.show`

### Infographics (`/infographics`, `/infographics/{id}`)
- **Purpose:** Visual cybersecurity education materials
- **Features:**
  - Grid gallery with lightbox preview
  - Individual infographic view
- **Routes:** `infographics.index`, `infographics.show`

### Laws & Regulations (`/laws`, `/laws/{id}`)
- **Purpose:** Government regulations and policies related to cybersecurity
- **Features:**
  - Paginated listing (12 per page) with sidebar filter
  - Individual law view with document download
- **Routes:** `laws.index`, `laws.show`

### Technical Guides (`/guides`, `/guides/{id}`)
- **Purpose:** Cybersecurity guides and best practices
- **Features:**
  - Paginated listing (12 per page) with sidebar filter
  - Individual guide view with external link
- **Routes:** `guides.index`, `guides.show`

### Search (`/search?q=...`)
- **Purpose:** Site-wide search across all content types
- **Features:**
  - Searches titles and descriptions across 6 content types
  - Results grouped by type (news, warnings, events, infographics, laws, guides)
  - Minimum 2 characters required
- **Route:** `search` → `SearchController@index`

### Profile (`/profile`)
- **Purpose:** About Jakarta CSIRT, mission, services
- **Features:**
  - Organization profile with mission statement
  - Service descriptions (incident response, preventive)
  - CTA to report incidents
- **Route:** `profile`

---

## Incident Portal (Bug-Hunter Flow)

### Report Flow (`/bug-hunter`)
- **Purpose:** Public incident reporting for reporters ("bug hunters") with ticket tracking
- **Features:**
  - Public registration/login (no 2FA), gated via `is_bug_hunter` flag
  - Terms & Conditions gate (versioned `tac_agreements`, scroll-to-bottom + checkbox)
  - Komdigi-style **single-page report form**: Kategori Insiden, Waktu Kejadian,
    Lokasi Insiden / URL, Down Time, Deskripsi, Tindakan Teknis, Bukti Laporan
    (≤3 rows, each File OR URL, ≤5MB each)
  - Files stored in `storage/app/public/bukti_laporan/`
  - Ticket numbers `INS-YYYY-XXXX`, reporter dashboard with per-ticket detail
  - 5-state status flow: `menunggu_validasi → divalidasi → ditindaklanjuti → dipulihkan → selesai` (+ `ditolak`)
  - Rate limited: 60 requests/minute per IP
- **Routes:** `bug-hunter.dashboard`, `bug-hunter.tac`, `bug-hunter.agree`, `bug-hunter.create`, `bug-hunter.store`, `bug-hunter.thank-you`, `bug-hunter.show`
- **Admin review:** `/admin/incidents` (assign CWE/Severity, advance status)

### Thank You (`/bug-hunter/laporan/selesai`)
- **Purpose:** Confirmation with the ticket number after successful submission
- **Route:** `bug-hunter.thank-you`

---

## Contact

### Contact Form (`/contact`)
- **Purpose:** General inquiries, partnership requests
- **Features:**
  - Fields: name, email, phone, organization, subject, inquiry type, message
  - Rate limited: 60 requests/minute per IP
  - Stored in `contact_us` table with `pending` status
- **Routes:** `contact.create`, `contact.store`, `contact.thank-you`

---

## Authentication

| Route | Status |
|-------|--------|
| `/register` | Real — public bug-hunter registration (`AuthController@register`) |
| `/login` | Real — public login (works for admin too, `is_admin` check) |
| `/logout` | Real — POST logout (`AuthController@logout`) |
| `/profile` | Placeholder — "under development" page |
| `/dashboard` | Placeholder — "under development" page |

Public auth exists **only** for the incident subsystem. See the Incident Portal section.

---

## Admin Panel

### Access
- **URL:** `/admin/login`
- **Credentials:** `admin@gmail.com` / `12345678` (seeder default)
- **Middleware:** `auth` + `admin` (checks `is_admin` flag in `users` table)

### Dashboard (`/admin`)
- **Purpose:** Central management hub for all content
- **Features:**
  - Tabbed interface (News, Events, Infographics, Warnings, Laws, Guides)
  - Each tab shows a table of records with Edit/Delete actions
  - "Add" button opens a modal form for new records
  - Pagination: 15 records per tab
  - Tab state preserved across pagination via URL hash
- **Route:** `admin.dashboard`

### CRUD Operations

Each content type has full CRUD (Create, Read, Update, Delete):

| Content Type | List | Create | Edit | Delete |
|---|---|---|---|---|
| News | `admin.news.list` | `admin.news.store` | `admin.news.edit` | `admin.news.delete` |
| Events | `admin.events.list` | `admin.event.store` | `admin.event.edit` | `admin.event.delete` |
| Warnings | `admin.warnings.list` | `admin.warning.store` | `admin.warning.edit` | `admin.warning.delete` |
| Laws | `admin.laws.list` | `admin.law.store` | `admin.law.edit` | `admin.law.delete` |
| Guides | `admin.guides.list` | `admin.guide.store` | `admin.guide.edit` | `admin.guide.delete` |
| Infographics | `admin.infographics.list` | `admin.infographic.store` | `admin.infographic.edit` | `admin.infographic.delete` |

All admin write operations are wrapped in try/catch with Indonesian error messages.

---

## Accessibility

- **Widget:** `components/accessibility.blade.php` — floating panel with 3 modes:
  - High contrast mode
  - Dark contrast mode
  - Default mode
- **Implementation:** CSS custom properties overridden via `accessibility-contrast.css`
- **JavaScript:** `public/js/accessibility.js` toggles classes on `<html>` element

---

## Design System

All pages follow a consistent design system documented in `DESIGN_SYSTEM.md`:
- Dark header pattern with NYC.gov-inspired styling
- CSS custom properties for all colors and fonts
- Custom card system (not Bootstrap cards)
- Bootstrap used only for grid, forms, and tables

---

## Seed Data

On fresh migration, the database is seeded with:

| Table | Records | Description |
|---|---|---|
| `users` | 1 | Admin user |
| `berita_siber` | 5 | Cybersecurity news articles |
| `event` | 6 | Past security events |
| `peraturan_kebijakan` | 1 | Sample regulation |
| `peringatan_keamanan` | 2 | Security warnings |

**Empty on fresh install:** `panduan_teknis`, `infografis_keamanan`, `contact_us`, `lapor_insiden`

---

## File Structure Reference

```
resources/views/
├── layouts/app.blade.php          # Master layout (navbar, footer, a11y widget)
├── components/
│   ├── navbar.blade.php           # Site navigation with search, auth-aware CTA
│   ├── footer.blade.php           # Site footer
│   └── accessibility.blade.php    # Accessibility widget
├── home.blade.php                 # Landing page
├── profile.blade.php              # About CSIRT
├── rfc2350.blade.php              # RFC 2350 page
├── statistics.blade.php           # Archived honeypot statistics
├── search/index.blade.php         # Search results
├── auth/
│   ├── register.blade.php         # Public registration (bug hunters)
│   └── login.blade.php            # Public login
├── bug-hunter/
│   ├── dashboard.blade.php        # Reporter ticket dashboard
│   ├── tac.blade.php              # Terms & Conditions gate
│   ├── create.blade.php           # Single-page report form
│   ├── thank-you.blade.php        # Confirmation with ticket number
│   └── show.blade.php             # Per-ticket detail
├── news/                          # News listing + detail
├── events/                        # Events listing + detail
├── warnings/                      # Warnings listing + detail
├── infographics/                  # Infographics listing + detail
├── laws/                          # Laws listing + detail
├── guides/                        # Guides listing + detail
├── contact/                       # Contact form + thank you
├── admin/                         # Dashboard + CRUD partials + edit pages
│   └── incidents/                 # Incident review list + detail
├── dashboard.blade.php            # Placeholder
└── (publickey is a download route — no view)
```
