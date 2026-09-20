<div class="mb-4">
    <div class="section-actions">
        <h4 class="section-title-small">Kelola Pesan Kontak</h4>
        <a href="{{ route('admin.contacts.list') }}" class="btn-add">
            <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i> Lihat Semua
        </a>
    </div>
    @if($contacts->isEmpty())
        <div class="empty-state">
            <p>Tidak ada pesan kontak.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Subjek</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contacts as $contact)
                    <tr>
                        <td>
                            <div style="font-weight:600;">{{ $contact->name }}</div>
                            <small style="color:var(--mid);">{{ $contact->email }}</small>
                        </td>
                        <td>{{ Str::limit($contact->subject, 40) }}</td>
                        <td><span class="status-badge status-menunggu_validasi" style="font-size:11.5px;">{{ $contact->inquiry_type }}</span></td>
                        <td>
                            @php
                                $badgeClass = match($contact->status) {
                                    'pending' => 'status-menunggu_validasi',
                                    'diproses' => 'status-ditindaklanjuti',
                                    'selesai' => 'status-selesai',
                                    'ditolak' => 'status-ditolak',
                                    default => 'status-menunggu_validasi',
                                };
                            @endphp
                            <span class="status-badge {{ $badgeClass }}" style="font-size:11.5px;">{{ $contact->statusLabel() }}</span>
                        </td>
                        <td>{{ $contact->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn-edit">
                                <i class="bi bi-eye" aria-hidden="true"></i> Lihat
                            </a>
                            <form action="{{ route('admin.contacts.delete', $contact->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn-delete" onclick="return confirm('Hapus pesan ini?')">
                                    <i class="bi bi-trash" aria-hidden="true"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($contacts->hasPages())
            <div class="mt-3">{{ $contacts->links() }}</div>
        @endif
    @endif
</div>
