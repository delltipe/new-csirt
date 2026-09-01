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
  - Hero slider (admin-updatable `slide_hero` — 4 slides: `JAKARTAPROVCSIRT` static + 3 scraped legacy banners `csirt.jakarta.go.id/images/banner/...`, `min-height 340px` `clamp 2` lines, `scrim` per slide, same-tab `tautan` → `/`, `/news/13`, `/news/14`, `berita_siber` internal; fallback single static slide; `HomeController@index` loads `HeroSlide::active()->ordered()`)
  - Alert strip for active security warnings
  - Latest news carousel (6 most recent, whole-box clickable `<a class="news-carousel__card">`, `hover var(--navy-tint)` + bottom 3px `var(--navy)` line + image zoom)
  - Services grid (`service-card` title now `text-transform:none 700` not caps, `hover var(--navy-tint)` + bottom line + icon `var(--navy)`)
  - Events grid (`gap 16px`, `event-card` `padding 22px` `bottom line`, 1px border)
  - Footer CTA section with 24/7 hotline info
  - Content background: `alert-strip` + `news/services/events` share one `div.content-bg-wrap.wash` (`linear-gradient white 32%→mist 92%`) with two desaturated halftone edge glows (Both L+R, 560×760 at -110px, slate `#94A4BC` dot `1.40px rgba(148,164,188,0.92)` `opacity 0.96` `glow 0.24`, mask `92%×70% black 58%`, `brighten` on proximity, CTA `Temukan Insiden…` stays `var(--navy-dim)` outside — see `DESIGN_SYSTEM.md` → Content Background Wrapper)
  - Pagination boxy navy `vendor/pagination/tailwind.blade.php` (`ul gap:4px` `36px` `var(--border)` `hover var(--navy-tint)` `aria-current var(--navy)`)
- **Route:** `home` → `HomeController@index`

### News (`/news`, `/news/{id}`)
- **Purpose:** Cybersecurity news articles
- **Features:**
  - Paginated listing (6 per page, `news-list-grid` `gap 16px` `card border 1px` `hover var(--navy-tint)` + bottom line + image zoom, whole-box clickable `<a class="news-list-card">`; boxy navy pagination) with year filter
  - Individual article view with sidebar
  - Seeded hero-linked news `berita_siber 13/14` (`Penipuan Hacker… PowerShell`, `Ako Ransomware… Windows APIs`) for hero tautan
- **Routes:** `news.index`, `news.show`

### Events (`/events`, `/events/{id}`)
- **Purpose:** Security awareness events, webinars, sosialisasi
- **Features:**
  - Paginated listing (6 per page `gap 16px` `card border 1px` `padding 22px` `bottom line` `hover var(--navy-tint)` sorted by date) with boxy navy pagination
  - Individual event view with registration link, capacity, related events
- **Routes:** `events.index`, `events.show`

### Warnings (`/warnings`, `/warnings/{id}`)
- **Purpose:** Active security warnings and advisories
- **Features:**
  - Paginated listing (12 per page, boxy navy pagination)
  - Individual warning view with severity indicators
- **Routes:** `warnings.index`, `warnings.show`

### Infographics (`/infographics`, `/infographics/{id}`)
- **Purpose:** Visual cybersecurity education materials
- **Features:**
  - Grid gallery (`gap 16px` `infographic-card` `border 1px` `bottom line` `hover var(--navy-tint)` + zoom, whole-box clickable `<button>`) with lightbox preview, boxy navy pagination
  - Individual infographic view
- **Routes:** `infographics.index`, `infographics.show`

### Laws & Regulations (`/laws`, `/laws/{id}`)
- **Purpose:** Government regulations and policies related to cybersecurity
- **Features:**
  - Paginated listing (12 per page `law-card` `border 1px` `overflow hidden` `bottom line` `hover var(--navy-tint)` with sidebar filter, boxy navy pagination)
  - Individual law view with document download
- **Routes:** `laws.index`, `laws.show`

### Technical Guides (`/guides`, `/guides/{id}`)
- **Purpose:** Cybersecurity guides and best practices
- **Features:**
  - Paginated listing (12 per page `guide-card` `bottom line` like law, boxy navy pagination) with sidebar filter
  - Individual guide view with external link
- **Routes:** `guides.index`, `guides.show`

### Search (`/search?q=...`)
- **Purpose:** Site-wide search across all content types
- **Features:**
  - Searches titles (+ `author` for guides) and descriptions across 6 content types, tokenized `>=2` chars up to 5 terms, `title 3`/`description 1`/`author 2` weighted ranking + exact `+2` + word-boundary `+1`, sorted `score desc, date/id`, `take 10` per type
  - Results grouped by type with `<mark style="background:var(--navy-tint)">` highlight + `excerpt` snippet `…pos-60…` (180 chars)
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
  - Fields: name, email, phone, organization, subject, inquiry type, message + `MathCaptcha`
  - Rate limited: 60 requests/minute per IP
  - Stored in `contact_us` table with `pending` status + `admin_note`
- **Routes:** `contact.create`, `contact.store`, `contact.thank-you`

### Contact Admin Review (`/admin/contacts`)
- **Purpose:** CSIRT triage for general inquiries
- **Features:**
  - List `GET /admin/contacts` filterable `?status=pending/diproses/selesai/ditolak` (15/page, boxy navy pagination)
  - Detail `GET /admin/contacts/{id}` + update `POST /admin/contacts/{id}/update` `status` (`pending→diproses→selesai` + `ditolak` from `pending/diproses`, `canTransitionTo()` + `admin_note`) + delete `POST /admin/contacts/{id}/delete`
  - Dashboard tab `Kontak` with `pendingContacts` badge (like `Insiden`)
- **Routes:** `admin.contacts.list/show/update/delete` (`auth+admin`)

---

## Authentication

| Route | Status |
|-------|--------|
| `/login` | Public login — admins → `/admin`, bug hunters → TaC gate (`AuthController@login`) |
| `/register` | Public registration — creates `is_bug_hunter = true` user (`AuthController@register`) |
| `/admin/login` | Admin-only login (`AdminController@login`, checks `is_admin`) |
| `/logout` | Session destroy (POST logout via `AuthController@logout`) |
| `/profile` | Placeholder — "under development" page |
| `/dashboard` | Placeholder — "under development" page |

---

## Admin Panel

### Access
- **URL:** `/admin/login`
- **Credentials:** `admin@gmail.com` / `12345678` (seeder default)
- **Middleware:** `auth` + `admin` (checks `is_admin` flag in `users` table)

### Dashboard (`/admin`)
- **Purpose:** Central management hub for all content
- **Features:**
  - Tabbed interface (News, Events, Infographics, Warnings, Laws, Guides, Insiden, Hero)
  - Each tab lists records in the insiden-style custom `.data-table` (boxy, uppercase headers, Edit/Delete actions)
  - "Add" button (`.btn-add`) opens a modal form for new records
  - Pagination: 15 records per tab (boxy navy `vendor/pagination/tailwind.blade.php` `public/css/style.css:705`)
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
| Hero Slider | `admin.hero.store` | `admin.hero.store` | `admin.hero.edit` | `admin.hero.delete` | (+ `admin.hero.reorder` `POST /admin/hero/reorder`) |

All admin write operations are wrapped in try/catch with Indonesian error messages.

### Incident Review

- **List:** `GET /admin/incidents` — paginated (15/page), newest first, filterable by `status`
- **Detail:** `GET /admin/incidents/{id}` — full report incl. attachments + reporter info
- **Review:** `POST /admin/incidents/{id}/review` — assign `cwe` (string) + `severity` (Low/Medium/High/Critical) and transition `status` (validated via `canTransitionTo()`)
- **Soft-delete:** `POST /admin/incidents/{id}/delete` (button on the detail page) — soft-deletes only (`SoftDeletes`); trashed reports leave the list and 404 on review, legal-evidence retention
- Reached from the "Insiden" tab on the admin dashboard (shows a pending-count badge)

### Hero Slider Admin

- **List/Edit:** Hero tab on `/admin` (`admin/partials/hero.blade.php` `.data-table`, `urutan` + `is_active` badge)
- **Create:** `POST /admin/hero` — `judul` required, `subjudul` nullable, `gambar` `file|mimes:jpg,jpeg,png,webp|max:5120` → `storage/app/public/hero/` or `gambar_url` external, `tautan` nullable, `teks_tautan` default `LAPOR INSIDEN SEKARANG`, `urutan` int, `is_active` bool
- **Edit:** `GET /admin/hero/{id}/edit` → `POST /admin/hero/{id}/update` (deletes old file if new upload)
- **Delete:** `POST /admin/hero/{id}/delete`
- **Reorder:** `POST /admin/hero/reorder` — `order` comma string `3,1,2` → updates `urutan`

---

## Accessibility

- **Widget:** `components/accessibility.blade.php` — modeled on the jakarta.go.id **"Widget
  Aksesibilitas Version 2.0"**, rebuilt on design tokens (boxy, `border-radius: 0`). Icon-tile
  grid with strip gauges: Mode Suara (Web Speech API TTS `id-ID`), Perbesar/Perkecil Teks (4),
  Skala Abu-Abu, Kontras+ (Normal→High→Dark→Invert, 4), Sembunyikan Gambar, Rata Tulisan (4),
  Tulisan Dapat Dibaca, Tinggi Garis (3), Animasi Dijeda, Kursor, Spasi Teks (3), Garis Bawahi
  Tautan (2), a language row (only Indonesian), and a reset bar
- **Contrast modes:** High contrast + dark mode override the design tokens via
  `accessibility-contrast.css`; extra states (`-grayscale`, `-invert`, `-hide-images`,
  `-readable-font`, `-pause-animations`, `-large-cursor`, `-underline-links`, `-align-*`) live at
  the end of the same file, each toggled by `accessibility.js` classes on `<html>`
- **JavaScript:** `public/js/accessibility.js` (mirrored as `resources/js/accessibility.js`)
  manages state in `localStorage` (`accessibilityState`), Ctrl+U shortcut, gauge strips that
  wrap, and TTS play/stop

---

## Design System

All pages follow a consistent design system documented in `DESIGN_SYSTEM.md`:
- Dark header pattern with NYC.gov-inspired styling
- CSS custom properties for all colors and fonts
- Custom card system (not Bootstrap cards)
- Bootstrap used only for grid and forms (admin tables use the custom `.data-table` component)

---

## Seed Data

On fresh migration, the database is seeded with:

| Table | Records | Description |
|---|---|---|
| `users` | 1 | Admin user |
| `berita_siber` | 14 | 12 legacy + 2 hero-linked (`Penipuan Hacker… PowerShell`, `Ako Ransomware…`) |
| `slide_hero` | 4 | Hero slides (1 static + 3 scraped legacy banners) |
| `events` | 6 | Past security events |
| `peraturan_kebijakan` | 1 | Sample regulation |
| `peringatan_keamanan` | 2 | Security warnings |

**Empty on fresh install:** `panduan_teknis`, `infografis_keamanan`, `contact_us`, `lapor_insiden`, `lampiran_insiden`, `tac_agreements`

Legacy `berita_siber`/`peringatan_keamanan` hotlink thumbnails to `csirt.jakarta.go.id`; hero `gambar` hotlinks `csirt.jakarta.go.id/images/banner/...` (or `storage/app/public/hero/` if uploaded). `SEMUA TENTANG WEB FILTERING` tautan → `/` (not legacy).

---

## File Structure Reference

```
resources/views/
├── layouts/app.blade.php          # Master layout (navbar, footer, a11y widget)
├── components/
│   ├── navbar.blade.php           # Site navigation with search, partner logos, auth-aware CTA
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
├── auth/                          # login.blade.php + register.blade.php
├── bug-hunter/                    # TaC gate, report form, dashboard, detail, thank-you
├── contact/                       # Contact form + thank you
├── admin/                         # Admin dashboard + CRUD partials + edit pages + incidents/

├── dashboard.blade.php            # Placeholder
└── (publickey is a download route — no view)
```
