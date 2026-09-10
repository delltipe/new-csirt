# stop-csirt.ps1 - stop Laravel serve (:8001) + cloudflared tunnel (:8001). App code/DB untouched.
$ErrorActionPreference = 'Continue'
$serve = Get-CimInstance Win32_Process | Where-Object {
  $_.Name -match '^php' -and ($_.CommandLine -match 'artisan serve.*8001' -or $_.CommandLine -match '\-S 127\.0\.0\.1:8001')
}
$cf = Get-CimInstance Win32_Process | Where-Object { $_.Name -match '^cloudflared' -and $_.CommandLine -match 'tunnel.*8001' }
if (-not $serve -and -not $cf) { 'NOTHING-RUNNING (no :8001 serve or tunnel procs)'; exit 0 }
foreach ($p in @($cf) + @($serve)) {
  if (-not $p) { continue }
  try {
    Stop-Process -Id $p.ProcessId -Force -ErrorAction Stop
    "STOPPED PID=$($p.ProcessId) $($p.Name)"
  } catch {
    "STOP-FAILED PID=$($p.ProcessId) $($_.Exception.Message)"
  }
}
