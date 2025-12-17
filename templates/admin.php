<?php require 'templates/header.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4 text-warning"><i class="fas fa-shield-alt"></i> Espace Modération</h2>

    <?php if (empty($posts)): ?>
        <div class="alert alert-info">Aucun post en attente de validation.</div>
    <?php else: ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Image</th>
                        <th>Utilisateur</th>
                        <th>Info</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $p): ?>
                        <tr>
                            <td>
                                <a href="assets/uploads/<?= htmlspecialchars($p['image_url']) ?>" target="_blank">
                                    <img src="assets/uploads/<?= htmlspecialchars($p['image_url']) ?>"
                                        style="width: 100px; height: 60px; object-fit: cover; border-radius: 5px;">
                                </a>
                            </td>

                            <td>
                                <strong><?= htmlspecialchars($p['username']) ?></strong>
                            </td>

                            <td>
                                <span class="badge bg-secondary"><?= htmlspecialchars($p['cat_name']) ?></span><br>
                                <small class="text-muted"><?= htmlspecialchars($p['description']) ?></small>
                            </td>

                            <td>
                                <a href="index.php?page=admin&action=publish&id=<?= $p['id_post'] ?>"
                                    class="btn btn-success btn-sm me-1" title="Valider">
                                    <i class="fas fa-check"></i>
                                </a>
                                <a href="index.php?page=admin&action=reject&id=<?= $p['id_post'] ?>"
                                    class="btn btn-danger btn-sm" title="Refuser">
                                    <i class="fas fa-times"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>
</div>

<?php require 'templates/footer.php'; ?>