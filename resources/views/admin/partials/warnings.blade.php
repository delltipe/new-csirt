<div class="section-actions">
    <h4 class="section-title-small">Manage Warnings</h4>
    <a href="#" class="btn-add" data-bs-toggle="modal" data-bs-target="#addWarningModal">
        <span>Add Warning</span>
    </a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Date</th>
            <th>File</th>
            <th style="width: 200px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($warnings as $item)
        <tr>
            <td>{{ $item->title }}</td>
            <td>{{ $item->date ? $item->date->format('Y-m-d H:i') : '' }}</td>
            <td>
                @if($item->file_path)
                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" style="color: var(--navy);">View</a>
                @else
                    -
                @endif
            </td>
            <td>
                <a href="{{ route('admin.warning.edit', $item->id) }}" class="btn-edit">Edit</a>
                <form action="{{ route('admin.warning.delete', $item->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn-delete" onclick="return confirm('Delete this warning?')">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="empty-state">
                <p>No warnings found</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<!-- Add Warning Modal -->
<div class="modal fade" id="addWarningModal" tabindex="-1" aria-labelledby="addWarningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.warning.store') }}" enctype="multipart/form-data">
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
                    <div class="mb-3">
                        <label for="warning-file" class="form-label">Upload Image (JPG/PNG)</label>
                        <input type="file" class="form-control" id="warning-file" name="file" accept="image/*">
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