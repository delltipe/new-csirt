# Setup Guide — Jakarta Prov CSIRT Portal

> How to set up, run, and test the Laravel 12 portal. For the feature inventory see
> **`FEATURES.md`**; for the database schema see **`docs/SCHEMA.md`**; for production
> deployment see **`docs/DEPLOYMENT.md`**.

---

## Requirements

- PHP 8.2+
- Composer
- Node.js (for `composer dev`'s `npx concurrently` and the unused Vite build)

---

## Quick Start

```bash
composer setup          # Fresh install: composer install, .env, APP_KEY, migrate, npm, build
composer dev            # Runs 4 processes: php artisan serve, queue:listen, pail, vite
composer test           # Clears config cache, then runs php artisan test
```

Notes:

- `composer setup` runs `migrate --force` **without seeding** — the DB starts empty.
  For the admin account and sample content, run `php artisan migrate:fresh --seed`.
- After the incident-portal rework, any schema changes require
  `php artisan migrate:fresh --seed`.
- `composer dev` requires `npx concurrently` (Node).

### Default Credentials

| Role | Credentials |
|---|---|
| Admin | `admin@gmail.com` / `12345678` (seeded — change before any live deploy) |
| Reporter (bug hunter) | self-register via `/register` (`is_bug_hunter` flag) |

---

## Database & Models

SQLite by default (`database/database.sqlite`). Models map to Indonesian table names —
do not assume Laravel conventions (see `docs/SCHEMA.md` for the full schema):

| Model | Table |
|---|---|
| CybersecurityNews | `berita_siber` |
| WarningPost | `peringatan_keamanan` |
| Event | `event` |
| Infographic | `infografis_keamanan` |
| LawRulePost | `peraturan_kebijakan` |
| CybersecurityGuide | `panduan_teknis` |
| IncidentReport | `lapor_insiden` |
| LampiranInsiden | `lampiran_insiden` |
| TacAgreement | `tac_agreements` |
| ContactMessage | `contact_us` |

Most content tables have no timestamps; matching models set `public $timestamps = false`.

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

---

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
```

Styling: Bootstrap 5.3 (CDN) for grid/forms/tables plus a custom design system in
`public/css/style.css` (CSS custom properties). Vite is registered but unused — assets
load from `public/`, not the Vite bundle. See `DESIGN_SYSTEM.md`.

---

## Testing

PHPUnit with SQLite in-memory (`phpunit.xml`):

```bash
composer test                     # whole suite
php artisan test --filter=TestName   # single test
```

Coverage: `tests/Feature/IncidentPortalSmokeTest` (public auth → TaC → submit →
ticket → admin review) and the default `ExampleTest`.

---

## Database Commands

```bash
php artisan migrate:fresh --seed     # Reset DB and re-seed
php artisan route:list               # List all routes
```

## File Uploads

Incident evidence files are stored in `storage/app/public/bukti_laporan/` (served via
`public/storage` symlink). Run `php artisan storage:link` in production.