# FEATURES.md — Jakarta Prov CSIRT Portal

> Complete feature inventory for the DKI Jakarta Cybersecurity Portal (csirt.jakarta.go.id).
> For developers接手 this project: this file tells you **what exists and why**.

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

## Incident Reporting

### Report Form (`/report-incident`)
- **Purpose:** Multi-step incident reporting wizard
- **Features:**
  - 3-step JS wizard (no page reloads between steps):
    - **Step 1:** Reporter data (name, email, phone, date found)
    - **Step 2:** Website data (domain, URL)
    - **Step 3:** Incident details (description, risk type/level, CVSS score, evidence, recommendation)
  - CAPTCHA verification ("JKT" / "jkt")
  - File upload for proof screenshots (PNG/JPG, max 2MB)
  - Risk fields are optional — designed for non-technical users
  - Rate limited: 60 requests/minute per IP
- **Routes:** `incidents.create.step1`, `incidents.store`, `incidents.thank-you`
- **Storage:** Files stored in `storage/app/public/proof_pics/`

### Thank You (`/report-incident/thank-you`)
- **Purpose:** Confirmation after successful report submission
- **Route:** `incidents.thank-you`

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

## Authentication (Placeholders)

| Route | Status |
|-------|--------|
| `/login` | Placeholder — "under development" page |
| `/register` | Placeholder — "under development" page |
| `/dashboard` | Placeholder — "under development" page |

These pages extend the layout but have no functionality yet.

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
├── layouts/app.blade.php          # Master layout (navbar, footer, assets)
├── components/
│   ├── navbar.blade.php           # Site navigation with search
│   ├── footer.blade.php           # Site footer
│   └── accessibility.blade.php    # Accessibility widget
├── home.blade.php                 # Landing page
├── profile.blade.php              # About CSIRT
├── search/index.blade.php         # Search results
├── news/                          # News listing + detail
├── events/                        # Events listing + detail
├── warnings/                      # Warnings listing + detail
├── infographics/                  # Infographics listing + detail
├── laws/                          # Laws listing + detail
├── guides/                        # Guides listing + detail
├── incidents/                     # Report form + thank you
├── contact/                       # Contact form + thank you
├── admin/                         # Admin dashboard + CRUD partials + edit pages
├── login.blade.php                # Placeholder
├── register.blade.php             # Placeholder
├── dashboard.blade.php            # Placeholder
├── statistics.blade.php           # Placeholder
├── publickey.blade.php            # Placeholder
└── rfc2350.blade.php              # Placeholder
```
