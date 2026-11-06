# Setup Guide — Jakarta Prov CSIRT Portal

> How to set up, run, and test the Laravel 12 portal. For the feature inventory see
> **`FEATURES.md`**; for the database schema see **`docs/SCHEMA.md`**; for production
> deployment see **`docs/DEPLOYMENT.md`**.

---

## Requirements

- PHP 8.2+
- Composer
- PHP extensions required by Composer, including PDO SQLite for the default database

---

## Quick Start

```bash
composer setup          # Fresh install: PHP dependencies, .env, APP_KEY, SQLite file, migrations
composer dev            # Start the local PHP server
composer test           # Clears config cache, then runs php artisan test
```

Notes:

- Use `composer setup` for a fresh installation only: it generates a new application
  key and runs migrations. Do not run it against an existing installation whose key
  or data must be preserved. It does not seed data; on a fresh database, run
  `php artisan db:seed` for the admin account and sample content.
- Run `php artisan storage:link` to expose uploaded files locally. To process queued
  jobs or follow logs, run `php artisan queue:listen --tries=1 --timeout=0` or
  `php artisan pail --timeout=0` in separate terminals as needed. Public asset edits
  require a browser refresh; Node.js and an asset build are not required.

7. **IncidentReport** - Cyber incident reports submitted by registered reporters
   - Fields: user_id, tiket_no, kategori_insiden, waktu_kejadian, lokasi_url, down_time, deskripsi, tindakan_teknis, cwe, severity, status

### Default Credentials

| Role | Credentials |
|---|---|
| Admin | `admin@gmail.com` / `12345678` (seeded — change before any live deploy) |
| Reporter (bug hunter) | self-register via `/register` (`is_bug_hunter` flag) |

---

## Database & Models

SQLite by default (`database/database.sqlite`). Models map to Indonesian table names —
do not assume Laravel conventions (see `docs/SCHEMA.md` for the full schema):

### Form Submission Routes
- `GET /register` - Public registration (bug hunters)
- `POST /register` - Create account (`is_bug_hunter = true`)
- `GET /login` - Public login
- `POST /login` - Authenticate (redirects admins → `/admin`, bug hunters → TaC)
- `POST /logout` - Destroy session
- `GET /bug-hunter/laporan` - Terms & Conditions gate
- `POST /bug-hunter/laporan/agree` - Accept TaC
- `GET /bug-hunter/laporan/baru` - Incident report form
- `POST /bug-hunter/laporan/simpan` - Submit incident report (throttled)
- `GET /bug-hunter/laporan/selesai` - Thank-you page (shows ticket number)
- `GET /contact` - Contact form
- `POST /contact` - Submit contact message
- `GET /thank-you/contact` - Thank you page

| Model | Table |
|---|---|
| CybersecurityNews | `berita_siber` |
| WarningPost | `peringatan_keamanan` |
| Event | `events` |
| Infographic | `infografis_keamanan` |
| LawRulePost | `peraturan_kebijakan` |
| CybersecurityGuide | `panduan_teknis` |
| IncidentReport | `lapor_insiden` |
| LampiranInsiden | `lampiran_insiden` |
| TacAgreement | `tac_agreements` |
| ContactMessage | `contact_us` |

Every table has `created_at` / `updated_at` (added in the 2026-08-18 schema hardening; see `docs/SCHEMA.md`).

---

## Routes

All routes live in `routes/web.php`:

| Group | Routes |
|---|---|
| Public content | `/`, `/profile`, `/rfc2350`, `/publickey`, `/statistics` |
| Content listings | `/news`, `/events`, `/infographics`, `/warnings`, `/laws`, `/guides` (+ `/{id}` detail) |
| Search | `/search?q=...` |
| Public auth | `GET/POST /register`, `GET/POST /login`, `POST /logout` |
| Bug hunter | `/bug-hunter` (dashboard), `/bug-hunter/laporan` (TaC), `/bug-hunter/laporan/baru`, `/bug-hunter/laporan/simpan` (POST), `/bug-hunter/laporan/selesai`, `/bug-hunter/laporan/{id}` |
| Contact | `GET/POST /contact`, `/thank-you/contact` |
| Admin auth | `GET/POST /admin/login`, `POST /admin/logout` |
| Admin (auth + admin middleware) | `/admin` dashboard, incident review `/admin/incidents`, CRUD for all 6 content types |

---

## Controllers

- **Public content:** `NewsController`, `EventController`, `InfographicController`,
  `WarningPostController`, `LawRulePostController`, `GuideController`, `SearchController`,
  `HomeController`
- **Auth:** `AuthController` (public register/login/logout), `AdminController` (admin login + all admin CRUD/review)
- **Intake:** `BugHunterController` (incident portal), `ContactController`

## View Structure

```
resources/views/
├── layouts/app.blade.php          # Master layout (navbar, footer, a11y widget)
├── components/                    # navbar, footer, accessibility
├── home.blade.php  profile.blade.php  rfc2350.blade.php  statistics.blade.php
├── auth/                          # register, login
├── bug-hunter/                    # dashboard, tac, create, thank-you, show
├── news/  events/  warnings/  infographics/  laws/  guides/  search/  contact/
├── admin/                         # dashboard + partials/ + *_edit pages
│   └── incidents/                 # index, show
└── dashboard.blade.php            # Placeholder

├── auth/                          (Public login + register views)
├── bug-hunter/                    (TaC gate, report form, dashboard, detail, thank-you)
├── contact/                       (Contact form + thank you)
├── admin/                         (Admin dashboard + CRUD partials + edit pages + incidents/)
```

Styling: Bootstrap 5.3 (CDN) for grid/forms plus a custom design system in
`public/css/style.css` (CSS custom properties). Admin tables use the custom `.data-table`
component (see `DESIGN_SYSTEM.md`). Assets load directly from `public/`.

---

## Testing

PHPUnit with SQLite in-memory (`phpunit.xml`):

```bash
composer test                     # whole suite
php artisan test --filter=TestName   # single test
```

### 3. Forms Styling
- Update `bug-hunter/create.blade.php` with your Figma form design
- Update `contact/create.blade.php` with your Figma form design
- Style validation error messages to match your design

Coverage: `tests/Feature/IncidentPortalSmokeTest` (public auth → TaC → submit →
ticket → admin review) and the default `ExampleTest`.

## Testing the Application

1. **View all pages**: Navigate to each route to see the placeholder views
2. **Test forms**: Try submitting the incident report and contact forms
3. **Check database**: Submitted forms save to the database

## File Locations

- **Models**: `app/Models/`
- **Controllers**: `app/Http/Controllers/`
- **Routes**: `routes/web.php`
- **Views**: `resources/views/`
- **Migrations**: `database/migrations/`

## Admin Features

Administrative access is implemented:
- Admin dashboard to manage content
- CRUD operations for all models
- Incident review workflow (`/admin/incidents`)
- User authentication (login/register)

## Database Commands

```bash
php artisan migrate:fresh --seed     # Reset DB and re-seed
php artisan route:list               # List all routes
```

## File Uploads

New incident evidence files are stored on the private `evidence` disk under
`storage/app/private/incident-evidence/bukti_laporan/`. The authenticated
`evidence.download` route permits the report owner or an admin to download them.
`php artisan storage:link` exposes files on the separate public disk, such as hero
uploads; it is not used for new incident evidence.

The download handler can fall back to legacy evidence on the public disk. Those
legacy files may still be accessible through old public URLs until the web server
and any CDN block them. See [the evidence cutover plan](docs/INCIDENT_EVIDENCE_CUTOVER.md)
before handling historical files; do not move or delete them as part of setup.
