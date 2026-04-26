<div class="section-actions">
    <h4 class="section-title-small">Manage News</h4>
    <a href="#" class="btn-add" data-bs-toggle="modal" data-bs-target="#addNewsModal">
        <span>Add News</span>
    </a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Date</th>
            <th style="width: 200px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($news as $item)
        <tr>
            <td>{{ $item->title }}</td>
            <td>{{ $item->date ? $item->date->format('Y-m-d') : '' }}</td>
            <td>
                <a href="{{ route('admin.news.edit', $item->id) }}" class="btn-edit">Edit</a>
                <form action="{{ route('admin.news.delete', $item->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn-delete" onclick="return confirm('Delete this news?')">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="empty-state">
                <p>No news items found</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

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
                        <label for="news-date" class="form-label">Date & Time</label>
                        <input type="datetime-local" class="form-control" id="news-date" name="date" required>
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