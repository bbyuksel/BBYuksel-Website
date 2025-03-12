<?php
require_once 'includes/header.php';

// Get publications
$publications = get_publications($pdo);
?>

<!-- Main Content -->
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="section-title">Publications</h2>
            
            <?php foreach($publications as $pub): ?>
            <div class="publication-item">
                <h5><?php echo $pub['title']; ?></h5>
                <p><?php echo $pub['authors']; ?></p>
                <p><?php echo $pub['venue']; ?>, <?php echo $pub['year']; ?></p>
                <?php if (!empty($pub['citation'])): ?>
                    <div class="citation">
                        <small><?php echo $pub['citation']; ?></small>
                    </div>
                <?php endif; ?>
                <?php if (!empty($pub['link'])): ?>
                    <a href="<?php echo $pub['link']; ?>" target="_blank" class="btn btn-sm btn-primary">View Publication</a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($publications)): ?>
                <p>No publications available.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
