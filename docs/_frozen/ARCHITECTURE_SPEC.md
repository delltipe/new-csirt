# CSIRT Portal — Backend Architecture Spec

> Frontend-agnostic specification for reimplementing the DKI Jakarta CSIRT
> portal in any framework, language, or UI stack.
>
> This document is **self-contained**. It describes what the system does and the
> backend contracts it must honor. It deliberately omits all frontend concerns
> (layout, styling, design tokens, accessibility, build tooling) so an agent can
> rebuild the system with a completely different architecture without needing
> access to the original codebase.

---

## 1. System Overview

A public-sector **CSIRT (Computer Security Incident Response Team)** portal for a
provincial government. Two audiences, two subsystems:

| Subsystem | Actors | Nature |
|---|---|---|
| **Content publishing** | Admin authors → public readers | Read-heavy, admin CRUD |
| **Inbound submissions** | Public users → CSIRT staff | Write-heavy, intake workflow |

There is **public registration/login** for incident reporters (bug hunters),
gated behind an `is_bug_hunter` flag on the user record. Admins are flagged with
`is_admin`. No 2FA, no email verification, no password reset.

### Core flow

```
Public (unauthenticated)
  1. Browse content (6 types)              → read queries
  2. Search across all content             → read query
  3. Register / log in (bug hunters)       → session auth (is_bug_hunter)
  4. Submit contact message                → validated write

Reporter (authenticated, is_bug_hunter)
  1. Accept Terms & Conditions (versioned) → write tac_agreement
  2. Submit incident report (+ ≤3 bukti)   → validated write (ticket no.)
  3. Track own tickets (list + detail)     → read (scoped to user)

Admin (authenticated, is_admin)
  1. Login / logout                        → session auth
  2. CRUD all 6 content types              → read/write
  3. Review incident reports               → read + CWE/severity + status transitions
```

---

## 2. Entities & Data Contract

Ten domain tables (6 content + incident reports, attachments, TaC agreements,
contact) plus `users`. A reimplementation should define **foreign keys** between
related tables and use **timestamps on every table** (both now implemented in
the Laravel build, 2026-08-18).

Use a modern relational DB (Postgres/MySQL/SQLite all fine). Column names below
use `snake_case`; any naming convention is acceptable as long as the contract is
the same.

### 2.1 Content tables (6)

All share a common shape: `id`, `title`, `description`, `thumbnail` (URL or file
path), plus type-specific extras. All are ordered by date descending in listings.

**News**
| Column | Type | Notes |
|---|---|---|
| `id` | PK | auto-increment |
| `title` | text | |
| `description` | text | |
| `thumbnail` | text | URL or file path |
| `source` | text | publisher, e.g. "Jakarta CSIRT", "BSSN" |
| `published_at` | datetime | |

**Security Warnings**
| Column | Type | Notes |
|---|---|---|
| `id` | PK | |
| `title` | text | |
| `description` | text | |
| `thumbnail` | text | |
| `source` | text | |
| `issued_at` | datetime | |
| `file_path` | text | nullable — optional uploaded image |

**Events**
| Column | Type | Notes |
|---|---|---|
| `id` | PK | |
| `title` | text | |
| `description` | text | nullable |
| `thumbnail` | text | nullable |
| `event_date` | datetime | nullable |
| `location` | text | nullable — e.g. "Online via Zoom" |
| `event_type` | text | nullable — e.g. "webinar", "sosialisasi" |
| `registration_url` | text | nullable |
| `capacity` | int | nullable |

> Name the table `events` (plural). `event` is a reserved word in MySQL.

**Infographics**
| Column | Type | Notes |
|---|---|---|
| `id` | PK | |
| `title` | text | |
| `thumbnail` | text | required |

**Laws & Regulations**
| Column | Type | Notes |
|---|---|---|
| `id` | PK | |
| `title` | text | |
| `description` | text | |
| `link` | text | document URL |
| `issued_date` | date | |
| `issued_time` | time | nullable |
| `download_amount` | int | default 0 — download counter |
| `file_path` | text | nullable — uploaded PDF |

**Technical Guides**
| Column | Type | Notes |
|---|---|---|
| `id` | PK | |
| `title` | text | |
| `author` | text | |
| `link` | text | external URL |
| `file_path` | text | nullable — uploaded file |

### 2.2 Submission tables

**Incident Reports** — the core inbound product of a CSIRT portal. Submitted by
an authenticated reporter; assigned CWE/severity by an admin during review.
| Column | Type | Notes |
|---|---|---|
| `id` | PK | |
| `user_id` | FK → users.id | reporter (required) |
| `tiket_no` | text | unique, `INS-YYYY-XXXX` — human-facing ticket number |
| `kategori_insiden` | text | required — e.g. "Phishing", "Malware / Ransomware", "Lainnya" |
| `waktu_kejadian` | datetime | required — when the incident occurred |
| `lokasi_url` | text | required, valid URL — affected location |
| `down_time` | time | required — downtime of the affected service |
| `deskripsi` | text | required — incident description |
| `tindakan_teknis` | text | required — technical actions already taken |
| `cwe` | text | nullable — CWE identifier, assigned by admin |
| `severity` | enum | nullable — Low / Medium / High / Critical, assigned by admin |
| `status` | enum | default `menunggu_validasi` (see §4.4) |
| `created_at` / `updated_at` | timestamp | required |

**Incident Attachments** — 0–3 rows per report, each a file **or** URL.
| Column | Type | Notes |
|---|---|---|
| `id` | PK | |
| `laporan_id` | FK → incident reports | parent report |
| `jenis` | enum | `file` / `url` |
| `value` | text | stored file path or evidence URL |
| `created_at` / `updated_at` | timestamp | required |

**TaC Agreements** — records acceptance of the current Terms & Conditions version.
| Column | Type | Notes |
|---|---|---|
| `id` | PK | |
| `user_id` | FK → users.id | reporter |
| `version` | text | e.g. `2026.08` |
| `agreed_at` | timestamp | when accepted |
| `created_at` / `updated_at` | timestamp | required |

**Contact Messages**
| Column | Type | Notes |
|---|---|---|
| `id` | PK | |
| `name` | text | required |
| `email` | text | required, valid email |
| `phone` | text | nullable |
| `organization` | text | nullable |
| `subject` | text | required |
| `message` | text | required |
| `inquiry_type` | enum | `general` / `support` / `partnership` / `media` / `other` |
| `status` | enum | default `pending` (see §4.4) |
| `created_at` / `updated_at` | timestamp | required |

### 2.3 Auth table

**Users**
| Column | Type | Notes |
|---|---|---|
| `id` | PK | |
| `name` | text | |
| `email` | text | unique |
| `password` | text | strongly hashed (bcrypt/argon2) |
| `is_admin` | bool | default false — gates all admin access |
| `is_bug_hunter` | bool | default false — set true on public registration |

Public signup creates reporters with `is_bug_hunter = true`; admins are created
by seeding. The same login page routes admins to `/admin` and reporters to the
TaC gate.

---

## 3. API Surface (Endpoint Semantics)

UI-agnostic endpoints. Any route shape is fine as long as semantics match.

### 3.1 Public read

| Method/Path | Semantics |
|---|---|
| `GET /` | recent news (6, newest first) + upcoming events (4, soonest first) |
| `GET /news` | paginated news, 6/page, newest first; optional year filter |
| `GET /news/{id}` | news detail |
| `GET /events` | paginated events, 6/page, date desc; optional upcoming-only filter |
| `GET /events/{id}` | event detail + up to 3 related events (exclude self) |
| `GET /warnings` | paginated warnings, 12/page, newest first |
| `GET /warnings/{id}` | warning detail |
| `GET /infographics` | paginated infographics, 12/page |
| `GET /infographics/{id}` | infographic detail |
| `GET /laws` | paginated laws, 12/page, newest first; optional sidebar filter |
| `GET /laws/{id}` | law detail + downloadable document link |
| `GET /guides` | paginated guides, 12/page; optional author filter |
| `GET /guides/{id}` | guide detail + external link |
| `GET /search?q=...` | see §3.2 |

### 3.2 Search

- Query param `q`, trimmed.
- **Minimum 2 characters** — below that return empty results.
- Search `title` + `description` (for guides also `author`) across **all six
  content types**.
- Result limit 10 per type, grouped by type, ordered by date desc.
- Implementation: simple substring/`LIKE` match is acceptable for the prototype;
  full-text search (Postgres `tsvector`, etc.) is a recommended upgrade.

### 3.3 Public auth & contact

| Method/Path | Semantics |
|---|---|
| `GET /register` | render registration form |
| `POST /register` | create user with `is_bug_hunter = true`; log in; redirect to TaC gate |
| `GET /login` | render login form |
| `POST /login` | authenticate; redirect admins → admin area, reporters → TaC gate |
| `POST /logout` | destroy session, redirect home |
| `GET /contact` | render contact form |
| `POST /contact` | create contact message — rate-limited (**60 requests/min per IP**) |
| `GET /thank-you/contact` | confirmation page |

No 2FA, email verification, or password reset in the current build.

### 3.4 Reporter (bug hunter, authenticated)

| Method/Path | Semantics |
|---|---|
| `GET /bug-hunter` | ticket list for the current user (No Tiket, tanggal, kategori, CWE, severity, status, aksi) |
| `GET /bug-hunter/laporan` | TaC gate; skip if current version already agreed |
| `POST /bug-hunter/laporan/agree` | record acceptance for the current TaC version |
| `GET /bug-hunter/laporan/baru` | render single-page report form |
| `POST /bug-hunter/laporan/simpan` | create incident report + attachments — rate-limited (**60 requests/min per IP**). See §4.1 for validation, §4.2 for uploads. Returns a thank-you page showing the ticket number. |
| `GET /bug-hunter/laporan/selesai` | thank-you / confirmation page |
| `GET /bug-hunter/laporan/{id}` | full report detail (scoped to current user), incl. attachment links |

All routes require a valid session auth + `is_bug_hunter`. Only the incident
submit POST (and the contact POST, §3.3) are rate-limited; GET routes are not.

### 3.5 Admin

All admin routes require a valid **session auth** + **`is_admin`** check. Failed
auth redirects to the admin login. All writes follow the error-handling
convention in §4.5.

| Method/Path | Semantics |
|---|---|
| `GET /admin/login` | render login form |
| `POST /admin/login` | authenticate (email + password); reject non-admin users; log out and re-show on failure |
| `POST /admin/logout` | destroy session, redirect to login |

**Content CRUD (repeated for each of the 6 content types):**

| Method/Path | Semantics |
|---|---|
| `GET /admin/{type}` | paginated list (15/page), newest first |
| `POST /admin/{type}` | create record |
| `GET /admin/{type}/{id}/edit` | render edit form with current values |
| `POST /admin/{type}/{id}/update` | update record |
| `POST /admin/{type}/{id}/delete` | delete record |

**Submission review:**

| Method/Path | Semantics |
|---|---|
| `GET /admin/incidents` | paginated incident reports (15/page), newest first, filterable by `status` |
| `GET /admin/incidents/{id}` | full report detail incl. attachments + reporter info |
| `POST /admin/incidents/{id}/review` | assign `cwe` + `severity` (Low/Medium/High/Critical) and transition `status` to a valid value (see §4.4) |
| `GET /admin/contacts` | paginated contact messages, filterable by `status` |
| `GET /admin/contacts/{id}` | full message detail |
| `POST /admin/contacts/{id}/status` | transition `status` to a valid value |

Prefer **soft-delete** (a deleted flag or `deleted_at`) for incident reports —
they are potential legal evidence and must not be hard-deleted.

---

## 4. Business Rules & Cross-Cutting Concerns

### 4.1 Validation (all writes)

Every write validates at the boundary before persisting:

- Required fields enforced (per §2); incident reports additionally require an
  authenticated reporter and acceptance of the current TaC version.
- Formats: `email`, `url`, dates, times (`H:i`).
- Size: attachment files ≤ **5MB**.
- Enums whitelisted: `inquiry_type`, attachment `jenis` (`file`/`url`),
  severity (`Low`/`Medium`/`High`/`Critical`), all `status` values.
- Max 3 attachment rows per incident report; each row is a file **or** a URL.
- Reject invalid input with **per-field** error messages; preserve the user's
  input so they don't retype everything.

### 4.2 File uploads

- Accepted types for incident evidence: **PNG / JPG / JPEG / GIF / PDF**, max
  **5MB**.
- Laws/guides/warnings may also carry an uploaded file (PDF/image).
- Store files on persistent storage (local public dir or object storage); store
  only the **relative path/object key** in the DB row.
- Serve via a public URL (local: `public/storage/...` symlink; cloud: CDN).

### 4.3 Abuse prevention

Incident submission requires authentication + TaC acceptance and the submit
POST is **rate-limited (60 requests/min per IP)**. There is no CAPTCHA in the
current build — a real CAPTCHA (hCaptcha, reCAPTCHA, Turnstile) is a recommended
upgrade. Rate limiting applies regardless.

### 4.4 Status workflows

Incident report statuses (closed enum, default `menunggu_validasi`):

```
menunggu_validasi → divalidasi → ditindaklanjuti → dipulihkan → selesai
        │               │              │
        └───→ ditolak ←─┴──────────────┘            (terminal)
```

`ditolak` (rejected) is reachable from `menunggu_validasi`, `divalidasi`, and
`ditindaklanjuti`; `dipulihkan` only moves to `selesai`; `selesai` and `ditolak`
are terminal. Transitions are validated on the server (`canTransitionTo`) before
persisting. Labels are localized on the UI side; stored enum values stay stable.

Contact message statuses (closed enum, default `pending`):
`pending` → `in_progress` | `resolved`.

### 4.5 Error handling convention

Every write operation must be wrapped in a try/catch. On failure:
- Redirect back with per-field error messages and preserved input.
- **Never** surface a raw 500 / stack trace to the user.
- Messages are user-facing and in **Indonesian** (this is an Indonesian
  government portal). Log the real exception server-side.

### 4.6 Content conventions

- All public-facing copy is **Indonesian**.
- Listings are paginated (6 for news/events, 12 for warnings/laws/guides, 15 for
  admin).
- Content is a document model, not a relational graph — each content type is
  self-contained (no categories/tags/taxonomy in the original; add only if the
  rework wants it).

### 4.7 Seeding (demo/bootstrap data)

- 1 admin user: `admin@gmail.com` / password `12345678` (change in production).
- 5 news, 6 events, 2 warnings, 1 law.
- Guides, infographics, contact messages, incident reports, attachments, and TaC
  agreements start empty.
- Seeders should be idempotent or run only on a fresh database.

---

## 5. Excluded Concerns (do not spec)

- **Frontend**: layout, CSS/design system, fonts, accessibility widget, Bootstrap,
  component libraries, JS bundlers. The incident form may be a wizard client-side;
  the backend contract stays a single validated POST.
- **Auth UX**: password reset, email verification, 2FA. Public registration for
  bug hunters **is** implemented.
- **Background jobs**: the prototype runs synchronously; a queue is only needed
  if notifications/email are added.
- **Analytics**: the original has placeholder-only statistics pages.

---

## 6. Known Gaps to Improve in the Rework

These are deliberate improvements over the original — an agent should implement
them. Status as of 2026-08-18: items **2–5 are implemented** in the Laravel
build; 1, 6, and 7 remain open.

1. **Real CAPTCHA** on incident reports (current build has none — relies on auth + rate limiting).
2. **Foreign keys** between related rows — **IMPLEMENTED**: `lapor_insiden.user_id` / `tac_agreements.user_id` → `users` (`ON DELETE RESTRICT`), `lampiran_insiden.laporan_id` → `lapor_insiden` (`ON DELETE CASCADE`).
3. **Timestamps on all tables** — **IMPLEMENTED**: the six content tables gained `created_at` / `updated_at`.
4. **`events` table name** — **IMPLEMENTED**: renamed from `event` (MySQL reserved-word collision gone).
5. **Soft-delete for incident reports** — **IMPLEMENTED**: `deleted_at` + `SoftDeletes`; admin soft-deletes only (legal-evidence retention).
6. **Full-text search** as an upgrade over substring matching.
7. **Stable app encryption key + hashed admin password** in any production deployment (default creds are public).
