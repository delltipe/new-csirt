# Public Preview Maintenance Guide

## Purpose

This branch provides a public demonstration of the Jakarta CSIRT Portal.
The original project's `main` branch is the source of truth for normal
features, UI, accessibility, design and public-content improvements.

The preview should follow these updates as closely as possible while
preserving the differences required for safe public demonstration.

## Rules

1. Keep normal features and UI aligned with the original project wherever applicable.
2. Preserve preview-only security restrictions, deployment configuration
   and GoatCounter analytics.
3. Never enable real incident submissions, real contact-message delivery,
   unrestricted uploads, real ticket updates or administrative operations
   merely to make the preview appear functional.
4. Any demo workflow must be clearly labelled as simulated and use fictional
   data. Never expose real incident records, private evidence, credentials,
   secrets or personal information.
5. Do not send demo input to real submission handlers, external notification
   services or analytics. Avoid collecting sensitive information.
6. Never copy preview-only security overrides or demo data into the original
   project without explicit authorization.
7. Never disturb the original repository's existing uncommitted changes.
8. Never push, deploy, or modify hosting settings without explicit authorization.

## Updating the Preview

When the original project receives new features or improvements:

1. Review the changes in `main` and identify which ones apply to the preview.
2. Bring applicable changes into the preview branch using a reviewed Git
   operation. Do not blindly merge or overwrite files.
3. Resolve conflicts while preserving both the original improvements and
   the preview's required security controls.
4. Review route restrictions, environment configuration, analytics and
   deployment-specific changes after synchronization.
5. Run relevant tests, including the preview security tests, and report
   any failures honestly.
6. Summarize the changes and remaining differences before requesting
   authorization to commit, push or deploy.

## Sandbox Incident Retention and Maintenance

### Database Architecture and Separation

The public preview strictly separates public showcase content from simulated user submissions:

- **Showcase Content (SQLite):** Stored in `database/database.sqlite` via the default connection (`sqlite`). This database holds read-only official content (news, events, infographics, warnings, laws, guides, hero slides) and is baked into the deployment container.
- **Simulated Incidents (PostgreSQL Sandbox):** Stored in table `sandbox_incidents` via the isolated `sandbox` database connection (`config/database.php`). In production and preview environments, this connects to an external PostgreSQL service configured via `DATABASE_URL` (or `SANDBOX_DB_*`).
- Container rebuilds and redeployments re-initialize only the ephemeral SQLite showcase; records in the PostgreSQL sandbox persist across redeployments, restarts, and sleep/wake cycles.

### Retention Policy

1. **Advisory Retention Window:** Transient public visitor submissions qualify for cleanup after **14 days** (`created_at < now()->subDays(14)`).
2. **Indefinitely Protected Demonstration Tickets:** The following tickets are permanently protected and must never be purged:
   - `DEMO-2026-B7TGZTCI` (canonical public tracking example)
   - `DEMO-2026-CHH7JAMF` (verified demonstration ticket)
   These tickets are programmatically excluded in `SandboxPruneIncidentsCommand::PROTECTED_TICKETS`.
3. **Execution Model:** Manual and on demand only. Automatic scheduled execution (e.g. cron daemons or `schedule:run`) and public web-accessible reset endpoints are strictly prohibited.
4. **Showcase Integrity:** Default SQLite showcase tables must remain untouched during any sandbox maintenance operation.

### Maintenance Command (`sandbox:prune-incidents`)

The Artisan command provides safe, inspected cleanup scoped exclusively to the sandbox database:

```bash
php artisan sandbox:prune-incidents [--days=14] [--execute]
```

#### 1. Default Inspection Mode (Dry-Run)
Without `--execute`, the command is strictly read-only:

```bash
# Inspect eligible records older than 14 days (default)
php artisan sandbox:prune-incidents

# Inspect eligible records with a custom threshold (e.g. 30 days)
php artisan sandbox:prune-incidents --days=30
```

- Discovers records older than the cutoff on the `sandbox` connection.
- Displays candidate count, ticket numbers, categories, statuses, and creation timestamps.
- Explicitly reports excluded protected demonstration tickets.
- Guarantees zero records are deleted.

#### 2. Explicit Execution Mode
To perform actual cleanup, supply `--execute`:

```bash
php artisan sandbox:prune-incidents --execute
php artisan sandbox:prune-incidents --days=30 --execute
```

- Recalculates candidate records immediately before execution.
- Displays candidate records.
- Prompts for interactive confirmation (`PERINGATAN: ... Lanjutkan?`) with default answer **No** (`false`).
- If confirmation is declined or cannot safely be obtained, the command aborts with zero deletions.
- Deletion is performed inside a database transaction on the `sandbox` connection.
- There is no `--force` flag; interactive confirmation cannot be bypassed.

#### 3. Fail-Closed Isolation Safeguards
The command aborts immediately if:
- The default database connection is configured as `sandbox`.
- The sandbox driver in non-testing environments is anything other than `pgsql`.
- The sandbox connection configuration is missing or cannot be resolved.
- An invalid `--days` value is supplied (e.g. zero, negative, or non-integer).

### Pre-Maintenance Backup Procedure (PostgreSQL)

Before performing destructive maintenance on the live sandbox database, create a verified backup:

1. **Export with `pg_dump` (Custom Format):**
   ```bash
   # Export only the sandbox_incidents table to a timestamped custom-format archive
   pg_dump "$DATABASE_URL" -Fc -t sandbox_incidents -f /secure/backup/path/sandbox_incidents_$(date +%Y%m%d_%H%M%S).dump
   ```

2. **Verify Archive Readability:**
   ```bash
   # Verify the backup archive contains the expected schema and table TOC
   pg_restore --list /secure/backup/path/sandbox_incidents_YYYYMMDD_HHMMSS.dump
   ```

3. **Secure Storage Location:**
   - Backup files must be stored **outside the Git repository** and strictly outside web-accessible directories (`public/`, `storage/app/public/`).
   - Secure the file with least-privilege permissions (e.g., `chmod 600`).
   - Never commit backup files to version control.

4. **Testing Restoration:**
   - Any restore operation must first be verified against an isolated, temporary local database.
   - Never run unverified restore operations directly against the live sandbox database.

## Boundaries

The original repository remains the main development project.
The preview branch must never be merged back into `main` without explicit
authorization.

A live restoration, hosting configuration change, deployment, or destructive
maintenance operation beyond the implemented `sandbox:prune-incidents` command
requires separate authorization.

This document is maintenance guidance, not a substitute for enforced
security controls or automated tests.
