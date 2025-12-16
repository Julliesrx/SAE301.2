<?php require 'templates/partials/header.php'; ?> <div class="container mb-4">
    <div class="d-flex gap-2 overflow-auto py-2">
        <a href="index.php?page=home" class="btn btn-dark rounded-pill">Tout</a>
        <?php foreach($categories as $c): ?>
            <a href="index.php?page=home&cat=<?= $c['id_category'] ?>" 
               class="btn btn-outline-secondary rounded-pill">
               <?= htmlspecialchars($c['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="container">
    <div class="row">
        <?php if (empty($posts)): ?>
            <div class="col-12 text-center mt-5">
                <h3>Oups, c'est vide ! 🌵</h3>
                <p>Aucune photo n'a été validée pour l'instant.</p>
            </div>
        <?php else: ?>
            
            <?php foreach($posts as $p): ?>
            <div class="col-12 col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    
                    <div class="card-header bg-white d-flex align-items-center border-0 pb-0">
                        <i class="fas fa-user-circle fa-2x text-secondary me-2"></i>
                        <strong><?= htmlspecialchars($p['username']) ?></strong>
                    </div>

                    <div class="card-body p-2">
                        <img src="assets/uploads/<?= htmlspecialchars($p['image_url']) ?>" 
                             class="card-img-top rounded" 
                             style="height: 250px; object-fit: cover;" 
                             alt="Post">
                    </div>

                    <div class="card-body pt-0">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-primary fw-bold">#<?= htmlspecialchars($p['cat_name']) ?></small>
                            
                            <button class="btn btn-link text-danger p-0 like-btn" data-id="<?= $p['id_post'] ?>">
                                <i class="far fa-heart fa-lg"></i>
                            </button>
                        </div>
                        
                        <?php if(!empty($p['description'])): ?>
                            <p class="card-text small"><?= htmlspecialchars($p['description']) ?></p>
                        <?php endif; ?>
                        
                        <p class="card-text"><small class="text-muted">
                            <?= date('d/m/Y', strtotime($p['creation'])) ?>
                        </small></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</div>

<?php require 'templates/partials/footer.php'; ?>