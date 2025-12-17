<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Free Message</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-light d-flex flex-column min-vh-100">

  <nav class="navbar navbar-expand-lg navbar-dark sticky-top mb-4">
    <div class="container">
      <a class="navbar-brand" href="index.php?page=home">Free Message</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <?php if (isset($_SESSION['user_id'])): ?>

            <li class="nav-item">
              <a class="nav-link active fw-bold" href="index.php?page=post_create">
                <i class="fas fa-plus-circle"></i> Poster
              </a>
            </li>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
              <li class="nav-item">
                <a class="nav-link text-warning fw-bold" href="index.php?page=admin">
                  <i class="fas fa-shield-alt"></i> Modération
                </a>
              </li>
            <?php endif; ?>

            <li class="nav-item mx-2">
              <a class="nav-link text-white" href="index.php?page=profile&id=<?= $_SESSION['user_id'] ?>">
                @<?= htmlspecialchars($_SESSION['username']) ?>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link text-danger" href="index.php?page=logout" title="Déconnexion">
                <i class="fas fa-power-off"></i>
              </a>
            </li>

          <?php else: ?>
            <li class="nav-item d-flex gap-2">
              <a class="btn btn-outline-light" href="index.php?page=login" style="border-color: #62c370; color: #62c370; font-weight: bold;">
                Connexion
              </a>

              <a class="btn btn-success" href="index.php?page=register">
                Inscription
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container main-content flex-grow-1">