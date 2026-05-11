<div class="mb-4">
    <h4>Manage Guides</h4>
    <a href="#" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addGuideModal">Add Guide</a>
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Title</th>
          <th>Author</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($guides as $item)
        <tr>
          <td>{{ $item->title }}</td>
          <td>{{ $item->author }}</td>
          <td>
            <a href="{{ route('admin.guide.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>
            <form action="{{ route('admin.guide.delete', $item->id) }}" method="POST" style="display:inline-block;">
              @csrf
              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this guide?')">Delete</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
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
