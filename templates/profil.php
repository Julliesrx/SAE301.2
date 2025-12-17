<?php require 'templates/header.php'; ?>

<div class="container mt-4">

    <div class="card shadow-sm mb-4">
        <div class="card-body text-center">

            <?php if (isset($user['pp']) && $user['pp'] !== 'default.jpg'): ?>
                <img src="assets/uploads/<?= htmlspecialchars($user['pp']) ?>" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            <?php else: ?>
                <div class="mb-3">
                    <i class="fas fa-user-circle fa-5x text-secondary"></i>
                </div>
            <?php endif; ?>

            <h2 class="card-title"><?= htmlspecialchars($user['username']) ?></h2>

            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['id_user']): ?>
                <a href="index.php?page=profile_edit" class="btn btn-outline-dark btn-sm mb-3"><i class="fas fa-cog"></i> Modifier le profil</a>
            <?php endif; ?>

            <br>

            <?php if (!empty($user['passion'])): ?>
                <span class="badge bg-primary mb-2"><i class="fas fa-heart"></i> Fan de : <?= htmlspecialchars($user['passion']) ?></span>
            <?php else: ?>
                <span class="badge bg-secondary mb-2">Passion non renseignée</span>
            <?php endif; ?>

            <p class="card-text text-muted fst-italic mt-2">
                <?= !empty($user['bio']) ? nl2br(htmlspecialchars($user['bio'])) : "Aucune bio pour l'instant..." ?>
            </p>
        </div>
    </div>

    <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts" type="button">
                <i class="fas fa-camera"></i>Post</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="likes-tab" data-bs-toggle="tab" data-bs-target="#likes" type="button">
                <i class="fas fa-heart text-danger"></i> Posts Likés</button>
        </li>
    </ul>

    <div class="tab-content" id="profileTabsContent">

        <div class="tab-pane fade show active" id="posts">
            <div class="row">
                <?php if (empty($myPosts)): ?>
                    <div class="col-12 text-center text-muted p-5">Cet utilisateur n'a rien posté.</div>
                <?php else: ?>
                    <?php foreach ($myPosts as $p): ?>
                        <div class="col-6 col-md-4 mb-4">
                            <div class="card shadow-sm h-100 position-relative">

                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $p['id_user']): ?>
                                    <a href="index.php?page=post_delete&id=<?= $p['id_post'] ?>"
                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2"
                                        onclick="return confirm('Vraiment supprimer cette photo ? 🗑️');"
                                        style="z-index: 10;">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                <?php endif; ?>

                                <img src="assets/uploads/<?= htmlspecialchars($p['image_url']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                                <div class="card-body p-2 text-center">
                                    <a href="index.php?page=home&cat=<?= $p['id_category'] ?>" class="text-decoration-none text-muted">
                                        <small>#<?= htmlspecialchars($p['cat_name']) ?></small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="likes">
            <div class="row">
                <?php if (empty($myLikes)): ?>
                    <div class="col-12 text-center text-muted p-5">Aucun coup de cœur pour l'instant.</div>
                <?php else: ?>
                    <?php foreach ($myLikes as $p): ?>
                        <div class="col-6 col-md-4 mb-4">
                            <div class="card shadow-sm h-100 border-danger">
                                <div class="position-absolute top-0 end-0 p-2">
                                    <i class="fas fa-heart text-danger"></i>
                                </div>
                                <img src="assets/uploads/<?= htmlspecialchars($p['image_url']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <p class="card-text small mb-0">Posté par
                                        <a href="index.php?page=profile&id=<?= $p['id_user'] ?>" class="text-dark text-decoration-none">
                                            <strong><?= htmlspecialchars($p['username']) ?></strong>
                                        </a>
                                    </p>
                                    <a href="index.php?page=home&cat=<?= $p['id_category'] ?>" class="text-decoration-none">
                                        <small class="text-primary">#<?= htmlspecialchars($p['cat_name']) ?></small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php require 'templates/footer.php'; ?>