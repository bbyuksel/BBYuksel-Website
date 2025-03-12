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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Get current user's data
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!password_verify($current_password, $user['password'])) {
        $error = 'Current password is incorrect.';
    } elseif (strlen($new_password) < 6) {
        $error = 'New password must be at least 6 characters long.';
    } elseif ($new_password !== $confirm_password) {
        $error = 'New passwords do not match.';
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update the password
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        if ($stmt->execute([$hashed_password, $_SESSION['user_id']])) {
            $success = 'Password updated successfully.';
        } else {
            $error = 'Failed to update password.';
        }
    }
}

require_once 'includes/admin-header.php';
?>

<div class="container mt-4">
    <h1>Change Password</h1>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <form method="post">
                <div class="form-group">
                    <label>Current Password *</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>New Password *</label>
                    <input type="password" name="new_password" class="form-control" required 
                           minlength="6" pattern=".{6,}"
                           title="Password must be at least 6 characters long">
                </div>
                
                <div class="form-group">
                    <label>Confirm New Password *</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Change Password</button>
            </form>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header">
            Password Requirements
        </div>
        <div class="card-body">
            <ul class="mb-0">
                <li>Must be at least 6 characters long</li>
                <li>Both new password fields must match</li>
                <li>Cannot be the same as your current password</li>
            </ul>
        </div>
    </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?> 