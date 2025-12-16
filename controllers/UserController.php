<?php
require_once 'models/UserModel.php';

// --- INSCRIPTION ---
function register() {
    $error = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];

        if (!empty($username) && !empty($email) && !empty($password)) {
            // Vérifier si l'email existe déjà
            if (getUserByEmail($email)) {
                $error = "Cet email est déjà utilisé.";
            } else {
                if (createUser($username, $email, $password)) {
                    header('Location: index.php?page=login&success=1');
                    exit;
                } else {
                    $error = "Erreur lors de l'inscription.";
                }
            }
        } else {
            $error = "Tous les champs sont requis.";
        }
    }
    require 'templates/register.php';
}

// --- CONNEXION ---
function login() {
    $error = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];

        $user = getUserByEmail($email);

        // Vérification du mot de passe haché
        if ($user && password_verify($password, $user['password_hash'])) {
            // Création de la session
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

// --- DÉCONNEXION ---
function logout() {
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}