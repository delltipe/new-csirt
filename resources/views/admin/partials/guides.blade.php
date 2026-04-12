<div class="mb-4">
    <h4>Manage Guides</h4>
    <a href="#" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addGuideModal">Add Guide</a>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop guide items here -->
        </tbody>
    </table>
</div>

<!-- Add Guide Modal -->
<div class="modal fade" id="addGuideModal" tabindex="-1" aria-labelledby="addGuideModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="#">
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
                <label for="guide-date" class="form-label">Date</label>
                <input type="date" class="form-control" id="guide-date" name="date" required>
            </div>
            <div class="mb-3">
                <label for="guide-content" class="form-label">Content</label>
                <textarea class="form-control" id="guide-content" name="content" rows="4" required></textarea>
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
