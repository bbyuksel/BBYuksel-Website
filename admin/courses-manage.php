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
    $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
    if ($stmt->execute([$_POST['id']])) {
        $success = 'Course deleted successfully.';
    } else {
        $error = 'Failed to delete course.';
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    $code = clean_input($_POST['code']);
    $title = clean_input($_POST['title']);
    $description = clean_input($_POST['description']);
    $semester = clean_input($_POST['semester']);
    $year = clean_input($_POST['year']);
    $level = clean_input($_POST['level']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Handle syllabus file upload
    $syllabus_file = '';
    if (!empty($_FILES['syllabus_file']['name'])) {
        $target_dir = "../uploads/syllabus/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES["syllabus_file"]["name"], PATHINFO_EXTENSION));
        $syllabus_file = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $syllabus_file;
        
        if (move_uploaded_file($_FILES["syllabus_file"]["tmp_name"], $target_file)) {
            // File uploaded successfully
        } else {
            $error = 'Failed to upload syllabus file.';
        }
    }
    
    if (empty($code) || empty($title)) {
        $error = 'Please fill in all required fields.';
    } else {
        if (isset($_POST['id'])) {
            // Update existing course
            $sql = "UPDATE courses SET code = ?, title = ?, description = ?, semester = ?, 
                    year = ?, level = ?, is_active = ?";
            $params = [$code, $title, $description, $semester, $year, $level, $is_active];
            
            if ($syllabus_file) {
                $sql .= ", syllabus_file = ?";
                $params[] = $syllabus_file;
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $_POST['id'];
            
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute($params);
        } else {
            // Add new course
            $stmt = $pdo->prepare("INSERT INTO courses (code, title, description, semester, year, 
                                level, is_active, syllabus_file, date_added) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $result = $stmt->execute([$code, $title, $description, $semester, $year, 
                                    $level, $is_active, $syllabus_file]);
        }
        
        if ($result) {
            $success = 'Course saved successfully.';
        } else {
            $error = 'Failed to save course.';
        }
    }
}

// Get all courses
$stmt = $pdo->query("SELECT * FROM courses ORDER BY year DESC, semester DESC");
$courses = $stmt->fetchAll();

require_once 'includes/admin-header.php';
?>

<div class="container mt-4">
    <h1>Manage Courses</h1>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Add New Form -->
    <div class="card mb-4">
        <div class="card-header">
            Add New Course
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Course Code *</label>
                    <input type="text" name="code" class="form-control" required>
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
                    <label>Semester</label>
                    <select name="semester" class="form-control">
                        <option value="Fall">Fall</option>
                        <option value="Spring">Spring</option>
                        <option value="Summer">Summer</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Year</label>
                    <input type="number" name="year" class="form-control" min="1900" max="2100">
                </div>
                
                <div class="form-group">
                    <label>Level</label>
                    <select name="level" class="form-control">
                        <option value="Undergraduate">Undergraduate</option>
                        <option value="Graduate">Graduate</option>
                        <option value="PhD">PhD</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="isActive" name="is_active" checked>
                        <label class="custom-control-label" for="isActive">Active Course</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Syllabus File</label>
                    <input type="file" name="syllabus_file" class="form-control-file" accept=".pdf,.doc,.docx">
                </div>
                
                <button type="submit" name="save" class="btn btn-primary">Add Course</button>
            </form>
        </div>
    </div>

    <!-- Courses List -->
    <div class="card">
        <div class="card-header">
            Courses
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Title</th>
                            <th>Semester</th>
                            <th>Year</th>
                            <th>Level</th>
                            <th>Status</th>
                            <th>Syllabus</th>
                            <th>Added Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($course['code']); ?></td>
                                <td><?php echo htmlspecialchars($course['title']); ?></td>
                                <td><?php echo htmlspecialchars($course['semester']); ?></td>
                                <td><?php echo htmlspecialchars($course['year']); ?></td>
                                <td><?php echo htmlspecialchars($course['level']); ?></td>
                                <td><?php echo $course['is_active'] ? 'Active' : 'Inactive'; ?></td>
                                <td>
                                    <?php if ($course['syllabus_file']): ?>
                                        <a href="../uploads/syllabus/<?php echo htmlspecialchars($course['syllabus_file']); ?>" target="_blank">View</a>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('Y-m-d', strtotime($course['date_added'])); ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary edit-course" 
                                            data-id="<?php echo $course['id']; ?>"
                                            data-code="<?php echo htmlspecialchars($course['code']); ?>"
                                            data-title="<?php echo htmlspecialchars($course['title']); ?>"
                                            data-description="<?php echo htmlspecialchars($course['description']); ?>"
                                            data-semester="<?php echo htmlspecialchars($course['semester']); ?>"
                                            data-year="<?php echo htmlspecialchars($course['year']); ?>"
                                            data-level="<?php echo htmlspecialchars($course['level']); ?>"
                                            data-is-active="<?php echo $course['is_active']; ?>">
                                        Edit
                                    </button>
                                    <form method="post" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
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
                <h5 class="modal-title">Edit Course</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label>Course Code *</label>
                        <input type="text" name="code" id="edit_code" class="form-control" required>
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
                        <label>Semester</label>
                        <select name="semester" id="edit_semester" class="form-control">
                            <option value="Fall">Fall</option>
                            <option value="Spring">Spring</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Year</label>
                        <input type="number" name="year" id="edit_year" class="form-control" min="1900" max="2100">
                    </div>
                    
                    <div class="form-group">
                        <label>Level</label>
                        <select name="level" id="edit_level" class="form-control">
                            <option value="Undergraduate">Undergraduate</option>
                            <option value="Graduate">Graduate</option>
                            <option value="PhD">PhD</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="edit_is_active" name="is_active">
                            <label class="custom-control-label" for="edit_is_active">Active Course</label>
                        </div>
                    </div>
                    
                    <button type="submit" name="save" class="btn btn-primary">Update Course</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>

<script>
$(document).ready(function() {
    $('.edit-course').click(function() {
        var id = $(this).data('id');
        var code = $(this).data('code');
        var title = $(this).data('title');
        var description = $(this).data('description');
        var semester = $(this).data('semester');
        var year = $(this).data('year');
        var level = $(this).data('level');
        var is_active = $(this).data('is-active');
        
        $('#edit_id').val(id);
        $('#edit_code').val(code);
        $('#edit_title').val(title);
        $('#edit_description').val(description);
        $('#edit_semester').val(semester);
        $('#edit_year').val(year);
        $('#edit_level').val(level);
        $('#edit_is_active').prop('checked', is_active === '1');
        
        $('#editModal').modal('show');
    });
});
</script> 