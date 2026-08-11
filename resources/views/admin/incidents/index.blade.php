@extends('layouts.app')

@section('content')
<style>
.admin-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 60px 28px;
}

.admin-header {
    border-bottom: 3px solid var(--ink);
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
    font-size: 42px;
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--ink);
    margin: 0;
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

.filter-bar {
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.filter-select {
    height: 40px;
    border: 1px solid var(--border);
    background: var(--white);
    color: var(--ink);
    font-family: var(--font-body);
    font-size: 13px;
    padding: 0 12px;
    outline: none;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid var(--border);
    background: var(--white);
}

.data-table thead {
    background: var(--mist);
}

.data-table th {
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--ink);
    padding: 16px 20px;
    text-align: left;
    border-bottom: 2px solid var(--border);
}

.data-table td {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    color: var(--ink);
    font-size: 14px;
}

.data-table tbody tr:hover {
    background: var(--navy-tint);
}

.btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 4px;
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

.btn-edit:hover {
    background: var(--navy-mid);
    color: var(--white);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--mid);
    background: var(--white);
    border: 1px solid var(--border);
}

.empty-state p {
    font-size: 16px;
    margin: 0;
}

.pagination-wrap { margin-top: 24px; }
</style>

<div class="admin-container">
    <div class="admin-header">
        <h1 class="admin-title">Laporan Insiden</h1>
        <a href="{{ route('admin.dashboard') }}" class="admin-back">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Dashboard
        </a>
    </div>

    <form method="GET" action="{{ route('admin.incidents.list') }}" class="filter-bar">
        <label class="filter-label" for="status-filter" style="font-size:13px;color:var(--mid);">Filter Status:</label>
        <select name="status" id="status-filter" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach (\App\Models\IncidentReport::labels() as $key => $label)
                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </form>

    @if ($incidents->isEmpty())
        <div class="empty-state">
            <p>Tidak ada data yang tersedia pada tabel ini</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No Tiket</th>
                        <th>Tanggal</th>
                        <th>Pelapor</th>
                        <th>Kategori</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($incidents as $incident)
                    <tr>
                        <td><strong>{{ $incident->tiket_no }}</strong></td>
                        <td>{{ $incident->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $incident->user->name ?? '—' }}</td>
                        <td>{{ $incident->kategori_insiden }}</td>
                        <td>{{ $incident->severity ?? '—' }}</td>
                        <td><span class="status-badge status-{{ $incident->status }}">{{ $incident->statusLabel() }}</span></td>
                        <td>
                            <a href="{{ route('admin.incidents.show', $incident->id) }}" class="btn-edit">
                                <i class="bi bi-eye" aria-hidden="true"></i> Review
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $incidents->links() }}</div>
    @endif
</div>
@endsection
