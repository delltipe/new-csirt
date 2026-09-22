<div class="mb-4">
    <div class="section-actions">
        <h4 class="section-title-small">Kelola Peraturan</h4>
        <a href="#" class="btn-add" data-bs-toggle="modal" data-bs-target="#addLawModal">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Peraturan
        </a>
    </div>
    @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if ($laws->isEmpty())
        <div class="empty-state">
            <p>Tidak ada data yang tersedia pada tabel ini</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Unduhan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laws as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->date ? $item->date->format('Y-m-d') : '' }}</td>
                        <td>{{ $item->downloadAmount ?? 0 }}</td>
                        <td>
                            <a href="{{ route('admin.law.edit', $item->id) }}" class="btn-edit">
                                <i class="bi bi-pencil" aria-hidden="true"></i> Edit
                            </a>
                            <form action="{{ route('admin.law.delete', $item->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus peraturan ini?')">
                                    <i class="bi bi-trash" aria-hidden="true"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($laws->hasPages())
            <div class="mt-3">{{ $laws->links() }}</div>
        @endif
    @endif
</div>

<!-- Add Law Modal -->
<div class="modal fade" id="addLawModal" tabindex="-1" aria-labelledby="addLawModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.law.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addLawModalLabel">Tambah Peraturan & Kebijakan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
              <label for="law-title" class="form-label">Judul</label>
              <input type="text" class="form-control" id="law-title" name="title" required>
            </div>
            <div class="mb-3">
              <label for="law-description" class="form-label">Deskripsi</label>
              <textarea class="form-control" id="law-description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label for="law-link" class="form-label">URL Dokumen</label>
              <input type="text" class="form-control" id="law-link" name="link">
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="law-date" class="form-label">Tanggal</label>
                  <input type="date" class="form-control" id="law-date" name="date" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="law-time" class="form-label">Waktu</label>
                  <input type="time" class="form-control" id="law-time" name="time">
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label for="law-download" class="form-label">Jumlah Unduhan</label>
              <input type="number" class="form-control" id="law-download" name="downloadAmount" value="0">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
