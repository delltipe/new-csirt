# Incident Portal Rework — Komdigi-style Bug-Hunter Flow (Plan)

> Status: **IMPLEMENTED** (2026-08-11). All 7 phases landed + verified.
> Model: csirt.komdigi.go.id incident-report flow (user-recorded, Aug 2026),
> adapted to the Jakarta Prov CSIRT Laravel portal.

## Implementation notes (deviations / additions from the plan)

- **Route shadowing fix:** `/bug-hunter/laporan/baru`, `/selesai` etc. must be
  declared **before** `/bug-hunter/laporan/{id}` (dynamic `show` route) or the
  static paths match `{id}` (a `string` hits the `int $id` type hint → 500).
  Verified by a failing feature test.
- **Reporter detail page** (`bug-hunter/show`, `GET /bug-hunter/laporan/{id}`)
  added as the target of the dashboard `Aksi` column — not in the original
  plan, needed to make the `Aksi` cell meaningful.
- **Smoke tests kept:** `tests/Feature/IncidentPortalSmokeTest.php` (2 tests,
  36 assertions) covering public auth → TaC → submit (file+URL attachment) →
  ticket → admin review incl. invalid-transition rejection. Although
  "feature tests" were listed out of scope, these document the locked flow.
- **Pre-existing test fix:** `tests/Feature/ExampleTest.php` had
  `RefreshDatabase` commented out, so `composer test` always failed on the
  in-memory DB (`no such table: berita_siber`). Enabling it makes the suite
  green — unrelated to the rework but required for `composer test`.
- **Status badge colors** mapped to existing tokens (`--mid`, `--navy`,
  `--alert`, … ) — no new tokens, no `accessibility-contrast.css` changes.
  Badge component lives in `public/css/style.css` (`.status-badge.*`).
- `php artisan migrate:fresh --seed` required and run; seeders do **not**
  create incidents, so the admin "Insiden" tab is empty until reports exist.

## Locked decisions

- **Auth:** public registration + login, NO 2FA (TOTP skipped). `is_bug_hunter` flag on `users`.
- **Form:** Komdigi-style single page (replaces the 3-step wizard):
  `Kategori Insiden` · `Waktu Kejadian` (datetime) · `Lokasi Insiden / URL Validasi` ·
  `Down Time` (time) · `Deskripsi Kejadian` · `Tindakan Teknis` · `Bukti Laporan`
  (≤3 rows, each **File OR URL**, ≤5MB each).
- **TaC gate:** scroll-to-bottom + checkbox; versioned (`2026.08`) + timestamped,
  stored in `tac_agreements`. Skips to form if already agreed.
- **Ticket tracking:** reporter-facing table
  `No | No Tiket | Tanggal Pengajuan | Jenis Laporan | CWE | Severity | Status | Aksi`;
  empty state "Tidak ada data yang tersedia pada tabel ini".
- **No Tiket format:** `INS-YYYY-XXXX` (year + sequence).
- **Status flow (5 states):**
  `menunggu_validasi → divalidasi | ditolak`,
  `divalidasi → ditindaklanjuti | ditolak`,
  `ditindaklanjuti → dipulihkan | ditolak`,
  `dipulihkan → selesai`;
  terminal at `selesai` / `ditolak`.
- **Lean admin review** assigns CWE + Severity (the ticket-table columns) and advances status.
- **DB:** `php artisan migrate:fresh --seed` required (schema changes). No new Composer deps.

## Phases / files

1. **Auth:** migration adds `is_bug_hunter`; `AuthController` (register/login/logout);
   routes `GET/POST /register`, `GET/POST /login`, `POST /logout`; views
   `auth/register.blade.php`, `auth/login.blade.php`; `BugHunterMiddleware` alias in
   `bootstrap/app.php` (alongside `admin`, per AGENTS.md `ee81505` precedent); navbar CTA
   becomes auth-aware (`components/navbar.blade.php:332`).
2. **Ticket dashboard:** `GET /bug-hunter`; view `bug-hunter/dashboard.blade.php`.
3. **TaC:** migration `tac_agreements`; routes `GET /bug-hunter/laporan`,
   `POST /bug-hunter/laporan/agree`; view `bug-hunter/tac.blade.php`.
4. **Form:** routes `GET /bug-hunter/laporan/baru`, `POST /bug-hunter/laporan/simpan`
   (`throttle:60,1`), `GET /bug-hunter/laporan/selesai`; view `bug-hunter/create.blade.php`;
   delete old wizard routes (`routes/web.php:43-49`) + `incidents/create.blade.php` + JKT captcha.
   Attachment files → `storage/app/public/bukti_laporan/`.
5. **Schema:** rewrite `lapor_insiden` migration (`user_id` FK, `tiket_no`, `kategori_insiden`,
   `waktu_kejadian`, `lokasi_url`, `down_time`, `deskripsi`, `tindakan_teknis`, `cwe`,
   `severity`, `status` default `menunggu_validasi`, timestamps); new `lampiran_insiden`
   table (`laporan_id`, `jenis` file|url, `value`); rewrite `IncidentReport` model with
   status constants/labels/transitions.
6. **Admin review:** routes `GET /admin/incidents`, `GET /admin/incidents/{id}`,
   `POST /admin/incidents/{id}/review`; views `admin/incidents/index.blade.php`,
   `admin/incidents/show.blade.php`; link + pending badge in `admin/dashboard.blade.php`.
7. **Thank-you:** `bug-hunter/thank-you.blade.php` shows No Tiket +
   Cegah→Tangani→Pulihkan note.

## Out of scope (on request)

2FA, Cloudflare Turnstile, soft-delete, contact review, feature tests.

## Verification

```bash
php artisan migrate:fresh --seed
php artisan route:list
```

Manual smoke: register → login → TaC → submit → ticket visible → admin reviews
(CWE/Severity/status) → status visible to reporter. Then `composer test`.
