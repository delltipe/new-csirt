# start-csirt.ps1 - start Laravel (127.0.0.1:8001) + Cloudflare Quick Tunnel.
# Safe to re-run: skips anything already running. Does NOT touch app code or DB.
$ErrorActionPreference = 'Stop'
$AppDir  = $PSScriptRoot
$Bind    = '127.0.0.1'
$Port    = 8001
$LocalUrl = "http://${Bind}:${Port}"
$RunDir  = Join-Path $env:TEMP 'csirt'
New-Item -ItemType Directory -Force -Path $RunDir | Out-Null
$PhpLog = Join-Path $RunDir 'php-8001.log'
$PhpErr = Join-Path $RunDir 'php-8001.err.log'
$CfLog  = Join-Path $RunDir 'cloudflared.log'

# Resolve php
$Php = $null
try { $Php = (Get-Command php -ErrorAction Stop).Source } catch {}
if (-not $Php -and (Test-Path 'C:\xampp\php\php.exe')) { $Php = 'C:\xampp\php\php.exe' }
if (-not $Php) { throw 'php not found in PATH or C:\xampp\php\php.exe' }

# Resolve cloudflared (prefer the binary matching the running instance)
$CfCandidates = @(
  (Join-Path $env:LOCALAPPDATA 'cloudflared\cloudflared.exe'),
  'C:\Program Files (x86)\cloudflared\cloudflared.exe',
  'C:\Program Files\cloudflared\cloudflared.exe'
)
$Cf = $null
foreach ($c in $CfCandidates) { if ($c -and (Test-Path -LiteralPath $c)) { $Cf = $c; break } }
if (-not $Cf) { try { $Cf = (Get-Command cloudflared -ErrorAction Stop).Source } catch {} }
if (-not $Cf) { throw 'cloudflared.exe not found' }

function Get-ServeProcs {
  Get-CimInstance Win32_Process | Where-Object {
    $_.Name -match '^php' -and ($_.CommandLine -match 'artisan serve.*8001' -or $_.CommandLine -match '\-S 127\.0\.0\.1:8001')
  }
}
function Get-TunnelProcs {
  Get-CimInstance Win32_Process | Where-Object { $_.Name -match '^cloudflared' -and $_.CommandLine -match 'tunnel.*8001' }
}
function Test-LocalUp {
  try {
    $r = Invoke-WebRequest "$LocalUrl/up" -TimeoutSec 3 -UseBasicParsing
    return ($r.StatusCode -eq 200)
  } catch { return $false }
}

# 1. PHP serve
$serve = Get-ServeProcs
if ($serve) {
  "PHP-ALREADY PID=$((@($serve) | ForEach-Object { $_.ProcessId }) -join ',') SKIP-START"
} else {
  $p = Start-Process -FilePath $Php -ArgumentList 'artisan','serve',"--host=$Bind","--port=$Port" `
    -WorkingDirectory $AppDir -RedirectStandardOutput $PhpLog -RedirectStandardError $PhpErr `
    -WindowStyle Hidden -PassThru
  "PHP-STARTED PID=$($p.Id) LOG=$PhpLog"
}
$ok = $false
for ($i = 0; $i -lt 30; $i++) {
  if (Test-LocalUp) { $ok = $true; break }
  Start-Sleep -Seconds 1
}
if (-not $ok) { throw "LOCAL-UP-FAILED $LocalUrl/up not 200 after 30s (see $PhpLog, $PhpErr)" }
"LOCAL-STATUS=200 $LocalUrl/up"

function Get-RecoveredTunnelUrl {
  param([array]$TunProcs, [string]$DefaultLog, [string]$RunDir)
  $cands = @()
  if ($DefaultLog) { $cands += $DefaultLog }
  foreach ($t in @($TunProcs)) {
    if ($t -and $t.CommandLine -match '--logfile[\s=]+("([^"]+)"|(\S+))') {
      $cands += ($Matches[2] + $Matches[3]).Trim()
    }
  }
  if ($RunDir -and (Test-Path -LiteralPath $RunDir)) {
    try { Get-ChildItem -LiteralPath $RunDir -Filter '*.log' -ErrorAction SilentlyContinue | ForEach-Object { $cands += $_.FullName } } catch {}
  }
  $cands = @($cands | Where-Object { $_ } | Select-Object -Unique)
  foreach ($f in $cands) {
    try {
      if (Test-Path -LiteralPath $f) {
        $m = Select-String -LiteralPath $f -Pattern 'https://[a-z0-9-]+\.trycloudflare\.com' -AllMatches -ErrorAction SilentlyContinue |
          ForEach-Object { $_.Matches } | ForEach-Object { $_.Value } | Select-Object -Last 1
        if ($m) { return @{ Url = $m; Source = $f } }
      }
    } catch {}
  }
  try {
    $r = Invoke-WebRequest 'http://127.0.0.1:20241/metrics' -TimeoutSec 3 -UseBasicParsing
    $m = [regex]::Matches($r.Content, 'https://[a-z0-9-]+\.trycloudflare\.com') | ForEach-Object { $_.Value } | Select-Object -Last 1
    if ($m) { return @{ Url = $m; Source = 'http://127.0.0.1:20241/metrics' } }
  } catch {}
  return $null
}

# 2. Quick Tunnel
$tun = Get-TunnelProcs
if ($tun) {
  "TUNNEL-ALREADY PID=$((@($tun) | ForEach-Object { $_.ProcessId }) -join ',') SKIP-START"
  $rec = Get-RecoveredTunnelUrl -TunProcs @($tun) -DefaultLog $CfLog -RunDir $RunDir
  if ($rec -and $rec.Url) {
    "PUBLIC-URL=$($rec.Url) (recovered from $($rec.Source))"
  } else {
    "TUNNEL-URL-UNKNOWN (no logfile yet). Check tunnel console output, or .\stop-csirt.ps1 then .\start-csirt.ps1 to relaunch with logging to $CfLog."
  }
} else {
  if (Test-Path -LiteralPath $CfLog) { Remove-Item -LiteralPath $CfLog -Force }
  $p = Start-Process -FilePath $Cf -ArgumentList 'tunnel','--url',"http://${Bind}:${Port}",'--logfile',$CfLog `
    -WindowStyle Hidden -PassThru
  "TUNNEL-STARTED PID=$($p.Id) LOG=$CfLog"
  $url = $null
  for ($i = 0; $i -lt 60; $i++) {
    if (Test-Path -LiteralPath $CfLog) {
      $t = Select-String -LiteralPath $CfLog -Pattern 'https://[a-z0-9-]+\.trycloudflare\.com' -AllMatches -ErrorAction SilentlyContinue |
        ForEach-Object { $_.Matches } | ForEach-Object { $_.Value } | Select-Object -Last 1
      if ($t) { $url = $t; break }
    }
    Start-Sleep -Seconds 1
  }
  if (-not $url) { throw "TUNNEL-URL-NOTFOUND after 60s (see $CfLog)" }
  "PUBLIC-URL=$url"
}
'STOP-WITH=.\stop-csirt.ps1'
