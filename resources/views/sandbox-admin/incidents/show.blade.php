@extends('layouts.app')

@php
    $isValidHttpUrl = function ($url): bool {
        if (!is_string($url) || trim($url) === '') {
            return false;
        }
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        if (!in_array($scheme, ['http', 'https'], true)) {
            return false;
        }
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    };
@endphp

@section('content')
<style>
.admin-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 48px 24px 64px;
}

.admin-header {
    border-bottom: 3px solid var(--border);
    padding-bottom: 20px;
    margin-bottom: 32px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 16px;
    flex-wrap: wrap;
}

.admin-title {
    font-family: var(--font-display);
    font-size: 32px;
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--ink);
    margin: 0 0 6px;
}

.admin-sub {
    font-size: 14px;
    color: var(--mid);
    margin: 0;
}

.admin-header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.admin-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: transparent;
    color: var(--navy);
    border: 1px solid var(--navy);
    font-family: var(--font-body);
    font-size: 13px;
    font-weight: 600;
    padding: 8px 16px;
    text-decoration: none;
    transition: background var(--ease), color var(--ease);
}
.admin-back:hover {
    background: var(--navy-tint);
    color: var(--navy);
}

.alert-success-box {
    background: var(--navy-tint);
    border: 1px solid var(--navy);
    color: var(--navy-dim);
    padding: 14px 18px;
    margin-bottom: 24px;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}

.alert-error-box {
    background: var(--alert-bg);
    border: 1px solid var(--alert);
    color: var(--alert-dark);
    padding: 14px 18px;
    margin-bottom: 24px;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}

.review-grid {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    gap: 32px;
    align-items: start;
}

.detail-card {
    background: var(--white);
    border: 1px solid var(--border);
}

.detail-card__head {
    padding: 18px 24px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    background: var(--mist);
}

.detail-card__title {
    font-family: var(--font-display);
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--ink);
    margin: 0;
}

.detail-item {
    display: grid;
    grid-template-columns: 180px 1fr;
    gap: 16px;
    padding: 14px 24px;
    border-bottom: 1px solid var(--border);
}
.detail-item:last-child {
    border-bottom: none;
}

.detail-item__label {
    font-family: var(--font-body);
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: var(--mid);
    padding-top: 2px;
}

.detail-item__value {
    font-size: 13.5px;
    color: var(--ink);
    line-height: 1.65;
    word-break: break-word;
}

.detail-item__value a {
    color: var(--navy);
    text-decoration: underline;
}

.review-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-top: 4px solid var(--navy);
    position: sticky;
    top: 96px;
}

.review-card__body {
    padding: 24px;
}

.review-card__title {
    font-family: var(--font-display);
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--ink);
    margin: 0 0 16px;
}

.current-status-box {
    background: var(--mist);
    border: 1px solid var(--border);
    padding: 16px;
    margin-bottom: 20px;
}

.current-status-label {
    font-family: var(--font-body);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--mid);
    margin-bottom: 6px;
}

.status-badge {
    display: inline-block;
    font-family: var(--font-body);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    padding: 5px 12px;
    border: 1px solid transparent;
}
.status-badge--pending {
    background: #FEF3C7;
    color: #92400E;
    border-color: #F59E0B;
}
.status-badge--validated {
    background: #DBEAFE;
    color: #1E40AF;
    border-color: #3B82F6;
}
.status-badge--in_progress {
    background: #EDE9FE;
    color: #5B21B6;
    border-color: #8B5CF6;
}
.status-badge--recovered {
    background: #CCFBF1;
    color: #115E59;
    border-color: #14B8A6;
}
.status-badge--done {
    background: #DCFCE7;
    color: #166534;
    border-color: #22C55E;
}
.status-badge--rejected {
    background: #FEE2E2;
    color: #991B1B;
    border-color: #EF4444;
}

.review-label {
    display: block;
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 8px;
}

.review-select {
    width: 100%;
    height: 42px;
    border: 1px solid var(--border);
    background: var(--white);
    color: var(--ink);
    font-family: var(--font-body);
    font-size: 13.5px;
    padding: 0 12px;
    outline: none;
    box-sizing: border-box;
    margin-bottom: 18px;
    transition: border-color var(--ease);
}
.review-select:focus {
    border-color: var(--navy);
}

.btn-review-submit {
    width: 100%;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    padding: 13px;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background var(--ease);
}
.btn-review-submit:hover {
    background: var(--navy-dim);
}

.review-terminal-note {
    background: var(--mist);
    border-left: 3px solid var(--mid);
    padding: 12px 14px;
    font-size: 13px;
    color: var(--ink);
    line-height: 1.5;
}

.review-footer-note {
    font-size: 11.5px;
    color: var(--mid);
    line-height: 1.5;
    margin-top: 18px;
    padding-top: 14px;
    border-top: 1px solid var(--border);
}

.sandbox-evidence-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--mist);
    border: 1px solid var(--border);
    padding: 6px 12px;
    color: var(--navy);
    text-decoration: none;
    font-size: 12.5px;
    margin-bottom: 6px;
    word-break: break-all;
}
.sandbox-evidence-link:hover {
    background: var(--navy-tint);
    border-color: var(--navy);
}

.sandbox-evidence-untrusted {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--mist);
    border: 1px dashed var(--mid);
    padding: 6px 12px;
    color: var(--mid);
    font-size: 12.5px;
    margin-bottom: 6px;
    word-break: break-all;
}

.untrusted-url {
    color: var(--mid);
    word-break: break-all;
}

@media (max-width: 900px) {
    .review-grid {
        grid-template-columns: 1fr;
    }
    .review-card {
        position: static;
    }
}
@media (max-width: 640px) {
    .detail-item {
        grid-template-columns: 1fr;
        gap: 6px;
        padding: 12px 16px;
    }
}
</style>

<div class="admin-container">
    <div class="admin-header">
        <div>
            <h1 class="admin-title">{{ $incident->tiket_no }}</h1>
            <p class="admin-sub">
                Laporan insiden fiktif demo diajukan pada {{ $incident->created_at->format('d M Y H:i:s') }}
            </p>
        </div>
        <div class="admin-header-actions">
            <a href="{{ route('sandbox-admin.incidents.index') }}" class="admin-back">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Daftar
            </a>
            <form method="POST" action="{{ route('sandbox-admin.logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="admin-back">
                    <i class="bi bi-box-arrow-right" aria-hidden="true"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-success-box" role="status">
            <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-error-box" role="alert">
            <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="review-grid">
        {{-- Left column: Detail Laporan --}}
        <div class="detail-card">
            <div class="detail-card__head">
                <h2 class="detail-card__title">Detail Laporan Insiden (Sandbox)</h2>
                <span class="detail-badge" style="font-size: 11.5px; color: var(--mid);">
                    ID Rekaman: #{{ $incident->id }}
                </span>
            </div>

            <div class="detail-item">
                <div class="detail-item__label">Nomor Tiket</div>
                <div class="detail-item__value">
                    <strong style="font-family: 'Consolas', monospace; font-size: 14px;">
                        {{ $incident->tiket_no }}
                    </strong>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-item__label">Kategori Insiden</div>
                <div class="detail-item__value">
                    {{ $incident->kategori_insiden }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-item__label">Waktu Kejadian</div>
                <div class="detail-item__value">
                    {{ $incident->waktu_kejadian ? $incident->waktu_kejadian->format('d M Y H:i') : '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-item__label">Down Time</div>
                <div class="detail-item__value">
                    {{ $incident->down_time }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-item__label">Lokasi URL Insiden</div>
                <div class="detail-item__value">
                    @php
                        $lokasiUrl = $incident->lokasi_url;
                        $isLokasiValid = $isValidHttpUrl($lokasiUrl);
                    @endphp
                    @if ($isLokasiValid)
                        <a href="{{ $lokasiUrl }}" target="_blank" rel="noopener noreferrer">
                            {{ $lokasiUrl }}
                        </a>
                    @else
                        <span class="untrusted-url" title="Tautan tidak dapat diklik karena bukan URL HTTP/HTTPS yang sah">
                            {{ $lokasiUrl }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-item__label">Deskripsi Kejadian</div>
                <div class="detail-item__value" style="white-space: pre-wrap;">{{ $incident->deskripsi }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-item__label">Tindakan Teknis</div>
                <div class="detail-item__value" style="white-space: pre-wrap;">{{ $incident->tindakan_teknis }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-item__label">Bukti Tautan Simulasi</div>
                <div class="detail-item__value">
                    @if (!empty($incident->bukti_urls) && is_array($incident->bukti_urls))
                        @foreach ($incident->bukti_urls as $url)
                            @php
                                $isBuktiValid = $isValidHttpUrl($url);
                            @endphp
                            <div>
                                @if ($isBuktiValid)
                                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="sandbox-evidence-link">
                                        <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
                                        {{ $url }}
                                    </a>
                                @else
                                    <span class="sandbox-evidence-untrusted" title="Tautan tidak dapat diklik karena bukan URL HTTP/HTTPS yang sah">
                                        <i class="bi bi-shield-slash" aria-hidden="true"></i>
                                        {{ $url }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <span style="color: var(--mid);">Tidak ada bukti tautan.</span>
                    @endif
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-item__label">Waktu Dibuat</div>
                <div class="detail-item__value">
                    {{ $incident->created_at->format('d M Y H:i:s') }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-item__label">Terakhir Diperbarui</div>
                <div class="detail-item__value">
                    {{ $incident->updated_at->format('d M Y H:i:s') }}
                </div>
            </div>
        </div>

        {{-- Right column: Pembaruan Status --}}
        <div class="review-card">
            <div class="review-card__body">
                <h2 class="review-card__title">Tinjauan &amp; Status</h2>

                <div class="current-status-box">
                    <div class="current-status-label">Status Saat Ini</div>
                    @php
                        $badgeClass = match ($incident->status) {
                            \App\Models\SandboxIncident::STATUS_PENDING => 'status-badge--pending',
                            \App\Models\SandboxIncident::STATUS_VALIDATED => 'status-badge--validated',
                            \App\Models\SandboxIncident::STATUS_IN_PROGRESS => 'status-badge--in_progress',
                            \App\Models\SandboxIncident::STATUS_RECOVERED => 'status-badge--recovered',
                            \App\Models\SandboxIncident::STATUS_DONE => 'status-badge--done',
                            \App\Models\SandboxIncident::STATUS_REJECTED => 'status-badge--rejected',
                            default => 'status-badge--pending',
                        };
                    @endphp
                    <span class="status-badge {{ $badgeClass }}">
                        {{ $incident->statusLabel() }}
                    </span>
                </div>

                @if (!empty($statusOptions))
                    <form method="POST" action="{{ route('sandbox-admin.incidents.update-status', $incident->id) }}">
                        @csrf
                        @method('PATCH')

                        <label for="status" class="review-label">Perbarui ke Status Baru:</label>
                        <select id="status" name="status" class="review-select" required>
                            <option value="">-- Pilih Status Berikutnya --</option>
                            @foreach ($statusOptions as $statusKey => $statusLabel)
                                <option value="{{ $statusKey }}">
                                    {{ $statusLabel }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn-review-submit">
                            <i class="bi bi-check2-circle" aria-hidden="true"></i> Perbarui Status Laporan
                        </button>
                    </form>
                @else
                    <div class="review-terminal-note">
                        <i class="bi bi-info-circle-fill" aria-hidden="true" style="margin-right: 4px;"></i>
                        Status saat ini (<strong>{{ $incident->statusLabel() }}</strong>) adalah status final. Tidak ada perubahan status lanjutan yang diizinkan pada alur ini.
                    </div>
                @endif

                <div class="review-footer-note">
                    <i class="bi bi-shield-lock" aria-hidden="true"></i>
                    Pembaruan status divalidasi ketat di sisi server. Hanya nilai status sah yang dapat disimpan ke basis data PostgreSQL sandbox.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
