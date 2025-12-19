<?php require 'templates/header.php'; ?>

<div class="container mb-4">
    <div class="d-flex gap-2 overflow-auto py-2">
        <a href="index.php?page=home"
            class="btn <?= !isset($_GET['cat']) ? 'btn-dark' : 'btn-outline-secondary' ?> rounded-pill">Tout</a>
        <?php foreach ($categories as $c): ?>
            <a href="index.php?page=home&cat=<?= $c['id_category'] ?>"
                class="btn <?= (isset($_GET['cat']) && $_GET['cat'] == $c['id_category']) ? 'btn-dark' : 'btn-outline-secondary' ?> rounded-pill">
                <?= htmlspecialchars($c['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="container">
    <div class="row">
        <?php if (empty($posts)): ?>
            <div class="col-12 text-center mt-5">
                <h3>Posts en cours de validation !</h3>
                <p>Aucune photo n'a été validée pour l'instant.</p>
            </div>
        <?php else: ?>

            <?php foreach ($posts as $p): ?>

                <?php
                $isLiked = isset($_SESSION['user_id']) ? hasLiked($_SESSION['user_id'], $p['id_post']) : false;
                $isDisliked = isset($_SESSION['user_id']) ? hasDisliked($_SESSION['user_id'], $p['id_post']) : false;

                $comments = getCommentsByPost($p['id_post']);
                ?>

                <div class="col-12 col-md-4 mb-4">
                    <div class="card shadow-sm h-100 position-relative">

                        <div class="card-header bg-white border-0 pb-0">
                            <a href="index.php?page=profile&id=<?= $p['id_user'] ?>" class="d-flex align-items-center text-decoration-none text-dark">
                                <?php if (isset($p['pp']) && $p['pp'] !== 'default.jpg' && !empty($p['pp'])): ?>
                                    <img src="assets/uploads/<?= htmlspecialchars($p['pp']) ?>"
                                        class="rounded-circle me-2"
                                        style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #ddd;">
                                <?php else: ?>
                                    <i class="fas fa-user-circle fa-2x text-secondary me-2"></i>
                                <?php endif; ?>
                                <strong><?= htmlspecialchars($p['username']) ?></strong>
                            </a>
                        </div>

                        <div class="post-content <?= $isDisliked ? 'd-none' : '' ?>">
                            <div class="card-body p-2">
                                <img src="assets/uploads/<?= htmlspecialchars($p['image_url']) ?>"
                                    class="card-img-top rounded"
                                    style="height: 250px; object-fit: cover;"
                                    alt="Post">
                            </div>

                            <div class="card-body pt-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <a href="index.php?page=home&cat=<?= $p['id_category'] ?>" class="text-decoration-none">
                                            <small class="text-primary fw-bold">#<?= htmlspecialchars($p['cat_name']) ?></small>
                                        </a>
                                    </div>

                                    <div>
                                        <button class="btn btn-link text-danger p-0 like-btn me-3" data-id="<?= $p['id_post'] ?>">
                                            <i class="<?= $isLiked ? 'fas' : 'far' ?> fa-heart fa-lg"></i>
                                        </button>

                                        <button class="btn btn-link text-secondary p-0 dislike-btn" data-id="<?= $p['id_post'] ?>" title="Masquer">
                                            <i class="fas fa-times fa-lg"></i>
                                        </button>
                                    </div>
                                </div>

                                <?php if (!empty($p['description'])): ?>
                                    <p class="card-text small"><?= htmlspecialchars($p['description']) ?></p>
                                <?php endif; ?>

                                <p class="card-text"><small class="text-muted">
                                        <?= date('d/m/Y', strtotime($p['creation'])) ?>
                                    </small></p>
                            </div>
                        </div>

                        <div class="post-overlay <?= $isDisliked ? '' : 'd-none' ?> d-flex flex-column justify-content-center align-items-center text-center p-5" style="height: 300px;">
                            <i class="fas fa-eye-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted fw-bold">Vous avez masqué ce contenu.</p>
                            <button class="btn btn-sm btn-outline-dark undo-btn" data-id="<?= $p['id_post'] ?>">Réafficher</button>
                        </div>

                        <div class="card-footer bg-white border-0 pt-0">
                            <div class="comments-list mb-2" style="max-height: 100px; overflow-y: auto;">
                                <?php if (empty($comments)): ?>
                                    <small class="text-muted fst-italic">Soyez le premier à commenter...</small>
                                <?php else: ?>
                                    <?php foreach ($comments as $c): ?>
                                        <div class="d-flex mb-1 align-items-center">
                                            <small>
                                                <strong><?= htmlspecialchars($c['username']) ?></strong> :
                                                <?= htmlspecialchars($c['content']) ?>

                                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                                    <a href="index.php?page=comment_delete&id=<?= $c['id_commentaire'] ?>"
                                                        class="text-danger ms-2 text-decoration-none"
                                                        onclick="return confirm('Supprimer ce commentaire ?')">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <form action="index.php?page=comment" method="POST" class="d-flex gap-1">
                                    <input type="hidden" name="post_id" value="<?= $p['id_post'] ?>">
                                    <input type="text" name="content" class="form-control form-control-sm rounded-pill" placeholder="Votre commentaire..." required>
                                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-circle">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</div>

<?php require 'templates/footer.php'; ?>