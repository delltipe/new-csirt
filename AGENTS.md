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

## Admin Dashboard Polish (IMPLEMENTED 2026-08-18)

Commit `dbcc22f` (plus earlier dark-mode work in `ee81505`+). No schema changes — no
`migrate:fresh` needed for any of this.

- **All 6 CRUD partials** (`resources/views/admin/partials/{news,events,infographics,
  warnings,laws,guides}.blade.php`) now use the same custom `.data-table` pattern as the
  Insiden partial: `.section-actions` header (`h4.section-title-small` + `.btn-add` opening
  the existing modal), `.table-responsive` > `table.data-table`, action buttons
  `.btn-edit` / `.btn-delete`, and a `.empty-state` block when the collection is empty.
  Component CSS lives in the `<style>` block of `admin/dashboard.blade.php`.
- **Dark-mode contrast overrides** in `public/css/accessibility-contrast.css`:
  `.admin-tab` (and `:hover`/`.active`) keep `--navy-tint` bg + `--navy` text instead of
  the generic `button` blue; `.btn-navy:hover` forces dark text `#0A0F1A` on `#66B3FF`
  (fixes blue-on-blue); `.nav-search button` / `.nav-logout button` restore their intended
  ink/transparent surfaces; `button.btn-delete` gets a red fill + dark text. The generic
  `html.accessibility-contrast-dark button` / `a:hover` rules flatten any unscoped button —
  new admin buttons MUST get matching dark overrides.
- **`.admin-header`** removed from the dark navy-band group (no more `#0F1B33` band) and
  its `border-bottom` changed `var(--ink)` → `var(--border)` in `dashboard.blade.php`,
  `admin/incidents/index.blade.php`, `admin/incidents/show.blade.php` — blends with the
  page background in both light and dark modes.
- **CSIRT logo whites out in dark mode** on navbar (`.nav-logo img`) and profile page
  (`.profile-logo-container img`) via `filter: brightness(0) invert(1) opacity(0.9)` —
  same technique as the footer logo.
- **Navbar partner logos**: `.nav-partners` cluster in `components/navbar.blade.php` —
  `jaya_raya.png`, `logo_diskominfo.png`, `logo_5abad.png`, `HUTRI81.png` (all in
  `public/`). Real links: Jaya Raya → `https://www.jakarta.go.id/`, Diskominfo →
  `https://diskominfotik.jakarta.go.id/`, 5 Abad → `https://jakarta500.id/`;
  **HUT RI ke-81 has no link** (plain `<span class="nav-partner">`). Dark mode swaps
  to `logo_5abad_white.svg` (official white SVG with its `#0F141E` background rect
  stripped — transparent white artwork) / `hutri81_white.png` (dual-image
  `.partner-light`/`.partner-dark` stack toggled in `accessibility-contrast.css`).
  Jaya Raya uses the **same** `jaya_raya.png` in both modes (no white-out). The
  HUT RI 81 marks are the **tertiary "81 only" (no-text) versions**: light =
  `HUTRI81.png` (merah/hitam on putih), dark = `hutri81_white.png` (putih on merah).
  The whole cluster is hidden below `1200px` — the navbar is over-packed (~1230px
  min-content in a 1144px container at 1200px), so partners only render when the
  container has full width; `white-space: nowrap` on `.nav-links > li > a` keeps
  "Hubungi Kami" on one line without overflowing.
- **Footer socials** (`components/footer.blade.php`): X → `https://x.com/dkijakarta`,
  Instagram → `https://www.instagram.com/diskominfotik.jakarta/`, YouTube →
  `https://www.youtube.com/dkijakarta`, Email → `mailto:csirt@jakarta.go.id`.
- Boxy corners: `border-radius: 0` applied sitewide in `style.css` (Bootstrap utilities
  section) to `.btn`, `.form-control`, `.card`/headers/footers, `.modal-*`, `.btn-close`,
  `.page-link` — matches the NYC.gov/insiden look in both themes.


## Stack

- **Laravel 12** / PHP 8.2+ / SQLite (default)
- **Bootstrap 5.3** via CDN (in layout) for grid, forms only — admin tables use the custom `.data-table` component (see Admin Dashboard Polish)
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
| Event | `events` |
| Infographic | `infografis_keamanan` |
| LawRulePost | `peraturan_kebijakan` |
| CybersecurityGuide | `panduan_teknis` |
| IncidentReport | `lapor_insiden` |
| LampiranInsiden | `lampiran_insiden` |
| TacAgreement | `tac_agreements` |
| ContactMessage | `contact_us` |

### Timestamps on All Tables

Every application table now has `created_at` / `updated_at` (added in the
2026-08-18 schema hardening). Content-table models previously set
`public $timestamps = false`; that line is gone — all models use the Eloquent
default. Seeders that insert via `DB::table()` set timestamps explicitly.

### Auth Is Custom (Not a Package)

Auth is Laravel's built-in `users`-table auth (login via `Auth::attempt` in `AdminController::login`), with an `is_admin` boolean flag on `users`. Public registration/login exists **only** for the incident subsystem (bug hunters): `/register` and `/login` POST via `AuthController` and set the `is_bug_hunter` flag (see the Incident Portal Rework section). `/profile` and `/dashboard` remain static placeholder `view()` routes with no POST handler; only `/admin/login` posts for the admin role.

- **`AdminController::login`** — admin-only login at `/admin/login`; checks `is_admin`.
- **`AuthController`** — public `/register` + `/login` for bug hunters (`routes/web.php:44-48`). Registration creates a user with `is_bug_hunter = true`; login redirects admins to `/admin` and bug hunters to `bug-hunter.tac`. No 2FA, no email verification.

`/profile` and `/dashboard` remain static `view()` routes with no POST handler.

The form is a **single-page form** — exactly one POST to `/bug-hunter/laporan/simpan` (`bug-hunter.store`), where `BugHunterController::store` runs one `$request->validate([...])` covering all fields. Do not add per-step routes. The controller creates the `IncidentReport` and up to 3 `LampiranInsiden` rows (jenis `file`|`url`). Implementation notes: some code paths use Eloquent models (`IncidentReport`/`LampiranInsiden`) while others perform a `DB::table('lapor_insiden')->insert(...)` inside a try/catch; the `IncidentReport` model's `$fillable` matches the migration columns.

### Error Handling

All form submissions and admin CRUD writes are wrapped in try/catch — on failure redirect back with a `withErrors`/`withInput` message instead of a raw 500. Messages are Indonesian.

### Rate Limiting

`throttle:60,1` on the two POST routes for incident reports and contact form only. GET routes are unthrottled.

### Layout and Styling

- Main layout: `resources/views/layouts/app.blade.php` — uses `@yield('content')`, includes `components.navbar`, `components.accessibility`, `components.footer`
- Loads Bootstrap 5.3 + icons from CDN, plus `public/css/style.css` and `public/css/accessibility-contrast.css`; `public/js/accessibility.js` at end of body
- Vite bundle is **not** loaded anywhere in the layout

**Accessibility widget** (`components/accessibility.blade.php`): modeled on the **jakarta.go.id
"Widget Aksesibilitas Version 2.0"** and restyled entirely on design tokens (boxy,
`border-radius: 0`). It's an icon-tile grid with strip gauges: Mode Suara (Web Speech API TTS,
`id-ID`), Perbesar/Perkecil Teks, Skala Abu-Abu, Kontras+ (Normal→High→Dark→Invert),
Sembunyikan Gambar, Rata Tulisan, Tulisan Dapat Dibaca, Tinggi Garis, Animasi Dijeda, Kursor,
Spasi Teks, Garis Bawahi Tautan, + a language row (only Indonesian) and a reset bar. New root
state classes live at the **end of `accessibility-contrast.css`** (`-grayscale`, `-invert`,
`-hide-images`, `-readable-font`, `-pause-animations`, `-large-cursor`, `-underline-links`,
`-align-*`). Gauge tiles increment on tap and **wrap** (max → 0). Both the high-contrast and
dark **overrides restyle the widget itself** (see the widget blocks in
`accessibility-contrast.css`) — always keep those in sync when touching the widget CSS.
`accessibility-grayscale`/`accessibility-invert` are applied **per-element**
(`body *:not(.accessibility-widget):not(.accessibility-widget *)`) — never move the filter onto
`html`/`body`, or the `position: fixed` widget re-anchors to the whole document and disappears.
The widget is also excluded from Perbesar/Perkecil Teks scaling (it stays fixed-size) so Reset
stays reachable.
**FOUC guard**: an inline `<script>` in the `<head>` of `layouts/app.blade.php` (right after the
CSS links) re-applies the saved `accessibilityState` classes to `<html>` *before first paint*
(localStorage is sync, inline head scripts run during parsing). Without it, every page load
flashed the original look for a split second before `accessibility.js`'s `DOMContentLoaded`
handler kicked in. The head script mirrors `applyContrast`/`applyTextAlign`/toggle class names
(including legacy `contrast:'high'/'dark'` migration) and is idempotent with the full widget
init. If you add a new root state class in `accessibility.js`, add it here too. Font-size /
line-height / letter-spacing stay JS-applied at DOMContentLoaded (they need computed styles),
so those reflow slightly after paint — acceptable, only when a non-zero level is set.

### Design System

- Full documentation in `DESIGN_SYSTEM.md`
- All colors/fonts/spacing as CSS custom properties in `public/css/style.css` `:root`
- **Always use `var(--token)`** — never hardcode hex values, font names, or spacing
- `public/css/accessibility-contrast.css` overrides tokens for high-contrast and dark mode
- Content pages use custom card system (`.news-grid`, `.news-card`) — not Bootstrap `.card`
- Admin tables use the custom `.data-table` component (see Admin Dashboard Polish), not Bootstrap `.table`; forms still use Bootstrap `.form-control`

### Admin Views (Layout Gotcha)

Admin pages render inside the public layout (`@yield('content')` of `layouts/app.blade.php`) wrapped in `.admin-container` panes in `admin/dashboard.blade.php`. The dashboard is a tabbed interface (`#news-tab` … `#guides-tab` + `#insiden-tab`); each tab `@include`s a partial from `resources/views/admin/partials/`. All CRUD partials use the same `.data-table` / `.section-actions` / `.btn-add` pattern as the Insiden partial.

**When editing partials, keep the `<div>` balance intact.** An extra stray `</div>` at the end of a partial (found in `events.blade.php` and `warnings.blade.php`, fixed in commit `00d7931`) breaks the whole dashboard layout: subsequent tabs and the logout button escape the centered container. Verify with:

```powershell
# balanced partials should all show diff=0
$files = Get-ChildItem resources/views/admin/partials/*.blade.php; foreach ($f in $files) { $c = Get-Content $f.FullName -Raw; "{0,-22} diff={1}" -f $f.Name, (([regex]::Matches($c,'<div[\s>]')).Count - ([regex]::Matches($c,'</div>')).Count) }
```

### Tests

- `phpunit.xml` uses SQLite in-memory (`DB_DATABASE=:memory:`)
- `tests/Unit` has the default `ExampleTest`; `tests/Feature` has `ExampleTest` + `IncidentPortalSmokeTest` (public auth → TaC → submit → ticket → admin review)
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
- **`/publickey`, `/rfc2350`, `/statistics` are routed** (since commit `af73f3e`) — footer/navbar links resolve. Note: `/publickey` is a file-download route (no `publickey.blade.php` view)
- **Foreign keys are enforced** since the 2026-08-18 hardening — see the Schema
  Hardening section for the FK map; deleting a user with incidents/TaC rows fails at the DB level
- **The `events` table name** (plural, renamed from `event` in the 2026-08-18 hardening) no longer collides with a MySQL reserved word; `docs/DEPLOYMENT.md` covers production setup
- **Blade views use Indonesian text throughout** — government portal for DKI Jakarta
- `config.php`, `bootstrap/app.php` trust all proxies (`trustProxies(at: '*')`)

## Schema Hardening (IMPLEMENTED 2026-08-18)

Schema changes — **requires `php artisan migrate:fresh --seed`** (migrations
edited in place, per this repo's rework convention). Part of the same commit
series as the Admin Dashboard Polish.

- **Foreign keys now enforced** (SQLite `PRAGMA foreign_keys=ON` is the Laravel
  default here — `config/database.php` `DB_FOREIGN_KEYS` defaults true):
  - `lapor_insiden.user_id` → `users(id)` `ON DELETE RESTRICT`
  - `tac_agreements.user_id` → `users(id)` `ON DELETE RESTRICT`
  - `lampiran_insiden.laporan_id` → `lapor_insiden(id)` `ON DELETE CASCADE`
  Deleting a user that owns reports or TaC rows fails at the DB level.
- **Timestamps on all 6 content tables** (`berita_siber`, `peringatan_keamanan`,
  `events`, `infografis_keamanan`, `peraturan_kebijakan`, `panduan_teknis`);
  matching models dropped `$timestamps = false`. Seeders set timestamps on
  `DB::table()` inserts; admin CRUD goes through Eloquent so it auto-fills.
- **`event` table renamed → `events`** (MySQL reserved-word collision gone):
  migration file `..._create_events_table.php`, `Event` model, and
  `EventSeeder` (`DB::table('events')`). All queries use the `Event` Eloquent
  model — no controller changes were needed.
- **Soft-delete on incidents:** `lapor_insiden` gained `deleted_at`;
  `IncidentReport` uses `SoftDeletes`. Admin "Hapus Laporan" button on
  `/admin/incidents/{id}` soft-deletes only (`POST /admin/incidents/{id}/delete`
  → `AdminController@incidentDelete`) — legal-evidence retention, hard-delete is
  never exposed. Trashed rows drop out of lists and 404 on review/detail (the
  `findOrFail` calls respect the SoftDeletes global scope). Covered by
  `IncidentPortalSmokeTest::test_admin_soft_deletes_incident_report`.

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