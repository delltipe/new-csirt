<div class="section-actions">
    <h4 class="section-title-small">Manage Guides</h4>
    <a href="#" class="btn-add" data-bs-toggle="modal" data-bs-target="#addGuideModal">
        <span>Add Guide</span>
    </a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>File</th>
            <th style="width: 200px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($guides as $item)
        <tr>
            <td>{{ $item->title }}</td>
            <td>{{ $item->author }}</td>
            <td>
                @if($item->file_path)
                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" style="color: var(--navy);">Download</a>
                @else
                    -
                @endif
            </td>
            <td>
                <a href="{{ route('admin.guide.edit', $item->id) }}" class="btn-edit">Edit</a>
                <form action="{{ route('admin.guide.delete', $item->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn-delete" onclick="return confirm('Delete this guide?')">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="empty-state">
                <p>No guides found</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<!-- Add Guide Modal -->
<div class="modal fade" id="addGuideModal" tabindex="-1" aria-labelledby="addGuideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.guide.store') }}" enctype="multipart/form-data">
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
                    <div class="mb-3">
                        <label for="guide-file" class="form-label">Upload File (PDF)</label>
                        <input type="file" class="form-control" id="guide-file" name="file" accept="application/pdf">
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