<div class="mb-4">
    <h4>Manage Laws & Regulations</h4>
    <a href="#" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addLawModal">Add Law</a>
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Title</th>
          <th>Date</th>
          <th>Downloads</th>
          <th>File</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($laws as $item)
        <tr>
          <td>{{ $item->title }}</td>
          <td>{{ $item->date ? $item->date->format('Y-m-d') : '' }}</td>
          <td>{{ $item->downloadAmount ?? 0 }}</td>
          <td>
            @if($item->file_path)
              <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank">Download</a>
            @else
              -
            @endif
          </td>
          <td>
            <a href="{{ route('admin.law.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>
            <form action="{{ route('admin.law.delete', $item->id) }}" method="POST" style="display:inline-block;">
              @csrf
              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this law?')">Delete</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
</div>

<!-- Add Law Modal -->
<div class="modal fade" id="addLawModal" tabindex="-1" aria-labelledby="addLawModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.law.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addLawModalLabel">Add Law/Regulation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
              <label for="law-title" class="form-label">Title</label>
              <input type="text" class="form-control" id="law-title" name="title" required>
            </div>
            <div class="mb-3">
              <label for="law-description" class="form-label">Description</label>
              <textarea class="form-control" id="law-description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label for="law-link" class="form-label">Document URL</label>
              <input type="text" class="form-control" id="law-link" name="link">
            </div>
            <div class="mb-3">
              <label for="law-file" class="form-label">Upload File (PDF)</label>
              <input type="file" class="form-control" id="law-file" name="file" accept="application/pdf">
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="law-date" class="form-label">Date</label>
                  <input type="date" class="form-control" id="law-date" name="date" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="law-time" class="form-label">Time</label>
                  <input type="time" class="form-control" id="law-time" name="time">
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label for="law-download" class="form-label">Download Count</label>
              <input type="number" class="form-control" id="law-download" name="downloadAmount" value="0">
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
