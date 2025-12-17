<?php require 'templates/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-camera"></i> Nouvelle Publication</h4>
                </div>
                <div class="card-body">

                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <?php if(isset($success)): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                        <div class="text-center mb-3">
                            <a href="index.php?page=home" class="btn btn-outline-primary">Retour au fil</a>
                        </div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label class="form-label">Photo du véhicule</label>
                            <input type="file" name="image" class="form-control" required>
                            <div class="form-text">Formats acceptés : JPG, PNG, GIF.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catégorie</label>
                            <select name="category" class="form-select" required>
                                <option value="" disabled selected>Choisir...</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id_category'] ?>">
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description (Optionnelle)</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Où as-tu pris cette photo ?"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Publier</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'templates/footer.php'; ?>