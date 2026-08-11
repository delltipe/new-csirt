@extends('layouts.app')

@section('content')
<style>
.dash-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
    overflow: hidden;
}
.dash-header::before {
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
.dash-header .container { position: relative; z-index: 1; }
.dash-header__eyebrow {
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
.dash-header__eyebrow::before {
    content: '';
    display: block;
    width: 20px; height: 1px;
    background: rgba(255,255,255,0.25);
}
.dash-header__title {
    font-family: var(--font-display);
    font-size: clamp(28px, 4vw, 44px);
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    line-height: 1;
    margin-bottom: 10px;
}
.dash-header__sub {
    font-size: 15px;
    font-weight: 300;
    color: rgba(255,255,255,0.5);
    max-width: 560px;
}

.dash-layout {
    padding: 48px 0 80px;
    background: var(--mist);
}

.dash-card {
    background: var(--white);
    border: 1px solid var(--border);
}

.dash-card__head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 24px 28px;
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
}

.dash-card__title {
    font-family: var(--font-display);
    font-size: 20px;
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--ink);
    margin: 0;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
}

.report-table thead {
    background: var(--mist);
}

.report-table th {
    font-family: var(--font-display);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--ink);
    padding: 14px 20px;
    text-align: left;
    border-bottom: 2px solid var(--border);
    white-space: nowrap;
}

.report-table td {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    color: var(--ink);
    font-size: 14px;
    vertical-align: middle;
}

.report-table tbody tr:hover {
    background: var(--navy-tint);
}

.tiket-no {
    font-family: var(--font-display);
    font-weight: 700;
    color: var(--navy);
    letter-spacing: 0.02em;
}

.btn-detail {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-body);
    font-size: 12px;
    font-weight: 600;
    padding: 6px 14px;
    border: none;
    cursor: pointer;
    transition: background var(--ease);
    text-decoration: none;
}

.btn-detail:hover {
    background: var(--navy-mid);
    color: var(--white);
}

.empty-state {
    text-align: center;
    padding: 64px 20px;
    color: var(--mid);
}

.empty-state i {
    font-size: 40px;
    color: var(--border);
    display: block;
    margin-bottom: 14px;
}

.empty-state p {
    font-size: 15px;
    margin: 0 0 24px;
}

@media (max-width: 760px) {
    .report-table th, .report-table td { padding: 12px 14px; }
    .dash-card__head { padding: 20px; }
}
</style>

<div class="dash-header">
    <div class="container">
        <div class="dash-header__eyebrow">
            <i class="bi bi-person-badge-fill" aria-hidden="true"></i>
            Portal Pelapor
        </div>
        <h1 class="dash-header__title">Dashboard Bug Hunter</h1>
        <p class="dash-header__sub">
            Pantau status laporan insiden Anda. Setiap laporan memiliki nomor tiket untuk penelusuran.
        </p>
    </div>
</div>

<div class="dash-layout">
    <div class="container">
        <div class="dash-card">
            <div class="dash-card__head">
                <h2 class="dash-card__title">Daftar Laporan Anda</h2>
                <a href="{{ route('bug-hunter.create') }}" class="btn-navy">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i> Lapor Insiden Baru
                </a>
            </div>

            @if($reports->isEmpty())
                <div class="empty-state">
                    <i class="bi bi-inbox" aria-hidden="true"></i>
                    <p>Tidak ada data yang tersedia pada tabel ini</p>
                    <a href="{{ route('bug-hunter.create') }}" class="btn-navy">
                        <i class="bi bi-megaphone-fill" aria-hidden="true"></i> Buat Laporan Pertama
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Tiket</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Jenis Laporan</th>
                                <th>CWE</th>
                                <th>Severity</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $index => $report)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="tiket-no">{{ $report->tiket_no }}</span></td>
                                <td>{{ $report->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $report->kategori_insiden }}</td>
                                <td>{{ $report->cwe ?? '—' }}</td>
                                <td>{{ $report->severity ?? '—' }}</td>
                                <td><span class="status-badge status-{{ $report->status }}">{{ $report->statusLabel() }}</span></td>
                                <td>
                                    <a href="{{ route('bug-hunter.show', $report->id) }}" class="btn-detail">
                                        <i class="bi bi-eye" aria-hidden="true"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
