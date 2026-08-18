<div class="mb-4">
    <div class="section-actions">
        <h4 class="section-title-small">Kelola Infografis</h4>
        <a href="#" class="btn-add" data-bs-toggle="modal" data-bs-target="#addInfographicModal">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Infografis
        </a>
    </div>
    @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if ($infographics->isEmpty())
        <div class="empty-state">
            <p>Tidak ada data yang tersedia pada tabel ini</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Thumbnail</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($infographics as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td><a href="{{ $item->thumbnail }}" target="_blank">View Image</a></td>
                        <td>
                            <a href="{{ route('admin.infographic.edit', $item->id) }}" class="btn-edit">
                                <i class="bi bi-pencil" aria-hidden="true"></i> Edit
                            </a>
                            <form action="{{ route('admin.infographic.delete', $item->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn-delete" onclick="return confirm('Delete this infographic?')">
                                    <i class="bi bi-trash" aria-hidden="true"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($infographics->hasPages())
            <div class="mt-3">{{ $infographics->links() }}</div>
        @endif
    @endif
</div>

<!-- Add Infographic Modal -->
<div class="modal fade" id="addInfographicModal" tabindex="-1" aria-labelledby="addInfographicModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.infographic.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addInfographicModalLabel">Add Infographic</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
              <label for="infographic-title" class="form-label">Title</label>
              <input type="text" class="form-control" id="infographic-title" name="title" required>
            </div>
            <div class="mb-3">
              <label for="infographic-thumbnail" class="form-label">Thumbnail URL</label>
              <input type="text" class="form-control" id="infographic-thumbnail" name="thumbnail" required>
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
