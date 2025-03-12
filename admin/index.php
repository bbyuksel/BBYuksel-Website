<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// If already logged in, redirect to dashboard
if (is_logged_in()) {
    redirect('dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean_input($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            redirect('dashboard.php');
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - BBYuksel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            flex: 1 0 auto;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            text-align: center;
            border-radius: 10px 10px 0 0 !important;
            padding: 20px;
        }
        .card-header i {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .card-body {
            padding: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 5px;
            padding: 12px;
        }
        .btn-login {
            padding: 12px;
            font-weight: bold;
            border-radius: 5px;
            width: 100%;
        }
        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 1rem;
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user-circle"></i>
                    <h4 class="mb-0">Admin Login</h4>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="post">
                        <div class="form-group">
                            <label>Username</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-login">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </button>
                    </form>
                    
                    <!-- Back to Main Page Button -->
                    <div class="text-center mt-3">
                        <a href="../" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Main Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            © <?php echo date('Y'); ?> Beyazıt Bestami YÜKSEL. All rights reserved.
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 