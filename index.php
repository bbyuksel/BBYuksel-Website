<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Profil verilerini çekelim
$stmt = $pdo->query("SELECT * FROM profile WHERE id = 1");
$profileData = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_TITLE; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once 'includes/header.php'; ?>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-4 text-center">
                <!-- Profil resmi -->
                <div class="profile-image mb-4">
                    <?php if (!empty($profileData['profile_image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($profileData['profile_image']); ?>" 
                             alt="Profile Image" 
                             class="img-fluid rounded shadow"
                             style="max-width: 200px; height: auto;">
                    <?php else: ?>
                        <img src="images/default-avatar.png" 
                             alt="Default Profile Image" 
                             class="img-fluid rounded shadow"
                             style="max-width: 200px; height: auto;">
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-8">
                <h1><?php echo htmlspecialchars($profileData['name']); ?></h1>
                <h3><?php echo htmlspecialchars($profileData['title']); ?></h3>
                <p>
                    <?php echo htmlspecialchars($profileData['faculty']); ?><br>
                    <?php echo htmlspecialchars($profileData['university']); ?><br>
                    <?php echo htmlspecialchars($profileData['location']); ?>
                </p>
                <div class="bio mt-4">
                    <?php echo nl2br(htmlspecialchars($profileData['bio'])); ?>
                </div>
            </div>
        </div>

        <!-- News Section -->
        <div class="row mt-5">
            <div class="col-12">
                <h2>News</h2>
                <?php
                $stmt = $pdo->query("SELECT * FROM news ORDER BY date DESC LIMIT 5");
                $news = $stmt->fetchAll();
                if ($news): ?>
                    <ul class="list-unstyled">
                        <?php foreach ($news as $item): ?>
                            <li class="mb-3">
                                <strong><?php echo date('F j, Y', strtotime($item['date'])); ?>:</strong>
                                <?php echo htmlspecialchars($item['content']); ?>
                                <?php if (!empty($item['link'])): ?>
                                    <a href="<?php echo htmlspecialchars($item['link']); ?>" target="_blank">Read more</a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No news available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>