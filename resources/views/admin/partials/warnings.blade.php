<div class="mb-4">
    <h4>Manage Warnings</h4>
    <a href="#" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addWarningModal">Add Warning</a>
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Title</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($warnings as $item)
        <tr>
          <td>{{ $item->title }}</td>
          <td>{{ $item->date ? $item->date->format('Y-m-d H:i') : '' }}</td>
          <td>
            <a href="{{ route('admin.warning.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>
            <form action="{{ route('admin.warning.delete', $item->id) }}" method="POST" style="display:inline-block;">
              @csrf
              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this warning?')">Delete</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
</div>

<!-- Add Warning Modal -->
<div class="modal fade" id="addWarningModal" tabindex="-1" aria-labelledby="addWarningModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.warning.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addWarningModalLabel">Add Warning</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
              <label for="warning-title" class="form-label">Title</label>
              <input type="text" class="form-control" id="warning-title" name="title" required>
            </div>
            <div class="mb-3">
              <label for="warning-description" class="form-label">Description</label>
              <textarea class="form-control" id="warning-description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label for="warning-thumbnail" class="form-label">Thumbnail URL</label>
              <input type="text" class="form-control" id="warning-thumbnail" name="thumbnail">
            </div>
            <div class="mb-3">
              <label for="warning-source" class="form-label">Source</label>
              <input type="text" class="form-control" id="warning-source" name="source">
            </div>
            <div class="mb-3">
              <label for="warning-date" class="form-label">Date & Time</label>
              <input type="datetime-local" class="form-control" id="warning-date" name="date" required>
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
</div>
