<div class="mb-4">
    <div class="section-actions">
        <h4 class="section-title-small">Kelola Panduan</h4>
        <a href="#" class="btn-add" data-bs-toggle="modal" data-bs-target="#addGuideModal">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Panduan
        </a>
    </div>
    @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if ($guides->isEmpty())
        <div class="empty-state">
            <p>Tidak ada data yang tersedia pada tabel ini</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($guides as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->author }}</td>
                        <td>
                            <a href="{{ route('admin.guide.edit', $item->id) }}" class="btn-edit">
                                <i class="bi bi-pencil" aria-hidden="true"></i> Edit
                            </a>
                            <form action="{{ route('admin.guide.delete', $item->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn-delete" onclick="return confirm('Delete this guide?')">
                                    <i class="bi bi-trash" aria-hidden="true"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($guides->hasPages())
            <div class="mt-3">{{ $guides->links() }}</div>
        @endif
    @endif
</div>

<!-- Add Guide Modal -->
<div class="modal fade" id="addGuideModal" tabindex="-1" aria-labelledby="addGuideModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.guide.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addGuideModalLabel">Add Guide</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
              <label for="guide-title" class="form-label">Title</label>
              <input type="text" class="form-control" id="guide-title" name="title" required>
            </div>
            <div class="mb-3">
              <label for="guide-author" class="form-label">Author</label>
              <input type="text" class="form-control" id="guide-author" name="author" required>
            </div>
            <div class="mb-3">
              <label for="guide-link" class="form-label">Link/URL</label>
              <input type="text" class="form-control" id="guide-link" name="link" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
