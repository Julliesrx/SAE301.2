<?php require 'templates/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4><i class="fas fa-user-edit"></i> Modifier mon profil</h4>
                </div>
                <div class="card-body">

                    <?php if(isset($success)): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                        <a href="index.php?page=profile" class="btn btn-sm btn-outline-secondary mb-3">Retour au profil</a>
                    <?php endif; ?>
                    
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-4 text-center">
                            <label class="form-label d-block fw-bold">Photo de profil</label>
                            
                            <?php if(isset($user['pp']) && $user['pp'] !== 'default.jpg'): ?>
                                <img src="assets/uploads/<?= htmlspecialchars($user['pp']) ?>" class="rounded-circle mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                            <?php else: ?>
                                <div class="mb-2"><i class="fas fa-user-circle fa-5x text-secondary"></i></div>
                            <?php endif; ?>

                            <input type="file" name="pp" class="form-control form-control-sm mt-2 w-50 mx-auto">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control" rows="3"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catégorie préférée</label>
                            <select name="category" class="form-select">
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id_category'] ?>" 
                                        <?= (isset($user['passion']) && $user['passion'] === $cat['name']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Enregistrer les modifications</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'templates/footer.php'; ?>