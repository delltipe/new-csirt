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

## Incident Reporting (Bug Hunter Portal)

Reporters register/login publicly (`/register`, `/login` — no 2FA, no email
verification) and reach a Komdigi-style reporter portal under `/bug-hunter`.

### Registration & Login (`/register`, `/login`)
- **Purpose:** Public account creation for incident reporters
- **Features:**
  - Register: name, email, password (min 8, confirmation) → `is_bug_hunter = true`
  - Login redirects admins to `/admin` and bug hunters to the TaC gate
  - No 2FA, no email verification, no password reset
- **Routes:** `register`, `register.submit`, `login`, `login.submit`, `logout`

### Terms & Conditions Gate (`/bug-hunter/laporan`)
- **Purpose:** Consent gate before reporting; versioned so a new T&C re-asks
- **Features:**
  - One-time accept per `TAC_VERSION` (`2026.08`), stored in `tac_agreements`
  - Users who already agreed go straight to the form
- **Routes:** `bug-hunter.tac`, `bug-hunter.agree`

### Report Form (`/bug-hunter/laporan/baru`)
- **Purpose:** Single-page incident report form (replaces the old 3-step wizard)
- **Features:**
  - Fields: `kategori_insiden` (dropdown), `waktu_kejadian`, `lokasi_url`,
    `down_time`, `deskripsi`, `tindakan_teknis`, plus up to **3 bukti** rows
    (each a file **or** URL)
  - File types: png/jpg/jpeg/gif/pdf, max 5MB → `storage/app/public/bukti_laporan/`
  - One POST, one `validate()` — no per-step routes
  - Rate limited: 60 requests/minute per IP
  - Creates an `IncidentReport` (status `menunggu_validasi`) with ticket number
    `INS-YYYY-XXXX` + `LampiranInsiden` rows
- **Routes:** `bug-hunter.create`, `bug-hunter.store`

### Reporter Dashboard (`/bug-hunter`)
- **Purpose:** Ticket list for the logged-in reporter
- **Features:**
  - Columns: `No | No Tiket | Tanggal Pengajuan | Jenis Laporan | CWE | Severity | Status | Aksi`
  - Row action links to the per-ticket detail page
- **Routes:** `bug-hunter.dashboard`

### Ticket Detail (`/bug-hunter/laporan/{id}`)
- **Purpose:** Full report view, scoped to the logged-in reporter
- **Features:**
  - Report fields + attachment list (files linked via `storage/`, URLs external)
  - Shows current status label
- **Route:** `bug-hunter.show` — static paths (`baru`, `selesai`) MUST be declared before this dynamic route

### Thank You (`/bug-hunter/laporan/selesai`)
- **Purpose:** Confirmation after successful report submission, shows the ticket number
- **Route:** `bug-hunter.thank-you`

### Status Flow
- `menunggu_validasi → divalidasi → ditindaklanjuti → dipulihkan → selesai`
- `ditolak` reachable from `menunggu_validasi`, `divalidasi`, `ditindaklanjuti`
- Transitions + labels live on `App\Models\IncidentReport`

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
| `/login` | Public login — admins → `/admin`, bug hunters → TaC gate (`AuthController@login`) |
| `/register` | Public registration — creates `is_bug_hunter = true` user (`AuthController@register`) |
| `/admin/login` | Admin-only login (`AdminController@login`, checks `is_admin`) |
| `/logout` | Session destroy |
| `/dashboard` | Placeholder — "under development" page |

Admins use the standard `users` table; there is no separate admin model.

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

### Incident Review

- **List:** `GET /admin/incidents` — paginated (15/page), newest first, filterable by `status`
- **Detail:** `GET /admin/incidents/{id}` — full report incl. attachments + reporter info
- **Review:** `POST /admin/incidents/{id}/review` — assign `cwe` (string) + `severity` (Low/Medium/High/Critical) and transition `status` (validated via `canTransitionTo()`)
- Reached from the "Insiden" tab on the admin dashboard (shows a pending-count badge)

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

**Empty on fresh install:** `panduan_teknis`, `infografis_keamanan`, `contact_us`, `lapor_insiden`, `lampiran_insiden`, `tac_agreements`

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
├── auth/                          # login.blade.php + register.blade.php
├── bug-hunter/                    # TaC gate, report form, dashboard, detail, thank-you
├── contact/                       # Contact form + thank you
├── admin/                         # Admin dashboard + CRUD partials + edit pages + incidents/
├── dashboard.blade.php            # Placeholder
├── statistics.blade.php           # Placeholder
├── publickey.blade.php            # Placeholder
└── rfc2350.blade.php              # Placeholder
```
