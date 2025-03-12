<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!is_logged_in()) {
    redirect('index.php');
}

// Get counts from database
$counts = [
    'news' => $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn(),
    'publications' => $pdo->query("SELECT COUNT(*) FROM publications")->fetchColumn(),
    'projects' => $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn(),
    'courses' => $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn(),
    'services' => $pdo->query("SELECT COUNT(*) FROM services_awards WHERE type = 'service'")->fetchColumn(),
    'awards' => $pdo->query("SELECT COUNT(*) FROM services_awards WHERE type = 'award'")->fetchColumn()
];

require_once 'includes/admin-header.php';
?>

<div class="container mt-4">
    <h1>Dashboard</h1>

    <!-- Overview Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">News</h6>
                            <h2 class="mb-0"><?php echo $counts['news']; ?></h2>
                        </div>
                        <div class="icon">
                            <i class="fas fa-newspaper fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="news-manage.php" class="text-white stretched-link">View Details</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Publications</h6>
                            <h2 class="mb-0"><?php echo $counts['publications']; ?></h2>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="publications-manage.php" class="text-white stretched-link">View Details</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Projects</h6>
                            <h2 class="mb-0"><?php echo $counts['projects']; ?></h2>
                        </div>
                        <div class="icon">
                            <i class="fas fa-project-diagram fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="projects-manage.php" class="text-white stretched-link">View Details</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Courses</h6>
                            <h2 class="mb-0"><?php echo $counts['courses']; ?></h2>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chalkboard-teacher fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="courses-manage.php" class="text-white stretched-link">View Details</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Services</h6>
                            <h2 class="mb-0"><?php echo $counts['services']; ?></h2>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hands-helping fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="services-awards-manage.php" class="text-white stretched-link">View Details</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card bg-secondary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Awards</h6>
                            <h2 class="mb-0"><?php echo $counts['awards']; ?></h2>
                        </div>
                        <div class="icon">
                            <i class="fas fa-award fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="services-awards-manage.php" class="text-white stretched-link">View Details</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="card">
        <div class="card-header">
            Quick Actions
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <a href="edit-profile.php" class="btn btn-outline-primary btn-block mb-3">
                        <i class="fas fa-user-edit mr-2"></i>Edit Profile
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="change-password.php" class="btn btn-outline-secondary btn-block mb-3">
                        <i class="fas fa-key mr-2"></i>Change Password
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card .icon {
    opacity: 0.4;
}
.card:hover .icon {
    opacity: 0.6;
    transition: opacity 0.3s ease;
}
.card-footer {
    background: rgba(0, 0, 0, 0.1);
    border-top: none;
}
.stretched-link::after {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1;
    content: "";
}
</style>

<?php require_once 'includes/admin-footer.php'; ?> 