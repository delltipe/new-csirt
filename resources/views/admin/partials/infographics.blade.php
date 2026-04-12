<div class="mb-4">
    <h4>Manage Infographics</h4>
    <a href="#" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addInfographicModal">Add Infographic</a>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop infographic items here -->
        </tbody>
    </table>
</div>

<!-- Add Infographic Modal -->
<div class="modal fade" id="addInfographicModal" tabindex="-1" aria-labelledby="addInfographicModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="#">
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
                <label for="infographic-date" class="form-label">Date</label>
                <input type="date" class="form-control" id="infographic-date" name="date" required>
            </div>
            <div class="mb-3">
                <label for="infographic-content" class="form-label">Content</label>
                <textarea class="form-control" id="infographic-content" name="content" rows="4" required></textarea>
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
