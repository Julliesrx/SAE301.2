<?php
require_once 'models/UserModel.php';
require_once 'models/PostModel.php'; 

// inscription

function register()
{
    $error = null;
    $categories = getAllCategories(); 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];
        $catId = $_POST['category']; 

        if (!empty($username) && !empty($email) && !empty($password) && !empty($catId)) {
            if (getUserByEmail($email)) {
                $error = "Cet email est déjà utilisé.";
            } else {
                $passionName = ""; 
                foreach ($categories as $c) {
                    if ($c['id_category'] == $catId) {
                        $passionName = $c['name'];
                        break;}
                }

                if (createUser($username, $email, $password, $passionName)) {
                    header('Location: index.php?page=login&success=1');
                    exit;
                } else {$error = "Erreur lors de l'inscription.";}
            }
        } else {
            $error = "Tous les champs sont requis.";
        }
    }
    require 'templates/register.php';
}

// modifier mon profil

function editProfile()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit;
    }

    $userId = $_SESSION['user_id'];
    $user = getUserById($userId); 
    $categories = getAllCategories(); 
    $error = null;
    $success = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $bio = htmlspecialchars($_POST['bio']);
        $catId = $_POST['category'];

        $passionName = $user['passion'];
        foreach ($categories as $c) {
            if ($c['id_category'] == $catId) {
                $passionName = $c['name'];
                break;
            }
        }

        $newPP = null;
        if (isset($_FILES['pp']) && $_FILES['pp']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($_FILES['pp']['name'], PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                $newPP = "pp_" . $userId . "_" . uniqid() . "." . $ext;
                move_uploaded_file($_FILES['pp']['tmp_name'], 'assets/uploads/' . $newPP);
            } else {
                $error = "Format d'image invalide.";
            }
        }

        if (!$error) {
            if (updateUser($userId, $bio, $passionName, $newPP)) {
                $success = "Profil mis à jour !";
                $user = getUserById($userId);
            } else {
                $error = "Erreur lors de la mise à jour.";
            }
        }
    }

    require 'templates/profile_edit.php';
}

// connexion APR7S INSCRIPTION
function login()
{
    $error = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];
        $user = getUserByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: index.php?page=home');
            exit;
        } else {
            $error = "Identifiants incorrects.";
        }
    }
    require 'templates/login.php';
}


function profile()
{
    if (isset($_GET['id'])) {
        $userId = $_GET['id'];
    } elseif (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
    } else {
        header('Location: index.php?page=login');
        exit;
    }

    $user = getUserById($userId);
    if (!$user) {
        echo "Utilisateur introuvable.";
        return;
    }

    $myPosts = getPostsByUser($userId);
    $myLikes = getLikedPostsByUser($userId);

    require 'templates/profil.php';
}

function logout()
{
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}
