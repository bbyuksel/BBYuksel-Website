<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get profile data
$profileData = get_profile($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_TITLE; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'publications.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="publications.php">Publications</a>
                    </li>
                    <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'projects.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="projects.php">Projects</a>
                    </li>
                    <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'services.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="services-awards.php">Services and Awards</a>
                    </li>
                    <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'teaching.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="teaching.php">Teaching</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html>
