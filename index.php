<?php
// ROUTEUR.PHP

// AJOUTE ÇA TEMPORAIREMENT POUR VOIR L'ERREUR
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// session_start(); 


session_start();
require_once 'config/db.php';

$page = $_GET['page'] ?? 'home';

switch ($page) {
    // --- GESTION UTILISATEURS ---
    case 'register':
        require_once 'controllers/UserController.php';
        register();
        break;
    case 'login':
        require_once 'controllers/UserController.php';
        login();
        break;
    case 'logout':
        require_once 'controllers/UserController.php';
        logout();
        break;

    // --- GESTION POSTS ---
    case 'home':
        require_once 'controllers/PostController.php';
        index(); // Affiche le feed
        break;
    case 'post_create':
        require_once 'controllers/PostController.php';
        create(); // Formulaire d'upload
        break;

    // --- ADMIN ---
    case 'admin':
        require_once 'controllers/PostController.php';
        admin(); // Modération
        break;

    // --- INTERACTIONS (AJAX) ---
    case 'like':
        require_once 'controllers/PostController.php';
        handleLike();
        break;

    default:
        require_once 'controllers/PostController.php';
        index(); // Par défaut, on va sur le feed
        break;

    case 'profile':
        require_once 'controllers/UserController.php';
        profile();
        break;

    case 'profile_edit':
        require_once 'controllers/UserController.php';
        editProfile();
        break;
}
