# AGENTS.md — Jakarta Prov CSIRT Portal

## Quick Commands

```bash
composer setup          # Full fresh install: composer install, .env, key, migrate, npm, build
composer dev            # Runs 4 processes: php artisan serve, queue:listen, pail, npm run dev
composer test           # Clears config cache then runs php artisan test
php artisan test --filter=TestName   # Run a single test
php artisan migrate:fresh --seed     # Reset DB and re-seed
php artisan route:list               # List all routes
```

No linting, formatting, or typecheck commands are configured. Laravel Pint is available via `vendor/bin/pint` but has no project-level config file.

## Stack

- **Laravel 12** / PHP 8.2+ / SQLite (default)
- **Tailwind CSS 4** (via Vite) AND **Bootstrap 5.3** (via CDN in layout) — both are in use
- **Vite 7** for asset bundling
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
| CybersecurityGuide / Guide | `panduan_teknis` |
| IncidentReport | `lapor_insiden` |
| ContactMessage | `contact_us` |

### Timestamps Disabled on Most Models

Most migrations do not include `$table->timestamps()`. Corresponding models set `public $timestamps = false`. Only `users`, `contact_us`, and `lapor_insiden` have timestamps.

### Duplicate Guide Models

Both `Guide` and `CybersecurityGuide` map to `panduan_teknis`. `CybersecurityGuide` is used by `AdminController`; `Guide` exists but appears unused. Do not create a third model for this table.

### Admin Auth Is Custom (Not a Package)

- `users` table has an `is_admin` boolean column
- `AdminMiddleware` checks `Auth::user()->is_admin`
- However, admin routes in `routes/web.php` use the `auth` middleware, not `admin`
- `AdminController` re-checks `is_admin` manually in every method — this is redundant but intentional; keep the pattern if adding new admin methods

### IncidentReportController Uses Raw DB

The `store` method inserts via `DB::table('lapor_insiden')` instead of Eloquent. The `IncidentReport` model's `$fillable` fields do not match the actual migration columns. Do not rely on the model for incident report writes.

### Layout and Styling

- Main layout: `resources/views/layouts/app.blade.php` — uses `@yield('content')`
- Static CSS/JS in `public/css/style.css` and `public/js/accessibility.js` (not Vite-compiled)
- Tailwind is imported in `resources/css/app.css` but the main layout does not load the Vite-compiled CSS — it loads Bootstrap + `style.css` from CDN/public
- Blade components are in `resources/views/components/` (navbar, footer, etc.)

### Tests

- PHPUnit config in `phpunit.xml` uses SQLite in-memory (`DB_DATABASE=:memory:`)
- Tests are minimal — only default `ExampleTest` in both `tests/Unit` and `tests/Feature`
- The `composer test` script clears config cache before running tests

### Seeders

`DatabaseSeeder` calls: AdminUserSeeder, CybersecurityNewsSeeder, EventSeeder, PublicationSeeder, WarningPostSeeder.

Admin credentials (from seeder): `admin@gmail.com` / `12345678`

## Gotchas

- **No `.env` committed** — `.env.example` has `DB_CONNECTION=sqlite` and `APP_KEY=` empty
- **Foreign keys are not enforced** in migrations — no `$table->foreign()` calls
- **The `event` table name collides with MySQL reserved word** — fine in SQLite but will break if you switch to MySQL without quoting
- **Blade views reference Indonesian text** throughout the UI — this is a government portal for DKI Jakarta
