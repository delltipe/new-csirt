<div class="mb-4">
    <div class="section-actions">
        <h4 class="section-title-small">Kelola Event</h4>
        <a href="#" class="btn-add" data-bs-toggle="modal" data-bs-target="#addEventModal">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Event
        </a>
    </div>
    @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if ($events->isEmpty())
        <div class="empty-state">
            <p>Tidak ada data yang tersedia pada tabel ini</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->event_date ? $item->event_date->format('Y-m-d H:i') : '' }}</td>
                        <td>{{ $item->location }}</td>
                        <td>
                            <a href="{{ route('admin.event.edit', $item->id) }}" class="btn-edit">
                                <i class="bi bi-pencil" aria-hidden="true"></i> Edit
                            </a>
                            <form action="{{ route('admin.event.delete', $item->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn-delete" onclick="return confirm('Delete this event?')">
                                    <i class="bi bi-trash" aria-hidden="true"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($events->hasPages())
            <div class="mt-3">{{ $events->links() }}</div>
        @endif
    @endif
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.event.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addEventModalLabel">Add Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
              <label for="event-title" class="form-label">Title</label>
              <input type="text" class="form-control" id="event-title" name="title" required>
            </div>
            <div class="mb-3">
              <label for="event-description" class="form-label">Description</label>
              <textarea class="form-control" id="event-description" name="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
              <label for="event-thumbnail" class="form-label">Thumbnail URL</label>
              <input type="text" class="form-control" id="event-thumbnail" name="thumbnail">
            </div>
            <div class="mb-3">
              <label for="event-date" class="form-label">Event Date & Time</label>
              <input type="datetime-local" class="form-control" id="event-date" name="event_date" required>
            </div>
            <div class="mb-3">
              <label for="event-location" class="form-label">Location</label>
              <input type="text" class="form-control" id="event-location" name="location">
            </div>
            <div class="mb-3">
              <label for="event-type" class="form-label">Event Type</label>
              <input type="text" class="form-control" id="event-type" name="event_type" placeholder="e.g., Webinar, Sosialisasi">
            </div>
            <div class="mb-3">
              <label for="event-registration" class="form-label">Registration URL</label>
              <input type="text" class="form-control" id="event-registration" name="registration_url">
            </div>
            <div class="mb-3">
              <label for="event-capacity" class="form-label">Capacity</label>
              <input type="number" class="form-control" id="event-capacity" name="capacity">
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
