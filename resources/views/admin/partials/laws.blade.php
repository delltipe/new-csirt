<div class="mb-4">
    <h4>Manage Laws</h4>
    <a href="#" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addLawModal">Add Law</a>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop law items here -->
        </tbody>
    </table>
</div>

<!-- Add Law Modal -->
<div class="modal fade" id="addLawModal" tabindex="-1" aria-labelledby="addLawModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="#">
        <div class="modal-header">
          <h5 class="modal-title" id="addLawModalLabel">Add Law</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="law-title" class="form-label">Title</label>
                <input type="text" class="form-control" id="law-title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="law-date" class="form-label">Date</label>
                <input type="date" class="form-control" id="law-date" name="date" required>
            </div>
            <div class="mb-3">
                <label for="law-content" class="form-label">Content</label>
                <textarea class="form-control" id="law-content" name="content" rows="4" required></textarea>
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
