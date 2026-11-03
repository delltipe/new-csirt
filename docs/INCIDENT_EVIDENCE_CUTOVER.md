# Incident evidence: historical-file cutover plan

New uploads use the private `evidence` disk. The authenticated download handler
temporarily reads a matching legacy file from the public disk if no private copy
exists. **This does not make legacy URLs private**: web servers may serve
`public/storage/bukti_laporan` directly without invoking Laravel. Do not treat
the portal as ready for confidential intake until the steps below are completed
and verified in the actual hosting environment.

1. Identify the operator, actual database and storage volumes, server/CDN rules,
   backup and restore procedures, and any outstanding legacy public URLs. Take
   a recoverable backup of the database and all evidence, including files of
   soft-deleted reports. Confirm the backup can be restored.
2. Inventory file attachment records (including those belonging to soft-deleted
   reports), corresponding legacy files and unreferenced files. Record missing
   files and SHA-256 hashes without recording sensitive content in logs. Resolve
   discrepancies before cutover; do not delete unreferenced or missing evidence.
3. Copy each legacy file to the same relative path under
   `storage/app/private/incident-evidence`, preserving the original. Verify every
   copied file's size and SHA-256 against the source and confirm application
   filesystem permissions. Do not overwrite a nonmatching private file.
4. Coordinate a maintenance window: prevent concurrent uploads or reconcile
   changes, repeat the inventory and hash comparison, and configure the actual
   web server and any CDN to deny direct access to **all** paths under
   `/storage/bukti_laporan/`, including encoded variants. Apply this rule at
   every serving layer; an application route alone cannot block a static file.
   Purge cached copies as supported by the host. Do not block unrelated public
   assets. Account for backups, replicas and previously distributed copies.
5. Verify through the real serving stack that a known legacy URL is denied, new
   uploads are never publicly served, the owner and admin can download active
   reports, other users and guests cannot, and soft-deleted reports remain
   inaccessible through normal downloads. Compare copies with the inventory.
   A PHP feature test cannot establish the web-server or CDN result.
6. Record results and unresolved discrepancies. Retain original legacy files
   and backups until the owner approves a separate retention/disposal procedure.
   Rollback must not re-expose evidence publicly. No move, delete, migration or
   server change is performed by this plan.

Rate limits use the request IP; production must configure trusted proxies to
match the real ingress and use a shared persistent cache for multiple instances.
The current trust-all proxy setting is not a verified production configuration.
URL-type evidence is hosted by third parties and remains governed by their access
controls; this application cannot make those remote resources private.
