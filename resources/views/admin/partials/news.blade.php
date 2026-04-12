<div class="mb-4">
    <h4>Manage News</h4>
    <a href="#" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addNewsModal">Add News</a>
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
        @foreach($news as $item)
        <tr>
          <td>{{ $item->title }}</td>
          <td>{{ $item->date ? $item->date->format('Y-m-d') : '' }}</td>
          <td>
            <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>
            <form action="{{ route('admin.news.delete', $item->id) }}" method="POST" style="display:inline-block;">
              @csrf
              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this news?')">Delete</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
</div>

<!-- Add News Modal -->
<div class="modal fade" id="addNewsModal" tabindex="-1" aria-labelledby="addNewsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.news.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addNewsModalLabel">Add News</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
              <label for="news-title" class="form-label">Title</label>
              <input type="text" class="form-control" id="news-title" name="title" required>
            </div>
            <div class="mb-3">
              <label for="news-description" class="form-label">Description</label>
              <textarea class="form-control" id="news-description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label for="news-thumbnail" class="form-label">Thumbnail URL</label>
              <input type="text" class="form-control" id="news-thumbnail" name="thumbnail">
            </div>
            <div class="mb-3">
              <label for="news-source" class="form-label">Source</label>
              <input type="text" class="form-control" id="news-source" name="source">
            </div>
            <div class="mb-3">
              <label for="news-date" class="form-label">Date</label>
              <input type="date" class="form-control" id="news-date" name="date" required>
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
