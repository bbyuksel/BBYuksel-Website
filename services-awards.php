<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Profil verilerini çekelim
$stmt = $pdo->query("SELECT * FROM profile WHERE id = 1");
$profileData = $stmt->fetch(PDO::FETCH_ASSOC);

// Services ve Awards verilerini çekelim
$stmt = $pdo->query("SELECT * FROM services_awards ORDER BY year DESC, type");
$items = $stmt->fetchAll();

// Verileri türlerine göre ayıralım
$services = array_filter($items, function($item) {
    return $item['type'] === 'service';
});

$awards = array_filter($items, function($item) {
    return $item['type'] === 'award';
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services and Awards - <?php echo SITE_TITLE; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once 'includes/header.php'; ?>

    <div class="container py-5">
        <!-- Services Section -->
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="mb-4">Professional Services</h2>
                <?php if ($services): ?>
                    <div class="list-group">
                        <?php foreach ($services as $service): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1"><?php echo htmlspecialchars($service['title']); ?></h5>
                                    <?php if ($service['year']): ?>
                                        <small><?php echo htmlspecialchars($service['year']); ?></small>
                                    <?php endif; ?>
                                </div>
                                <?php if ($service['institution']): ?>
                                    <p class="mb-1"><strong><?php echo htmlspecialchars($service['institution']); ?></strong></p>
                                <?php endif; ?>
                                <?php if ($service['description']): ?>
                                    <p class="mb-1"><?php echo nl2br(htmlspecialchars($service['description'])); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No professional services to display.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Awards Section -->
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Awards and Honors</h2>
                <?php if ($awards): ?>
                    <div class="list-group">
                        <?php foreach ($awards as $award): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1"><?php echo htmlspecialchars($award['title']); ?></h5>
                                    <?php if ($award['year']): ?>
                                        <small><?php echo htmlspecialchars($award['year']); ?></small>
                                    <?php endif; ?>
                                </div>
                                <?php if ($award['institution']): ?>
                                    <p class="mb-1"><strong><?php echo htmlspecialchars($award['institution']); ?></strong></p>
                                <?php endif; ?>
                                <?php if ($award['description']): ?>
                                    <p class="mb-1"><?php echo nl2br(htmlspecialchars($award['description'])); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No awards to display.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 