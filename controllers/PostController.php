<?php
require_once 'models/PostModel.php';

// --- PAGE D'ACCUEIL (FEED) ---
function index() {
    // 1. Gestion du filtre (ex: index.php?page=home&cat=1)
    $catId = isset($_GET['cat']) ? $_GET['cat'] : null;

    // 2. Récupération des données via le Model
    $posts = getPublishedPosts($catId);       // Les posts
    $categories = getAllCategories();         // Les boutons de filtre

    // 3. Affichage de la vue
    require 'templates/home.php';
}

// --- PAGE D'UPLOAD ---
function create()
{
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

        // Gestion de l'image
        $file = $_FILES['image'];
        $fileName = $file['name'];
        $fileTmp = $file['tmp_name'];
        $fileError = $file['error'];

        // Extension
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed)) {
            if ($fileError === 0) {
                // Nom unique pour éviter les écrasements
                $newFileName = uniqid('', true) . "." . $ext;
                $destination = 'assets/uploads/' . $newFileName;

                if (move_uploaded_file($fileTmp, $destination)) {
                    // Enregistrement en BDD
                    if (createPost($_SESSION['user_id'], $categoryId, $newFileName, $description)) {
                        $success = "Photo envoyée ! Elle sera visible après validation de l'admin.";
                    } else {
                        $error = "Erreur BDD.";
                    }
                } else {
                    $error = "Erreur lors de l'upload de l'image.";
                }
            } else {
                $error = "Erreur lors du transfert.";
            }
        } else {
            $error = "Format invalide (JPG, PNG, GIF, WEBP uniquement).";
        }
    }

    // 3. Récupérer les catégories pour le select
    $categories = getAllCategories();

    // 4. Afficher la vue
    require 'templates/post_create.php';
}

// Fonctions vides pour l'instant (pour éviter les crashs)
function admin() {}
function handleLike() {}
