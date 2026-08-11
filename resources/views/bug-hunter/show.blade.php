@extends('layouts.app')

@section('content')
<style>
.detail-header {
    background: var(--ink);
    padding: 48px 0 40px;
    position: relative;
    overflow: hidden;
}
.detail-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: repeating-linear-gradient(
        90deg,
        rgba(255,255,255,0.02) 0px, rgba(255,255,255,0.02) 1px,
        transparent 1px, transparent 80px
    );
    pointer-events: none;
}
.detail-header .container { position: relative; z-index: 1; }
.detail-header__eyebrow {
    font-family: var(--font-body);
    font-size: 10.5px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--muted-on-dark);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.detail-header__eyebrow::before {
    content: '';
    display: block;
    width: 20px; height: 1px;
    background: rgba(255,255,255,0.2);
}
.detail-header__title {
    font-family: var(--font-display);
    font-size: clamp(28px, 4vw, 44px);
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    line-height: 1;
    margin-bottom: 12px;
}

.detail-layout {
    padding: 48px 0 80px;
    background: var(--mist);
}
.detail-layout .container {
    max-width: 900px;
}

.detail-card {
    background: var(--white);
    border: 1px solid var(--border);
}

.detail-card__head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 24px 32px;
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
}

.detail-card__title {
    font-family: var(--font-display);
    font-size: 18px;
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--ink);
    margin: 0;
}

.detail-list {
    padding: 12px 0;
}

.detail-item {
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: 20px;
    padding: 14px 32px;
    border-bottom: 1px solid var(--border);
}

.detail-item__label {
    font-family: var(--font-body);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: var(--mid);
    padding-top: 2px;
}

.detail-item__value {
    font-size: 14px;
    color: var(--ink);
    line-height: 1.7;
    word-break: break-word;
}

.detail-item__value a {
    color: var(--navy);
    text-decoration: none;
    border-bottom: 1px solid var(--navy);
}

.attachment-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--navy-tint);
    color: var(--navy);
    border: 1px solid var(--navy);
    font-family: var(--font-body);
    font-size: 12.5px;
    font-weight: 600;
    padding: 6px 12px;
    text-decoration: none;
    margin: 0 6px 6px 0;
    transition: background var(--ease);
}
.attachment-link:hover { background: var(--navy); color: var(--white); }

.detail-actions {
    padding: 24px 32px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.btn-detail-action {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 14px;
    font-weight: 800;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    padding: 12px 26px;
    border: none;
    cursor: pointer;
    transition: background var(--ease);
    text-decoration: none;
}
.btn-detail-action:hover { background: var(--navy-dim); color: var(--white); }

.btn-detail-ghost {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    color: var(--navy);
    border: 1px solid var(--navy);
    font-family: var(--font-body);
    font-size: 13px;
    font-weight: 500;
    padding: 11px 26px;
    text-decoration: none;
    transition: background var(--ease), color var(--ease);
}
.btn-detail-ghost:hover { background: var(--navy-tint); }

@media (max-width: 640px) {
    .detail-item { grid-template-columns: 1fr; gap: 4px; padding: 14px 20px; }
    .detail-card__head, .detail-actions { padding: 20px; }
}
</style>

<div class="detail-header">
    <div class="container">
        <div class="detail-header__eyebrow">
            <i class="bi bi-ticket-perforated-fill" aria-hidden="true"></i>
            Detail Laporan
        </div>
        <h1 class="detail-header__title">{{ $report->tiket_no }}</h1>
        <span class="status-badge status-{{ $report->status }}">{{ $report->statusLabel() }}</span>
    </div>
</div>

<div class="detail-layout">
    <div class="container">
        <div class="detail-card">
            <div class="detail-card__head">
                <h2 class="detail-card__title">Informasi Laporan</h2>
            </div>

            <div class="detail-list">
                <div class="detail-item">
                    <div class="detail-item__label">No Tiket</div>
                    <div class="detail-item__value">{{ $report->tiket_no }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item__label">Tanggal Pengajuan</div>
                    <div class="detail-item__value">{{ $report->created_at->format('d M Y H:i') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item__label">Kategori Insiden</div>
                    <div class="detail-item__value">{{ $report->kategori_insiden }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item__label">Waktu Kejadian</div>
                    <div class="detail-item__value">{{ $report->waktu_kejadian?->format('d M Y H:i') ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item__label">Lokasi / URL</div>
                    <div class="detail-item__value">
                        <a href="{{ $report->lokasi_url }}" target="_blank" rel="noopener noreferrer">{{ $report->lokasi_url }}</a>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-item__label">Down Time</div>
                    <div class="detail-item__value">{{ $report->down_time ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item__label">Deskripsi</div>
                    <div class="detail-item__value">{{ $report->deskripsi }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item__label">Tindakan Teknis</div>
                    <div class="detail-item__value">{{ $report->tindakan_teknis ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item__label">CWE</div>
                    <div class="detail-item__value">{{ $report->cwe ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item__label">Severity</div>
                    <div class="detail-item__value">{{ $report->severity ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item__label">Status</div>
                    <div class="detail-item__value"><span class="status-badge status-{{ $report->status }}">{{ $report->statusLabel() }}</span></div>
                </div>
                <div class="detail-item">
                    <div class="detail-item__label">Bukti Laporan</div>
                    <div class="detail-item__value">
                        @forelse ($report->attachments as $attachment)
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

            <div class="detail-actions">
                <a href="{{ route('bug-hunter.dashboard') }}" class="btn-detail-ghost">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
                </a>
                <a href="{{ route('bug-hunter.create') }}" class="btn-detail-action">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i> Lapor Insiden Baru
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
