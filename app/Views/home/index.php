<div class="container my-5">
    <div class="welcome-section text-center mb-4">
        <h1><i class="fas fa-user-circle"></i> Welcome to GradShare!</h1>
        <p class="lead">Here are the projects you have uploaded:</p>
    </div>
    
    <h2 class="mb-4">Your Projects</h2>
    <ul class="list-group">
        <?php if (empty($projects)): ?>
            <li class="list-group-item text-center text-muted">No projects found.</li>
        <?php else: ?>
            <?php foreach ($projects as $project): ?>
                <li class="list-group-item d-flex justify-content-between align-items-start rounded shadow-sm mb-3">
                    <div>
                        <h5 class="mb-1"><?= htmlspecialchars($project["projectName"]) ?></h5>
                        <p class="mb-1"><?= htmlspecialchars($project["projectDescription"]) ?></p>
                        <small class="text-muted">Project ID: <?= htmlspecialchars($project["id"]) ?></small>
                    </div>
                    <a href="view/<?= htmlspecialchars($project["id"]) ?>" target="_blank" class="btn btn-outline-primary">
                        View Project <i class="fas fa-eye"></i>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>