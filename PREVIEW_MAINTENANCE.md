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

## Boundaries

The original repository remains the main development project.
The preview branch must never be merged back into `main` without explicit
authorization.

This document is maintenance guidance, not a substitute for enforced
security controls or automated tests.
