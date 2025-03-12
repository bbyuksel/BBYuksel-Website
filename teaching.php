<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Profil verilerini çekelim
$stmt = $pdo->query("SELECT * FROM profile WHERE id = 1");
$profileData = $stmt->fetch(PDO::FETCH_ASSOC);

// Aktif kursları çekelim
$stmt = $pdo->query("SELECT * FROM courses WHERE is_active = 1 ORDER BY year DESC, semester DESC");
$active_courses = $stmt->fetchAll();

// Geçmiş kursları çekelim
$stmt = $pdo->query("SELECT * FROM courses WHERE is_active = 0 ORDER BY year DESC, semester DESC");
$past_courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teaching - <?php echo SITE_TITLE; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once 'includes/header.php'; ?>

    <div class="container py-5">
        <!-- Current Courses -->
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="mb-4">Current Courses</h2>
                <?php if ($active_courses): ?>
                    <div class="list-group">
                        <?php foreach ($active_courses as $course): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">
                                        <?php echo htmlspecialchars($course['code']); ?> - 
                                        <?php echo htmlspecialchars($course['title']); ?>
                                    </h5>
                                    <small>
                                        <?php echo htmlspecialchars($course['semester']); ?> 
                                        <?php echo htmlspecialchars($course['year']); ?>
                                    </small>
                                </div>
                                <p class="mb-1">
                                    <span class="badge badge-info"><?php echo htmlspecialchars($course['level']); ?></span>
                                </p>
                                <?php if ($course['description']): ?>
                                    <p class="mb-1"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
                                <?php endif; ?>
                                <?php if ($course['syllabus_file']): ?>
                                    <a href="uploads/syllabus/<?php echo htmlspecialchars($course['syllabus_file']); ?>" 
                                       class="btn btn-sm btn-outline-primary mt-2" 
                                       target="_blank">
                                        Download Syllabus
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No current courses to display.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Past Courses -->
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Past Courses</h2>
                <?php if ($past_courses): ?>
                    <div class="list-group">
                        <?php foreach ($past_courses as $course): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">
                                        <?php echo htmlspecialchars($course['code']); ?> - 
                                        <?php echo htmlspecialchars($course['title']); ?>
                                    </h5>
                                    <small>
                                        <?php echo htmlspecialchars($course['semester']); ?> 
                                        <?php echo htmlspecialchars($course['year']); ?>
                                    </small>
                                </div>
                                <p class="mb-1">
                                    <span class="badge badge-secondary"><?php echo htmlspecialchars($course['level']); ?></span>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No past courses to display.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 