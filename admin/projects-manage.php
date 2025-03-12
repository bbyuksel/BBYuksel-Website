<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!is_logged_in()) {
    redirect('index.php');
}

$success = '';
$error = '';

// Handle Delete
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    if ($stmt->execute([$_POST['id']])) {
        $success = 'Project deleted successfully.';
    } else {
        $error = 'Failed to delete project.';
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    $title = clean_input($_POST['title']);
    $description = clean_input($_POST['description']);
    $start_date = clean_input($_POST['start_date']);
    $end_date = clean_input($_POST['end_date']);
    $funding = clean_input($_POST['funding']);
    
    if (empty($title)) {
        $error = 'Please fill in all required fields.';
    } else {
        if (isset($_POST['id'])) {
            // Update existing project
            $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, start_date = ?, end_date = ?, funding = ? WHERE id = ?");
            $result = $stmt->execute([$title, $description, $start_date, $end_date, $funding, $_POST['id']]);
        } else {
            // Add new project
            $stmt = $pdo->prepare("INSERT INTO projects (title, description, start_date, end_date, funding) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([$title, $description, $start_date, $end_date, $funding]);
        }
        
        if ($result) {
            $success = 'Project saved successfully.';
        } else {
            $error = 'Failed to save project.';
        }
    }
}

// Get all projects
$stmt = $pdo->query("SELECT * FROM projects ORDER BY start_date DESC");
$projects = $stmt->fetchAll();

require_once 'includes/admin-header.php';
?>

<div class="container mt-4">
    <h1>Manage Projects</h1>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Add New Form -->
    <div class="card mb-4">
        <div class="card-header">
            Add New Project
        </div>
        <div class="card-body">
            <form method="post">
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="end_date" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Funding</label>
                    <input type="text" name="funding" class="form-control">
                </div>
                
                <button type="submit" name="save" class="btn btn-primary">Add Project</button>
            </form>
        </div>
    </div>

    <!-- Projects List -->
    <div class="card">
        <div class="card-header">
            Projects
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Funding</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($project['title']); ?></td>
                                <td><?php echo htmlspecialchars($project['description']); ?></td>
                                <td><?php echo htmlspecialchars($project['start_date']); ?></td>
                                <td><?php echo htmlspecialchars($project['end_date']); ?></td>
                                <td><?php echo htmlspecialchars($project['funding']); ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary edit-project" 
                                            data-id="<?php echo $project['id']; ?>"
                                            data-title="<?php echo htmlspecialchars($project['title']); ?>"
                                            data-description="<?php echo htmlspecialchars($project['description']); ?>"
                                            data-start-date="<?php echo htmlspecialchars($project['start_date']); ?>"
                                            data-end-date="<?php echo htmlspecialchars($project['end_date']); ?>"
                                            data-funding="<?php echo htmlspecialchars($project['funding']); ?>">
                                        Edit
                                    </button>
                                    <form method="post" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                                        <button type="submit" name="delete" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Project</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="title" id="edit_title" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" id="edit_start_date" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" id="edit_end_date" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Funding</label>
                        <input type="text" name="funding" id="edit_funding" class="form-control">
                    </div>
                    
                    <button type="submit" name="save" class="btn btn-primary">Update Project</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>

<script>
$(document).ready(function() {
    $('.edit-project').click(function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        var description = $(this).data('description');
        var startDate = $(this).data('start-date');
        var endDate = $(this).data('end-date');
        var funding = $(this).data('funding');
        
        $('#edit_id').val(id);
        $('#edit_title').val(title);
        $('#edit_description').val(description);
        $('#edit_start_date').val(startDate);
        $('#edit_end_date').val(endDate);
        $('#edit_funding').val(funding);
        
        $('#editModal').modal('show');
    });
});
</script> 