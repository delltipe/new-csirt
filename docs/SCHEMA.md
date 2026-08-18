# Database Schema — Jakarta CSIRT Portal

> All tables, columns, and types for the Laravel project (repo root `new-csirt`).
> SQLite by default. See `DEPLOYMENT.md` for MySQL/PostgreSQL migration notes.

---

## Application Tables

### `users`
| Column | Type | Notes |
|---|---|---|
| id | bigIncrements | PK |
| name | string | |
| email | string | unique |
| email_verified_at | timestamp | nullable |
| password | string | |
| remember_token | string(100) | nullable |
| is_admin | boolean | default: false — admin flag |
| is_bug_hunter | boolean | default: false — reporter flag (added 2026-08-11; set true on public registration) |
| created_at | timestamp | |
| updated_at | timestamp | |

### `berita_siber` (Cybersecurity News)
| Column | Type | Notes |
|---|---|---|
| id | bigIncrements | PK |
| title | text | |
| description | text | |
| thumbnail | text | URL or file path |
| source | text | e.g. "Jakarta CSIRT", "BSSN" |
| date | dateTime | publication date |

**Has timestamps.**

### `peringatan_keamanan` (Security Warnings)
| Column | Type | Notes |
|---|---|---|
| id | bigIncrements | PK |
| title | text | |
| description | text | |
| thumbnail | text | URL or file path |
| source | text | |
| date | dateTime | |
| file_path | string | nullable — uploaded image |

**Has timestamps.**

### `events` (Events)
| Column | Type | Notes |
|---|---|---|
| id | bigIncrements | PK |
| title | text | |
| description | text | nullable |
| thumbnail | text | nullable — URL or file path |
| event_date | dateTime | nullable |
| location | string | nullable — e.g. "Online via Zoom" |
| event_type | string | nullable — e.g. "webinar", "sosialisasi" |
| registration_url | string | nullable |
| capacity | integer | nullable |

**Has timestamps.**

### `infografis_keamanan` (Security Infographics)
| Column | Type | Notes |
|---|---|---|
| id | bigIncrements | PK |
| title | text | |
| thumbnail | text | URL or file path |

**Has timestamps.**

### `peraturan_kebijakan` (Laws & Regulations)
| Column | Type | Notes |
|---|---|---|
| id | bigIncrements | PK |
| title | text | |
| description | text | |
| link | text | document URL |
| date | date | |
| time | time | |
| downloadAmount | integer | default: 0 |
| file_path | string | nullable — uploaded PDF |

**Has timestamps.**

### `panduan_teknis` (Technical Guides)
| Column | Type | Notes |
|---|---|---|
| id | bigIncrements | PK |
| title | text | |
| author | text | |
| link | text | external URL |
| file_path | string | nullable — uploaded file |

**Has timestamps.**

### `lapor_insiden` (Incident Reports)
| Column | Type | Notes |
|---|---|---|
| id | bigIncrements | PK |
| user_id | unsignedBigInteger | reporter (`users.id`) — FK enforced, `ON DELETE RESTRICT` |
| tiket_no | string | unique — `INS-YYYY-XXXX` |
| kategori_insiden | string | e.g. "Phishing", "Malware / Ransomware" |
| waktu_kejadian | dateTime | nullable — when incident occurred |
| lokasi_url | text | affected URL |
| down_time | time | nullable — downtime of the service |
| deskripsi | text | incident description |
| tindakan_teknis | text | nullable — technical actions already taken |
| cwe | string | nullable — assigned by admin (e.g. "CWE-79") |
| severity | string | nullable — Low/Medium/High/Critical (assigned by admin) |
| status | string | default: `menunggu_validasi` (see below) |
| deleted_at | timestamp | nullable — soft-delete timestamp (added 2026-08-18) |
| created_at | timestamp | |
| updated_at | timestamp | |

**Has timestamps.** Status is a closed enum with 6 values:
`menunggu_validasi → divalidasi → ditindaklanjuti → dipulihkan → selesai`,
plus `ditolak` (rejectable from `menunggu_validasi`, `divalidasi`,
`ditindaklanjuti`). Valid transitions are enforced on `App\Models\IncidentReport`
(`transitions()` / `canTransitionTo()`).

### `lampiran_insiden` (Incident Attachments)
| Column | Type | Notes |
|---|---|---|
| id | bigIncrements | PK |
| laporan_id | unsignedBigInteger | parent `lapor_insiden.id` — FK enforced, `ON DELETE CASCADE` |
| jenis | string | `file` or `url` |
| value | text | file path or evidence URL |
| created_at | timestamp | |
| updated_at | timestamp | |

**Has timestamps.** Up to 3 rows per report. File attachments are stored in
`storage/app/public/bukti_laporan/`.

### `tac_agreements` (Terms & Conditions Acceptances)
| Column | Type | Notes |
|---|---|---|
| id | bigIncrements | PK |
| user_id | unsignedBigInteger | reporter (`users.id`) — FK enforced, `ON DELETE RESTRICT` |
| version | string | e.g. `2026.08` |
| agreed_at | timestamp | when accepted |
| created_at | timestamp | |
| updated_at | timestamp | |

**Has timestamps.** One row per (user, version); the TaC gate skips users who
already agreed to the current version (`BugHunterController::TAC_VERSION`).

### `contact_us` (Contact Messages)
| Column | Type | Notes |
|---|---|---|
| id | bigIncrements | PK |
| name | string | |
| email | string | |
| phone | string | nullable |
| organization | string | nullable |
| subject | string | |
| message | text | |
| inquiry_type | string | default: 'general' |
| status | string | default: 'pending' |
| created_at | timestamp | |
| updated_at | timestamp | |

**Has timestamps.**

---

## Laravel Framework Tables

These are standard Laravel tables — you typically don't need to modify them:

| Table | Purpose |
|---|---|
| `password_reset_tokens` | Password reset tokens |
| `sessions` | User session storage |
| `cache` | Application cache |
| `cache_locks` | Cache lock mechanism |
| `jobs` | Queued jobs |
| `job_batches` | Batch job tracking |
| `failed_jobs` | Failed job records |

---

## Notes

- **Foreign keys are enforced** (added 2026-08-18): `lapor_insiden.user_id` and
  `tac_agreements.user_id` → `users` (`ON DELETE RESTRICT`), and
  `lampiran_insiden.laporan_id` → `lapor_insiden` (`ON DELETE CASCADE`).
  Deleting a user that owns reports or TaC rows fails at the DB level;
  deleting an incident cascade-deletes its attachments.
- **`events` table** (renamed from `event` in the 2026-08-18 hardening) — the
  plural name avoids the MySQL reserved-word collision; no quoting needed.
- **Every table has timestamps** — `users`, `lapor_insiden` (incl. `deleted_at`),
  `lampiran_insiden`, `tac_agreements`, `contact_us`, and the six content tables.
- **Soft-delete:** `lapor_insiden.deleted_at` + `SoftDeletes` on
  `IncidentReport` — admin delete soft-deletes only (legal-evidence retention).
- **File storage:** Proof images and uploaded files use Laravel's `storage/app/public/` directory (`bukti_laporan/` for incident evidence). In production, symlink `public/storage` → `storage/app/public`.
