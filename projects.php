<?php
require_once 'includes/header.php';

// Get projects
$projects = get_projects($pdo);
?>

<!-- Main Content -->
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="section-title">Projects</h2>
            
            <?php foreach($projects as $project): ?>
            <div class="project-item">
                <div class="project-title"><?php echo $project['title']; ?></div>
                <div class="project-date">
                    <?php echo format_date($project['start_date']); ?> - 
                    <?php echo (!empty($project['end_date'])) ? format_date($project['end_date']) : 'Present'; ?>
                </div>
                <div class="project-funding">
                    <strong>Funding:</strong> <?php echo $project['funding']; ?>
                </div>
                <div class="project-description">
                    <?php echo $project['description']; ?>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($projects)): ?>
                <p>No projects available.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 