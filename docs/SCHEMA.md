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
| is_admin | boolean | default: false — custom admin flag |
| is_bug_hunter | boolean | default: false — public incident reporters (added 2026-08-11) |
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

**No timestamps.**

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

**No timestamps.**

### `event` (Events)
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

**No timestamps.**

### `infografis_keamanan` (Security Infographics)
| Column | Type | Notes |
|---|---|---|
| id | bigIncrements | PK |
| title | text | |
| thumbnail | text | URL or file path |

**No timestamps.**

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

**No timestamps.**

### `panduan_teknis` (Technical Guides)
| Column | Type | Notes |
|---|---|---|
| id | bigIncrements | PK |
| title | text | |
| author | text | |
| link | text | external URL |
| file_path | string | nullable — uploaded file |

**No timestamps.**

### `lapor_insiden` (Incident Reports)
| Column | Type | Notes |
|---|---|---|
| id | bigIncrements | PK |
| user_id | unsignedBigInteger | reporter's `users.id` |
| tiket_no | string | unique — `INS-YYYY-XXXX` |
| kategori_insiden | string | incident category |
| waktu_kejadian | dateTime | nullable — when the incident happened |
| lokasi_url | text | affected URL / location |
| down_time | time | nullable |
| deskripsi | text | incident description |
| tindakan_teknis | text | nullable — technical action taken |
| cwe | string | nullable — assigned by admin |
| severity | string | nullable — assigned by admin |
| status | string | default: `menunggu_validasi` |
| created_at | timestamp | |
| updated_at | timestamp | |

**Has timestamps.** Status flow:
`menunggu_validasi → divalidasi → ditindaklanjuti → dipulihkan → selesai` (+ `ditolak`).

### `lampiran_insiden` (Incident Attachments)
| Column | Type | Notes |
|---|---|---|
| id | bigIncrements | PK |
| laporan_id | unsignedBigInteger | parent `lapor_insiden.id` |
| jenis | string | `file` or `url` |
| value | text | file path or URL |
| created_at | timestamp | |
| updated_at | timestamp | |

**Has timestamps.** ≤3 rows per report; files stored in `storage/app/public/bukti_laporan/`.

### `tac_agreements` (Terms & Conditions Acceptance)
| Column | Type | Notes |
|---|---|---|
| id | bigIncrements | PK |
| user_id | unsignedBigInteger | `users.id` |
| version | string | TaC version, e.g. `2026.08` |
| agreed_at | timestamp | |
| created_at | timestamp | |
| updated_at | timestamp | |

**Has timestamps.** One row per user per agreed version.

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

- **No foreign keys** are defined in migrations. The database relies on application-level integrity.
- **`event` table** uses a MySQL reserved word. This works in SQLite but will require quoting (`event`) if you switch to MySQL.
- **Most tables have no timestamps** — only `users`, `lapor_insiden`, `lampiran_insiden`, `tac_agreements`, and `contact_us` track `created_at`/`updated_at`.
- **File storage:** Proof images and uploaded files use Laravel's `storage/app/public/` directory (`bukti_laporan/` for incident evidence). In production, symlink `public/storage` → `storage/app/public`.
