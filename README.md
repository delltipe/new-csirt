# Jakarta Prov CSIRT Portal

Public-facing portal for the Computer Security Incident Response Team of DKI Jakarta
Provincial Government (csirt.jakarta.go.id). Citizens and government employees can report
cyber incidents and read security resources; CSIRT staff manage content and review reports.

**Stack:** Laravel 12 · PHP 8.2+ · SQLite · Bootstrap 5.3 (grid/forms/tables) · custom
design system (CSS custom properties) · Vite registered but unused (assets load from `public/`).

---

## Quick Start

```bash
composer setup          # Fresh install: composer install, .env, APP_KEY, migrate, npm, build
composer dev            # Runs 4 processes: php artisan serve, queue:listen, pail, vite
composer test           # Clears config cache, then runs php artisan test
```

- `composer setup` runs `migrate --force` **without seeding** — the DB starts empty.
  For the admin account and sample content, run `php artisan migrate:fresh --seed`.
- `composer dev` needs `npx concurrently` (Node).

### Default Credentials

| Role | Credentials |
|---|---|
| Admin | `admin@gmail.com` / `12345678` (seeded — change before any live deploy) |
| Reporter | self-register via `/register` (`is_bug_hunter` flag) |

---

## Features

### Public Pages
- Home, profile, and six content types: **news, events, infographics, warnings,
  laws/regulations, technical guides** (listing + detail, paginated, Indonesian copy)
- Site-wide **search** across all content types (min. 2 characters)
- Official CSIRT documents: **RFC 2350**, **public key** download, archived **statistics** pages
- **Accessibility widget** — high-contrast, dark-contrast, and default modes

### Incident Portal (public bug-hunter flow)
- Public **registration / login** (no 2FA), reporters gated via `is_bug_hunter`
- **Terms & Conditions gate** (versioned `tac_agreements`)
- Komdigi-style **single-page report form**: category, incident time, URL, downtime,
  description, technical action, evidence (≤3 rows, File or URL each)
- **Ticket tracking** — `INS-YYYY-XXXX` numbers, reporter dashboard with per-ticket
  detail and status (`menunggu_validasi → divalidasi → ditindaklanjuti → dipulihkan → selesai`, or `ditolak`)
- **Admin review** — assigns CWE + Severity and advances status (`/admin/incidents`)

### Admin Panel (`/admin`)
- Tabbed dashboard: News, Events, Infographics, Warnings, Laws, Guides
- Full CRUD per content type (15/page), plus the incident review workflow
- All writes wrapped in try/catch with Indonesian error messages

### Contact
- Contact form intake only (no admin review workflow yet) — stored in `contact_us`

---

## Testing

PHPUnit with SQLite in-memory (`phpunit.xml`). Run:

```bash
composer test                     # whole suite
php artisan test --filter=TestName   # single test
```

Coverage: `IncidentPortalSmokeTest` (public auth → TaC → submit → ticket → admin
review) and the default `ExampleTest`.

---

## Project Structure

```
routes/web.php                   # all routes (auth, bug-hunter, contact, admin)
app/Http/Controllers/            # content, search, auth, bug-hunter, contact, admin
app/Models/                      # maps to Indonesian table names (see SCHEMA.md)
resources/views/
  layouts/app.blade.php          # master layout (navbar, footer, a11y widget)
  admin/                         # dashboard + CRUD partials + incident review
  bug-hunter/                    # reporter dashboard, TaC, form, thank-you, detail
  auth/ news/ events/ warnings/  # public pages
  infographics/ laws/ guides/ search/ contact/ incidents/
database/migrations/             # SQLite schema (Indonesian table names)
public/css/style.css             # design tokens (CSS custom properties)
docs/                            # FEATURES.md, SCHEMA.md, DEPLOYMENT.md, plan docs
```

For the full inventory see **`FEATURES.md`**; for the database schema see
**`docs/SCHEMA.md`**; for design tokens see **`DESIGN_SYSTEM.md`**.

---

## Deployment

Docker-ready (`Dockerfile` at repo root, Render Docker runtime confirmed working) but
**not yet deployed**. The only blocker is Render's card verification — the bank declines
the debit card for the cloud-hosting merchant category. Cardless alternatives are
documented: **Hetzner + PayPal**, **Zeabur + Wonder Mesh**, or **Cloudflare Tunnel**.

See **`docs/DEPLOYMENT.md`** for the full guide.

---

## Roadmap / Planned

- **CAPTCHA** on the incident and contact forms (self-hosted, legacy-style; deferred —
  not needed for the frontend demo)
- **Admin contact review** workflow (list / detail / status transitions)
- **Schema hardening**: foreign keys, timestamps on content tables, `event` → `events`
  rename (MySQL reserved-word collision), soft-delete for incident reports
- **Full-text search** over substring matching
- **Legacy-feature port-in**: select features from the legacy portal, after the main
  data-input and content pages are finalized

---

## Design System

All pages follow the design system in **`DESIGN_SYSTEM.md`** — every color/font/spacing
value is a CSS custom property in `public/css/style.css`; never hardcode hex values or
fonts. Bootstrap is used only for grid, forms, and tables. Accessibility overrides live
in `public/css/accessibility-contrast.css`.
