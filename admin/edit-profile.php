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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean_input($_POST['name']);
    $title = clean_input($_POST['title']);
    $email = clean_input($_POST['email']);
    $about = clean_input($_POST['about']);
    $cv_link = clean_input($_POST['cv_link']);
    
    // Handle image upload
    $profile_image = '';
    if (!empty($_FILES['profile_image']['name'])) {
        $upload_result = handle_image_upload($_FILES['profile_image'], 'profile');
        if (is_array($upload_result)) {
            $profile_image = $upload_result['filename'];
        } else {
            $error = $upload_result;
        }
    }
    
    if (empty($error)) {
        $sql = "UPDATE profile SET name = ?, title = ?, email = ?, about = ?, cv_link = ?";
        $params = [$name, $title, $email, $about, $cv_link];
        
        if ($profile_image) {
            $sql .= ", profile_image = ?";
            $params[] = $profile_image;
        }
        
        $sql .= " WHERE id = 1";
        
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute($params)) {
            $success = 'Profile updated successfully.';
        } else {
            $error = 'Failed to update profile.';
        }
    }
}

// Get current profile data
$stmt = $pdo->query("SELECT * FROM profile WHERE id = 1");
$profile = $stmt->fetch();
?>

<?php require_once 'includes/admin-header.php'; ?>

<div class="container mt-4">
    <h1>Edit Profile</h1>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" name="name" class="form-control" required 
                           value="<?php echo htmlspecialchars($profile['name']); ?>">
                </div>
                
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" class="form-control" required 
                           value="<?php echo htmlspecialchars($profile['title']); ?>">
                </div>
                
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" class="form-control" required 
                           value="<?php echo htmlspecialchars($profile['email']); ?>">
                </div>
                
                <div class="form-group">
                    <label>About</label>
                    <textarea name="about" class="form-control" rows="5"><?php echo htmlspecialchars($profile['about']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>CV Link</label>
                    <input type="url" name="cv_link" class="form-control" 
                           value="<?php echo htmlspecialchars($profile['cv_link']); ?>">
                </div>
                
                <div class="form-group">
                    <label>Profile Image</label>
                    <?php if ($profile['profile_image']): ?>
                        <div class="mb-2">
                            <img src="../uploads/images/<?php echo htmlspecialchars($profile['profile_image']); ?>" 
                                 alt="Current profile image" style="max-width: 200px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="profile_image" class="form-control-file" accept="image/*">
                    <small class="form-text text-muted">Leave empty to keep current image</small>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?> 