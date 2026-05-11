<div class="mb-4">
    <h4>Manage Infographics</h4>
    <a href="#" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addInfographicModal">Add Infographic</a>
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Title</th>
          <th>Thumbnail</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($infographics as $item)
        <tr>
          <td>{{ $item->title }}</td>
          <td><a href="{{ $item->thumbnail }}" target="_blank">View Image</a></td>
          <td>
            <a href="{{ route('admin.infographic.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>
            <form action="{{ route('admin.infographic.delete', $item->id) }}" method="POST" style="display:inline-block;">
              @csrf
              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this infographic?')">Delete</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
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
