# AGENTS.md — Jakarta Prov CSIRT Portal

## Quick Commands

```bash
composer setup          # Full fresh install: composer install, .env, key, migrate, npm, build
composer dev            # Runs 4 processes: php artisan serve, queue:listen, pail, vite (npx concurrently)
composer test           # Clears config cache then runs php artisan test
php artisan test --filter=TestName   # Run a single test
php artisan migrate:fresh --seed     # Reset DB and re-seed
php artisan route:list               # List all routes
```

`composer setup` does **not** seed data (runs `migrate --force`, no `--seed`). After a fresh setup the DB is empty — the admin login and sample content only exist after `php artisan migrate:fresh --seed` (or `db:seed`). `composer dev` requires `npx concurrently` (Node).

No linting, formatting, or typecheck commands are configured. Laravel Pint is available via `vendor/bin/pint` but has no project-level config file.

## Stack

- **Laravel 12** / PHP 8.2+ / SQLite (default)
- **Bootstrap 5.3** via CDN (in layout) for grid, forms, tables
- **Custom design system** (`public/css/style.css`) with CSS custom properties — see `DESIGN_SYSTEM.md`
- **Vite 7** registered but unused: layout loads CSS/JS from `public/`, not the Vite bundle. `resources/css/app.css` is just a comment; do not expect Vite output in templates.
- No CI/CD pipeline (no `.github/workflows`)
- No Pest — uses PHPUnit 11

## Architecture

### Database Tables Use Indonesian Names

Models map to non-English table names. Do not assume Laravel conventions:

| Model | Table |
|---|---|
| CybersecurityNews | `berita_siber` |
| WarningPost | `peringatan_keamanan` |
| Event | `event` |
| Infographic | `infografis_keamanan` |
| LawRulePost | `peraturan_kebijakan` |
| CybersecurityGuide | `panduan_teknis` |
| IncidentReport | `lapor_insiden` |
| ContactMessage | `contact_us` |

### Timestamps Disabled on Most Models

Most migrations omit `$table->timestamps()`; matching models set `public $timestamps = false`. Only `users`, `contact_us`, and `lapor_insiden` have timestamps.

### Admin Auth Is Custom (Not a Package)

Auth is Laravel's built-in `users`-table auth (login via `Auth::attempt` in `AdminController::login`), with an `is_admin` boolean flag on `users`. There is **no** public registration/login flow — `/login`, `/register`, `/profile`, `/dashboard` are static `view()` routes with no POST handler. Only `/admin/login` posts.

**Known bug (as of now):** the `admin` middleware alias is defined only in the legacy `app/Http/Kernel.php` `$routeMiddleware`, but this app uses `bootstrap/app.php` config and binds Laravel's default kernel — the alias is **not registered**. Hitting any `['auth','admin']` route as an authenticated user throws `BindingResolutionException: Target class [admin] does not exist.` Register it with `$middleware->alias(['admin' => \App\Http\Middleware\AdminMiddleware::class])` in `bootstrap/app.php` (or inline the FQCN in routes). `AdminMiddleware` itself does the `is_admin` check.

### IncidentReport Uses Raw DB::table Inserts

The wizard is a **single-page JS stepper** — exactly one POST to `/report-incident/store` (`incidents.store`), where `IncidentReportController::store` runs one `$request->validate([...])` covering all steps. Do not add per-step routes. The controller writes via `DB::table('lapor_insiden')->insert(...)` with try/catch, though the `IncidentReport` model's `$fillable` matches the migration columns.

### Error Handling

All form submissions and admin CRUD writes are wrapped in try/catch — on failure redirect back with a `withErrors`/`withInput` message instead of a raw 500. Messages are Indonesian.

### Rate Limiting

`throttle:60,1` on the two POST routes for incident reports and contact form only. GET routes are unthrottled.

### Layout and Styling

- Main layout: `resources/views/layouts/app.blade.php` — uses `@yield('content')`, includes `components.navbar`, `components.accessibility`, `components.footer`
- Loads Bootstrap 5.3 + icons from CDN, plus `public/css/style.css` and `public/css/accessibility-contrast.css`; `public/js/accessibility.js` at end of body
- Vite bundle is **not** loaded anywhere in the layout

### Design System

- Full documentation in `DESIGN_SYSTEM.md`
- All colors/fonts/spacing as CSS custom properties in `public/css/style.css` `:root`
- **Always use `var(--token)`** — never hardcode hex values, font names, or spacing
- `public/css/accessibility-contrast.css` overrides tokens for high-contrast and dark mode
- Content pages use custom card system (`.news-grid`, `.news-card`) — not Bootstrap `.card`
- Admin pages may use Bootstrap `.table` and `.form-control`

### Tests

- `phpunit.xml` uses SQLite in-memory (`DB_DATABASE=:memory:`)
- Only default `ExampleTest` exists in `tests/Unit` and `tests/Feature`
- `composer test` clears the config cache first

### Seeders

`DatabaseSeeder` calls: AdminUserSeeder, CybersecurityNewsSeeder, EventSeeder, PublicationSeeder, WarningPostSeeder.

Admin credentials (from seeder): `admin@gmail.com` / `12345678`

### `presentation/` Deliverables (Internship Docs)

Contains internship report & deck plus Python generators:

- `python build_report.py` → `Laporan_Magang_CSIRT_DKI_Jakarta.docx` (python-docx)
- `python build_presentation.py` → `Presentasi_Magang_CSIRT_DKI_Jakarta.pptx` (python-pptx)

Run from `presentation/`. These are regenerated artifacts, not hand-edited. Also contains source survey CSV and pre-proposal docs.

## Gotchas

- **No `.env` committed** — `.env.example` has `DB_CONNECTION=sqlite` and `APP_KEY=` empty
- **Views `publickey.blade.php`, `rfc2350.blade.php`, `statistics.blade.php` have no routes** — the footer/navbar link to `/publickey`, `/rfc2350`, `/statistics` (will 404)
- **Foreign keys are not enforced** in migrations — no `$table->foreign()` calls
- **The `event` table name collides with MySQL reserved word** — fine in SQLite but breaks on MySQL without quoting; note `docs/DEPLOYMENT.md` covers production setup
- **Blade views use Indonesian text throughout** — government portal for DKI Jakarta
- `config.php`, `bootstrap/app.php` trust all proxies (`trustProxies(at: '*')`)