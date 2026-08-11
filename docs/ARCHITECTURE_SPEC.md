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

There is **no public registration/login**. The only authenticated role is a
single admin (flagged via `is_admin` on the user record).

### Core flow

```
Public (unauthenticated)
  1. Browse content (6 types)              → read queries
  2. Search across all content             → read query
  3. Submit incident report (+ optional file) → validated write
  4. Submit contact message                → validated write

Admin (authenticated, is_admin)
  1. Login / logout                        → session auth
  2. CRUD all 6 content types              → read/write
  3. Review inbound submissions            → read + status transitions
```

---

## 2. Entities & Data Contract

Nine domain tables. A reimplementation should define **foreign keys** between
related tables (the original has none — integrity is application-level only) and
use **timestamps on every table** (the original omits them on most).

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

### 2.2 Submission tables (2)

**Incident Reports** — the core inbound product of a CSIRT portal.
| Column | Type | Notes |
|---|---|---|
| `id` | PK | |
| `full_name` | text | reporter name (required) |
| `email` | text | reporter email (required, valid email) |
| `phone_number` | text | reporter phone (required) |
| `found_date` | date | nullable — when incident was discovered |
| `domain` | text | affected domain, expected `*.jakarta.go.id` (required) |
| `url` | text | affected URL (required, valid URL) |
| `description` | text | incident description (required) |
| `risk_type` | text | nullable — e.g. "XSS", "SQL Injection" |
| `risk_level` | text | nullable — Low / Medium / High / Critical |
| `cvss_score` | float | nullable — 0.0 to 10.0 |
| `video_url` | text | nullable — evidence video |
| `reference` | text | nullable — CVE links, articles |
| `recommendation` | text | nullable — suggested fix |
| `proof_pic` | text | nullable — file path to uploaded screenshot |
| `status` | enum | default `menunggu_validasi` (see §4.4) |
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

Only one admin exists (created by seeding). No public signup.

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

### 3.3 Public write (rate-limited)

| Method/Path | Semantics |
|---|---|
| `POST /report-incident` | Create incident report. See §4.1 for validation, §4.2 for file upload, §4.3 for CAPTCHA. On success return a confirmation/thank-you. On failure return the input back with per-field errors. |
| `POST /contact` | Create contact message. Same success/failure contract. |

Both endpoints **rate-limited to 60 requests/min per IP**. Get-only routes are
not rate-limited.

### 3.4 Admin

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

**Submission review (new in this spec — closes a gap in the original):**

| Method/Path | Semantics |
|---|---|
| `GET /admin/incidents` | paginated incident reports (15/page), newest first, filterable by `status` |
| `GET /admin/incidents/{id}` | full report detail incl. proof file link |
| `POST /admin/incidents/{id}/status` | transition `status` to a valid value (see §4.4) |
| `GET /admin/contacts` | paginated contact messages, filterable by `status` |
| `GET /admin/contacts/{id}` | full message detail |
| `POST /admin/contacts/{id}/status` | transition `status` to a valid value |

Prefer **soft-delete** (a deleted flag or `deleted_at`) for incident reports —
they are potential legal evidence and must not be hard-deleted.

---

## 4. Business Rules & Cross-Cutting Concerns

### 4.1 Validation (all writes)

Every write validates at the boundary before persisting:

- Required fields enforced (per §2).
- Formats: `email`, `url`, dates.
- Ranges: `cvss_score` 0.0–10.0; file size ≤ 2MB.
- Enums whitelisted: `inquiry_type`, report `risk_level`, all `status` values.
- Reject invalid input with **per-field** error messages; preserve the user's
  input so they don't retype everything.

### 4.2 File uploads

- Accepted types for incident proof: **PNG / JPG / JPEG**, max **2MB**.
- Laws/guides/warnings may also carry an uploaded file (PDF/image).
- Store files on persistent storage (local public dir or object storage); store
  only the **relative path/object key** in the DB row.
- Serve via a public URL (local: `public/storage/...` symlink; cloud: CDN).
- Only an image is expected for incident reports; validate the MIME type.

### 4.3 CAPTCHA / abuse prevention

The original uses a trivial static token (`JKT`/`jkt`) — **do not reproduce it**.
In a rework use a real CAPTCHA (hCaptcha, reCAPTCHA, Turnstile) on the incident
report form. Rate limiting (§3.3) applies regardless.

### 4.4 Status workflows

Incident report statuses (closed enum, default `menunggu_validasi`):
`menunggu_validasi` (pending validation) → `divalidasi` (validated) |
`ditolak` (rejected) | `ditindaklanjuti` (being acted upon).

Contact message statuses (closed enum, default `pending`):
`pending` → `in_progress` | `resolved`.

The original stores the initial status at insert time and never changes it
afterward. This spec **adds admin transitions** (see §3.4). Display localized
labels on the UI side; keep the stored enum values stable.

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
- Guides, infographics, contact messages, and incident reports start empty.
- Seeders should be idempotent or run only on a fresh database.

---

## 5. Excluded Concerns (do not spec)

- **Frontend**: layout, CSS/design system, fonts, accessibility widget, Bootstrap,
  component libraries, JS bundlers. The incident form may be a wizard client-side;
  the backend contract stays a single validated POST.
- **Auth UX**: password reset, email verification, public registration.
- **Background jobs**: the prototype runs synchronously; a queue is only needed
  if notifications/email are added.
- **Analytics**: the original has placeholder-only statistics pages.

---

## 6. Known Gaps to Improve in the Rework

These are deliberate improvements over the original — an agent should implement
them:

1. **Real CAPTCHA** on incident reports (original used a trivially broken static token).
2. **Foreign keys** between related rows (original had none).
3. **Timestamps on all tables** (original omitted them on most content tables).
4. **Admin submission-review workflow** (original ingested reports/messages but had no management UI).
5. **`events` table name** (original `event` collides with a MySQL reserved word).
6. **Soft-delete for incident reports** (legal-evidence retention).
7. **Status transitions** enforced as a closed enum + admin action, not free-form text.
8. **Full-text search** as an upgrade over substring matching.
9. **Stable app encryption key + hashed admin password** in any production deployment (default creds are public).
