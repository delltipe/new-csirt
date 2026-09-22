@extends('layouts.app')

@section('content')
<style>
/* ============================================================
   PAGE HEADER
   ============================================================ */
.track-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
    overflow: hidden;
}
.track-header .container { position: relative; z-index: 1; }
.track-header__eyebrow {
    font-family: var(--font-body);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #D6E4F8;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 7px;
}
.track-header h1 {
    font-family: var(--font-display);
    font-size: clamp(28px, 4.5vw, 48px);
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    line-height: 1.1;
    margin-bottom: 12px;
}
.track-header__sub {
    font-size: 15px;
    font-weight: 300;
    color: rgba(255,255,255,0.65);
    max-width: 620px;
    line-height: 1.6;
}

/* ============================================================
   LAYOUT & SEARCH CARD
   ============================================================ */
.track-layout {
    padding: 48px 0 80px;
    background: var(--mist);
}
.track-container {
    max-width: 860px;
    margin: 0 auto;
}

.track-search-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-top: 4px solid var(--navy);
    padding: 36px 36px 32px;
    margin-bottom: 32px;
}
.track-search-card h2 {
    font-family: var(--font-display);
    font-size: 20px;
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 8px;
}
.track-search-card p {
    font-size: 14px;
    color: var(--mid);
    margin-bottom: 20px;
}

.track-search-form {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.track-search-input {
    flex: 1;
    min-width: 260px;
    height: 48px;
    border: 2px solid var(--border);
    background: var(--white);
    color: var(--ink);
    font-family: var(--font-body);
    font-size: 16px;
    font-weight: 600;
    letter-spacing: 0.04em;
    padding: 0 16px;
    text-transform: uppercase;
    outline: none;
    transition: border-color var(--ease);
}
.track-search-input:focus {
    border-color: var(--navy);
}
.track-search-btn {
    height: 48px;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 14px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 0 28px;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background var(--ease);
}
.track-search-btn:hover {
    background: var(--navy-dim);
}

/* ============================================================
   RESULT CARD & TIMELINE
   ============================================================ */
.track-result-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-top: 4px solid var(--navy);
    padding: 36px;
    margin-bottom: 32px;
}
.track-result-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--border);
    margin-bottom: 28px;
    flex-wrap: wrap;
}
.track-result-head__tiket {
    font-family: var(--font-display);
    font-size: 26px;
    font-weight: 800;
    color: var(--navy);
    letter-spacing: 0.04em;
    margin: 4px 0 0;
}
.track-result-head__label {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--mid);
}

/* Status Badges */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    font-family: var(--font-body);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.03em;
    text-transform: uppercase;
}
.status-menunggu_validasi { background: #FEF3C7; color: #92400E; border: 1px solid #F59E0B; }
.status-divalidasi        { background: #DBEAFE; color: #1E40AF; border: 1px solid #3B82F6; }
.status-ditindaklanjuti   { background: #E0E7FF; color: #3730A3; border: 1px solid #6366F1; }
.status-dipulihkan       { background: #D1FAE5; color: #065F46; border: 1px solid #10B981; }
.status-selesai           { background: #DEF7EC; color: #03543F; border: 1px solid #31C48D; }
.status-ditolak           { background: #FEE2E2; color: #991B1B; border: 1px solid #EF4444; }

/* Stepper Progress Bar */
.track-stepper {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 8px;
    margin: 32px 0 36px;
    position: relative;
}
.step-node {
    text-align: center;
    position: relative;
    padding: 12px 6px;
    background: var(--mist);
    border: 1px solid var(--border);
}
.step-node.is-active {
    background: var(--navy-tint);
    border-color: var(--navy);
}
.step-node.is-done {
    background: var(--white);
    border-color: var(--navy-mid);
}
.step-node__num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 26px;
    height: 26px;
    background: var(--border);
    color: var(--ink);
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 800;
    margin-bottom: 6px;
}
.step-node.is-done .step-node__num {
    background: var(--navy);
    color: var(--white);
}
.step-node.is-active .step-node__num {
    background: var(--navy);
    color: var(--white);
    box-shadow: 0 0 0 3px rgba(0, 53, 128, 0.25);
}
.step-node__title {
    font-family: var(--font-display);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--ink);
    line-height: 1.2;
}
.step-node.is-active .step-node__title {
    color: var(--navy);
}

/* Info Grid */
.track-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    padding: 20px;
    background: var(--mist);
    border: 1px solid var(--border);
    margin-bottom: 24px;
}
.track-info-item__label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--mid);
    margin-bottom: 4px;
}
.track-info-item__val {
    font-family: var(--font-body);
    font-size: 14px;
    font-weight: 600;
    color: var(--ink);
}

.track-disclosure-box {
    padding: 14px 18px;
    background: var(--white);
    border-left: 3px solid var(--navy);
    font-size: 12.5px;
    color: var(--mid);
    line-height: 1.6;
}
.track-disclosure-box a {
    color: var(--navy);
    font-weight: 600;
    text-decoration: underline;
}

/* Alert Error */
.track-alert-error {
    background: var(--alert-bg);
    border: 1px solid var(--alert);
    color: var(--alert-dark);
    padding: 18px 22px;
    margin-bottom: 32px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14px;
    font-weight: 600;
}
.track-alert-error i {
    font-size: 20px;
    color: var(--alert);
    flex-shrink: 0;
}

/* FAQ / Workflow Guide */
.track-guide-card {
    background: var(--white);
    border: 1px solid var(--border);
    padding: 32px 36px;
}
.track-guide-card h3 {
    font-family: var(--font-display);
    font-size: 18px;
    font-weight: 800;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 18px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border);
}
.track-guide-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 16px;
    list-style: none;
    padding: 0;
    margin: 0;
}
.track-guide-item {
    padding: 14px 16px;
    background: var(--mist);
    border-left: 3px solid var(--navy);
}
.track-guide-item strong {
    display: block;
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 4px;
}
.track-guide-item p {
    font-size: 12.5px;
    color: var(--mid);
    margin: 0;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .track-search-card, .track-result-card, .track-guide-card { padding: 24px 20px; }
    .track-stepper { grid-template-columns: 1fr; gap: 6px; }
    .step-node { display: flex; align-items: center; text-align: left; gap: 12px; }
    .step-node__num { margin-bottom: 0; flex-shrink: 0; }
}
</style>

<div class="track-header">
    <div class="container">
        <div class="track-header__eyebrow">
            <i class="bi bi-search" aria-hidden="true"></i> Layanan Pelacakan Insiden
        </div>
        <h1>Lacak Status Penanganan</h1>
        <p class="track-header__sub">
            Pantau perkembangan penanganan insiden siber secara transparan menggunakan nomor tiket resmi Anda (contoh: <code>INS-2026-0001</code>).
        </p>
    </div>
</div>

<div class="track-layout">
    <div class="container track-container">

        {{-- Search Form Card --}}
        <div class="track-search-card">
            <h2>Pencarian Nomor Tiket</h2>
            <p>Masukkan nomor tiket yang Anda dapatkan saat mengirimkan formulir laporan insiden siber.</p>
            <form action="{{ route('ticket.track') }}" method="GET" class="track-search-form" role="search">
                <input type="text" name="tiket" value="{{ $tiket }}" class="track-search-input"
                       placeholder="INS-YYYY-XXXX" aria-label="Nomor Tiket Insiden" required autofocus>
                <button type="submit" class="track-search-btn">
                    <i class="bi bi-search" aria-hidden="true"></i> Lacak Tiket
                </button>
            </form>
        </div>

        {{-- Error Alert --}}
        @if ($error)
        <div class="track-alert-error" role="alert">
            <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
            <div>{{ $error }}</div>
        </div>
        @endif

        {{-- Report Result Card --}}
        @if ($report)
        @php
            $stages = [
                'menunggu_validasi' => ['label' => 'Menunggu Validasi', 'num' => 1],
                'divalidasi'        => ['label' => 'Divalidasi', 'num' => 2],
                'ditindaklanjuti'   => ['label' => 'Ditindaklanjuti', 'num' => 3],
                'dipulihkan'       => ['label' => 'Dipulihkan', 'num' => 4],
                'selesai'           => ['label' => 'Selesai', 'num' => 5],
            ];
            $currentNum = $stages[$report->status]['num'] ?? 0;
            $isRejected = ($report->status === 'ditolak');
        @endphp
        <div class="track-result-card" aria-live="polite">
            <div class="track-result-head">
                <div>
                    <span class="track-result-head__label">Nomor Tiket Resmi</span>
                    <h2 class="track-result-head__tiket">{{ $report->tiket_no }}</h2>
                </div>
                <div>
                    <span class="status-pill status-{{ $report->status }}">
                        <i class="bi bi-dot" aria-hidden="true"></i> {{ $report->statusLabel() }}
                    </span>
                </div>
            </div>

            @if ($isRejected)
            <div style="background:#FEE2E2; border:1px solid #EF4444; padding:16px 20px; color:#991B1B; margin-bottom:24px; font-size:13.5px;">
                <strong><i class="bi bi-x-circle-fill"></i> Laporan Ditolak:</strong>
                Laporan ini dinyatakan tidak valid setelah verifikasi teknis awal oleh tim triage CSIRT. Jika Anda memiliki bukti temuan tambahan, Anda dapat mengajukan laporan baru melalui portal pelapor.
            </div>
            @else
            {{-- Stepper for Normal 5-stage Lifecycle --}}
            <div class="track-stepper" aria-label="Tahapan Penanganan">
                @foreach ($stages as $key => $meta)
                @php
                    $isDone = $currentNum > $meta['num'];
                    $isActive = ($report->status === $key);
                    $cls = $isActive ? 'is-active' : ($isDone ? 'is-done' : '');
                @endphp
                <div class="step-node {{ $cls }}">
                    <div class="step-node__num">
                        @if ($isDone)
                            <i class="bi bi-check-lg" aria-hidden="true"></i>
                        @else
                            {{ $meta['num'] }}
                        @endif
                    </div>
                    <div class="step-node__title">{{ $meta['label'] }}</div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Metadata Grid --}}
            <div class="track-info-grid">
                <div>
                    <div class="track-info-item__label">Kategori Insiden</div>
                    <div class="track-info-item__val">{{ $report->kategori_insiden }}</div>
                </div>
                <div>
                    <div class="track-info-item__label">Waktu Dilaporkan</div>
                    <div class="track-info-item__val">{{ $report->created_at->format('d M Y, H:i') }} WIB</div>
                </div>
                <div>
                    <div class="track-info-item__label">Pembaruan Terakhir</div>
                    <div class="track-info-item__val">{{ $report->updated_at->format('d M Y, H:i') }} WIB</div>
                </div>
                <div>
                    <div class="track-info-item__label">Tingkat Keparahan</div>
                    <div class="track-info-item__val">{{ $report->severity ? strtoupper($report->severity) : 'Menunggu Penilaian' }}</div>
                </div>
                <div>
                    <div class="track-info-item__label">Klasifikasi CWE</div>
                    <div class="track-info-item__val">{{ $report->cwe ?: 'Menunggu Klasifikasi' }}</div>
                </div>
            </div>

            <div class="track-disclosure-box">
                <i class="bi bi-shield-lock-fill" aria-hidden="true" style="color:var(--navy); margin-right:4px;"></i>
                <strong>Prinsip Pelindungan Data:</strong> Detail payload eksploitasi, deskripsi kerentanan, dan lampiran bukti dirahasiakan sesuai standar penanganan insiden siber.
                Pemilik laporan terdaftar dapat masuk ke <a href="{{ route('login') }}">Dashboard Pelapor</a> untuk melihat detail lengkap.
            </div>
        </div>
        @endif

        {{-- Lifecycle Guide Card --}}
        <div class="track-guide-card">
            <h3>Tahapan Operasional Penanganan Insiden CSIRT</h3>
            <ul class="track-guide-list">
                <li class="track-guide-item">
                    <strong>1. Menunggu Validasi</strong>
                    <p>Laporan masuk ke antrean triage CSIRT DKI. Tim memeriksa kelengkapan bukti dan orisinalitas temuan.</p>
                </li>
                <li class="track-guide-item">
                    <strong>2. Divalidasi</strong>
                    <p>Temuan kerentanan diverifikasi dan diberi klasifikasi CWE serta penilaian severity.</p>
                </li>
                <li class="track-guide-item">
                    <strong>3. Ditindaklanjuti</strong>
                    <p>Koordinasi teknis penanganan dan mitigasi langsung bersama OPD/instansi pengelola sistem.</p>
                </li>
                <li class="track-guide-item">
                    <strong>4. Dipulihkan</strong>
                    <p>Celah keamanan berhasil ditutup atau sistem yang terdampak telah kembali beroperasi normal.</p>
                </li>
                <li class="track-guide-item">
                    <strong>5. Selesai</strong>
                    <p>Pengujian verifikasi akhir selesai, laporan penanganan diarsipkan, dan tiket resmi ditutup.</p>
                </li>
            </ul>
        </div>

    </div>
</div>
@endsection
