@extends('layouts.app')

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

.admin-btn {
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
    cursor: pointer;
    transition: background var(--ease), color var(--ease);
}
.admin-btn:hover {
    background: var(--navy-tint);
    color: var(--navy);
}

.sandbox-info-banner {
    background: var(--navy-tint);
    border-left: 4px solid var(--navy);
    padding: 14px 18px;
    margin-bottom: 28px;
    font-size: 13.5px;
    color: var(--navy-dim);
    line-height: 1.55;
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-bar {
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.filter-tab {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: var(--font-body);
    font-size: 12.5px;
    font-weight: 600;
    padding: 7px 14px;
    border: 1px solid var(--border);
    background: var(--white);
    color: var(--ink);
    text-decoration: none;
    transition: background var(--ease), border-color var(--ease);
}
.filter-tab:hover {
    background: var(--mist);
    border-color: var(--mid);
    color: var(--ink);
}
.filter-tab.active {
    background: var(--navy);
    border-color: var(--navy);
    color: var(--white);
}

.filter-count {
    display: inline-block;
    padding: 1px 6px;
    font-size: 11px;
    font-weight: 700;
    border-radius: 2px;
    background: rgba(0, 0, 0, 0.08);
}
.filter-tab.active .filter-count {
    background: rgba(255, 255, 255, 0.25);
    color: var(--white);
}

.data-table-wrap {
    overflow-x: auto;
    border: 1px solid var(--border);
    background: var(--white);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: var(--mist);
}

.data-table th {
    font-family: var(--font-display);
    font-size: 11.5px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--ink);
    padding: 14px 16px;
    text-align: left;
    border-bottom: 2px solid var(--border);
    white-space: nowrap;
}

.data-table td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border);
    color: var(--ink);
    font-size: 13.5px;
    vertical-align: middle;
}

.data-table tbody tr:hover {
    background: var(--navy-tint);
}

.ticket-code {
    font-family: 'Consolas', 'Courier New', monospace;
    font-weight: 700;
    font-size: 13.5px;
    color: var(--navy);
    text-decoration: none;
}
.ticket-code:hover {
    text-decoration: underline;
}

.status-badge {
    display: inline-block;
    font-family: var(--font-body);
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    padding: 4px 10px;
    border: 1px solid transparent;
    white-space: nowrap;
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

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-body);
    font-size: 12px;
    font-weight: 600;
    padding: 6px 12px;
    text-decoration: none;
    transition: background var(--ease);
}
.action-btn:hover {
    background: var(--navy-dim);
    color: var(--white);
}

.empty-state {
    padding: 48px 24px;
    text-align: center;
    color: var(--mid);
    font-size: 14px;
}

.pagination-wrap {
    margin-top: 24px;
    display: flex;
    justify-content: center;
}
</style>

<div class="admin-container">
    <div class="admin-header">
        <div>
            <h1 class="admin-title">Tinjauan Insiden Sandbox</h1>
            <p class="admin-sub">
                Menampilkan {{ $incidents->total() }} laporan fiktif demo pada basis data PostgreSQL sandbox.
            </p>
        </div>
        <div class="admin-header-actions">
            <form method="POST" action="{{ route('sandbox-admin.logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="admin-btn">
                    <i class="bi bi-box-arrow-right" aria-hidden="true"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    <div class="sandbox-info-banner">
        <i class="bi bi-shield-check" aria-hidden="true" style="font-size: 18px;"></i>
        <div>
            <strong>Isolasi Sandbox:</strong> Seluruh laporan insiden di bawah ini dibaca dan dikelola secara eksklusif dari koneksi PostgreSQL <code>sandbox</code>. Basis data publik SQLite tetap terisolasi dan tidak mengalami mutasi data.
        </div>
    </div>

    {{-- Filter bar --}}
    <div class="filter-bar">
        <a href="{{ route('sandbox-admin.incidents.index') }}"
           class="filter-tab {{ empty($statusFilter) ? 'active' : '' }}">
            Semua <span class="filter-count">{{ $totalCount }}</span>
        </a>
        @foreach (\App\Models\SandboxIncident::labels() as $key => $label)
            @php $count = $statusCounts[$key] ?? 0; @endphp
            <a href="{{ route('sandbox-admin.incidents.index', ['status' => $key]) }}"
               class="filter-tab {{ $statusFilter === $key ? 'active' : '' }}">
                {{ $label }} <span class="filter-count">{{ $count }}</span>
            </a>
        @endforeach
    </div>

    {{-- Data Table --}}
    <div class="data-table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>No Tiket</th>
                    <th>Kategori Insiden</th>
                    <th>Lokasi URL Insiden</th>
                    <th>Waktu Kejadian</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($incidents as $index => $incident)
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
                    <tr>
                        <td style="color: var(--mid); font-size: 12.5px;">
                            {{ $incidents->firstItem() + $index }}
                        </td>
                        <td>
                            <a href="{{ route('sandbox-admin.incidents.show', $incident->id) }}" class="ticket-code">
                                {{ $incident->tiket_no }}
                            </a>
                        </td>
                        <td>
                            <strong>{{ $incident->kategori_insiden }}</strong>
                        </td>
                        <td style="max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <span title="{{ $incident->lokasi_url }}">{{ $incident->lokasi_url }}</span>
                        </td>
                        <td style="white-space: nowrap; font-size: 13px;">
                            {{ $incident->waktu_kejadian ? $incident->waktu_kejadian->format('d M Y H:i') : '—' }}
                        </td>
                        <td>
                            <span class="status-badge {{ $badgeClass }}">
                                {{ $incident->statusLabel() }}
                            </span>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <a href="{{ route('sandbox-admin.incidents.show', $incident->id) }}" class="action-btn">
                                <i class="bi bi-eye" aria-hidden="true"></i> Tinjau
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="bi bi-folder2-open" aria-hidden="true" style="font-size: 28px; display: block; margin-bottom: 8px;"></i>
                            Belum ada laporan insiden demo yang ditemukan pada filter ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($incidents->hasPages())
        <div class="pagination-wrap">
            {{ $incidents->links() }}
        </div>
    @endif
</div>
@endsection
