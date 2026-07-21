# Database Schema — Jakarta CSIRT Portal

> All tables, columns, and types for the Laravel project at `/mnt/d/laravel projects/new-csirt`.
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
| fullName | string | reporter name |
| email | string | reporter email |
| phoneNumber | string | reporter phone |
| foundDate | date | nullable — when incident was found |
| domain | string | affected domain (*.jakarta.go.id) |
| url | text | affected URL |
| loporDesc | text | incident description |
| riskType | string | nullable — e.g. "XSS", "SQL Injection" |
| riskLevel | string | nullable — Low/Medium/High/Critical |
| cvssScore | float | nullable — 0.0 to 10.0 |
| videoUrl | text | nullable — evidence video |
| reference | text | nullable — CVE links, articles |
| recommendation | text | nullable — suggested fix |
| proofPic | string | nullable — file path to screenshot |
| status | string | default: 'Menunggu Validasi' |
| created_at | timestamp | |
| updated_at | timestamp | |

**Has timestamps.** File uploads stored in `storage/app/public/proof_pics/`.

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
- **Most tables have no timestamps** — only `users`, `lapor_insiden`, and `contact_us` track `created_at`/`updated_at`.
- **File storage:** Proof images and uploaded files use Laravel's `storage/app/public/` directory. In production, symlink `public/storage` → `storage/app/public`.
