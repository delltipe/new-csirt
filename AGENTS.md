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

## Incident Portal Rework (IMPLEMENTED 2026-08-11)

Read `docs/INCIDENT_PORTAL_PLAN.md` for the full record + implementation notes.
Landed: public register/login (NO 2FA, `is_bug_hunter` flag on `users`),
Komdigi-style single-page incident form + TaC gate (versioned `tac_agreements`),
`INS-YYYY-XXXX` ticket numbers, reporter dashboard
(`No | No Tiket | ... | Status | Aksi`) + per-ticket detail page, 5-state status
flow (`menunggu_validasi → divalidasi → ditindaklanjuti → dipulihkan → selesai`,
+ `ditolak`), lean admin review assigning CWE/Severity (routes
`/admin/incidents`, plus an "Insiden" tab + pending badge on the admin dashboard).

Gotchas for this subsystem:
- `lapor_insiden` schema rewritten + new `lampiran_insiden` / `tac_agreements`
  tables → touches require `php artisan migrate:fresh --seed`.
- In the `bug-hunter/laporan/*` route group, static paths (`baru`, `selesai`)
  MUST be declared before the dynamic `/bug-hunter/laporan/{id}` (`int $id`).
- Files (≤3 bukti rows, File OR URL) → `storage/app/public/bukti_laporan/`.
- Seeders do **not** create incidents; the Insiden tab is empty until reports exist.
- Status transitions + labels live on `App\Models\IncidentReport`
  (constants, `labels()`, `transitions()`, `canTransitionTo()`).
- Admin login: the public `/login` also works for the admin user (is_admin check)
  and the admin nav CTA points to `/admin`.

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
| LampiranInsiden | `lampiran_insiden` |
| TacAgreement | `tac_agreements` |
| ContactMessage | `contact_us` |

### Timestamps Disabled on Most Models

Most migrations omit `$table->timestamps()`; matching models set `public $timestamps = false`. Only `users`, `contact_us`, `lapor_insiden`, `lampiran_insiden`, and `tac_agreements` have timestamps.

### Auth Is Custom (Not a Package)

Auth is Laravel's built-in `users`-table auth. Two controllers drive it:

- **`AdminController::login`** — admin-only login at `/admin/login`; checks `is_admin`.
- **`AuthController`** — public `/register` + `/login` for bug hunters (`routes/web.php:44-48`). Registration creates a user with `is_bug_hunter = true`; login redirects admins to `/admin` and bug hunters to `bug-hunter.tac`. No 2FA, no email verification.

`/profile` and `/dashboard` remain static `view()` routes with no POST handler.

**Middleware aliases** (`admin` + `bug_hunter`) are registered in `bootstrap/app.php` — NOT the legacy `app/Http/Kernel.php` (unused; app binds Laravel's default kernel). Do not move them back to `Kernel.php`.

### Incident Report Intake (Eloquent, single POST)

Incident intake is a **logged-in single-page form** (no wizard, no per-step routes). Flow: login → TaC gate (`bug-hunter.tac`) → `bug-hunter/create.blade.php` → one POST `/bug-hunter/laporan/simpan` (`bug-hunter.store`, `throttle:60,1`) → thank-you with ticket number. `BugHunterController::store` runs one `$request->validate([...])` covering all fields, then creates the `IncidentReport` via Eloquent plus up to 3 `LampiranInsiden` rows (jenis `file`|`url`). Ticket numbers `INS-YYYY-XXXX` come from `generateTiketNo()`. Do not add per-step routes.

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

### Admin Views (Layout Gotcha)

Admin pages render inside the public layout (`@yield('content')` of `layouts/app.blade.php`) wrapped in `.admin-container` panes in `admin/dashboard.blade.php`. The dashboard is a tabbed interface (`#news-tab` … `#guides-tab`); each tab `@include`s a partial from `resources/views/admin/partials/`.

**When editing partials, keep the `<div>` balance intact.** An extra stray `</div>` at the end of a partial (found in `events.blade.php` and `warnings.blade.php`, fixed in commit `00d7931`) breaks the whole dashboard layout: subsequent tabs and the logout button escape the centered container. Verify with:

```powershell
# balanced partials should all show diff=0
$files = Get-ChildItem resources/views/admin/partials/*.blade.php; foreach ($f in $files) { $c = Get-Content $f.FullName -Raw; "{0,-22} diff={1}" -f $f.Name, (([regex]::Matches($c,'<div[\s>]')).Count - ([regex]::Matches($c,'</div>')).Count) }
```

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

## Deployment (Render, Docker runtime)

**Status (as of 2026-08-05): NOT deployed.** The repo is deploy-ready and Render's Docker runtime is confirmed working (the New Web Service form auto-detects Docker and the repo auto-fills — the old "no PHP runtime" concern is obsolete). The **only remaining blocker is card verification**: Render requires card verification even on the Free plan, and the bank declines the debit card.

- The decline is **bank-side, not a Render UI bug**: the Stripe card step silently re-prompts (no error text) after entry — classic signature of a bank decline. Card works for Crunchyroll (entertainment MCC) but is blocked for cloud-hosting/stripe.com MCC, even with "international payments" enabled. Likely per-category block or failing 3D-Secure/OTP.
- **Last session fixes (committed):** commit `b9e13c2` added `Dockerfile` + `.dockerignore`; commit `ebc4e54` added `cp .env.example .env` to the Dockerfile `RUN` step — **required**, because `php artisan key:generate` fails at boot if no `.env` file exists (`.env` is git-ignored). Keep that line.
- **Next session paths:** (a) unblock the card — bank app: per-category toggles ("digital goods/online subscriptions"), enable 3D-Secure/OTP, whitelist `stripe.com`, or try a virtual card from another bank (Jenius/Jago); or (b) go cardless: **Hetzner + PayPal** (same Dockerfile, 24/7, ~€4/mo), **Zeabur + Wonder Mesh** (zero code change, needs the generated install script run on the dev machine — creates a `zeabur` sudo user + Tailscale + K3s; script generated but NOT run), or **Cloudflare Tunnel** (free, no card, needs a PC online).

Key deploy facts (already committed, do not re-derive):

- **Runtime:** Docker. Render now offers a Docker runtime (the form auto-detects the committed `Dockerfile`). Base: `php:8.2-cli` + `pdo_sqlite/mbstring/zip/bcmath`.
- **Start command (in CMD):** `php artisan key:generate --force --quiet && php artisan migrate --force --seed && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=$PORT` — runs at container start, so a fresh SQLite DB is migrated+seeded every boot (seeders are non-idempotent, but the ephemeral disk resets anyway; never add `--seed` to a long-lived DB).
- **Build:** `composer install --no-dev --optimize-autoloader` **and `cp .env.example .env`**; **no npm/Vite** (assets live in `public/`).
- `database/database.sqlite` is git-ignored (`database/.gitignore`); build must `touch` it.
- Data is ephemeral on free tier: SQLite file + uploaded proof images reset on redeploy/spin-down. Fine for prototype.
- Render env vars to set: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://<name>.onrender.com`, `DB_CONNECTION=sqlite` (stable `APP_KEY` optional since it's regenerated each start). No `APP_NAME` needed (defaults fine).

## Deployment (other platforms — explored, NOT pursued)

- **Vercel:** feasible but requires a real architecture port, NOT a drop-in: Vercel has no PHP/Docker and no persistent filesystem → must switch SQLite→**Neon Postgres** (free), add `vercel-php@0.9.0` runtime + `api/index.php` + `vercel.json` rewrites, move sessions/cache off `file`, and move incident proof-pic uploads to **Supabase Storage** (free) since uploads can't persist on Vercel's disk. User chose "just exploring" — not pursuing. Hobby is free, no card, personal-use only, ~300s function ceiling, cold starts.
- **Koyeb:** abandoned — console shows "Koyeb is joining Mistral" banner, API/token page broken, CLI login fails.
- **Zeabur:** signup done (GitHub auth). Chose the free server path: New Project → Bind External Server → **Wonder Mesh**. A server card was created and an install script generated (`curl ... api.zeabur.com/mesh-server/install.sh?token=... | sudo bash`) but **NOT run** — it installs Tailscale, creates a `zeabur` sudo user (passwordless sudo), enables SSH password auth, and installs K3s. Invasive to the dev machine; user was cautious. Status: "Server not connected yet."