<?php
require_once 'models/PostModel.php';

// --- PAGE D'ACCUEIL (FEED) ---
function index() {
    // 1. Gestion du filtre (ex: index.php?page=home&cat=1)
    $catId = isset($_GET['cat']) ? $_GET['cat'] : null;

    // 2. Récupération des données via le Model
    $posts = getPublishedPosts($catId);       
    $categories = getAllCategories();         

    // 3. Affichage de la vue
    require 'templates/home.php';
}

// --- PAGE D'UPLOAD ---
function create() {
    // 1. Vérifier si connecté
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit;
    }

    $error = null;
    $success = null;

    // 2. Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
        $categoryId = $_POST['category'];
        $description = htmlspecialchars($_POST['description']);
        
        $file = $_FILES['image'];
        $fileName = $file['name'];
        $fileTmp = $file['tmp_name'];
        $fileError = $file['error'];
        
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed)) {
            if ($fileError === 0) {
                $newFileName = uniqid('', true) . "." . $ext;
                $destination = 'assets/uploads/' . $newFileName;

                if (move_uploaded_file($fileTmp, $destination)) {
                    if (createPost($_SESSION['user_id'], $categoryId, $newFileName, $description)) {
                        $success = "Photo envoyée ! En attente de validation.";
                    } else {
                        $error = "Erreur BDD.";
                    }
                } else {
                    $error = "Erreur lors de l'upload.";
                }
            } else {
                $error = "Erreur fichier.";
            }
        } else {
            $error = "Format invalide.";
        }
    }

    $categories = getAllCategories();
    require 'templates/post_create.php';
}

// --- PAGE ADMIN (MODÉRATION) ---
function admin() {
    // 1. SÉCURITÉ : Seul l'admin passe
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: index.php?page=home');
        exit;
    }

    // 2. Gestion des actions (Valider / Refuser)
    if (isset($_GET['action']) && isset($_GET['id'])) {
        $id = $_GET['id'];
        $action = $_GET['action']; 
        
        if ($action === 'publish') {
            updatePostStatus($id, 'PUBLISHED');
        } elseif ($action === 'reject') {
            updatePostStatus($id, 'REJECTED');
        }
        
        header('Location: index.php?page=admin');
        exit;
    }

    // 3. Récupérer les données via le Modèle (Maintenant ça va marcher !)
    $posts = getPendingPosts();

    // 4. Afficher la vue
    require 'templates/admin.php';
}
// Like
function handleLike() {
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'Connexion requise']);
        exit;
    }

    if (isset($_GET['id'])) {
        $postId = $_GET['id'];
        $userId = $_SESSION['user_id'];

        if (hasLiked($userId, $postId)) {
            removeLike($userId, $postId);
            echo json_encode(['status' => 'unliked']);
        } else {
            addLike($userId, $postId);
            removeDislike($userId, $postId); // <--- AJOUT : On est sûr qu'il n'est plus masqué
            echo json_encode(['status' => 'liked']);
        }
    }
    exit;
}

function handleDislike() {
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'Connexion requise']);
        exit;
    }

    if (isset($_GET['id'])) {
        $postId = $_GET['id'];
        $userId = $_SESSION['user_id'];

        if (hasDisliked($userId, $postId)) {
            // Si on clique sur "Réafficher"
            removeDislike($userId, $postId);
            echo json_encode(['status' => 'revealed']);
        } else {
            // Si on masque le post
            addDislike($userId, $postId);
            removeLike($userId, $postId); // <--- AJOUT : On enlève le like automatiquement !
            echo json_encode(['status' => 'hidden']);
        }
    }
    exit;
}

function delete() {
    // 1. Sécurité : Connecté ?
    if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
        header('Location: index.php?page=home');
        exit;
    }

    $postId = $_GET['id'];
    $currentUserId = $_SESSION['user_id'];
    $authorId = getPostAuthorId($postId);

    // 2. Vérification : Est-ce MON post ou suis-je ADMIN ?
    // (On autorise l'admin à supprimer n'importe quoi aussi, c'est pratique)
    if ($currentUserId == $authorId || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin')) {
        deletePost($postId);
    }

    // 3. Retour à la page précédente (Referer) ou Home
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}