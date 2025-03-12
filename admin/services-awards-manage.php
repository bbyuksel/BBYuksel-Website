<?php
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
    $stmt = $pdo->prepare("DELETE FROM services_awards WHERE id = ?");
    if ($stmt->execute([$_POST['id']])) {
        $success = 'Item deleted successfully.';
    } else {
        $error = 'Failed to delete item.';
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    $title = clean_input($_POST['title']);
    $description = clean_input($_POST['description']);
    $year = clean_input($_POST['year']);
    $type = clean_input($_POST['type']);
    
    if (empty($title) || empty($type)) {
        $error = 'Please fill in all required fields.';
    } else {
        if (isset($_POST['id'])) {
            // Update existing item
            $stmt = $pdo->prepare("UPDATE services_awards SET title = ?, description = ?, year = ?, type = ? WHERE id = ?");
            $result = $stmt->execute([$title, $description, $year, $type, $_POST['id']]);
        } else {
            // Add new item
            $stmt = $pdo->prepare("INSERT INTO services_awards (title, description, year, type) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([$title, $description, $year, $type]);
        }
        
        if ($result) {
            $success = 'Item saved successfully.';
        } else {
            $error = 'Failed to save item.';
        }
    }
}

// Get all items
$stmt = $pdo->query("SELECT * FROM services_awards ORDER BY type, year DESC");
$items = $stmt->fetchAll();

require_once 'includes/admin-header.php';
?>

<div class="container mt-4">
    <h1>Manage Services & Awards</h1>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Add New Form -->
    <div class="card mb-4">
        <div class="card-header">
            Add New Item
        </div>
        <div class="card-body">
            <form method="post">
                <div class="form-group">
                    <label>Type *</label>
                    <select name="type" class="form-control" required>
                        <option value="service">Service</option>
                        <option value="award">Award</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Year</label>
                    <input type="number" name="year" class="form-control" min="1900" max="2100">
                </div>
                
                <button type="submit" name="save" class="btn btn-primary">Add Item</button>
            </form>
        </div>
    </div>

    <!-- Services List -->
    <div class="card mb-4">
        <div class="card-header">
            Services
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Year</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <?php if ($item['type'] == 'service'): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                                    <td><?php echo htmlspecialchars($item['year']); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary edit-item" 
                                                data-id="<?php echo $item['id']; ?>"
                                                data-title="<?php echo htmlspecialchars($item['title']); ?>"
                                                data-description="<?php echo htmlspecialchars($item['description']); ?>"
                                                data-year="<?php echo htmlspecialchars($item['year']); ?>"
                                                data-type="<?php echo htmlspecialchars($item['type']); ?>">
                                            Edit
                                        </button>
                                        <form method="post" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                            <button type="submit" name="delete" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Awards List -->
    <div class="card">
        <div class="card-header">
            Awards
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Year</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <?php if ($item['type'] == 'award'): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                                    <td><?php echo htmlspecialchars($item['year']); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary edit-item" 
                                                data-id="<?php echo $item['id']; ?>"
                                                data-title="<?php echo htmlspecialchars($item['title']); ?>"
                                                data-description="<?php echo htmlspecialchars($item['description']); ?>"
                                                data-year="<?php echo htmlspecialchars($item['year']); ?>"
                                                data-type="<?php echo htmlspecialchars($item['type']); ?>">
                                            Edit
                                        </button>
                                        <form method="post" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                            <button type="submit" name="delete" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endif; ?>
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
                <h5 class="modal-title">Edit Item</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label>Type *</label>
                        <select name="type" id="edit_type" class="form-control" required>
                            <option value="service">Service</option>
                            <option value="award">Award</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="title" id="edit_title" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Year</label>
                        <input type="number" name="year" id="edit_year" class="form-control" min="1900" max="2100">
                    </div>
                    
                    <button type="submit" name="save" class="btn btn-primary">Update Item</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>

<script>
$(document).ready(function() {
    $('.edit-item').click(function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        var description = $(this).data('description');
        var year = $(this).data('year');
        var type = $(this).data('type');
        
        $('#edit_id').val(id);
        $('#edit_title').val(title);
        $('#edit_description').val(description);
        $('#edit_year').val(year);
        $('#edit_type').val(type);
        
        $('#editModal').modal('show');
    });
});
</script> 