<?php

session_start();
require_once 'config/db.php';

$page = $_GET['page'] ?? 'home';

switch ($page) {
    // --- users ---
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

    // --- posts ---
    case 'home':
        require_once 'controllers/PostController.php';
        index();
        break;
    case 'post_create':
        require_once 'controllers/PostController.php';
        create();
        break;

    // --- admin ---
    case 'admin':
        require_once 'controllers/PostController.php';
        admin();
        break;

    // --- évenements ---
    case 'like':
        require_once 'controllers/PostController.php';
        handleLike();
        break;

    default:
        require_once 'controllers/PostController.php';
        index();
        break;

    case 'profile':
        require_once 'controllers/UserController.php';
        profile();
        break;

    case 'profile_edit':
        require_once 'controllers/UserController.php';
        editProfile();
        break;

    case 'post_delete':
        require_once 'controllers/PostController.php';
        delete();
        break;

    case 'comment':
        require_once 'controllers/PostController.php';
        comment();
        break;
    case 'comment_delete':
        require_once 'controllers/PostController.php';
        deleteCommentAction();
        break;
}
