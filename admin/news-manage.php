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
    $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
    if ($stmt->execute([$_POST['id']])) {
        $success = 'News item deleted successfully.';
    } else {
        $error = 'Failed to delete news item.';
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    $date = $_POST['date'];
    $content = clean_input($_POST['content']);
    $link = clean_input($_POST['link']);
    
    if (empty($date) || empty($content)) {
        $error = 'Please fill in all required fields.';
    } else {
        if (isset($_POST['id'])) {
            // Update existing news
            $stmt = $pdo->prepare("UPDATE news SET date = ?, content = ?, link = ? WHERE id = ?");
            $result = $stmt->execute([$date, $content, $link, $_POST['id']]);
        } else {
            // Add new news
            $stmt = $pdo->prepare("INSERT INTO news (date, content, link) VALUES (?, ?, ?)");
            $result = $stmt->execute([$date, $content, $link]);
        }
        
        if ($result) {
            $success = 'News item saved successfully.';
        } else {
            $error = 'Failed to save news item.';
        }
    }
}

// Get all news items
$stmt = $pdo->query("SELECT * FROM news ORDER BY date DESC");
$news = $stmt->fetchAll();

require_once 'includes/admin-header.php';
?>

<div class="container mt-4">
    <h1>Manage News</h1>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Add New Form -->
    <div class="card mb-4">
        <div class="card-header">
            Add New News Item
        </div>
        <div class="card-body">
            <form method="post">
                <div class="form-group">
                    <label>Date *</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Content *</label>
                    <textarea name="content" class="form-control" rows="3" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Link (optional)</label>
                    <input type="url" name="link" class="form-control">
                </div>
                
                <button type="submit" name="save" class="btn btn-primary">Add News</button>
            </form>
        </div>
    </div>

    <!-- News List -->
    <div class="card">
        <div class="card-header">
            News Items
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Content</th>
                            <th>Link</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($news as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['date']); ?></td>
                                <td><?php echo htmlspecialchars($item['content']); ?></td>
                                <td>
                                    <?php if ($item['link']): ?>
                                        <a href="<?php echo htmlspecialchars($item['link']); ?>" target="_blank">View</a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary edit-news" 
                                            data-id="<?php echo $item['id']; ?>"
                                            data-date="<?php echo $item['date']; ?>"
                                            data-content="<?php echo htmlspecialchars($item['content']); ?>"
                                            data-link="<?php echo htmlspecialchars($item['link']); ?>">
                                        Edit
                                    </button>
                                    <form method="post" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
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
                <h5 class="modal-title">Edit News Item</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label>Date *</label>
                        <input type="date" name="date" id="edit_date" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Content *</label>
                        <textarea name="content" id="edit_content" class="form-control" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Link (optional)</label>
                        <input type="url" name="link" id="edit_link" class="form-control">
                    </div>
                    
                    <button type="submit" name="save" class="btn btn-primary">Update News</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>

<script>
$(document).ready(function() {
    $('.edit-news').click(function() {
        var id = $(this).data('id');
        var date = $(this).data('date');
        var content = $(this).data('content');
        var link = $(this).data('link');
        
        $('#edit_id').val(id);
        $('#edit_date').val(date);
        $('#edit_content').val(content);
        $('#edit_link').val(link);
        
        $('#editModal').modal('show');
    });
});
</script> 