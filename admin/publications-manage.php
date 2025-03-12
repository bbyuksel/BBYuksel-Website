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
    $stmt = $pdo->prepare("DELETE FROM publications WHERE id = ?");
    if ($stmt->execute([$_POST['id']])) {
        $success = 'Publication deleted successfully.';
    } else {
        $error = 'Failed to delete publication.';
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    $title = clean_input($_POST['title']);
    $authors = clean_input($_POST['authors']);
    $venue = clean_input($_POST['venue']);
    $year = clean_input($_POST['year']);
    $link = clean_input($_POST['link']);
    
    if (empty($title) || empty($authors) || empty($venue)) {
        $error = 'Please fill in all required fields.';
    } else {
        if (isset($_POST['id'])) {
            // Update existing publication
            $stmt = $pdo->prepare("UPDATE publications SET title = ?, authors = ?, venue = ?, year = ?, link = ? WHERE id = ?");
            $result = $stmt->execute([$title, $authors, $venue, $year, $link, $_POST['id']]);
        } else {
            // Add new publication
            $stmt = $pdo->prepare("INSERT INTO publications (title, authors, venue, year, link) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([$title, $authors, $venue, $year, $link]);
        }
        
        if ($result) {
            $success = 'Publication saved successfully.';
        } else {
            $error = 'Failed to save publication.';
        }
    }
}

// Get all publications
$stmt = $pdo->query("SELECT * FROM publications ORDER BY year DESC");
$publications = $stmt->fetchAll();

require_once 'includes/admin-header.php';
?>

<div class="container mt-4">
    <h1>Manage Publications</h1>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Add New Form -->
    <div class="card mb-4">
        <div class="card-header">
            Add New Publication
        </div>
        <div class="card-body">
            <form method="post">
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Authors *</label>
                    <input type="text" name="authors" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Venue *</label>
                    <input type="text" name="venue" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Year</label>
                    <input type="number" name="year" class="form-control" min="1900" max="2100">
                </div>
                
                <div class="form-group">
                    <label>Link</label>
                    <input type="url" name="link" class="form-control">
                </div>
                
                <button type="submit" name="save" class="btn btn-primary">Add Publication</button>
            </form>
        </div>
    </div>

    <!-- Publications List -->
    <div class="card">
        <div class="card-header">
            Publications
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Authors</th>
                            <th>Venue</th>
                            <th>Year</th>
                            <th>Link</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($publications as $pub): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pub['title']); ?></td>
                                <td><?php echo htmlspecialchars($pub['authors']); ?></td>
                                <td><?php echo htmlspecialchars($pub['venue']); ?></td>
                                <td><?php echo htmlspecialchars($pub['year']); ?></td>
                                <td>
                                    <?php if ($pub['link']): ?>
                                        <a href="<?php echo htmlspecialchars($pub['link']); ?>" target="_blank">View</a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary edit-pub" 
                                            data-id="<?php echo $pub['id']; ?>"
                                            data-title="<?php echo htmlspecialchars($pub['title']); ?>"
                                            data-authors="<?php echo htmlspecialchars($pub['authors']); ?>"
                                            data-venue="<?php echo htmlspecialchars($pub['venue']); ?>"
                                            data-year="<?php echo htmlspecialchars($pub['year']); ?>"
                                            data-link="<?php echo htmlspecialchars($pub['link']); ?>">
                                        Edit
                                    </button>
                                    <form method="post" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="id" value="<?php echo $pub['id']; ?>">
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
                <h5 class="modal-title">Edit Publication</h5>
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
                        <label>Authors *</label>
                        <input type="text" name="authors" id="edit_authors" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Venue *</label>
                        <input type="text" name="venue" id="edit_venue" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Year</label>
                        <input type="number" name="year" id="edit_year" class="form-control" min="1900" max="2100">
                    </div>
                    
                    <div class="form-group">
                        <label>Link</label>
                        <input type="url" name="link" id="edit_link" class="form-control">
                    </div>
                    
                    <button type="submit" name="save" class="btn btn-primary">Update Publication</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>

<script>
$(document).ready(function() {
    $('.edit-pub').click(function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        var authors = $(this).data('authors');
        var venue = $(this).data('venue');
        var year = $(this).data('year');
        var link = $(this).data('link');
        
        $('#edit_id').val(id);
        $('#edit_title').val(title);
        $('#edit_authors').val(authors);
        $('#edit_venue').val(venue);
        $('#edit_year').val(year);
        $('#edit_link').val(link);
        
        $('#editModal').modal('show');
    });
});
</script> 