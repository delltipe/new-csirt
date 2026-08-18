@extends('layouts.app')

@php
    $transitionTargets = \App\Models\IncidentReport::transitions()[$incident->status] ?? [];
    $statusOptions = array_merge(
        [$incident->status => $incident->statusLabel()],
        array_intersect_key(\App\Models\IncidentReport::labels(), array_flip($transitionTargets))
    );
    $cweOptions = [
        'CWE-79' => 'CWE-79 — Cross-site Scripting (XSS)',
        'CWE-89' => 'CWE-89 — SQL Injection',
        'CWE-352' => 'CWE-352 — Cross-Site Request Forgery (CSRF)',
        'CWE-434' => 'CWE-434 — Unrestricted Upload of Dangerous Type',
        'CWE-200' => 'CWE-200 — Exposure of Sensitive Information',
        'CWE-601' => 'CWE-601 — Open Redirect',
        'CWE-22' => 'CWE-22 — Path Traversal',
        'CWE-611' => 'CWE-611 — Improper Restriction of XML (XXE)',
        'CWE-918' => 'CWE-918 — Server-Side Request Forgery (SSRF)',
        'CWE-287' => 'CWE-287 — Improper Authentication',
        'CWE-284' => 'CWE-284 — Improper Access Control',
        'CWE-400' => 'CWE-400 — Uncontrolled Resource Consumption',
        'CWE-502' => 'CWE-502 — Deserialization of Untrusted Data',
        'CWE-1204' => 'CWE-1204 — Weak Password Requirements',
        'Lainnya' => 'Lainnya / Tidak Diketahui',
    ];
@endphp

@section('content')
<style>
.admin-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 60px 28px;
}

.admin-header {
    border-bottom: 3px solid var(--border);
    padding-bottom: 20px;
    margin-bottom: 40px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 16px;
    flex-wrap: wrap;
}

.admin-title {
    font-family: var(--font-display);
    font-size: 36px;
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--ink);
    margin: 0;
}

.admin-sub {
    font-size: 14px;
    color: var(--mid);
    margin-top: 6px;
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
    padding: 9px 18px;
    text-decoration: none;
    transition: background var(--ease), color var(--ease);
}
.admin-back:hover { background: var(--navy-tint); }

.alert-success {
    background: var(--navy-tint);
    border-left: 4px solid var(--navy);
    color: var(--navy-dim);
    padding: 16px 20px;
    margin-bottom: 24px;
    font-size: 14px;
    font-weight: 500;
}

.alert-error {
    background: var(--alert-bg);
    border-left: 4px solid var(--alert);
    color: var(--alert-dark);
    padding: 16px 20px;
    margin-bottom: 24px;
    font-size: 14px;
    font-weight: 500;
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
    padding: 20px 28px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.detail-card__title {
    font-family: var(--font-display);
    font-size: 16px;
    font-weight: 800;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--ink);
    margin: 0;
}

.detail-item {
    display: grid;
    grid-template-columns: 170px 1fr;
    gap: 16px;
    padding: 13px 28px;
    border-bottom: 1px solid var(--border);
}

.detail-item__label {
    font-family: var(--font-body);
    font-size: 11px;
    font-weight: 600;
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
    text-decoration: none;
    border-bottom: 1px solid var(--navy);
}

.review-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-top: 3px solid var(--navy);
    position: sticky;
    top: 96px;
}

.review-card__body {
    padding: 28px;
}

.review-label {
    display: block;
    font-family: var(--font-display);
    font-size: 12.5px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 8px;
}

.review-select,
.review-input {
    width: 100%;
    height: 42px;
    border: 1px solid var(--border);
    background: var(--white);
    color: var(--ink);
    font-family: var(--font-body);
    font-size: 13.5px;
    padding: 0 12px;
    outline: none;
    margin-bottom: 22px;
    transition: border-color var(--ease);
}

.review-select:focus,
.review-input:focus {
    border-color: var(--navy);
}

.btn-review-submit {
    width: 100%;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 14px;
    font-weight: 800;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    padding: 14px;
    border: none;
    cursor: pointer;
    transition: background var(--ease);
}
.btn-review-submit:hover { background: var(--navy-dim); }

.delete-divider {
    border: none;
    border-top: 1px solid var(--border);
    margin: 24px 0;
}

.btn-delete {
    width: 100%;
    background: var(--alert);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    padding: 12px;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: background var(--ease);
}
.btn-delete:hover {
    background: var(--alert-dark);
    color: var(--white);
}

.attachment-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--navy-tint);
    color: var(--navy);
    border: 1px solid var(--navy);
    font-family: var(--font-body);
    font-size: 12px;
    font-weight: 600;
    padding: 5px 10px;
    text-decoration: none;
    margin: 0 6px 6px 0;
    transition: background var(--ease);
}
.attachment-link:hover { background: var(--navy); color: var(--white); }

@media (max-width: 900px) {
    .review-grid { grid-template-columns: 1fr; }
    .review-card { position: static; }
}
@media (max-width: 640px) {
    .detail-item { grid-template-columns: 1fr; gap: 4px; padding: 12px 20px; }
}
</style>

<div class="admin-container">
    <div class="admin-header">
        <div>
            <h1 class="admin-title">{{ $incident->tiket_no }}</h1>
            <div class="admin-sub">
                Diajukan {{ $incident->created_at->format('d M Y H:i') }} oleh {{ $incident->user->name ?? '—' }}
                ({{ $incident->user->email ?? '—' }})
            </div>
        </div>
        <a href="{{ route('admin.incidents.list') }}" class="admin-back">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
        </a>
    </div>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    <div class="review-grid">

        <div class="detail-card">
            <div class="detail-card__head">
                <h2 class="detail-card__title">Detail Laporan</h2>
                <span class="status-badge status-{{ $incident->status }}">{{ $incident->statusLabel() }}</span>
            </div>

            <div class="detail-item">
                <div class="detail-item__label">Kategori Insiden</div>
                <div class="detail-item__value">{{ $incident->kategori_insiden }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-item__label">Waktu Kejadian</div>
                <div class="detail-item__value">{{ $incident->waktu_kejadian?->format('d M Y H:i') ?? '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-item__label">Lokasi / URL</div>
                <div class="detail-item__value">
                    <a href="{{ $incident->lokasi_url }}" target="_blank" rel="noopener noreferrer">{{ $incident->lokasi_url }}</a>
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-item__label">Down Time</div>
                <div class="detail-item__value">{{ $incident->down_time ?? '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-item__label">Deskripsi</div>
                <div class="detail-item__value">{{ $incident->deskripsi }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-item__label">Tindakan Teknis</div>
                <div class="detail-item__value">{{ $incident->tindakan_teknis ?? '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-item__label">CWE</div>
                <div class="detail-item__value">{{ $incident->cwe ?? '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-item__label">Severity</div>
                <div class="detail-item__value">{{ $incident->severity ?? '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-item__label">Status</div>
                <div class="detail-item__value"><span class="status-badge status-{{ $incident->status }}">{{ $incident->statusLabel() }}</span></div>
            </div>
            <div class="detail-item">
                <div class="detail-item__label">Bukti Laporan</div>
                <div class="detail-item__value">
                    @forelse ($incident->attachments as $attachment)
                        @if ($attachment->jenis === 'file')
                            <a class="attachment-link" href="{{ asset('storage/' . $attachment->value) }}" target="_blank" rel="noopener noreferrer">
                                <i class="bi bi-file-earmark-image" aria-hidden="true"></i> {{ basename($attachment->value) }}
                            </a>
                        @else
                            <a class="attachment-link" href="{{ $attachment->value }}" target="_blank" rel="noopener noreferrer">
                                <i class="bi bi-link-45deg" aria-hidden="true"></i> {{ $attachment->value }}
                            </a>
                        @endif
                    @empty
                        Tidak ada bukti lampiran.
                    @endforelse
                </div>
            </div>
        </div>

        <div class="review-card">
            <div class="review-card__body">
                <form method="POST" action="{{ route('admin.incidents.review', $incident->id) }}">
                    @csrf

                    <label class="review-label" for="cwe">CWE</label>
                    <select name="cwe" id="cwe" class="review-select">
                        <option value="" {{ $incident->cwe ? '' : 'selected' }}>Pilih CWE...</option>
                        @foreach ($cweOptions as $value => $label)
                            <option value="{{ $value }}" {{ $incident->cwe === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>

                    <label class="review-label" for="severity">Severity</label>
                    <select name="severity" id="severity" class="review-select">
                        <option value="" {{ $incident->severity ? '' : 'selected' }}>Pilih Severity...</option>
                        @foreach (['Low', 'Medium', 'High', 'Critical'] as $severity)
                            <option value="{{ $severity }}" {{ $incident->severity === $severity ? 'selected' : '' }}>{{ $severity }}</option>
                        @endforeach
                    </select>

                    <label class="review-label" for="status">Perbarui Status</label>
                    <select name="status" id="status" class="review-select">
                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ $incident->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn-review-submit">
                        <i class="bi bi-check2-circle" aria-hidden="true"></i> Simpan Review
                    </button>
                </form>

                <hr class="delete-divider">

                <form method="POST" action="{{ route('admin.incidents.delete', $incident->id) }}" onsubmit="return confirm('Hapus laporan {{ $incident->tiket_no }}? Laporan hanya ditandai terhapus dan tetap tersimpan untuk arsip.');">
                    @csrf
                    <button type="submit" class="btn-delete">
                        <i class="bi bi-trash" aria-hidden="true"></i> Hapus Laporan
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
