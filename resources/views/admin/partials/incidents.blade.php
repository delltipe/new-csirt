<div class="mb-4">
    <div class="section-actions">
        <h4 class="section-title-small">Laporan Insiden</h4>
        <a href="{{ route('admin.incidents.list') }}" class="btn-add">
            <i class="bi bi-arrow-up-right-square" aria-hidden="true"></i> Lihat Semua
        </a>
    </div>
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
                        <td>{{ $incident->created_at->format('d M Y') }}</td>
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
        @if ($incidents->hasPages())
            <div class="mt-3">{{ $incidents->links() }}</div>
        @endif
    @endif
</div>
