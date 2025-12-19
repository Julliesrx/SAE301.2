<?php
require_once 'models/PostModel.php';

function index()
{
    $catId = isset($_GET['cat']) ? $_GET['cat'] : null;

    $posts = getPublishedPosts($catId);
    $categories = getAllCategories();

    require 'templates/home.php';
}

// créer post 
function create()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit;
    }

    $error = null;
    $success = null;

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

// pour la modération admin TRIER ADMIN/USER
function admin()
{
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: index.php?page=home');
        exit;
    }

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

    $posts = getPendingPosts();

    require 'templates/admin.php';
}

function handleLike()
{
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
            removeDislike($userId, $postId);
            echo json_encode(['status' => 'liked']);
        }
    }
    exit;
}

function handleDislike()
{
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'Connexion requise']);
        exit;
    }

    if (isset($_GET['id'])) {
        $postId = $_GET['id'];
        $userId = $_SESSION['user_id'];

        if (hasDisliked($userId, $postId)) {
            removeDislike($userId, $postId);
            echo json_encode(['status' => 'revealed']);
        } else {
            addDislike($userId, $postId);
            removeLike($userId, $postId);
            echo json_encode(['status' => 'hidden']);
        }
    }
    exit;
}

function delete()
{
    if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
        header('Location: index.php?page=home');
        exit;
    }

    $postId = $_GET['id'];
    $currentUserId = $_SESSION['user_id'];
    $authorId = getPostAuthorId($postId);

    if ($currentUserId == $authorId || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin')) {
        deletePost($postId);
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

function comment()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content']) && isset($_POST['post_id'])) {
        $content = htmlspecialchars($_POST['content']);
        $postId = $_POST['post_id'];
        $userId = $_SESSION['user_id'];

        if (!empty($content)) {
            addComment($userId, $postId, $content);
        }
    }

    header('Location: index.php?page=home');
    exit;
}

function deleteCommentAction()
{
    if (isset($_GET['id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        deleteComment($_GET['id']);
    }
    header('Location: index.php?page=home');
    exit;
}
