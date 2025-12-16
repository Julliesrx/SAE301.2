<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Free Message - Transport Social</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php?page=home"><i class="fas fa-bus"></i> Free Message</a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item">
                <a class="nav-link active" href="index.php?page=post_create"><i class="fas fa-camera"></i> Poster</a>
            </li>
            
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link text-warning fw-bold" href="index.php?page=admin"><i class="fas fa-shield-alt"></i> Modération</a>
                </li>
            <?php endif; ?>

            <li class="nav-item">
                <a class="nav-link text-white" href="#">@<?= htmlspecialchars($_SESSION['username']) ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="index.php?page=logout"><i class="fas fa-sign-out-alt"></i></a>
            </li>

        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="index.php?page=login">Connexion</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?page=register">Inscription</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container main-content">