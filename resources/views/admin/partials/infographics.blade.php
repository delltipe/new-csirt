<div class="section-actions">
    <h4 class="section-title-small">Manage Infographics</h4>
    <a href="#" class="btn-add" data-bs-toggle="modal" data-bs-target="#addInfographicModal">
        <span>Add Infographic</span>
    </a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Thumbnail</th>
            <th style="width: 200px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($infographics as $item)
        <tr>
            <td>{{ $item->title }}</td>
            <td>
                <a href="{{ $item->thumbnail }}" target="_blank" style="color: var(--navy);">View Image</a>
            </td>
            <td>
                <a href="{{ route('admin.infographic.edit', $item->id) }}" class="btn-edit">Edit</a>
                <form action="{{ route('admin.infographic.delete', $item->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn-delete" onclick="return confirm('Delete this infographic?')">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="empty-state">
                <p>No infographics found</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

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