<?php
if (!defined('INCLUDED_FROM_ADMIN')) {
    define('INCLUDED_FROM_ADMIN', true);
}

// Check if user is logged in
if (!is_logged_in()) {
    redirect('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - BBYuksel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .nav-link {
            padding: 0.5rem 1rem !important;
        }
        .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            border-radius: 4px;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">BBYuksel Admin</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" 
                           href="dashboard.php">
                            <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'news-manage.php' ? 'active' : ''; ?>" 
                           href="news-manage.php">
                            <i class="fas fa-newspaper mr-1"></i>News
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'publications-manage.php' ? 'active' : ''; ?>" 
                           href="publications-manage.php">
                            <i class="fas fa-book mr-1"></i>Publications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'projects-manage.php' ? 'active' : ''; ?>" 
                           href="projects-manage.php">
                            <i class="fas fa-project-diagram mr-1"></i>Projects
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'courses-manage.php' ? 'active' : ''; ?>" 
                           href="courses-manage.php">
                            <i class="fas fa-chalkboard-teacher mr-1"></i>Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'services-awards-manage.php' ? 'active' : ''; ?>" 
                           href="services-awards-manage.php">
                            <i class="fas fa-award mr-1"></i>Services & Awards
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                            <i class="fas fa-user mr-1"></i><?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="edit-profile.php">
                                <i class="fas fa-user-edit mr-1"></i>Edit Profile
                            </a>
                            <a class="dropdown-item" href="change-password.php">
                                <i class="fas fa-key mr-1"></i>Change Password
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">
                                <i class="fas fa-sign-out-alt mr-1"></i>Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav> 